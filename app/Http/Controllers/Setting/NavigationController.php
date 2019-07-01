<?php

namespace App\Http\Controllers\Setting;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\NavigationRepository as Repository;
use App\Http\Requests\Setting\Menu\Request as MenuRequest;

class NavigationController extends Controller
{
    public function __construct(Menu $menu)
    {
        parent::__construct();
        $this->menu = $menu;
    }

    public function index(MenuRequest $request)
    {
        return view("{$this->view}::setting.menu.index", [
            'nav' => self::findAll()
        ]);
    }

    public function store(MenuRequest $request)
    {
        if($request->ajax()){
            $data = self::create(collect($request->request)->toArray());            
            return response()->successResponse(microtime_float(), $data, 'update navigation succesfully.');
        }
    }

    public function edit(MenuRequest $request, $id)
    {
        if($request->ajax()){
            $data = $this->menu->find($id);
            return response()->successResponse(microtime_float(), $data, 'find a navigation succesfully.');
        }
    }

    public function update(MenuRequest $request, $id)
    {
        if($request->ajax()){
            $data = $this->menu->find($id)->update($request->all());            
            return response()->successResponse(microtime_float(), $data, 'update a navigation succesfully.');
        }
    }

    private function findAll()
    {
        return $this->menu->select([
            'id', 'parent', 'queue', 'en_name', 'id_name', 'route', 'icon'
        ])->where('parent',  null)->ofChildren()->ofRole()
        ->orderBy('queue', 'asc')
        // ->orderBy(app()->getLocale().'_name','asc')
        ->get();
    }

    private function create(array $data)
    {
        foreach($data as $row) self::recursive(json_decode($row));
        return self::findAll();
    }

    private function recursive(array $row, $parent = null)
    {
        for($i = 0; $i < count($row); $i++){
            if(isset($row[$i]->children)) self::recursive($row[$i]->children, $row[$i]->id);
            $this->menu->find($row[$i]->id)->update(['parent' => $parent, 'queue' => $i+1]);
        }
    }
}
