<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    /**
     * @param int $id
     *
     * @return mixed
     */
    public static function fetch(int $id)
    {
        return static::find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAll(): array
    {
        return static::all();
    }
}