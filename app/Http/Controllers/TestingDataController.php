<?php

namespace App\Http\Controllers;

use App\Exports\TestingExport;
use App\Imports\TestingImport;
use App\Models\Atribut;
use App\Models\Classification;
use App\Models\NilaiAtribut;
use App\Models\Probability;
use App\Models\TestingData;
use App\Support\ActivityLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TestingDataController extends Controller
{
	public function export()
	{ //Download Data Testing
		if (TestingData::count() === 0)
			return back()->withError('Gagal download: Data Testing kosong');
		return Excel::download(new TestingExport, 'testing_' . time() . '.xlsx');
	}
	public function import(Request $request)
	{ //Upload Data Testing
		$request->validate(TestingData::$filerule);
		Excel::import(new TestingImport, request()->file('data'));
		ActivityLogger::log('testing.imported', 'Mengunggah data testing baru', TestingData::class, [
			'filename' => $request->file('data')?->getClientOriginalName(),
		]);
		return response()->json(['message' => 'Berhasil diimpor']);
	}
	public function count()
	{ //Tampilkan jumlah nilai yang hilang dan data duplikat berdasarkan ID pelanggan
		$test = TestingData::get();
		$testUnique = $test->unique(function ($item) {
			if (empty($item->id_pelanggan))
				return 'legacy-' . $item->id;
			return strtolower(trim($item->id_pelanggan));
		});
		$empty = 0;
		foreach (Atribut::get() as $attr)
			$empty += TestingData::whereNull($attr->slug)->count();
		return ['duplicate' => $test->diff($testUnique)->count(), 'empty' => $empty];
	}
	/**
	 * Tampilkan halaman Data Testing
	 */
	public function index()
	{
		$atribut = Atribut::get();
		if (count($atribut) === 0) {
			return to_route('home')
				->withWarning('Admin perlu menambahkan atribut sebelum data testing dapat diinput');
		}
		$nilai = NilaiAtribut::get();
		return view(
			'main.dataset.testing',
			compact('atribut', 'nilai')
		);
	}
	/**
	 * DataTables: Tampilkan Data Testing
	 */
	public function create()
	{
		$dt = DataTables::of(TestingData::with('nilai_atribut')->select('testing_data.*'));
		foreach (Atribut::get() as $attr) {
			if ($attr->type === 'categorical') {
				$dt->editColumn($attr->slug, function (TestingData $test) use ($attr) {
					$atrib = NilaiAtribut::find($test[$attr->slug]);
					return $atrib->name ?? "?";
				});
			}
		}
		return $dt->make();
	}
	/**
	 * Simpan Data Testing baru atau Simpan perubahan
	 */
	public function store(Request $request)
	{
		try {
			$request->validate(TestingData::$rules);
			foreach ($request->q as $id => $q) $req[$id] = $q;
			$req['nama'] = ucfirst($request->nama);
			$req['id_pelanggan'] = strtoupper(trim($request->id_pelanggan));
			$req['daya_terpasang'] = $request->filled('daya_terpasang') ? (int) $request->daya_terpasang : null;

			$duplikat = TestingData::where('id_pelanggan', $req['id_pelanggan']);
			if (!empty($request->id)) $duplikat->where('id', '!=', $request->id);
			if ($duplikat->exists()) {
				return response()->json([
					'message' => 'ID Pelanggan sudah digunakan',
					'errors' => [
						'id_pelanggan' => ['ID Pelanggan sudah digunakan']
					]
				], 422);
			}

			if ($request->status === 'auto') {
				if (Probability::count() === 0) {
					return response()->json([
						'message' => "Probabilitas belum dihitung"
					], 400);
				}
				$hasil = ProbabLabel::hitungProbab($req);
				$req['status'] = $hasil['predict'];
			} elseif ($request->filled('status')) {
				$req['status'] = filter_var($request->status, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			} else {
				$req['status'] = null;
			}
			if (!empty($request->id)) {
				$data = TestingData::updateOrCreate(['id' => $request->id], $req);
				ActivityLogger::log('testing.updated', 'Mengubah data testing ' . $data->id_pelanggan, $data);
				return response()->json(['message' => 'Berhasil diedit']);
			} else {
				$data = TestingData::create($req);
				ActivityLogger::log('testing.created', 'Menambahkan data testing ' . $data->id_pelanggan, $data);
				return response()->json(['message' => 'Berhasil disimpan']);
			}
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	/**
	 * Ambil Data Testing
	 */
	public function edit(TestingData $testing)
	{
		return response()->json($testing);
	}
	/**
	 * Hapus Data Testing
	 */
	public function destroy(TestingData $testing)
	{
		Classification::where('type', 'test')
			->where('id_pelanggan', $testing->id_pelanggan)
			->where('daya_terpasang', $testing->daya_terpasang)
			->delete();
		ActivityLogger::log('testing.deleted', 'Menghapus data testing ' . $testing->id_pelanggan, $testing);
		$testing->delete();
		return response()->json(['message' => 'Berhasil dihapus']);
	}
	public function clear()
	{ //Hapus semua Data Testing
		try {
			Classification::where('type', 'test')->delete();
			TestingData::truncate();
			ActivityLogger::log('testing.cleared', 'Menghapus seluruh data testing', TestingData::class);
			return response()->json(['message' => 'Berhasil dihapus']);
		} catch (QueryException $e) {
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
}
