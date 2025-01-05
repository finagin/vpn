<?php

namespace App\Services\Outline\Commands\Concerns;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \Illuminate\Console\Command
 */
trait StorageInteraction
{
    /**
     * Get the storage instance.
     */
    private function storage(): Filesystem
    {
        return Storage::build(storage_path('outline'));
    }

    /**
     * Ensure the directory exists and ignore file is created.
     */
    protected function ensureDirectoryExists(): void
    {
        if (! $this->storage()->exists($ignoreFile = '.gitignore')) {
            $this->storage()->put($ignoreFile, "*\n");
        }
    }

    /**
     * Get the backup data.
     *
     * @throws \JsonException
     */
    protected function getBackup(): Collection
    {
        return collect(json_decode(
            $this->storage()->get($this->argument('file')),
            flags: JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR
        ));
    }

    /**
     * Put the backup data.
     */
    protected function putBackup(Collection $collection): bool
    {
        if (! $this->storage()->exists($this->argument('file')) || $this->isForceOrConfirm()) {
            return $this->storage()->put($this->argument('file'), $collection->toJson());
        }

        return false;
    }

    protected function isForce(): bool
    {
        return $this->hasOption('force') && $this->option('force');
    }

    protected function isForceOrConfirm(): bool
    {
        return $this->isForce() || $this->confirm(trans('outline::command.backup.rewrite_confirmation'));
    }
}
