<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @property-read array<string, string> $schema
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder<static>
 */
trait ApiAsSource
{
    // <editor-fold desc="Boot and initialize trait">
    public static function bootApiAsSource(): void
    {
        app('config')->set('database.connections.'.static::class, [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        (new static)
            ->migrate()
            ->filling();
    }
    // </editor-fold>

    // <editor-fold desc="Override eloquent methods">
    public function getConnectionName()
    {
        return static::class;
    }

    protected function newRelatedInstance($class)
    {
        return tap(new $class, function (Model $instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection($this->getConnectionResolver()->getDefaultConnection());
            }
        });
    }
    // </editor-fold>

    // <editor-fold desc="Getters">
    /**
     * @return array<array-key, TModel>
     */
    abstract public function getRows(): array;

    /**
     * @return array<string, string>
     */
    public function getSchema(): array
    {
        return $this->schema ?? [];
    }
    // </editor-fold>

    // <editor-fold desc="Migration and filling">
    protected function migrate(): static
    {
        $this->getConnection()->getSchemaBuilder()->create($this->getTable(), function (Blueprint $table) {
            $schema = $this->getMigrationSchema();

            in_array(Arr::pull($schema, $this->getKeyName(), $this->getKeyType()), ['int', 'integer', 'float'])
                ? $table->unsignedBigInteger($this->getKeyName(), $this->getIncrementing())
                : $table->string($this->getKeyName());

            foreach ($schema as $column => $type) {
                $table->{$type}($column)->nullable();
            }

            $this->usesTimestamps() && ! array_intersect($this->getDates(), array_keys($schema))
            && $table->timestamps();
        });

        return $this;
    }

    protected function filling(): static
    {
        foreach (array_chunk($this->getRows(), 100) as $inserts) {
            static::insert($inserts);
        }

        return $this;
    }

    /**
     * @return array<string, string>
     */
    private function getMigrationSchema(): array
    {
        return array_merge(
            array_map(fn ($value) => match (true) {
                is_numeric($value) && (string) (int) $value === $value,
                is_int($value) => 'integer',
                is_numeric($value) => 'float',
                $value instanceof \DateTime => 'timestamp',
                default => 'string',
            }, Arr::first($this->getRows(), default: [])),
            $this->getSchema(),
        );
    }
    // </editor-fold>
}
