<?php namespace Alfredoem\Ragnarok\SecParameters;

use Illuminate\Database\Eloquent\Model;


class SecParameter extends Model
{
    protected $table = 'SecParameters';
    protected $primaryKey = "parId";
    public $timestamps = false;

}
