<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Airport extends Model
{
    static function closestTo($latitude, $longitude)
    {
        return static::query()
            ->orderBy(DB::raw("st_distance(point($latitude, $longitude), point(latitude, longitude))"))
            ->take(5)
            ->get();
    }
}
