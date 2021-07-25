<?php

namespace App\Validations;

class FrequencyValidation
{
	const VALID_FREQUENCY = [
		'Once-Off',
		'Weekly',
		'Monthly'
	];

	static function validate($frequency){
		if (in_array($frequency, self::VALID_FREQUENCY)) {
			return true;
		}
		return false;
	}
}
