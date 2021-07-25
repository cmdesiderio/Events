<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Event;

class EventControllerTest extends TestCase
{
    public function testEventPostEndpointApi()
    {
		$payload = [
			"eventName"     => "Event Post Endpoint Test",
			"frequency"     => "Weekly",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-30 00:00",
			"duration"      => 30,
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(201)
		->assertJson([
			"status" => 1,
			"items"=> []
		]);

		$this->assertDatabaseHas('events', [
			'event_name' => $payload['eventName'],
			'frequency'  => $payload['frequency'],
			'start_date' => $payload['startDateTime'],
			'end_date'   => $payload['endDateTime'],
			'duration'   => $payload['duration']
		]);
		
		$this->assertDatabaseHas('event_invitees', 
				['user_id' => 1]
		);
	}

	public function testEventGetEndpointApi()
    {
		request()->invitees = [1,2,3];

		$event = Event::create([
			"event_name" => "Event Get Endpoint Test",
			"frequency"  => "Weekly",
			"start_date" => "2020-12-01 00:00",
		]);
	
       $this->getJson('/api/events/'.$event->id)
		->assertStatus(200)
		->assertJson([
			"status" => 1,
			"items"=> []
		]);
	}

	public function testEventPutEndpointApi()
    {
		request()->invitees = [1,2,3];

		$event = Event::create([
			"event_name" => "Event Put Endpoint Test",
			"frequency"  => "Weekly",
			"start_date" => "2020-12-01 00:00",
		]);

		$payload = [
			"eventName"     => "Event Put Updated Endpoint Test",
			"frequency"     => "Monthly",
			"startDateTime" => "2020-12-01 00:00",
			"invitees" 		=> [4],
		];

		$this->putJson('/api/events/'.$event->id, $payload)
		->assertStatus(200)
		->assertJson([
			"status" => 1,
			"items"=> []
		]);

		$this->assertDatabaseHas('events', [
			'id'         => $event->id,
			'event_name' => $payload['eventName'],
			'frequency'  => $payload['frequency'],
			'start_date' => $payload['startDateTime']
		]);
		
		$this->assertDatabaseHas('event_invitees', [
			'event_id' => $event->id,
			'user_id'  => 4
		]);
	}

	public function testEventDeleteEndpointApi()
    {
		request()->invitees = [1,2,3];

		$event = Event::create([
			"event_name" => "Event Put Endpoint Test",
			"frequency"  => "Weekly",
			"start_date" => "2020-12-01 00:00",
		]);

		$this->deleteJson('/api/events/'.$event->id)
		->assertStatus(200)
		->assertJson([
			"status"  => 1,
			"message" => true
		]);

		$this->assertDatabaseMissing('events', [
			'id' => $event->id
		]);
		
		$this->assertDatabaseMissing('event_invitees', [
			'event_id' => $event->id
		]);
	}

	public function testEventFilterDateRangeEndpointApi()
    {
		request()->invitees = [100,101,102];

		$event = Event::create([
			"event_name" => "Event Put Endpoint Test",
			"frequency"  => "Weekly",
			"start_date" => "2020-12-01 00:00",
			"end_time"   => "2020-12-31 00:00",
			"duration"   => 60,
		]);

		$this->getJson('/api/events?from=2020-12-01 00:00&to=2020-12-31 00:00')
		->assertStatus(200)
		->assertJson([
			"status" => 1,
			"count"  => true,
			"items"  => []
		]);
	}

	public function testEventFilterDateRangeInviteesEndpointApi()
    {
		request()->invitees = [100,101,102];

		$event = Event::create([
			"event_name" => "Event Put Endpoint Test",
			"frequency"  => "Weekly",
			"start_date" => "2020-12-01 00:00",
			"end_time"   => "2020-12-31 00:00",
			"duration"   => 60,
		]);

		$this->getJson('/api/events?from=2020-12-01 00:00&to=2020-12-31 00:00&invitees=100,101,102')
		->assertStatus(200)
		->assertJson([
			"status" => 1,
			"count"  => true,
			"items"  => []
		]);
	}
}