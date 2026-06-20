<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		$now = now();
		$adminId = DB::table('roles')->where('name', 'admin')->value('id');
		$userRoleId = DB::table('roles')->where('name', 'user')->value('id');
		$petugasId = DB::table('roles')->where('name', 'petugas')->value('id');
		$pimpinanId = DB::table('roles')->where('name', 'pimpinan')->value('id');

		if ($userRoleId && !$petugasId) {
			DB::table('roles')
				->where('id', $userRoleId)
				->update([
					'name' => 'petugas',
					'description' => 'Petugas operasional untuk input data dan menjalankan klasifikasi',
					'updated_at' => $now
				]);
			$petugasId = $userRoleId;
		}

		if (!$adminId) {
			$adminId = DB::table('roles')->insertGetId([
				'name' => 'admin',
				'description' => 'Administrator dengan akses penuh',
				'created_at' => $now,
				'updated_at' => $now
			]);
		}

		if (!$petugasId) {
			$petugasId = DB::table('roles')->insertGetId([
				'name' => 'petugas',
				'description' => 'Petugas operasional untuk input data dan menjalankan klasifikasi',
				'created_at' => $now,
				'updated_at' => $now
			]);
		}

		if (!$pimpinanId) {
			DB::table('roles')->insert([
				'name' => 'pimpinan',
				'description' => 'Pimpinan untuk melihat tren dan mengunduh laporan',
				'created_at' => $now,
				'updated_at' => $now
			]);
		}

		DB::table('roles')
			->where('id', $adminId)
			->update([
				'description' => 'Administrator dengan akses penuh',
				'updated_at' => $now
			]);

		DB::table('roles')
			->where('id', $petugasId)
			->update([
				'description' => 'Petugas operasional untuk input data dan menjalankan klasifikasi',
				'updated_at' => $now
			]);

		DB::table('roles')
			->where('name', 'pimpinan')
			->update([
				'description' => 'Pimpinan untuk melihat tren dan mengunduh laporan',
				'updated_at' => $now
			]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		$petugasId = DB::table('roles')->where('name', 'petugas')->value('id');

		if ($petugasId) {
			DB::table('roles')
				->where('id', $petugasId)
				->update([
					'name' => 'user',
					'description' => 'User biasa dengan akses terbatas',
					'updated_at' => now()
				]);
		}

		DB::table('roles')->where('name', 'pimpinan')->delete();
	}
};
