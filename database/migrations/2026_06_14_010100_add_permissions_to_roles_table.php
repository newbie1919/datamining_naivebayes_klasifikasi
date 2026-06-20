<?php

use App\Models\Role;
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
		Schema::table('roles', function (Blueprint $table) {
			if (!Schema::hasColumn('roles', 'permissions')) {
				$table->json('permissions')->nullable()->after('description');
			}
		});

		foreach (config('permissions.role_defaults') as $roleName => $permissions) {
			Role::where('name', $roleName)->update(['permissions' => $permissions]);
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('roles', function (Blueprint $table) {
			if (Schema::hasColumn('roles', 'permissions')) {
				$table->dropColumn('permissions');
			}
		});
	}
};
