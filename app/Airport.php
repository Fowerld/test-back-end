<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Airport extends AbstractModel
{
    const NUMBER_OF_RESULTS = 5;
    const MAX_DISTANCE_IN_METERS = 50000;
    const TABLE_NAME = 'airports';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAircrafts()
    {
        return $this->hasMany('App\Aircraft', 'airport_id');
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getClosestsFromCoordinates(float $latitude, float $longitude, int $maxDistance = Airport::MAX_DISTANCE_IN_METERS,int $limit = Airport::NUMBER_OF_RESULTS)
    {
        $queryResult = static::query()
            ->whereRaw(DB::raw(sprintf("st_distance_sphere(point(%f, %f), point(longitude, latitude)) < %d", $longitude, $latitude, $maxDistance)))
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
     * @return Airport
     */
    public static function getClosestFromCoordinates(float $latitude, float $longitude, int $maxDistance = Aircraft::MAX_DISTANCE_IN_METERS)
    {
        return self::getClosestsFromCoordinates($latitude, $longitude, $maxDistance = Aircraft::MAX_DISTANCE_IN_METERS, 1)->get(0);
    }
}
