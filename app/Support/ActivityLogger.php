<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
	public static function log(string $action, string $description, Model|string|null $subject = null, array $properties = []): void
	{
		$subjectType = null;
		$subjectId = null;

		if ($subject instanceof Model) {
			$subjectType = $subject::class;
			$subjectId = $subject->getKey();
		} elseif (is_string($subject)) {
			$subjectType = $subject;
		}

		ActivityLog::create([
			'user_id' => Auth::id(),
			'action' => $action,
			'subject_type' => $subjectType,
			'subject_id' => $subjectId,
			'description' => $description,
			'properties' => $properties,
			'ip_address' => Request::ip(),
		]);
	}
}
