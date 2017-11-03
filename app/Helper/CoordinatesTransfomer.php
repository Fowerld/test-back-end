<?php

namespace App\Helper;

use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class CoordinatesTransfomer
{
    /**
     * @param Coordinate $from
     * @param Coordinate $to
     *
     * @return float
     */
    public static function calculateDistance(Coordinate $from, Coordinate $to): float
    {
        $geotools = new Geotools();
        $distance = $geotools->distance()->setFrom($from)->setTo($to);

        return $distance->in('km')->haversine();
    }
}
