<?php

namespace App\Http\Controllers;

use App\Exports\ClassificationExport;
use App\Models\Classification;
use App\Models\Probability;
use App\Models\TestingData;
use App\Models\TrainingData;
use App\Support\ActivityLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ClassificationController extends Controller
{
	public function export($type)
	{ //Download data hasil klasifikasi
		if (Classification::count() === 0)
			return back()->withError('Gagal download: Tidak ada data hasil klasifikasi');
		return Excel::download(
			new ClassificationExport($type),
			"klasifikasi_{$type}_" . time() . ".xlsx"
		);
	}
	/**
	 * Tampilkan halaman Klasifikasi
	 */
	public function index()
	{
		return view('main.naivebayes.classify', ['hasil' => ProbabLabel::$label]);
	}

	/**
	 * Hitung klasifikasi
	 */
	public function create(Request $request)
	{
		$request->validate(Classification::$rule);
		try {
			if (Probability::count() === 0)
				return response()->json(['message' => 'Probabilitas belum dihitung'], 400);

			//Preprocessor
			if ($request->tipe === 'test') $pre = ProbabLabel::preprocess('test');

			$semuadata = $this->getData($request->tipe); //Dataset
			if (!$semuadata) {
				return response()->json([
					'message' => 'Tipe Data yang dipilih kosong',
					'errors' => ['tipe' => 'Tipe Data yang dipilih kosong']
				], 400);
			}
			foreach ($semuadata as $dataset) {
				$klasifikasi = ProbabLabel::hitungProbab($dataset);
				Classification::updateOrCreate([
					'type' => $request->tipe,
					'id_pelanggan' => $dataset->id_pelanggan,
					'daya_terpasang' => $dataset->daya_terpasang
				], [
					'name' => $dataset->nama,
					'true' => $klasifikasi['true'],
					'false' => $klasifikasi['false'],
					'predicted' => $klasifikasi['predict'],
					'real' => $dataset->status ?? null
				]);
			}
			ActivityLogger::log(
				'classification.calculated',
				'Menjalankan klasifikasi untuk data ' . $request->tipe,
				Classification::class,
				['type' => $request->tipe]
			);
			return response()->json([
				'message' => 'Berhasil dihitung', 'preprocess' => $pre ?? 0
			]);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}

	/**
	 * DataTables: Tampilkan data hasil klasifikasi
	 */
	public function show()
	{
		return DataTables::of(Classification::query())
			->editColumn('type', function (Classification $class) {
				return Classification::$tipedata[$class->type];
			})->editColumn('predicted', function (Classification $class) {
				return ProbabLabel::$label[$class->predicted];
			})->editColumn('real', function (Classification $class) {
				return is_null($class->real) ? '-' : ProbabLabel::$label[$class->real];
			})->make();
	}
	public function detail(Classification $classification)
	{
		if ($classification->type === 'train') {
			$dataset = TrainingData::where('id_pelanggan', $classification->id_pelanggan)
				->where('daya_terpasang', $classification->daya_terpasang)
				->first();
		} else {
			$dataset = TestingData::where('id_pelanggan', $classification->id_pelanggan)
				->where('daya_terpasang', $classification->daya_terpasang)
				->first();
		}
		if (!$dataset) {
			return response()->json(['message' => 'Data sumber klasifikasi tidak ditemukan'], 404);
		}
		$detail = ProbabLabel::explainProbab($dataset);
		$totalTrain = TrainingData::count();
		$totalLayak = TrainingData::where('status', true)->count();
		$totalTidakLayak = TrainingData::where('status', false)->count();
		return response()->json([
			'meta' => [
				'nama' => $classification->name,
				'id_pelanggan' => $classification->id_pelanggan,
				'daya_terpasang' => $classification->daya_terpasang,
				'type' => Classification::$tipedata[$classification->type],
				'predicted' => ProbabLabel::$label[$classification->predicted],
				'real' => is_null($classification->real) ? '-' : ProbabLabel::$label[$classification->real]
			],
			'prior_count' => [
				'total' => $totalTrain,
				'true' => $totalLayak,
				'false' => $totalTidakLayak
			],
			'formula' => [
				'posterior' => 'P(Y|X) = (P(Y) * Π P(xi|Y)) / P(X)',
				'gaussian' => 'P(x|Y) = (1 / (sd * sqrt(2π))) * exp(-0.5 * ((x-mean)/sd)^2)'
			],
			'detail' => $detail
		]);
	}

	/**
	 * Hapus data hasil klasifikasi sesuai tipe data yang dipilih
	 */
	public function destroy(Request $request)
	{
		$request->validate(Classification::$rule);
		try {
			if ($request->tipe === 'all') Classification::truncate();
			else Classification::where('type', $request->tipe)->delete();
			ActivityLogger::log(
				'classification.reset',
				'Mereset hasil klasifikasi untuk data ' . $request->tipe,
				Classification::class,
				['type' => $request->tipe]
			);
			return response()->json(['message' => 'Berhasil direset']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	private function getData(string $type)
	{ //Ambil data dari tabel Dataset
		if ($type === 'train') {
			if (TrainingData::count() === 0) return false;
			$data = TrainingData::get();
		} else {
			if (TestingData::count() === 0) return false;
			$data = TestingData::get();
		}
		return $data;
	}
}
