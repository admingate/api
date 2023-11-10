<?php

namespace Admingate\Api\Facades;

use Admingate\Api\Supports\ApiHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Admingate\Api\Supports\ApiHelper
 */
class ApiHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiHelper::class;
    }
}
