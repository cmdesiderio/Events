<?php

function shortDateTime(?string $date): string
{
	return $date ? date('Y-m-d H:i', strtotime($date)) : '';
}

function dayFromDate(string $date): string
{
	return date('d', strtotime($date));
}

function endDateTime(string $date, int $duration): string
{
	return date('Y-m-d H:i', strtotime("+".$duration." minutes", strtotime($date)));
}

function weeklyInterval(string $startDate, string $endDate): object
{
	return new DatePeriod(
		new DateTime($startDate),
		new DateInterval('P7D'),
		new DateTime($endDate)
	);
}

function monthlyInterval(string $startDate, string $endDate): object
{
	return new DatePeriod(
		(new DateTime($startDate))->modify('first day of this month'),
		DateInterval::createFromDateString('1 month'),
		(new DateTime($endDate))->modify('first day of next month')
	);
}

function validateFormat($date, $format = 'Y-m-d H:i'): bool
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) === $date;
}

