<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Validations\Event\{
	StoreParamValidation as EventStore,
	UpdateParamValidation as EventUpdate,
	FilterParamValidation as EventFilter
};
use App\Services\{
	EventService,
	InviteService as Invite
};
use Illuminate\Http\Request;

class EventController extends Controller
{	
	/**
	 * TODO
	 * Filter for frequency
	 */
    public function index(EventService $eventService, Request $request): object
	{
		if (! EventFilter::validate($request)){
			return response()->json([
				'status'  => 0,
				'message' => 'Must have filter for event date range (from=yyyy-mm-dd hh:mm&to=yyyy-mm-dd hh:mm) and/or invitees (user_id1,user_id2,etc)'
			], 422);
		}

		$items = $eventService->getEvents($request);
		return response()->json([
			'status' => 1,
			'count'  => count($items),
			'items'  => $items
		], 200);
	}

	public function show($eventId, Invite $invite): object
	{
		$event = Event::findOrFail($eventId);
		$items = [
			'eventId'       => $event->id,
			'eventName'     => $event->event_name,
			'startDateTime' => shortDateTime($event->start_date),
			'endDateTime'   => shortDateTime($event->end_date),
			'duration'      => $event->duration,
			'created_at'    => shortDateTime($event->created_at),
			'updated_at'    => shortDateTime($event->updated_at),
			'invitees'      => $invite->getInvitees($event->id)
		];
		return response()->json([
			'status' => 1,
			'items' => $items
		], 200);
	}

	/**
     * TODO
     * Store more than 1 schedule for each event, example Monday and Friday events.
     * Additional supported frequency like bi-weekly, bi-monthly, quarterly, annual etc
     */
	public function store(Request $request): object
	{
		$input = json_decode($request->getContent(), true);

		$validate = EventStore::validate($input);
		if (! $validate[0]){
			return response()->json([
				'status'  => 0,
				'message' => $validate[1]
			], $validate[2] ?? 422);
		}

		$newEvent = [
			'event_name' => $input['eventName'],
			'frequency'  => $input['frequency'],
			'start_date' => $input['startDateTime'],
			'end_date'   => $input['endDateTime'] ?? null,
			'duration'   => $input['duration'] ?? null
		];
		
		$event = Event::create($newEvent);
		
		return response()->json([
			'status' => 1,
			'items'  => array_merge(
				['eventId' => $event->id], 
				$newEvent, 
				['invitees' => (! empty($input['invitees'])) ? $input['invitees'] : []]
			)
		], 201); 
	}

	/**
     * TODO
     * Update should only affect future events, will retain past event details
     * Reschedule of specific event instance 
     */
	public function update($eventId, Request $request): object
	{
		$input = json_decode($request->getContent(), true);

		$validate = EventUpdate::validate($input);
		if (! $validate[0]){
			return response()->json([
				'status'  => 0,
				'message' => $validate[1]
			], $validate[2] ?? 422);
		}

		$event = Event::findOrFail($eventId);
		$event->event_name = $input['eventName'];
		$event->frequency  = $input['frequency'];
		$event->start_date = $input['startDateTime'];
		$event->end_date   = $input['endDateTime'] ?? null;
		$event->duration   = $input['duration'] ?? null;
		$event->save();
		
		return response()->json([
			'status' => 1,
			'items'  =>  [
				'eventId'    => $event->id,
				'event_name' => $input['eventName'],
				'frequency'  => $input['frequency'],
				'start_date' => $input['startDateTime'],
				'end_date'   => $input['endDateTime'] ?? null,
				'duration'   => $input['duration'] ?? null,
				'invitees'   => (! empty($input['invitees'])) ? $input['invitees'] : []
			]
		], 200); 
	}

	/**
     * TODO
     * Delete should only remove future events, will retain past events.
     */
	public function destroy($eventId): object
	{
		$event = Event::findOrFail($eventId);
		$event->delete();

		return response()->json([
			'status' => 1,
			'message'  => 'Delete successful'
		], 200); 
	}
}
