<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Currency implements CastsAttributes
{
    /**
     * Divide the value with 100 from storage.
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value / 100;
    }

    /**
     * Multiply the value with 100 to store in storage.
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value * 100;
    }
}
