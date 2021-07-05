<?php

namespace Onepoint\Base\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\CustomResetPasswordNotification;

/**
 * 會員
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
        'status',
        'note',

        'enable',

        // 姓名
        'username',
        'realname',
        'password',

        // 帳號
        'email',
        'confirmation_code',

        // 性別
        // 'gender',
        // 'tel',
        // 'state',
        // 'zipcode',
        // 'county',
        // 'district',
        // 'address',
		'remember_token',
        'confirmed',
        
		// 'birthday',
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
        return $this->belongsToMany('Onepoint\Dashboard\Entities\Role', 'role_users', 'user_id', 'role_id');
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

    // 訂購項目關聯
    public function order()
    {
        return $this->hasMany('Onepoint\Base\Entities\Order');
    }

    // 保固登錄關聯
    public function warranty()
    {
        return $this->hasMany('Onepoint\Base\Entities\Warranty');
    }
}
