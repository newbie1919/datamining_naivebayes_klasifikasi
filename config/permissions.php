<?php

return [
	'groups' => [
		'Manajemen Akun' => [
			'manage_users' => 'Kelola daftar pengguna',
			'manage_role_permissions' => 'Kelola hak akses dan peran',
			'view_activity_logs' => 'Lihat log aktivitas',
		],
		'Master Data' => [
			'manage_attributes' => 'Kelola atribut dan nilai atribut',
			'manage_training_data' => 'Kelola data training',
			'manage_testing_data' => 'Kelola data testing',
		],
		'Data Mining' => [
			'manage_probabilities' => 'Hitung dan reset probabilitas',
			'run_classification' => 'Jalankan dan reset klasifikasi',
		],
		'Laporan' => [
			'view_reports' => 'Lihat dashboard, performa, dan unduh laporan',
		],
	],
	'role_defaults' => [
		'admin' => [
			'manage_users',
			'manage_role_permissions',
			'view_activity_logs',
			'manage_attributes',
			'manage_training_data',
			'manage_testing_data',
			'manage_probabilities',
			'run_classification',
			'view_reports',
		],
		'petugas' => [
			'manage_testing_data',
			'run_classification',
			'view_reports',
		],
		'pimpinan' => [
			'view_reports',
		],
	],
];
