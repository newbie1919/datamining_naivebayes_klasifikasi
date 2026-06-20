<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\TestingData;
use App\Models\TrainingData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
	public function index()
	{ //Halaman Dashboard
		$train = TrainingData::count();
		$test = TestingData::count();
		$total = $test + $train;
		$classTestTotal = Classification::where('type', 'test')->whereNotNull('real')->count();
		$tp = Classification::where('type', 'test')->whereNotNull('real')->where('predicted', true)->where('real', true)->count();
		$fp = Classification::where('type', 'test')->whereNotNull('real')->where('predicted', true)->where('real', false)->count();
		$fn = Classification::where('type', 'test')->whereNotNull('real')->where('predicted', false)->where('real', true)->count();
		$tn = Classification::where('type', 'test')->whereNotNull('real')->where('predicted', false)->where('real', false)->count();
		if ($classTestTotal === 0) {
			$accuracy = $precision = $recall = $f1 = 0;
		} else {
			$accuracy = (($tp + $tn) / $classTestTotal) * 100;
			$precision = ($tp + $fp) === 0 ? 0 : ($tp / ($tp + $fp)) * 100;
			$recall = ($tp + $fn) === 0 ? 0 : ($tp / ($tp + $fn)) * 100;
			$f1 = ($precision + $recall) === 0 ? 0 : 2 * ($precision * $recall) / ($precision + $recall);
		}

		$days = collect(range(6, 0))->map(function ($minusDays) {
			return Carbon::now()->subDays($minusDays)->format('Y-m-d');
		});
		$trendLabels = $days->map(function ($date) {
			return Carbon::parse($date)->format('d M');
		})->values()->toArray();
		$trendValues = $days->map(function ($date) {
			return Classification::whereDate('created_at', $date)->count();
		})->values()->toArray();

		$data = [
			'test' => $test,
			'train' => $train,
			'total' => $total,
			'classDist' => [
				'labels' => ['Layak', 'Tidak Layak'],
				'train' => [
					TrainingData::where('status', true)->count(),
					TrainingData::where('status', false)->count()
				],
				'test' => [
					TestingData::where('status', true)->count(),
					TestingData::where('status', false)->count()
				]
			],
			'modelPerf' => [
				'labels' => ['Accuracy', 'Precision', 'Recall', 'F1-score'],
				'values' => [
					round($accuracy, 2),
					round($precision, 2),
					round($recall, 2),
					round($f1, 2)
				]
			],
			'trend' => [
				'labels' => $trendLabels,
				'values' => $trendValues
			]
		];
		return view('main.index', $data);
	}
	public function logout()
	{ //Logout
		User::find(Auth::id())->update(['remember_token' => null]);
		Auth::logout();
		Session::invalidate();
		Session::regenerateToken();
		Session::flash('success', 'Anda sudah logout');
		return to_route('login');
	}
	public function edit()
	{ //Halaman Profil
		return view('main.profil');
	}
	public function update(Request $request)
	{ //Update Profil
		try {
			$request->validate([
				'name' => ['bail', 'required', 'string'],
				'email' => [
					'bail', 'required', 'email', Rule::unique('users')->ignore(Auth::id())
				], 'current_password' => ['bail', 'required', 'current_password'],
				'password' => ['nullable', 'bail', 'confirmed', 'between:8,20'],
				'password_confirmation' => 'required_with:password'
			]);
			$req = $request->all();
			if (!empty($req['password']))
				$req['password'] = Hash::make($req['password']);
			else unset($req['password']);
			$req['name'] = ucfirst($req['name']);
			$req['email'] = strtolower($req['email']);
			User::findOrFail(Auth::id())->update($req);
			return response()->json(['message' => 'Tersimpan', 'nama' => $req['name']]);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Akun tidak ditemukan'], 404);
		} catch (QueryException $e) {
			if ($e->errorInfo[1] === 1062) {
				return response()->json([
					'message' => "Email \"$request->email\" sudah digunakan",
					'errors' => ['email' => "Email sudah digunakan"]
				], 422);
			}
			Log::error($e);
			return response()->json(['message' => $e->errorInfo[2]], 500);
		}
	}
	public function delete(Request $request)
	{ //Hapus Akun
		try {
			$request->validate(User::$delrules);
			User::findOrFail(Auth::id())->delete();
			Auth::logout();
			Session::invalidate();
			Session::regenerateToken();
			Session::flash('success', "Akun sudah dihapus");
			return response()->json(['message' => 'Dihapus']);
		} catch (ModelNotFoundException) {
			return response()->json(['message' => 'Akun tidak ditemukan'], 404);
		} catch (QueryException $db) {
			Log::error($db);
			return response()->json(['message' => $db->errorInfo[2]], 500);
		}
	}
}
