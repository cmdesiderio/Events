<?php

namespace App\Services;

use App\Models\Event;
use App\Services\{
	Frequency\WeeklyFrequencyService as Weekly,
	Frequency\MonthlyFrequencyService as Monthly,
	InviteService as Invite
};

class EventService
{
	private $event;
	private $invite;
	private $weekly;
	private $monthly;

	public function __construct()
	{
		$this->event   = new Event();
		$this->invite  = new Invite();
		$this->weekly  = new Weekly();
		$this->monthly = new Monthly();
	}

    public function getEvents(object $request): array
	{
		$events = $this->event->getByFilter($request);

		$items = [];
		foreach ($events as $event) {
			$items = array_merge($items, $this->getEventInstances($event, $request));
		}
		return $items;		
	}

	public function getEventInstances(object $event, object $request): array
	{
		switch ($event->frequency) {
			case 'Once-Off':
				return  [[
					'eventId'       => $event->id,
					'eventName'     => $event->event_name,
					'startDateTime' => shortDateTime($event->start_date),
					'endDateTime'   => endDateTime($event->start_date, $event->duration),
					'invitees'      => $this->invite->getInvitees($event->id)
				]];
			case 'Weekly':
				return $this->weekly->eventInstance($event, $request->from, $request->to);
			case 'Monthly':
				return $this->monthly->eventInstance($event, $request->from, $request->to);
			default:
				return [];
		}
	}
}
