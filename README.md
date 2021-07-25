This repo demonstrates how to use and test APIs in Laravel.

## Setting Up

Clone the repo and install dependencies:

```bash
git clone https://github.com/cmdesiderio/Events.gitt
cd events
composer install
```

Set up `.env` file:

```bash
cp .env.example .env
```

Create the database for your application and swap `YOUR_DATABASE_NAME`, `YOUR_DATABASE_USERNAME`, and `YOUR_DATABASE_PASSWORD` with the appropriate values for your database:

```
DB_CONNECTION=YOUR_DB_CONNECTOR
DB_PORT=3306 # 3306 for MySQL
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

Seed your database:

```bash
php artisan migrate --seed
```

Start your application:

```bash
php artisan serve
```

Run test:

```bash
php artisan test
```
or
```bash
vendor/bin/phpunit
```

End points
----------

Create
```
// create event
POST api/events

sample payload
{
    "eventName": "event 1",
    "frequency": "Weekly",
    "startDateTime": "2020-12-01 00:00",
    "endDateTime": "2020-12-15 00:00",
    "duration": 30, 
    "invitees": [1,2,3]
}

validation
- eventName, frequency, startDate are mandatory fields
- startDateTime and endDateTime must not be equal and have the correct format
- frequency accepted value : Once-off, Weekly or Monthly
- endDateTime must be empty for Once-off frequency
- event instance must not overlap for weekly and monthly frequency
- invitees must have unique user ids
```

Read
```
// return details of a single event
GET api/events/{eventId}

// return all event instance for a given dateTime range and/or invitees
GET api/events??from=2020-12-01 00:00&to=2020-12-31 00:00&invitees=1,2,3

validation
- dfilter for dateTime range is required
```

Update
```
// update an event
PUT api/events/{eventId}

sample payload
{
    "eventName": "event 1",
    "frequency": "Monthly",
    "startDateTime": "2020-01-01 00:00",
    "endDateTime": "2020-12-31 00:00",
    "duration": 30, 
    "invitees": [1,2,3]
}

validation
- eventName, frequency, startDate are mandatory fields
- startDateTime and endDateTime must not be equal and have the correct format
- frequency accepted value : Once-off, Weekly or Monthly
- endDateTime must be empty for Once-off frequency
- event instance must not overlap for weekly and monthly frequency
- invitees must have unique user ids
- check if event exist
```

Delete
```
// delete an event
DELETE api/events/{eventId}

validation
- check if event exist
```
