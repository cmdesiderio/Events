<?php

namespace App\Validations\Event;

class FilterParamValidation 
{
	public static function validate(object $request): bool
	{
		if ($request->input('from') && $request->input('to')) {
			return true;
		} else {
			return false;
		}
	}
}
