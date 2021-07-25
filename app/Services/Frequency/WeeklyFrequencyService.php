<?php

namespace App\Services\Frequency;

use App\Services\Frequency\FrequencyService;

class WeeklyFrequencyService extends FrequencyService
{
    public function eventInstance(object $event, string $startDateTime, string $endDateTime): array
	{
		$period = $this->period($event, $endDateTime);
		
		$items = [];
		foreach ($period as $key => $value) {
			
			$item = $this->getInstanceIfInDateRange($event, $value->format('Y-m-d H:i'), $startDateTime, $endDateTime);
			
			if (! empty($item)) {
				$items[] = $item;
			}
		}
		return $items;
	}
}
