<?php

namespace App\Models;

use App\Models\Concerns\ApiAsSource;
use App\Services\Outline\Facade;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder<static>
 */
class Outline extends Model
{
    /** @use ApiAsSource<static> */
    use ApiAsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'bytesTransferred' => 'int',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'spending',
        'url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'port',
        'method',
        'accessUrl',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        static::creating(function (Outline $outline) {
            $outline->setRawAttributes(Facade::create(Arr::only(
                $outline->getAttributes(), ['name', 'password']
            )));
        });
    }

    /**
     * @return array<array-key, array<string, int|string>>
     */
    public function getRows(): array
    {
        return iterator_to_array(Facade::list());
    }

    // <editor-fold desc="Relationships">
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'name');
    }
    // </editor-fold>

    // <editor-fold desc="Mutators">
    /**
     * @return Attribute<string, string>
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 'Key #'.$attributes['id'],
        );
    }

    /**
     * @return Attribute<string, string>
     */
    public function spending(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => ($attributes['bytesTransferred'] ?? 0).' / 100 GB',
        );
    }

    /**
     * @return Attribute<string, string>
     */
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => sprintf(
                '%s#%s',
                $attributes['accessUrl'],
                rawurlencode('Finagin\'s Outline'),
            ),
        );
    }
    // </editor-fold>
}
