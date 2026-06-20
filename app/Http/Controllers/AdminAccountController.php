<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
	public function users(Request $request)
	{
		$roles = Role::orderBy('name')->get();
		$users = User::with('role')
			->when($request->filled('q'), function ($query) use ($request) {
				$keyword = trim($request->q);
				$query->where(function ($subquery) use ($keyword) {
					$subquery->where('name', 'like', '%' . $keyword . '%')
						->orWhere('email', 'like', '%' . $keyword . '%');
				});
			})
			->when($request->filled('role'), function ($query) use ($request) {
				$query->whereHas('role', function ($roleQuery) use ($request) {
					$roleQuery->where('name', $request->role);
				});
			})
			->orderBy('name')
			->paginate(10)
			->withQueryString();

		return view('main.account.users', compact('users', 'roles'));
	}

	public function updateUser(Request $request, User $user)
	{
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
			'role_id' => ['required', 'exists:roles,id'],
			'password' => ['nullable', 'confirmed', 'between:8,20'],
		]);

		$data = [
			'name' => ucfirst($request->name),
			'email' => strtolower($request->email),
			'role_id' => (int) $request->role_id,
		];

		if ($request->filled('password')) {
			$data['password'] = Hash::make($request->password);
		}

		$user->update($data);

		ActivityLogger::log(
			'user.updated',
			'Admin memperbarui data pengguna ' . $user->email,
			$user,
			[
				'role_id' => $user->role_id,
				'updated_by' => Auth::user()?->email,
			]
		);

		return back()->withSuccess('Data pengguna berhasil diperbarui.');
	}

	public function toggleUserStatus(User $user)
	{
		if ($user->id === Auth::id()) {
			return back()->withError('Akun yang sedang login tidak dapat dinonaktifkan.');
		}

		$user->update([
			'is_active' => !$user->is_active,
		]);

		ActivityLogger::log(
			$user->is_active ? 'user.activated' : 'user.deactivated',
			($user->is_active ? 'Admin mengaktifkan ' : 'Admin memblokir ') . $user->email,
			$user,
			['updated_by' => Auth::user()?->email]
		);

		return back()->withSuccess(
			$user->is_active ? 'Akun berhasil diaktifkan.' : 'Akun berhasil diblokir.'
		);
	}

	public function permissions()
	{
		$roles = Role::orderBy('id')->get();
		$permissionGroups = config('permissions.groups');

		return view('main.account.permissions', compact('roles', 'permissionGroups'));
	}

	public function updatePermissions(Request $request, Role $role)
	{
		if ($role->name === User::ROLE_ADMIN) {
			return back()->withWarning('Hak akses admin dijaga penuh dan tidak dapat diubah dari menu ini.');
		}

		$permissionKeys = collect(config('permissions.groups'))
			->flatten(1)
			->keys()
			->values()
			->all();

		$request->validate([
			'permissions' => ['array'],
			'permissions.*' => ['string', Rule::in($permissionKeys)],
		]);

		$role->update([
			'permissions' => $request->input('permissions', []),
		]);

		ActivityLogger::log(
			'role.permissions_updated',
			'Admin memperbarui hak akses untuk role ' . $role->name,
			$role,
			['permissions' => $role->permissions]
		);

		return back()->withSuccess('Hak akses role berhasil diperbarui.');
	}

	public function activityLogs(Request $request)
	{
		$roles = Role::orderBy('name')->get();
		$logs = ActivityLog::with('user.role')
			->when($request->filled('q'), function ($query) use ($request) {
				$keyword = trim($request->q);
				$query->where(function ($subquery) use ($keyword) {
					$subquery->where('action', 'like', '%' . $keyword . '%')
						->orWhere('description', 'like', '%' . $keyword . '%')
						->orWhereHas('user', function ($userQuery) use ($keyword) {
							$userQuery->where('name', 'like', '%' . $keyword . '%')
								->orWhere('email', 'like', '%' . $keyword . '%');
						});
				});
			})
			->when($request->filled('role'), function ($query) use ($request) {
				$query->whereHas('user.role', function ($roleQuery) use ($request) {
					$roleQuery->where('name', $request->role);
				});
			})
			->latest()
			->paginate(20)
			->withQueryString();

		return view('main.account.activity-logs', compact('logs', 'roles'));
	}
}
