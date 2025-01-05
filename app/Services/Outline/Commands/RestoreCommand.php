<?php

namespace App\Services\Outline\Commands;

use App\Models\Outline;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'outline:restore')]
class RestoreCommand extends Command
{
    use Concerns\StorageInteraction;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outline:restore
                            {file=keys.json : Backup filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore outline keys to current server';

    /**
     * Execute the console command.
     *
     * @throws \JsonException
     */
    public function handle(): int
    {
        $this->ensureDirectoryExists();

        return $this
            ->getBackup()
            ->whenEmpty(
                fn () => $this->error(trans('outline::command.restore.not_found_or_empty')) ?? static::FAILURE,
                fn (Collection $backup) => $backup
                    ->map(fn (string $name, string $password) => Outline::updateOrCreate([
                        'name' => $name,
                        'password' => $password,
                    ]))
                    ->every(fn (Outline $outline) => $outline->exists || $outline->wasRecentlyCreated)
                    ? $this->info(trans_choice('outline::command.restore.success', $backup)) ?? static::SUCCESS
                    : $this->error(trans('outline::command.restore.failure')) ?? static::FAILURE
            );
    }
}
