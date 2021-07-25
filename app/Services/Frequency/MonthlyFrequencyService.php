<?php

namespace App\Services\Frequency;

use App\Services\Frequency\FrequencyService;

class MonthlyFrequencyService extends FrequencyService
{
    public function eventInstance(object $event, string $startDateTime, string $endDateTime): array
	{
		$period = $this->period($event, $endDateTime);
		
		$items = [];
		foreach ($period as $key => $value) {
			
			$item = $this->getInstanceIfInDateRange($event, $this->checkDate($value, $event->start_date), $startDateTime, $endDateTime);
			
			if (! empty($item)) {
				$items[] = $item;
			}
		}
		return $items;
	}

	private function checkDate(object $value, string $startDate): string
	{
		$month = $value->format('m');
		$day   = dayFromDate($startDate);
		$year  = $value->format('Y');

		$date = checkdate($month, $day, $year);

		if ($date) {
			return  $value->format('Y-m-'. $day .' H:i');
		} else {
			return  $value->format('Y-m-t H:i');
		}
	}
}
