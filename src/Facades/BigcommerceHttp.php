<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RobMellett\BigcommerceHttp\BigcommerceHttp
 */
class BigcommerceHttp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RobMellett\BigcommerceHttp\BigcommerceHttp::class;
    }
}
