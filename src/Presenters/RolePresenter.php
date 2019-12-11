<?php

namespace Onepoint\Dashboard\Presenters;

/**
 * 群組輔助方法
 */
class RolePresenter
{
    /**
     * 判斷checkbox是否勾選
     */
    static function is_check($role_permissions_array, $contain_string)
    {
        return isset($role_permissions_array[$contain_string]) && $role_permissions_array[$contain_string] == true ? ' checked' : '';
    }
}