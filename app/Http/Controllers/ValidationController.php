<?php

namespace App\Http\Controllers;

use App\Models\Atribut;
use App\Models\Classification;
use App\Models\NilaiAtribut;
use App\Models\Probability;
use App\Models\TestingData;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
	/**
	 * Tampilkan halaman Validasi dan Pengujian
	 */
	public function index()
	{
		$atribut = Atribut::all();
		if (count($atribut) === 0) {
			return to_route('atribut.index')
				->withWarning('Tambahkan Atribut dulu sebelum melakukan validasi');
		}

		$nilai = NilaiAtribut::all();

		return view('main.naivebayes.validation', compact('atribut', 'nilai'));
	}

	/**
	 * Validasi dan klasifikasi data tunggal
	 */
	public function singleTest(Request $request)
	{
		try {
			// Validate input
			$rules = [
				'nama' => ['bail', 'required', 'string'],
				'id_pelanggan' => ['bail', 'required', 'string', 'max:50'],
				'daya_terpasang' => ['bail', 'nullable', 'integer', 'min:1'],
				'include_actual' => ['nullable', 'boolean'],
				'actual_value' => ['nullable', 'in:0,1'],
			];

			foreach (Atribut::all() as $attr) {
				$rules["q.{$attr->slug}"] = ['bail', 'required', 'numeric'];
			}

			$request->validate($rules);

			// Check if probability exists
			if (Probability::count() === 0) {
				return response()->json([
					'success' => false,
					'message' => 'Model belum dilatih. Lakukan perhitungan probabilitas terlebih dahulu.'
				], 400);
			}

			// Prepare data
			$data = (object)[
				'nama' => $request->nama,
				'id_pelanggan' => strtoupper(trim($request->id_pelanggan)),
				'daya_terpasang' => $request->daya_terpasang ?? null,
			];

			// Add attributes
			foreach (Atribut::all() as $attr) {
				$data->{$attr->slug} = $request->input("q.{$attr->slug}");
			}

			// Calculate classification using ProbabLabel class
			$klasifikasi = ProbabLabel::hitungProbab($data);

			// Prepare response
			$response = [
				'success' => true,
				'data' => [
					'nama' => $request->nama,
					'id_pelanggan' => $data->id_pelanggan,
					'daya_terpasang' => $data->daya_terpasang,
					'predicted' => $klasifikasi['predict'],
					'prob_true' => $klasifikasi['true'],
					'prob_false' => $klasifikasi['false'],
					'confidence' => max($klasifikasi['true'], $klasifikasi['false']),
					'predicted_label' => ProbabLabel::$label[$klasifikasi['predict']]
				]
			];

			// Add actual value comparison if provided
			if ($request->filled('include_actual') && $request->filled('actual_value')) {
				$actual = (int)$request->actual_value;
				$isCorrect = $klasifikasi['predict'] == $actual;

				$response['data']['actual'] = $actual;
				$response['data']['actual_label'] = ProbabLabel::$label[$actual];
				$response['data']['is_correct'] = $isCorrect;
				$response['data']['error_type'] = !$isCorrect ? 
					($klasifikasi['predict'] == 1 ? 'False Positive' : 'False Negative') : 
					null;
			}

			// Save to classification table for history
			Classification::updateOrCreate(
				[
					'type' => 'test',
					'id_pelanggan' => $data->id_pelanggan,
					'daya_terpasang' => $data->daya_terpasang ?? 0
				],
				[
					'name' => $request->nama,
					'true' => $klasifikasi['true'],
					'false' => $klasifikasi['false'],
					'predicted' => $klasifikasi['predict'],
					'real' => $request->filled('actual_value') ? (int)$request->actual_value : null
				]
			);

			return response()->json($response);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Validasi gagal',
				'errors' => $e->errors()
			], 422);
		} catch (\Exception $e) {
			\Log::error('Validation Error:', ['error' => $e->getMessage()]);
			return response()->json([
				'success' => false,
				'message' => 'Terjadi kesalahan: ' . $e->getMessage()
			], 500);
		}
	}

	/**
	 * Validasi batch data dari file
	 */
	public function batchTest(Request $request)
	{
		try {
			$request->validate([
				'data' => [
					'bail',
					'required',
					'file',
					'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.spreadsheet,text/csv,text/tab-separated-values'
				]
			]);

			if (Probability::count() === 0) {
				return response()->json([
					'success' => false,
					'message' => 'Model belum dilatih. Lakukan perhitungan probabilitas terlebih dahulu.'
				], 400);
			}

			// Import and validate
			\Maatwebsite\Excel\Facades\Excel::import(
				new \App\Imports\TestingImport,
				$request->file('data')
			);

			// Get all testing data
			$testingData = TestingData::all();
			$results = [];
			$correctCount = 0;

			foreach ($testingData as $test) {
				$klasifikasi = ProbabLabel::hitungProbab($test);

				$result = [
					'nama' => $test->nama,
					'id_pelanggan' => $test->id_pelanggan,
					'predicted' => $klasifikasi['predict'],
					'predicted_label' => ProbabLabel::$label[$klasifikasi['predict']],
					'confidence' => max($klasifikasi['true'], $klasifikasi['false']),
					'prob_true' => $klasifikasi['true'],
					'prob_false' => $klasifikasi['false']
				];

				// Check if actual value exists
				if ($test->status !== null) {
					$isCorrect = $klasifikasi['predict'] == $test->status;
					$result['actual'] = $test->status;
					$result['actual_label'] = ProbabLabel::$label[$test->status];
					$result['is_correct'] = $isCorrect;
					if ($isCorrect) $correctCount++;
				}

				$results[] = $result;
			}

			$accuracy = count($results) > 0 ? ($correctCount / count($results)) * 100 : 0;

			return response()->json([
				'success' => true,
				'data' => [
					'total' => count($results),
					'correct' => $correctCount,
					'wrong' => count($results) - $correctCount,
					'accuracy' => round($accuracy, 2),
					'results' => $results
				]
			]);

		} catch (\Exception $e) {
			\Log::error('Batch Test Error:', ['error' => $e->getMessage()]);
			return response()->json([
				'success' => false,
				'message' => 'Terjadi kesalahan: ' . $e->getMessage()
			], 500);
		}
	}

	/**
	 * Dapatkan statistik validasi
	 */
	public function getStats()
	{
		try {
			$classifications = Classification::all();

			if ($classifications->isEmpty()) {
				return response()->json([
					'success' => true,
					'data' => [
						'total' => 0,
						'accuracy' => 0,
						'precision' => 0,
						'recall' => 0,
						'f1' => 0,
						'tp' => 0,
						'fp' => 0,
						'fn' => 0,
						'tn' => 0
					]
				]);
			}

			// Calculate confusion matrix
			$tp = $classifications->where('predicted', 1)->where('real', 1)->count();
			$fp = $classifications->where('predicted', 1)->where('real', 0)->count();
			$fn = $classifications->where('predicted', 0)->where('real', 1)->count();
			$tn = $classifications->where('predicted', 0)->where('real', 0)->count();
			$total = $tp + $fp + $fn + $tn;

			// Calculate metrics
			$accuracy = $total > 0 ? (($tp + $tn) / $total) : 0;
			$precision = ($tp + $fp) > 0 ? ($tp / ($tp + $fp)) : 0;
			$recall = ($tp + $fn) > 0 ? ($tp / ($tp + $fn)) : 0;
			$f1 = ($precision + $recall) > 0 ? (2 * ($precision * $recall) / ($precision + $recall)) : 0;

			return response()->json([
				'success' => true,
				'data' => [
					'total' => $total,
					'accuracy' => round($accuracy * 100, 2),
					'precision' => round($precision * 100, 2),
					'recall' => round($recall * 100, 2),
					'f1' => round($f1, 4),
					'tp' => $tp,
					'fp' => $fp,
					'fn' => $fn,
					'tn' => $tn
				]
			]);

		} catch (\Exception $e) {
			\Log::error('Stats Error:', ['error' => $e->getMessage()]);
			return response()->json([
				'success' => false,
				'message' => 'Terjadi kesalahan'
			], 500);
		}
	}
}
