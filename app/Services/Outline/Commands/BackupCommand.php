<?php

namespace App\Services\Outline\Commands;

use App\Services\Outline\Facade as Outline;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'outline:backup')]
class BackupCommand extends Command
{
    use Concerns\StorageInteraction;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'outline:backup
                            {file=keys.json : Backup filename}
                            {--force : Overwrite existing backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup outline keys from current server';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->ensureDirectoryExists();

        return $this
            ->putBackup(
                $backup = collect(Outline::list())
                    ->filter(fn ($key) => filled($key['name']))
                    ->pluck('name', 'password')
            )
            ? $this->info(trans_choice('outline::command.backup.success', $backup)) ?? static::SUCCESS
            : $this->error(trans('outline::command.backup.failure')) ?? static::FAILURE;
    }
}
