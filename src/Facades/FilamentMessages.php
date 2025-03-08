<?php

namespace Jeddsaliba\FilamentMessages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeddsaliba\FilamentMessages\FilamentMessages
 */
class FilamentMessages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Jeddsaliba\FilamentMessages\FilamentMessages::class;
    }
}
