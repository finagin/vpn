<?php

declare(strict_types=1);

namespace App\Services\Outline;

use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

/**
 * @mixin \Illuminate\Http\Client\PendingRequest
 */
readonly class ApiClient
{
    protected UuidInterface $uuid;

    protected PendingRequest $client;

    /**
     * @param  array<array-key, string>  $needsCache
     * @param  Collection<array-key, string>  $cached
     */
    public function __construct(
        #[Config('services.outline.api.host')] string $host,
        #[Config('services.outline.api.port')] string $port,
        #[Config('services.outline.api.secret')] string $secret,
        #[Config('services.outline.api.secret', false)] string $selfSigned,
        protected array $needsCache = ['get'],
        protected Collection $cached = new Collection,
    ) {
        $this->uuid = Str::uuid();
        $this->client = Http::baseUrl("https://$host:$port/$secret")
            ->withOptions([
                'verify' => ! $selfSigned,
            ])
            ->acceptJson()
            ->asJson();
    }

    /**
     * @param  array<array-key, mixed>  $args
     */
    protected function getCacheKey(...$args): string
    {
        return (string) tap(
            $this->uuid.md5(serialize($args)),
            fn ($key) => $this->cached->push($key)
        );
    }

    /**
     * @param  array<array-key, mixed>  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return in_array($name, $this->needsCache)
            ? Cache::remember(
                $this->getCacheKey(func_get_args()), 60,
                fn () => tap($this->client->$name(...$arguments), fn (Response $response) => $response->json())
            )
            : tap(
                $this->client->$name(...$arguments),
                fn () => $this->cached->each(fn ($key) => Cache::forget($key))
            );
    }
}
