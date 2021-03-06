<?php

namespace Onepoint\Dashboard\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\CustomResetPasswordNotification;

/**
 * 人員
 * 
 * 產生預設資料
 * php artisan make:seeder UsersTableSeeder
 * composer dump-autoload
 * php artisan db:seed --class=UsersTableSeeder
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'old_version',
        'origin_id',
        'update_user_id',

        'enable',
        'username',
        'realname',
        'password',
        'email',
        'confirmation_code',
        'gender',
        'tel',
        'state',
        'zipcode',
        'county',
        'district',
        'address',
		'remember_token',
		'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function roles()
    {
        return $this->belongsToMany('Onepoint\Dashboard\Entities\Role', 'role_users');
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions) : bool
    {
        // check if the permission is available in any role
        if (config('backend.user.use_role', false)) {
            foreach ($this->roles as $role) {
                if($role->hasAccess($permissions)) {
                    return true;
                }
            }
        } else {
            return true;
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('role_name', $roleSlug)->count() == 1;
    }
}

// ALTER TABLE `users` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL, CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT NULL;
// ALTER TABLE `users` ADD `old_version` BOOLEAN NOT NULL AFTER `deleted_at`;
// ALTER TABLE `users` ADD `origin_id` INT NOT NULL AFTER `old_version`;
// ALTER TABLE `users` ADD `update_user_id` INT NOT NULL DEFAULT '0' AFTER `origin_id`;