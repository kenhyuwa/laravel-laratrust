<?php

namespace App\Http\Controllers\Access;

use App\Models\Role;
use App\Models\Menu as Navigation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Navigation\Request as MenuRequest;

class AccessController extends Controller
{
    public function __construct(Navigation $navigation, Role $roles)
    {
        parent::__construct();
        $this->roles = $roles;
        $this->menu = $navigation;
        $this->navigation = $navigation;
    }

    public function index(MenuRequest $request)
    {
    	$app = app()->getLocale();
    	$roles = $this->roles->ofRoles()->get();
    	if($request->ajax()){
            $raws = [];
    		foreach ($roles as $key => $value) {
        		array_push($raws, "action_{$key}");
        	}
            $datatables = app('datatables')->eloquent($this->navigation->query());
	            foreach ($roles as $key => $v) {
	            	$datatables->addColumn("action_{$key}", function($query) use ($key, $v){
		            	if($v->hasMenu($query->id)){
			            	return '<div class="checkbox icheck">
		                        <label>
		                            <input type="checkbox" name="checkbox" id="checkbox" class="checkbox" checked="checked" data-roles='.$v->id.' data-permissions='.$query->id.'>
		                        </label>
		                    </div>';
	            		}
		            	return '<div class="checkbox icheck">
	                        <label>
	                            <input type="checkbox" name="checkbox" id="checkbox" class="checkbox" data-roles='.$v->id.' data-permissions='.$query->id.'>
	                        </label>
	                    </div>';
		            });
	            }
                $datatables->editColumn(app()->getLocale().'_name', function($q){
                	$app = app()->getLocale();
                	return ucwords($q->{"{$app}_name"});
                });
	            $datatables->rawColumns($raws);
	            $datatables->orderColumns([app()->getLocale().'_name'], ':column $1');
	            return $datatables->addIndexColumn()->make(true);
        }
        return view("{$this->view}::access-controls.access.index", [
            'nav' => $this->navigation->all(),
            'menu' => $this->menu->all(),
            'roles' => $this->roles->ofRoles()->get()
        ]);
    }

    public function store(MenuRequest $request)
    {
        if($request->ajax()){
        	$role = $this->roles->findOrFail(request('roles'));
            if(!$role) return response()->failedResponse(microtime_float());
    	    if(request('status') == true){
    	    	$role->roleGiveMenu(request('permissions'));
    	    	$message = 'attach access successfully';
    	    }else{
    	    	$role->roleRemoveMenu(request('permissions'));
    	    	$message = 'dettach access successfully';
    	    }
    		return response()->successResponse(microtime_float(), [], $message);
        }
    }
}
