<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([RoleSeeder::class]);

		$accounts = [
			[
				'name' => 'Admin',
				'email' => 'admin@example.com',
				'role' => 'admin',
			],
			[
				'name' => 'Petugas',
				'email' => 'petugas@example.com',
				'role' => 'petugas',
			],
			[
				'name' => 'Pimpinan',
				'email' => 'pimpinan@example.com',
				'role' => 'pimpinan',
			],
		];

		foreach ($accounts as $account) {
			User::firstOrCreate(
				['email' => $account['email']],
				[
					'name' => $account['name'],
					'password' => Hash::make('password123'),
					'role_id' => Role::where('name', $account['role'])->value('id'),
					'is_active' => true,
				]
			);
		}

		$this->call([AtributSeeder::class, NilaiAtributSeeder::class]);
	}
}
