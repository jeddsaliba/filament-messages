<?php

namespace Jeddsaliba\FilamentMessages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeddsaliba\FilamentMessages\FilamentMessages
 */
class FilamentMessages extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Jeddsaliba\FilamentMessages\FilamentMessages::class;
    }
}
