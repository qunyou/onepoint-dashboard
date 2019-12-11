<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * 人員群組
 * php artisan make:model Entities/RoleUser -m
 * php artisan make:migration create_role_users_table
 */
class RoleUser extends Model
{
    protected $fillable = [
        // 'created_at',
        // 'updated_at',

        // 人員關聯
        'user_id',

        // 群組關聯
        'role_id',
    ];
}
