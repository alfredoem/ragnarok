<?php

namespace Alfredoem\Ragnarok\SecMenus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SecMenu extends Model
{

    protected $table = 'SecMenus';
    public $primaryKey = 'menuId';
    protected $fillable = ['menuParentId', 'isChild', 'name', 'description', 'position', 'division', 'icon', 'route', 'status'];
    public $timestamps = false;

    public function parentMenu()
    {
        return $this->hasOne(SecMenu::class, 'menuId', 'menuParentId');
    }

    /**
     * Obtiene todos los menus principales
     * @return mixed
     */
    public function getParents()
    {
        //return $this->whereischild(0)->orderBy('position', 'desc')->get();
        return $this->whereischild(0)->wherestatus(1)->orderBy('position', 'desc')->get();
    }

    /**
     * Obtiene los submenus de un menu principal
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submenu()
    {
        return $this->hasMany(SecMenu::class, 'menuParentId', 'menuId')->whereStatus(1)->orderBy('position');
    }

    public function getChilds($parent_id)
    {
        $childs = $this->where('menuParentId', '=', $parent_id)->whereStatus(1)->get(['menuId'])->toArray();

        if(count($childs) > 1)
        {
            $reduce = call_user_func_array('array_merge_recursive', $childs);
            return $reduce['menuId'];
        }

        return array($childs[0]['menuId']);
    }


    public function getMenu($excluir = array(''))
    {
        if(is_null($excluir))
        {
            $menu = DB::table('SecMenus')
                ->select('*')
                ->orderBy('menuId', 'ASC')
                ->get();
        }
        else
        {
            if(count($excluir) == 1)
            {
                $excluir = [$excluir];
            }

            $menu = DB::table('SecMenus')
                ->select('*')
                ->whereNotIn('menuId', $excluir)
                ->orderBy('menuId', 'ASC')
                ->get();
        }

        return $this->makeNested($menu);
    }

    public function makeNested($source)
    {

        $nested = array();

        $cur_state = '';
        $cont = -1;

        foreach ( $source as &$s ) {

            if ( $s->menuParentId == 0 && $s->isChild == 0)
            {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
                $cur_state = 'menu';
            }
            else
            {
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
}
