<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	public const ROLE_ADMIN = 'admin';

	public const ROLE_PETUGAS = 'petugas';

	public const ROLE_PIMPINAN = 'pimpinan';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = ['name', 'email', 'password', 'role_id', 'is_active'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
	}

	/**
	 * Get the role for this user
	 */
	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	/**
	 * Check if user is admin
	 */
	public function isAdmin(): bool
	{
		return $this->hasRole(self::ROLE_ADMIN);
	}

	public function isPetugas(): bool
	{
		return $this->hasRole(self::ROLE_PETUGAS);
	}

	public function isPimpinan(): bool
	{
		return $this->hasRole(self::ROLE_PIMPINAN);
	}

	public function hasRole(string $name): bool
	{
		return $this->role?->name === $name;
	}

	public function hasAnyRole(array $roles): bool
	{
		return in_array($this->getRoleName(), $roles, true);
	}

	public function hasPermission(string $permission): bool
	{
		return $this->role?->hasPermission($permission) ?? false;
	}

	/**
	 * Get role name
	 */
	public function getRoleName(): string
	{
		return $this->role?->name ?? self::ROLE_PETUGAS;
	}

	public function getRoleLabel(): string
	{
		return match ($this->getRoleName()) {
			self::ROLE_ADMIN => 'Admin',
			self::ROLE_PETUGAS => 'Petugas',
			self::ROLE_PIMPINAN => 'Pimpinan',
			default => 'Pengguna',
		};
	}
	public static array $rules = [
			'name' => ['bail', 'required', 'string'],
			'email' => ['bail', 'required', 'email', 'unique:users'],
			'password' => ['bail', 'required', 'confirmed', 'between:8,20'],
			'password_confirmation' => 'required'
		], $updrules = [
			'name' => ['bail', 'required', 'string'],
			'email' => ['bail', 'required', 'email', 'unique:users'],
			'current_password' => ['bail', 'required', 'current_password'],
			'password' => ['nullable', 'bail', 'confirmed', 'between:8,20'],
			'password_confirmation' => 'required_with:password'
		], $loginrules = [
			'email' => ['bail', 'required', 'email'],
			'password' => ['bail', 'required', 'between:8,20']
		], $forgetrules = ['email' => ['bail', 'required', 'email', 'exists:users']],
		$resetrules = [
			'email' => ['bail', 'required', 'email', 'exists:users'],
			'password' => ['bail', 'required', 'confirmed', 'between:8,20'],
			'password_confirmation' => 'required',
			'token' => 'required'
		], $delrules = [
			'confirm_pass' => ['bail', 'required', 'current_password', 'between:8,20']
		], $userrules = [
			'name' => ['bail', 'required', 'string'],
			'email' => ['bail', 'required', 'email', 'unique:users'],
			'current_password' => ['bail', 'required', 'current_password', 'between:8,20'],
			'password' => ['bail', 'required', 'confirmed', 'between:8,20'],
			'password_confirmation' => 'required'
		];
}
