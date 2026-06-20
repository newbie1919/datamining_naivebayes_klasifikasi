@extends('layout')
@section('title', 'Log Aktivitas')
@section('content')
@include('main.account._tabs')
<div class="card mb-3">
	<div class="card-body">
		<form method="GET" class="row g-2 align-items-end">
			<div class="col-md-6">
				<label class="form-label">Pencarian</label>
				<input type="text" name="q" class="form-control" value="{{ request('q') }}"
					placeholder="Cari aksi, deskripsi, nama, atau email">
			</div>
			<div class="col-md-4">
				<label class="form-label">Filter Role</label>
				<select name="role" class="form-select">
					<option value="">Semua role</option>
					@foreach($roles as $role)
					<option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ ucfirst($role->name) }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-2 d-grid">
				<button type="submit" class="btn btn-primary">Terapkan</button>
			</div>
		</form>
	</div>
</div>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered align-middle">
				<thead>
					<tr>
						<th>Waktu</th>
						<th>Pengguna</th>
						<th>Role</th>
						<th>Aksi</th>
						<th>Deskripsi</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody>
					@forelse($logs as $log)
					<tr>
						<td>{{ $log->created_at?->format('d M Y H:i:s') }}</td>
						<td>{{ $log->user?->name ?? 'Sistem' }}<br><span class="text-muted small">{{ $log->user?->email }}</span></td>
						<td>{{ $log->user?->getRoleLabel() ?? '-' }}</td>
						<td><code>{{ $log->action }}</code></td>
						<td>{{ $log->description }}</td>
						<td>{{ $log->ip_address ?? '-' }}</td>
					</tr>
					@empty
					<tr>
						<td colspan="6" class="text-center text-muted">Belum ada log aktivitas.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		{{ $logs->links() }}
	</div>
</div>
@endsection
