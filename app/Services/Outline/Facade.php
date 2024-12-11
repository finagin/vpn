<?php

namespace App\Services\Outline;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @template TAccessKey of array{id: int, name: string, password: string, port: int, method: string, accessUrl: string, bytesTransferred: int}
 *
 * @mixin \App\Services\Outline\Contract<TAccessKey>
 */
final class Facade extends BaseFacade
{
    protected static function getFacadeAccessor(): string
    {
        return Contract::class;
    }
}
