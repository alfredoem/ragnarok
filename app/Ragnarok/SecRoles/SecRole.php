<?php

namespace Alfredoem\Ragnarok\SecRoles;

use Illuminate\Database\Eloquent\Model;

class SecRole extends Model
{
    protected $table = 'SecRoles';
    public $primaryKey = 'roleId';
    protected $fillable = ['name', 'description', 'level', 'status'];
    public $timestamps = false;

    public function getActiveRolesByUserLevel()
    {
        return $this->wherestatus(1)->where('level', '>=', auth()->user()->role->level)->lists('name', 'roleId')->toArray();
    }

    public function getRoleLevels()
    {
        return ['4' => 'Administrador', '3' => 'Sub-Administrador', '2' => 'Supervisor', '1' => 'Cajero', '0' => 'Root'];
    }

    public function getRoleLevelName()
    {
        if (array_key_exists($this->level, $this->getRoleLevels())) {
            return $this->getRoleLevels()[$this->level];
        }

        return $this->level;
    }

    public function getByStore($roleId)
    {
        return $this->where('roleId','=',$roleId)->first();
    }
}
