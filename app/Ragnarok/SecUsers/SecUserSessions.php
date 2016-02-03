<?php namespace Alfredoem\Ragnarok\SecUsers;

use Illuminate\Database\Eloquent\Model;

class SecUserSessions extends Model
{
    protected $table = 'SecUserSessions';
    public $primaryKey = 'userSessionId';
    protected $fillable = ['userId', 'sessionCode', 'status', 'datetimeIns'];
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('Alfredoem\Ragnarok\SecUser', 'userId', 'userId');
    }


}