<?php namespace Alfredoem\Ragnarok\SecUsers;

use Illuminate\Database\Eloquent\Model;

class SecUserSessions extends Model
{
    protected $table = 'SecUserSessions';
    public $primaryKey = 'userSessionId';
    protected $fillable = ['userId', 'sessionCode', 'ipAddress', 'status', 'dateIns', 'datetimeIns'];
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('Alfredoem\Ragnarok\SecUsers\SecUser', 'userId', 'userId');
    }

}