<?php

use App\Exports\DatasetTemplate,
	App\Http\Controllers\AdminController,
	App\Http\Controllers\AdminAccountController,
	App\Http\Controllers\AtributController,
	App\Http\Controllers\AuthController,
	App\Http\Controllers\ClassificationController,
	App\Http\Controllers\NilaiAtributController,
	App\Http\Controllers\ProbabilityController,
	App\Http\Controllers\ResultController,
	App\Http\Controllers\TestingDataController,
	App\Http\Controllers\TrainingDataController,
	App\Models\User,
	Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->controller(AuthController::class)->group(function () {
	Route::prefix('register')->name('register')->group(function () {
		Route::get('/', 'register');
		Route::post('/', 'postRegister')->name('.submit');
	});
	Route::prefix('login')->name('login')->group(function () {
		Route::get('/', 'login');
		Route::post('/', 'postLogin')->name('.submit');
	});
	Route::prefix('password')->name('password.')->group(function () {
		Route::get('/', 'forget')->name('forget');
		Route::post('/', 'forgetLink')->name('send');
		Route::prefix('reset')->group(function () {
			Route::get('/', 'showReset')->name('change');
			Route::patch('/', 'reset')->name('reset');
		});
	});
});
Route::middleware(['auth', 'active'])->group(function () {
	Route::controller(AdminController::class)->group(function () {
		Route::get('/', 'index')->name('home');
		Route::prefix('profil')->name('profil.')->group(function () {
			Route::get('/', 'edit')->name('index');
			Route::post('/', 'update')->name('update');
			Route::delete('/', 'delete')->name('delete');
		});
		Route::post('logout', 'logout')->name('logout');
	});

	Route::middleware('permission:manage_users')->prefix('admin/accounts')->name('admin.accounts.')->group(function () {
		Route::controller(AdminAccountController::class)->group(function () {
			Route::get('users', 'users')->name('users');
			Route::patch('users/{user}', 'updateUser')->name('users.update');
			Route::patch('users/{user}/toggle-status', 'toggleUserStatus')->name('users.toggle-status');
		});
	});

	Route::middleware('permission:manage_role_permissions')->prefix('admin/accounts')->name('admin.accounts.')->group(function () {
		Route::controller(AdminAccountController::class)->group(function () {
			Route::get('permissions', 'permissions')->name('permissions');
			Route::patch('permissions/{role}', 'updatePermissions')->name('permissions.update');
		});
	});

	Route::middleware('permission:view_activity_logs')->prefix('admin/accounts')->name('admin.accounts.')->group(function () {
		Route::controller(AdminAccountController::class)->group(function () {
			Route::get('activity-logs', 'activityLogs')->name('activity-logs');
		});
	});

	Route::middleware('permission:manage_training_data')->group(function () {
		Route::controller(TrainingDataController::class)->prefix('training')
			->name('training.')->group(function () {
				Route::get('count', 'count')->name('count')->block();
				Route::get('download', 'export')->name('export')->block();
				Route::post('upload', 'import')->name('import')->block();
				Route::delete('/', 'clear')->name('clear')->block();
			});
		Route::resources([
			'training' => TrainingDataController::class,
		]);
	});

	Route::middleware('permission:manage_attributes')->group(function () {
		Route::prefix('atribut')->name('atribut.')->group(function () {
			Route::get('count', [AtributController::class, 'count'])->name('count')
				->block();
			Route::get('nilai/count', [NilaiAtributController::class, 'count'])
				->name('nilai.count')->block();
			Route::resource('nilai', NilaiAtributController::class);
		});
		Route::resources([
			'atribut' => AtributController::class
		]);
	});

	Route::middleware('permission:manage_probabilities')->group(function () {
		Route::controller(ProbabilityController::class)->prefix('probab')
			->name('probab.')->group(function () {
				Route::get('/', 'index')->name('index');
				Route::get('calc', 'create')->name('create');
				Route::delete('/', 'destroy')->name('reset');
			});
	});

	Route::middleware('permission:manage_testing_data')->group(function () {
		Route::controller(TestingDataController::class)->prefix('testing')
			->name('testing.')->group(function () {
				Route::get('count', 'count')->name('count')->block();
				Route::get('download', 'export')->name('export')->block();
				Route::post('upload', 'import')->name('import')->block();
				Route::delete('/', 'clear')->name('clear')->block();
			});
		Route::resource('testing', TestingDataController::class);
	});

	Route::middleware('permission:run_classification')->group(function () {
		Route::prefix('class')->controller(ClassificationController::class)
			->name('class.')->group(function () {
				Route::get('/', 'index')->name('index')->block();
				Route::get('datatable', 'show')->name('datatable')->block();
				Route::get('detail/{classification}', 'detail')->name('detail')->block();
				Route::get('export/{type}', 'export')->name('export')->block();
				Route::post('calc', 'create')->name('create')->block();
				Route::delete('/', 'destroy')->name('reset')->block();
			});
	});

	Route::middleware('permission:manage_testing_data')->group(function () {
		Route::get('template', function () {
			return (new DatasetTemplate)->download('template_' . time() . '.xlsx');
		})->name('template-data');
	});

	Route::middleware('permission:view_reports')->group(function () {
		Route::controller(ResultController::class)->prefix('result')->name('result.')->group(function () {
			Route::get('/', '__invoke')->name('index');
			Route::get('report/classification', 'report')->name('report');
			Route::get('report/classification/export/csv', 'exportClassificationCsv')
				->name('report.export.csv');
			Route::get('report/classification/export/excel', 'exportClassificationExcel')
				->name('report.export.excel');
			Route::get('report/classification/export/pdf', 'exportClassificationPdf')
				->name('report.export.pdf');
			Route::get('export/csv', 'exportCsv')->name('export.csv');
			Route::get('export/pdf', 'exportPdf')->name('export.pdf');
		});
	});
	Route::get('laravel', function () {
		return view('welcome');
	})->name('laravel');
	Route::get('php', function () {
		return phpinfo();
	})->name('phpinfo');
});
