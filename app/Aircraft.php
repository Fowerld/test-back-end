<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Aircraft extends AbstractModel
{
    const NUMBER_OF_RESULTS = 0;
    const MAX_DISTANCE_IN_METERS = 50000;
    const TABLE_NAME = 'aircrafts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getAirport()
    {
        return $this->belongsTo('App\Airport', 'id');
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getFromAirportId(int $airportId)
    {
        $queryResult = static::query()
            ->where('airport_id', $airportId)
            ->orderBy(DB::raw(sprintf("st_distance(point(%f, %f), point(latitude, longitude))", $latitude, $longitude)))
            ->take($limit)
            ->get();

        return $queryResult;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $maxDistance
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getClosestFromCoordinates(float $latitude, float $longitude, int $maxDistance = Aircraft::MAX_DISTANCE_IN_METERS)
    {
        return self::getClosestsFromCoordinates($latitude, $longitude, $maxDistance = Aircraft::MAX_DISTANCE_IN_METERS, 1);
    }
}

