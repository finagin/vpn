<?php

namespace App\Services\Outline;

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
     * @throws \Illuminate\Http\Client\ConnectionException
     * @return \Illuminate\Support\Collection<array-key, TAccessKey>
     */
    public function list(): iterable
    {
        return $this->client
            ->get('access-keys')
            ->collect('accessKeys')
            ->map([$this, 'enrichAccessKey']);
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
     * @param TAccessKey $key
     * @return TAccessKey
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function enrichAccessKey(array $key): array
    {
        return [
            ...$key,
            'bytesTransferred' => $this->transfer($key['id']),
        ];
    }
}
