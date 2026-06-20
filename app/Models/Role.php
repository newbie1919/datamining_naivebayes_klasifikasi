<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use HasFactory;

	protected $fillable = ['name', 'description', 'permissions'];

	protected function casts(): array
	{
		return [
			'permissions' => 'array',
		];
	}

	/**
	 * Get all users for this role
	 */
	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function hasPermission(string $permission): bool
	{
		return in_array($permission, $this->permissions ?? [], true);
	}
}
