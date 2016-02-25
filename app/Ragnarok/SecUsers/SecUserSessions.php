<?php namespace Alfredoem\Ragnarok\SecUsers;

use Illuminate\Database\Eloquent\Model;

class SecUserSessions extends Model
{
    protected $table = 'SecUserSessions';
    public $primaryKey = 'userSessionId';
    protected $fillable = ['userId', 'sessionCode', 'ipAddress', 'status', 'dateIns', 'datetimeIns', 'datetimeUpd'];
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(SecUser::class, 'userId', 'userId');
    }

}