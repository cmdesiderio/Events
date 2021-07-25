## Setting Up

Clone the repo and install dependencies:

```bash
git clone https://github.com/cmdesiderio/Events.git
cd events
composer install
```

Set up `.env` file:

```bash
cp .env.example .env
```

Create the database for your application and swap `MY_DB_HOST`, `MY_DB_NAME`, `MY_DB_USERNAME`, and `MY_DB_PASSWORD` with the appropriate values for your database:

```
DB_CONNECTION=mysql
DB_HOST=MY_DB_HOST
DB_PORT=3306
DB_DATABASE=MY_DB_NAME
DB_USERNAME=MY_DB_USERNAME
DB_PASSWORD=MY_DB_PASSWORD
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
- duration value for weekly and monthly events should not cause two event instance to overlap
- invitees must have unique user ids
```

Read
```
// return details of a single event
GET api/events/{eventId}

// return all event instance for a given dateTime range and/or invitees
GET api/events??from=2020-12-01 00:00&to=2020-12-31 00:00&invitees=1,2,3

validation
- filter for dateTime range is required
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
- accepted frequency value : Once-off, Weekly or Monthly
- endDateTime must be empty for Once-off frequency
- duration value for weekly and monthly events should not cause two event instance to overlap
- invitees must have unique user ids
```

Delete
```
// delete an event
DELETE api/events/{eventId}

```
