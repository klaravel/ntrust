<?php namespace Klaravel\Ntrust;

use Illuminate\Support\Facades\Facade;

class NtrustFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ntrust';
    }
}