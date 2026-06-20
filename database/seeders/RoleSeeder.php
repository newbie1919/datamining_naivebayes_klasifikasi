<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$defaults = config('permissions.role_defaults');

		Role::updateOrCreate(['name' => 'admin'], [
			'name' => 'admin',
			'description' => 'Administrator dengan akses penuh',
			'permissions' => $defaults['admin'] ?? [],
		]);

		Role::updateOrCreate(['name' => 'petugas'], [
			'name' => 'petugas',
			'description' => 'Petugas operasional untuk input data dan menjalankan klasifikasi',
			'permissions' => $defaults['petugas'] ?? [],
		]);

		Role::updateOrCreate(['name' => 'pimpinan'], [
			'name' => 'pimpinan',
			'description' => 'Pimpinan untuk melihat tren dan mengunduh laporan',
			'permissions' => $defaults['pimpinan'] ?? [],
		]);
	}
}
