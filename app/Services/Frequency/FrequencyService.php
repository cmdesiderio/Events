<?php
namespace App\Services\Frequency;

use App\Services\InviteService as Invite;

use DateTime;
use DatePeriod;
use DateInterval;

Abstract class FrequencyService
{
	abstract function eventInstance(object $event, string $startDateTime, string $endDateTime): array;

	protected function getInstanceIfInDateRange(object $event, string $date, string $startDateTime, string $endDateTime): array
	{
		if ($date >= $startDateTime && $date <= $endDateTime) {
			$invite = new Invite();
			return [
				'eventId'       => $event->id,
				'eventName'     => $event->event_name,
				'startDateTime' => $date,
				'endDateTime'   => endDateTime($date, $event->duration),
				'invitees'      => $invite->getInvitees($event->id)
			];
		}
		return [];
	}

	protected function period(object $event, string $endDateTime): object
	{
		$endDate = $event->end_date == null ? $endDateTime : $event->end_date;

		switch ($event->frequency) {
			case 'Weekly':
				return weeklyInterval($event->start_date, $endDate);
			case 'Monthly':
				return monthlyInterval($event->start_date, $endDate);
			default:
				return (object) [];
		}
		
	}
}
