<?php

declare(strict_types = 1);

namespace App;

use App\Notifications\ResetAdminPasswordNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Admin
 *
 * @package App
 * @property int $id
 * @property string|null $name
 * @property string|null $last_name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property bool $active
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Collection|Roles[] $roles
 * @property-read int|null $notifications_count
 * @property-read int|null $roles_count
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereActive($value)
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereEmail($value)
 * @method static Builder|Admin whereEmailVerifiedAt($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin whereLastName($value)
 * @method static Builder|Admin whereName($value)
 * @method static Builder|Admin wherePassword($value)
 * @method static Builder|Admin whereRememberToken($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'active',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Roles::class,
            'admin_role',
            'admin_id',
            'role_id'
        );
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetAdminPasswordNotification($token));
    }

}
