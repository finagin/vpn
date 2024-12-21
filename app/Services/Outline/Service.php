<?php

namespace App\Services\Outline;

use Illuminate\Support\Arr;

/**
 * @template TAccessKey of array{id: int, name: string, password: string, port: int, method: string, accessUrl: string, bytesTransferred: int}
 *
 * @implements \App\Services\Outline\Contract<TAccessKey>
 */
readonly class Service implements Contract
{
    public function __construct(
        private ApiClient $client,
    ) {}

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function commonLimit(): int
    {
        return $this->client
            ->get('server')
            ->json('accessKeyDataLimit.bytes');
    }

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function transfer(?int $keyId = null): int|iterable
    {
        /** @var \Illuminate\Support\Collection<int, int> $bytesTransferred */
        $bytesTransferred = $this->client
            ->get('metrics/transfer')
            ->collect('bytesTransferredByUserId');

        return $keyId !== null
            ? $bytesTransferred[$keyId] ?? 0
            : $bytesTransferred;
    }

    /**
     * @return \Illuminate\Support\Collection<array-key, TAccessKey>
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function list(): iterable
    {
        return $this->client
            ->get('access-keys')
            ->collect('accessKeys')
            ->map(fn ($key) => $this->enrichAccessKey($key));
    }

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function get(int $id): array
    {
        return $this->enrichAccessKey(
            $this->client->get("access-keys/$id")->json()
        );
    }

    /**
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function create(array $attributes = []): array
    {
        return $this->enrichAccessKey(
            $this->client->post('access-keys', $attributes)->json()
        );
    }

    /**
     * @param  TAccessKey  $key
     * @return TAccessKey
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function enrichAccessKey(array $key): array
    {
        return [
            ...Arr::except($key, 'dataLimit'),
            'limit' => data_get($key, 'dataLimit.bytes', $this->commonLimit()),
            'spending' => $this->transfer($key['id']),
        ];
    }
}
