@extends('layout')
@section('title', 'Daftar Pengguna')
@section('content')
@include('main.account._tabs')
<div class="card mb-3">
	<div class="card-body">
		<form method="GET" class="row g-2 align-items-end">
			<div class="col-md-6">
				<label class="form-label">Pencarian</label>
				<input type="text" name="q" class="form-control" value="{{ request('q') }}"
					placeholder="Cari nama atau email pengguna">
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
						<th>Nama</th>
						<th>Email</th>
						<th>Role</th>
						<th>Status</th>
						<th>Dibuat</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($users as $user)
					<tr>
						<td>{{ $user->name }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->getRoleLabel() }}</td>
						<td>
							<span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
								{{ $user->is_active ? 'Aktif' : 'Diblokir' }}
							</span>
						</td>
						<td>{{ $user->created_at?->format('d M Y H:i') }}</td>
						<td>
							<div class="d-flex flex-wrap gap-2">
								<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
									data-bs-target="#editUserModal{{ $user->id }}">
									Edit
								</button>
								<form method="POST" action="{{ route('admin.accounts.users.toggle-status', $user) }}">
									@csrf
									@method('PATCH')
									<button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
										@if($user->id === auth()->id()) disabled @endif>
										{{ $user->is_active ? 'Blokir' : 'Aktifkan' }}
									</button>
								</form>
							</div>
							<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title">Edit Pengguna</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<form method="POST" action="{{ route('admin.accounts.users.update', $user) }}">
											@csrf
											@method('PATCH')
											<div class="modal-body">
												<div class="mb-3">
													<label class="form-label">Nama</label>
													<input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
												</div>
												<div class="mb-3">
													<label class="form-label">Email</label>
													<input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
												</div>
												<div class="mb-3">
													<label class="form-label">Role</label>
													<select name="role_id" class="form-select" required>
														@foreach($roles as $role)
														<option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ ucfirst($role->name) }}</option>
														@endforeach
													</select>
												</div>
												<div class="mb-3">
													<label class="form-label">Password baru</label>
													<input type="password" name="password" class="form-control" minlength="8" maxlength="20">
													<div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
												</div>
												<div class="mb-0">
													<label class="form-label">Konfirmasi password</label>
													<input type="password" name="password_confirmation" class="form-control" minlength="8" maxlength="20">
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
												<button type="submit" class="btn btn-primary">Simpan</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="6" class="text-center text-muted">Belum ada pengguna yang cocok dengan filter.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		{{ $users->links() }}
	</div>
</div>
@endsection
