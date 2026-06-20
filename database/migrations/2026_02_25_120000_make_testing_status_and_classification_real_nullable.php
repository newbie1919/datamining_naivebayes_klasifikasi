<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('testing_data', function (Blueprint $table) {
			$table->boolean('status')->nullable()->change();
		});

		Schema::table('classifications', function (Blueprint $table) {
			$table->boolean('real')->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		DB::table('testing_data')->whereNull('status')->update(['status' => false]);
		DB::table('classifications')->whereNull('real')->update(['real' => false]);

		Schema::table('testing_data', function (Blueprint $table) {
			$table->boolean('status')->nullable(false)->change();
		});

		Schema::table('classifications', function (Blueprint $table) {
			$table->boolean('real')->nullable(false)->change();
		});
	}
};
