<?php

namespace App\Validations\Event;
use App\Validations\FrequencyValidation as Frequency;

Abstract class ParamValidation
{
	public const OVERLAP_DURATION = [
		'weekly' => 10080,
		'monthly' => 43800
	]; 

	static function validate(?array $input): array
	{

		if (is_null($input)){
			return [false, 'Invalid JSON format', 400];
		}
	
		// validate required parameters are present
		if (empty($input['eventName']) || empty($input['frequency']) || empty($input['startDateTime'])) {
			return [false, 'Event name, frequency and start datetime are required fields.'];
		}

		// validate start and end datetime must not be equal 
		if (! empty($input['endDateTime'])) {
			if ($input['startDateTime'] == $input['endDateTime']) {
				return [false, 'Start and end datetime should not be equal.'];
			}	
		}

		// validate start and end datetime format
		if (! validateFormat($input['startDateTime'])) {
			return [false, 'Invalid date for start datetime'];
		}
		if (! empty($input['endDateTime'])) {
			if (! validateFormat($input['endDateTime'])) {
				return [false, 'Invalid date for end datetime'];
			}	
		}

		// validate frequency value
		if (! Frequency::validate($input['frequency'])) {
			return [false, 'Invalid frequency value.'];
		}

		// validate end datetime must be empty for once off event
		if ($input['frequency'] == 'Once-Off' && ! empty($input['endDateTime'])	) {
			return [false, 'For once-off event, end datetime must be empty.'];
		}

		// validate if duration will cause same event overlap
		if (! empty($input['duration'])) {
			if (
				(
					$input['frequency'] == 'Weekly' 
					&& $input['duration'] > self::OVERLAP_DURATION['weekly']
				)
				|| (
					$input['frequency'] == 'Monthly' 
					&& $input['duration'] > self::OVERLAP_DURATION['monthly']
				)
			) {
				return [false, 'Duration should not cause two event instance to overlap.'];
			}
		}

		//
		if (! empty($input['invitees'])) {
			if (count($input['invitees']) !== count(array_unique($input['invitees']))) {
				return [false, 'There`s a duplicate in invitees.'];
			}
		}
		

		return [true];
	}

}
