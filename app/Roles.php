<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Roles
 *
 * @package App
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property int $full_access
 * @property array $accessible_routes
 * @property string|null $description
 * @method static Builder|Roles newModelQuery()
 * @method static Builder|Roles newQuery()
 * @method static Builder|Roles query()
 * @method static Builder|Roles whereAccessibleRoutes($value)
 * @method static Builder|Roles whereCreatedAt($value)
 * @method static Builder|Roles whereDescription($value)
 * @method static Builder|Roles whereFullAccess($value)
 * @method static Builder|Roles whereId($value)
 * @method static Builder|Roles whereName($value)
 * @method static Builder|Roles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Roles extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'full_access',
        'accessible_routes',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'accessible_routes' => 'array',
    ];
}
