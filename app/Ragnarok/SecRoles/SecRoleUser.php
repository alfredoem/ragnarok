<?php

namespace Alfredoem\Ragnarok\SecRoles;

use Illuminate\Database\Eloquent\Model;

class SecRoleUser extends Model
{
    protected $table = 'SecRoleUsers';
    protected $primaryKey = 'userId';
    protected $fillable = ['userId', 'roleId', 'status'];
    public $timestamps = false;

    public function role()
    {
        return $this->hasOne(SecRole::class, 'roleId', 'roleId');
    }

}
