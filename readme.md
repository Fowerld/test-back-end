## CaptainJet Technical Test

Starting from a (almost) blank Laravel application, you're going to built a small aircraft search engine that will take in geographical coordinates and output a list of aircraft ordered by price.

### Requirements

- PHP >= 7.0.0
- Composer

### Database

You will be provided by an initial database of aircraft and airports.
> `$ ./artisan migrate --seed`

#### Airports

- `id` - `int`
- `name` - `string`
- `latitude` - `decimal`
- `longitude` - `decimal`

#### Aircraft

- `id` - `int`
- `airport_id` - `int` - Airport where the aircraft is currently located
- `name` - `string`
- `speed` - `int` - Speed in km/h
- `hourly_cost` - `int` - Cost per hour in cents

### API

Implement the following endpoint:

#### Request
```
GET /api/aircraft?from=lat,lng&to=lat,lng
```

#### Response
```javascript
{
    "data": [
        {
            "id": 123,
            "name": "Aircraft name",
            "time": 1.25,
            "cost": 1250000,
            "departure_airport": "Charles de Gaulle",
            "arrival_airport": "London"
        },
        {
            "id": 125,
            "name": "Another aircraft name",
            "time": 1.1,
            "cost": 1400000,
            "departure_airport": "Orly",
            "arrival_airport": "London"
        }
    ]
}
```

### Instructions

You should only consider the first 5 airports within 50km of the given coordinates.

The `time` field should be computed using the aircraft speed and the distance between 2 airports.

The `cost` field should be computed using the time and the hourly cost.

You probably can't do the whole thing in 1 SQL query. That's not the goal.

### Hints

- A `closestTo()` method is already implement on `Airport`.
- [league/geotools](https://github.com/thephpleague/geotools#distance) is a good choice to calculate distance between points. The package is already installed.

### Bonus

- Handle error where there are no eligible airports.
- Handle request input validation with proper HTTP status.
- Write tests.
- Nice code.
