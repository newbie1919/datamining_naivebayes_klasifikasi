<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('training_data', function (Blueprint $table) {
			$table->string('id_pelanggan', 50)->nullable()->after('nama');
			$table->unsignedInteger('daya_terpasang')->nullable()->after('id_pelanggan');
		});

		Schema::table('testing_data', function (Blueprint $table) {
			$table->string('id_pelanggan', 50)->nullable()->after('nama');
			$table->unsignedInteger('daya_terpasang')->nullable()->after('id_pelanggan');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('training_data', function (Blueprint $table) {
			$table->dropColumn(['id_pelanggan', 'daya_terpasang']);
		});

		Schema::table('testing_data', function (Blueprint $table) {
			$table->dropColumn(['id_pelanggan', 'daya_terpasang']);
		});
	}
};
