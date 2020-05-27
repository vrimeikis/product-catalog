<?php

declare(strict_types = 1);

namespace Modules\Api\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Modules\Api\Entities\ApiKey
 *
 * @property int $id
 * @property string $title
 * @property string $app_key
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ApiKey newModelQuery()
 * @method static Builder|ApiKey newQuery()
 * @method static Builder|ApiKey query()
 * @method static Builder|ApiKey whereActive($value)
 * @method static Builder|ApiKey whereAppKey($value)
 * @method static Builder|ApiKey whereCreatedAt($value)
 * @method static Builder|ApiKey whereId($value)
 * @method static Builder|ApiKey whereTitle($value)
 * @method static Builder|ApiKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiKey extends Model
{
    protected $fillable = [
        'title',
        'app_key',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
