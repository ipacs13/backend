<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    const ROLE_ADMIN = 'Administrator';
    const ROLE_USER = 'User';
    const ROLE_CUSTOMER = 'Customer';
    const ROLE_SUPER_ADMIN = 'Super Admin';
    const ROLE_STAFF = 'Staff';


    const ADMIN_ROLES = [self::ROLE_ADMIN];
    const USER_ROLES = [self::ROLE_USER];


    protected $with = ['permissions'];
}
