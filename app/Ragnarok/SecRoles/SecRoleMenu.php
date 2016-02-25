<?php

namespace Alfredoem\Ragnarok\SecRoles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Alfredoem\Ragnarok\SecMenus\SecMenu;

class SecRoleMenu extends Model
{

    protected $table = 'SecRoleMenus';
    protected $primaryKey = 'roleId';
    protected $fillable = ['roleId', 'menuId', 'status'];
    public $timestamps = false;

    public function menu()
    {
        return $this->hasOne(SecMenu::class, 'menuId', 'menuId');
    }

    public function getRoleMenu($role)
    {
        $permits = DB::table('SecRoleMenus')
            ->select('SecRoleMenus.roleId','SecRoleMenus.menuId', 'SecMenus.name', 'SecMenus.isChild', 'SecMenus.icon', 'SecMenus.menuParentId', 'SecMenus.route')
            ->join('SecMenus','SecRoleMenus.menuId','=','SecMenus.menuId')
            ->where('SecRoleMenus.roleId', "=", $role)
            ->where('SecMenus.Status', "=", '1')
            ->orderBy('menuId', 'ASC')
            ->get();

        return $this->makeNested($permits);
    }

    public function getRoleMenuId($role, $onlyChilds = 0)
    {
        $menusId = array();

        $roleMenus = $this->whereroleid($role)->wherestatus(1)->orderby('menuId', 'asc')->get();

        if(count($roleMenus) < 1)
        {
            return null;
        }

        $roleMenus->each(function($item, $key) use (&$menusId, $onlyChilds) {
            if($onlyChilds == 1) {
                if($item->menu['isChild'] == 1) {
                    array_push($menusId, $item->menu['menuId']);
                }
            } else {
                array_push($menusId, $item->menu['menuId']);
            }
        });

        return $menusId;
    }

    public function addAllRole($data)
    {
        $allMenu = SecMenu::whereStatus(1)->get(['menuId'])->toArray();

        array_walk($allMenu, function(&$menu) use ($data){
            $menu['roleId'] = $data['roleId'];
        });

        $this->removeAllRole($data);

        $this->addMultiRole($allMenu);
    }

    public function addRole($data)
    {
        $user = $this->create($data);
    }

    public function addRolex($data)
    {
        $parent = $this->findExistsParentMenu($data['roleId'], $data['menuId']);

        if(is_null($parent['parent']))
        {
            $this->create(['roleId' => $data['roleId'], 'menuId' => $parent['info'][0]['menuParentId']]);
        }

        $user = $this->create($data);
    }

    public function addMultiRole($array)
    {
        DB::table($this->table)->insert($array);
    }

    public function addParentRole($data)
    {
        $this->addRole($data);

        $menuAndSubmenus = SecMenu::where('menuParentId', '=', $data['menuId'])->get(['menuId'])->toArray();

        array_walk($menuAndSubmenus, function(&$submenu) use ($data){
            $submenu['roleId'] = $data['roleId'];
        });

        $this->addMultiRole($menuAndSubmenus);
    }

    public function removeAllRole($data)
    {
        $focus = $this->where('roleId', '=', $data['roleId']);
        $focus->delete();
    }

    public function removeRole($data)
    {
        $focus = $this->where('roleId', '=', $data['roleId'])
                ->where('menuId', '=', $data['menuId']);
        $focus->delete();
    }

    public function removeParentRole($data)
    {
        $this->removeRole($data);
        $menuAndSubmenus = SecMenu::where('menuParentId', '=', $data['menuId'])->get(['menuId'])->toArray();

        $reduce = call_user_func_array('array_merge_recursive', $menuAndSubmenus);

        $aloha = $this->where('roleId', '=', $data['roleId']);

        if(count($reduce['menuId']) > 1)
        {
            $aloha->whereIn('menuId', $reduce['menuId']);
        }
        else
        {
            $aloha->where('menuId', $reduce['menuId']);
        }

        $aloha->delete();
    }

    public function findExistsRoleMenu($roleId, $menuId)
    {
        $result = $this->where('roleId', '=', $roleId)
                       ->where('menuId', '=', $menuId)
                       ->first();
        return $result;
    }

    public function findExistsParentMenu($roleId, $menuId)
    {
        $secMenu = new SecMenu;
        $info = $secMenu->where('menuId', '=', $menuId)->get(['menuParentId'])->toArray();

        $parent = $this->where('menuId', '=', $info[0]['menuParentId'])
                        ->where('roleId', '=', $roleId)
                         ->first();
        return ['parent' =>$parent, 'info' => $info];
    }

    public function childExists($roleId, $menuId)
    {
        $secMenu = new SecMenu;
        $info = $secMenu->where('menuId', '=', $menuId)->get(['menuParentId'])->first()->toArray();

        $parent = $info['menuParentId'];

        $childs = $secMenu->getChilds($parent);

        $find = $this->where('roleId', '=', $roleId)->whereIn('menuId', $childs)->count();

        return ['exists' => $find, 'parent' => $parent];
    }

    public function emptyPermits($roleId)
    {
        $permits = $this->whereRoleid($roleId)->first();
        return $permits;
    }

    public function fullPermits($roleId)
    {
        $secMenu = SecMenu::whereStatus(1)->count();
        $permits = $this->whereRoleid($roleId)->count();

        if($permits == $secMenu)
        {
            return TRUE;
        }

        return FALSE;
    }


    public function makeNested($source) {
        $nested = array();

        $cur_state = '';
        $cont = -1;
        foreach ( $source as &$s ) {

            if ( $s->menuParentId == 0 ) {
                $nested[] = &$s;
                $cur_state = 'menu';
            }
            else {
                $pid = $s->menuParentId;
                if ( isset($nested[$cont]) )
                {
                    if ( !isset($nested[$cont]->submenu) )
                    {
                        $nested[$cont]->submenu = [];
                    }

                }

                $cur_state = 'submenu';
            }
            if($cur_state == 'menu')
            {
                $cont++;
            }

        }

        foreach ( $source as &$s ) {

            if ( $s->isChild == "1")
            {

                foreach ($nested as &$nes) {

                    $pid = $s->menuParentId;

                    if ($nes->menuId == $pid)
                    {
                        $nes->submenu[] = $s;
                        break;
                    }

                }// end foreach

            }// end if isChild

        }

        return $nested;
    }


    /* function getRoleMenu($role)// Refactor code, pendiente
    {
        $permits =  collect();

        $roleMenus = $this->whereroleid($role)->wherestatus(1)->orderby('menuId', 'asc')->get();

        $roleMenus->each(function($item, $key) use ($permits){
            if ($item->menu['isChild'] == 0) {
                $permits->push($item->menu);
            }
        });

        return $permits;
    }*/


}