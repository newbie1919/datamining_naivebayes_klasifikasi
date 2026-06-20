@php
	$accountMenuOpen = request()->routeIs('admin.accounts.*');
@endphp
<div class="card mb-3">
	<div class="card-body">
		<div class="d-flex flex-wrap gap-2">
			@if(auth()->user()?->hasPermission('manage_users'))
			<a href="{{ route('admin.accounts.users') }}"
				@class(['btn', request()->routeIs('admin.accounts.users') ? 'btn-primary' : 'btn-outline-primary'])>
				Daftar Pengguna
			</a>
			@endif
			@if(auth()->user()?->hasPermission('manage_role_permissions'))
			<a href="{{ route('admin.accounts.permissions') }}"
				@class(['btn', request()->routeIs('admin.accounts.permissions') ? 'btn-primary' : 'btn-outline-primary'])>
				Hak Akses & Peran
			</a>
			@endif
			@if(auth()->user()?->hasPermission('view_activity_logs'))
			<a href="{{ route('admin.accounts.activity-logs') }}"
				@class(['btn', request()->routeIs('admin.accounts.activity-logs') ? 'btn-primary' : 'btn-outline-primary'])>
				Log Aktivitas
			</a>
			@endif
		</div>
	</div>
</div>
