<?php

namespace Alfredoem\Ragnarok\SecUsers;

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

    protected $hidden = ['password'];

    protected $attributes = ['userSessionId' => '', 'sessionCode' => '', 'ipAddress' => '', 'environment' => ''];

    public $timestamps = false;

    public function roleUser()
    {
        return $this->hasOne('Alfredoem\Ragnarok\SecRoles\SecRoleUser', 'userId', 'userId');
    }

    /**
     * Fill the user attributes
     * @param $data
     */
    public function populate($data)
    {
        $this->userId = $data->userId;
        $this->email = $data->email;
        $this->firstName = $data->firstName;
        $this->lastName = $data->lastName;
        $this->status = $data->status;
        $this->remember_token = $data->remember_token;
        $this->userSessionId = $data->userSessionId;
        $this->sessionCode = $data->sessionCode;
        $this->ipAddress = $data->ipAddress;
        $this->environment = $data->environment;
        return $this;
    }

}
