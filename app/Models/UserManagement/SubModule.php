<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class SubModule extends Model
{
    public function permissions()
    {
        return $this->hasMany(Permission::class,'submodule_id');
    }
}
