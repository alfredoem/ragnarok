<?php

namespace Alfredoem\Ragnarok\SecParameters;

use Illuminate\Database\Eloquent\Model;

class SecParameter extends Model
{
    protected $table = 'SecParameters';
    protected $primaryKey = "parId";
    protected $maxLoginAttempts = 3;
    public $timestamps = false;

    const API_SECURITY_URL = 'API_SECURITY_URL';
    const SERVER_SECURITY_URL = 'SERVER_SECURITY_URL';
    const MAX_LOGIN_ATTEMPTS = 'MAX_LOGIN_ATTEMPTS';
}
