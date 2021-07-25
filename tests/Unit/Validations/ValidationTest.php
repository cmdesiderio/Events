<?php

namespace Tests\Unit\Validations;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    
	public function testPayloadNoEventName()
	{
		$payload = [
			// "eventName"     => "Event name",
			"frequency"     => "Weekly",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-30 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadNoFrequency()
	{
		$payload = [
			"eventName"     => "Event name",
			// "frequency"     => "Weekly",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-30 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadNoStartDatetime()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Weekly",
			// "startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-30 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadStartDatetimeFormat()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Weekly",
			"startDateTime" => "20201-12-01 00:00",
			"endDateTime"   => "2020-12-01 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadEndDatetimeFormat()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Weekly",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "20201-12-01 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}
	
	public function testPayloadEqualStartAndEndDatetime()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Weekly",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-01 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadInvalidFrequency()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Every 100 Years",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-31 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadOnceOffEndDatetimeNotNull()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Once-Off",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-31 00:00",
			"duration"      => rand(1,1000),
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadOverlapInstanceWeekly()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Once-Off",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-31 00:00",
			"duration"      => 10081,
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testPayloadOverlapInstanceMonthly()
	{
		$payload = [
			"eventName"     => "Event name",
			"frequency"     => "Once-Off",
			"startDateTime" => "2020-12-01 00:00",
			"endDateTime"   => "2020-12-31 00:00",
			"duration"      => 43801,
			"invitees"      => [1]
		];
	
       $this->postJson('/api/events', $payload)
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}

	public function testEventNotFound()
	{
		$this->getJson('/api/events/0')
		->assertStatus(404)
		->assertJson([
			"message"=> true
		]);
	}

	public function testInvalidFilter()
	{
		$this->getJson('/api/events?from_date=2020-12-01 00:00&to=2020-12-31 00:00&invitees=100,101,102')
		->assertStatus(422)
		->assertJson([
			"status" => 0,
			"message"=> true
		]);
	}
}