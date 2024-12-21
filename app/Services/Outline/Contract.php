<?php

namespace App\Services\Outline;

/**
 * @template TAccessKey of array{id: int, name: string, password: string, port: int, method: string, accessUrl: string, bytesTransferred: int}
 */
interface Contract
{
    public function commonLimit(): int;

    /**
     * @return ($keyId is null ? iterable<int, int> : int)
     */
    public function transfer(?int $keyId = null): int|iterable;

    /**
     * @return iterable<array-key, TAccessKey>
     */
    public function list(): iterable;

    /**
     * @return TAccessKey
     */
    public function get(int $id): array;

    /**
     * @param  array<string, mixed>  $attributes
     * @return TAccessKey
     */
    public function create(array $attributes = []): array;
}
