<?php

namespace App\Http\Controllers;

use App\Exports\TrainingExport;
use App\Imports\TrainingImport;
use App\Models\Atribut;
use App\Models\Classification;
use App\Models\NilaiAtribut;
use App\Models\TrainingData;
use App\Support\ActivityLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TrainingDataController extends Controller
{
	public function export()
	{ //Download Data Training
		if (TrainingData::count() === 0) //Cek jika kosong
			return back()->withError('Gagal download: Data Training kosong');
		return Excel::download(new TrainingExport, 'training_' . time() . '.xlsx');
	}
	public function import(Request $request)
	{ //Upload Data Training
		$request->validate(TrainingData::$filerule);
		Excel::import(new TrainingImport, $request->file('data'));
		ActivityLogger::log('training.imported', 'Mengunggah data training baru', TrainingData::class, [
			'filename' => $request->file('data')?->getClientOriginalName(),
		]);
		return response()->json(['message' => 'Berhasil diimpor']);
	}
	public function count()
	{ //Tampilkan jumlah nilai yang hilang dan data duplikat berdasarkan ID pelanggan
		$train = TrainingData::get();
		$trainUnique = $train->unique(function ($item) {
			if (empty($item->id_pelanggan))
				return 'legacy-' . $item->id;
			return strtolower(trim($item->id_pelanggan));
		});
		$empty = 0;
		foreach (Atribut::get() as $attr)
			$empty += TrainingData::whereNull($attr->slug)->count();
		return ['duplicate' => $train->diff($trainUnique)->count(), 'empty' => $empty];
	}
	/**
	 * Tampilkan halaman Data Training
	 */
	public function index()
	{
		$atribut = Atribut::get();
		if (count($atribut) === 0) {
			return to_route('atribut.index')
				->withWarning('Tambahkan Atribut dulu sebelum menginput Dataset');
		}
		$nilai = NilaiAtribut::get();
		$hasil = ProbabLabel::$label;
		return view('main.dataset.training', compact('atribut', 'nilai', 'hasil'));
	}

	/**
	 * Tampilkan Data Training
	 */
	public function create()
	{
		$dt = DataTables::of(TrainingData::with('nilai_atribut')->select('training_data.*'));
		foreach (Atribut::get() as $attr) {
			if ($attr->type === 'categorical') {
				$dt->editColumn($attr->slug, function (TrainingData $train) use ($attr) {
					$atrib = NilaiAtribut::find($train[$attr->slug]);
					return $atrib->name ?? "?";
				});
			}
		}
		$dt->editColumn('status', function (TrainingData $train) {
			return ProbabLabel::$label[$train->status];
		});
		return $dt->make();
	}

	/**
	 * Simpan Data Training baru atau Simpan perubahan
	 */
	public function store(Request $request)
	{
		try {
			$request->validate(TrainingData::$rules);
			foreach ($request->q as $id => $q) $req[$id] = $q;
			$req['nama'] = ucfirst($request->nama);
			$req['id_pelanggan'] = strtoupper(trim($request->id_pelanggan));
			$req['daya_terpasang'] = $request->filled('daya_terpasang') ? (int) $request->daya_terpasang : null;
			$req['status'] = $request->status;

			$duplikat = TrainingData::where('id_pelanggan', $req['id_pelanggan']);
			if (!empty($request->id)) $duplikat->where('id', '!=', $request->id);
			if ($duplikat->exists()) {
				return response()->json([
					'message' => 'ID Pelanggan sudah digunakan',
					'errors' => [
						'id_pelanggan' => ['ID Pelanggan sudah digunakan']
					]
				], 422);
			}

			ProbabLabel::resetProbab();
			if (!empty($request->id)) {
				$data = TrainingData::updateOrCreate(['id' => $request->id], $req);
				ActivityLogger::log('training.updated', 'Mengubah data training ' . $data->id_pelanggan, $data);
				return response()->json(['message' => 'Berhasil diedit']);
			} else {
				$data = TrainingData::create($req);
				ActivityLogger::log('training.created', 'Menambahkan data training ' . $data->id_pelanggan, $data);
				return response()->json(['message' => 'Berhasil disimpan']);
			}
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}

	/**
	 * Ambil Data Training
	 */
	public function edit(TrainingData $training)
	{
		return response()->json($training);
	}

	/**
	 * Hapus Data Training terpilih
	 */
	public function destroy(TrainingData $training)
	{
		Classification::where('type', 'train')
			->where('id_pelanggan', $training->id_pelanggan)
			->where('daya_terpasang', $training->daya_terpasang)
			->delete();
		ActivityLogger::log('training.deleted', 'Menghapus data training ' . $training->id_pelanggan, $training);
		$training->delete();
		ProbabLabel::resetProbab();
		return response()->json(['message' => 'Berhasil dihapus']);
	}
	public function clear()
	{ //Hapus semua Data Training
		try {
			Classification::where('type', 'train')->delete();
			ProbabLabel::resetProbab();
			TrainingData::truncate();
			ActivityLogger::log('training.cleared', 'Menghapus seluruh data training', TrainingData::class);
			return response()->json(['message' => 'Berhasil dihapus']);
		} catch (QueryException $e) {
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
}
