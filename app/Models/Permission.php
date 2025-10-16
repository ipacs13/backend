<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    const PERMISSION_USER_CREATE_USER = 'user.create.user';
    const PERMISSION_USER_READ_USER = 'user.read.user';
    const PERMISSION_USER_UPDATE_USER = 'user.update.user';
    const PERMISSION_USER_DELETE_USER = 'user.delete.user';

    const PERMISSION_USER_CREATE_PRODUCT = 'user.create.product';
    const PERMISSION_USER_READ_PRODUCT = 'user.read.product';
    const PERMISSION_USER_UPDATE_PRODUCT = 'user.update.product';
    const PERMISSION_USER_DELETE_PRODUCT = 'user.delete.product';


    const USER_PERMISSIONS = [
        self::PERMISSION_USER_CREATE_USER => 'Create User',
        self::PERMISSION_USER_READ_USER => 'Read User',
        self::PERMISSION_USER_UPDATE_USER => 'Update User',
        self::PERMISSION_USER_DELETE_USER => 'Delete User',
        self::PERMISSION_USER_CREATE_PRODUCT => 'Create Product',
        self::PERMISSION_USER_READ_PRODUCT => 'Read Product',
        self::PERMISSION_USER_UPDATE_PRODUCT => 'Update Product',
        self::PERMISSION_USER_DELETE_PRODUCT => 'Delete Product',
    ];
}
