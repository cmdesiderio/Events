<?php

namespace App\Services;

use App\Models\Event;

class InviteService
{
    public function getInvitees(int $eventId): array
	{
		$invitees = Event::find($eventId)->invite;

		$users = [];
		foreach ($invitees as $invite){
			$users[] =  $invite->user_id;
		}
		return $users;		
	}
	
	public function storeInvitees(object $event): void
    {
		if (! empty(request()->invitees)) {
			$newInvitees = [];
			foreach (request()->invitees as $invite) {
				$newInvitees[] = [
					'user_id'  => $invite
				];
			}
			$event->invite()->createMany($newInvitees);
		}
       
    }
}
