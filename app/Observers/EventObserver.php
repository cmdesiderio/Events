<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\InviteService;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
		$invite = new InviteService;
		$invite->storeInvitees($event);
    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
		$event->invite()->delete();
		
        $invite = new InviteService;
		$invite->storeInvitees($event);
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        $event->invite()->delete();
    }
}
