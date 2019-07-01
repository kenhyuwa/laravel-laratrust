<?php 

namespace App\Http\ViewComposers;

use App\Models\Menu;
use Illuminate\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Schema;

class NavigationComposer
{
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function compose(View $view)
    {
        $view->with([
            'navigation' => Schema::hasTable((new $this->menu)->getTable()) && auth()->check() ? $this->generateTreeNavigations($this->menu->navigationMenu()->get()->toArray()) : ""
        ]);
    }

    private function generateTreeNavigations(array $data = [], string $html = ''){
        config(['app.locale' => app()->getLocale()]);
        switch (__v()):
            case 'v2':
                //
            break;

            default:
                $html = $this->themeVersionOne($data, $html);
            break;
        endswitch;
        return new HtmlString($html);
    }

    private function themeVersionOne(array $data = [], string $html = '')
    {
        foreach ($data as $i => $v):
            if (sizeof($v['role']) > 0):
                $name    = ucwords($v[app()->getLocale().'_name']);
                $link    = is_null($v['route']) ? sizeof($v['children']) > 0 ? "javascript:void(0);" : url(__prefix()) : url(__prefix().'/'.$v['route']);
                $icon    = !is_null($v['parent']) ? "fa fa-circle-o" : $v['icon'];
                $chevron = sizeof($v['children']) > 0 ? '<i class="fa fa-angle-left pull-right"></i>' : "";
                $active  = explode('.', \Route::current()->getName())[0] == $v['route'] || in_array(explode('.', \Route::current()->getName())[0], array_column($v['children'], 'route')) ? 'active' : '';
                $html    .= "<li class='treeview {$active}'><a href='{$link}' class='child-nav'><i class='{$icon}'></i><span>{$name}</span>{$chevron}</a>";
                if (sizeof($v['children']) > 0):
                    $parent_active = '';
                    if(in_array(explode('.', \Route::current()->getName())[0], array_column($v['children'], 'route'))){
                        $parent_active = 'menu-open';
                    }
                    $html .= "<ul class='treeview-menu {$parent_active}'>";
                        $html = $this->generateTreeNavigations($v['children'], $html, $v['role']);
                    $html .= "</ul>";
                endif;
                $html    .= "</li>";
            endif;
        endforeach;
        return $html;
    }
}