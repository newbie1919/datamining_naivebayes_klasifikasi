@extends('layout')
@section('title', 'Hak Akses & Peran')
@section('content')
@include('main.account._tabs')
<div class="row">
	@foreach($roles as $role)
	<div class="col-lg-6 mb-3">
		<div class="card h-100">
			<div class="card-header d-flex justify-content-between align-items-center">
				<div>
					<div class="fw-bold text-capitalize">{{ $role->name }}</div>
					<div class="text-muted small">{{ $role->description }}</div>
				</div>
				@if($role->name === 'admin')
				<span class="badge bg-primary">Akses penuh</span>
				@endif
			</div>
			<div class="card-body">
				<form method="POST" action="{{ route('admin.accounts.permissions.update', $role) }}">
					@csrf
					@method('PATCH')
					@foreach($permissionGroups as $groupLabel => $permissions)
					<div class="mb-3">
						<div class="fw-semibold mb-2">{{ $groupLabel }}</div>
						@foreach($permissions as $permissionKey => $permissionLabel)
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" name="permissions[]"
								value="{{ $permissionKey }}" id="{{ $role->name }}-{{ $permissionKey }}"
								@checked($role->hasPermission($permissionKey))
								@if($role->name === 'admin') disabled @endif>
							<label class="form-check-label" for="{{ $role->name }}-{{ $permissionKey }}">
								{{ $permissionLabel }}
							</label>
						</div>
						@endforeach
					</div>
					@endforeach
					@if($role->name !== 'admin')
					<button type="submit" class="btn btn-primary">Simpan Hak Akses</button>
					@else
					<div class="alert alert-info mb-0">
						Role admin dijaga tetap memiliki akses penuh ke seluruh modul.
					</div>
					@endif
				</form>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
