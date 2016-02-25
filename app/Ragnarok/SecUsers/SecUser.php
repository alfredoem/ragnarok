<?php namespace Alfredoem\Ragnarok\SecUsers;

use Alfredoem\Ragnarok\SecRoles\SecRole;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SecUser extends Model implements AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'SecUsers';
    protected $primaryKey = "userId";
    protected $fillable = ['firstName', 'lastName', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
    public $timestamps = false;

    public function role()
    {
        return $this->hasOne(SecRole::class, 'roleId', 'roleId');
    }

}
