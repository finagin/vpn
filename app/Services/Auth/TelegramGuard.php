<?php

namespace App\Services\Auth;

use App\Models\Telegram;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class TelegramGuard implements Guard
{
    use GuardHelpers, Macroable;

    public function __construct(
        UserProvider $provider,
        protected Request $request,
        protected string $token,
        protected string $header = 'Authorization',
        protected string $headerPrefix = 'TelegramMiniApp',
    ) {
        $this->provider = $provider;
    }

    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        if (! is_null($telegram = $this->getTelegramForRequest())) {
            $user = $this->provider->retrieveByCredentials([
                $telegram->user()->getForeignKeyName() => $telegram->getKey(),
            ]);
        }

        return $this->user = $user;
    }

    public function getTelegramForRequest(): ?Telegram
    {
        $initData = collect()
            ->pipe(function ($collection): Collection {
                parse_str(
                    Str::of($this->request->header($this->header))
                        ->after(rtrim($this->headerPrefix).' '),
                    $collection
                );

                return collect($collection);
            })
            ->sortKeys();

        if (! hash_equals($initData->pull('hash'), hash_hmac('sha256',
            $initData->implode(fn ($value, $key) => "$key=$value", "\n"),
            hash_hmac('sha256', $this->token, 'WebAppData', true)
        ))) {
            return null;
        }

        $telegramData = json_decode($initData->get('user'), true);

        return tap(Telegram::unguarded(fn () => Telegram::firstOrCreate([
            'id' => $telegramData['id'],
        ], $telegramData)), fn (Telegram $telegram) => $telegram->user()->firstOrCreate(values: [
            'name' => $telegramData['first_name'].' '.$telegramData['last_name'],
            'email' => 'id'.$telegramData['id'].'@t.me',
            'password' => Hash::make(Str::random()),
        ]));
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function validate(array $credentials = []): bool
    {
        return ! is_null($telegram = $this->getTelegramForRequest())
        && $this->provider->retrieveByCredentials([
            $telegram->user()->getForeignKeyName() => $telegram->getKey(),
        ]);
    }

    /**
     * Set the current request instance.
     *
     * @return $this
     */
    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }
}
