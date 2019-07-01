<?php

namespace App\Http\Controllers\Access;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Permissions\Request as PermissionsRequest;

class PermissionsController extends Controller
{
    public function __construct(Role $roles, Permission $permissions)
    {
    	parent::__construct();
    	$this->roles = $roles;
    	$this->permissions = $permissions;
    }

    public function index(PermissionsRequest $request)
    {
    	$roles = $this->roles->ofRoles()->get();
    	if($request->ajax()){
    		$raws = [];
    		foreach ($roles as $key => $value) {
        		array_push($raws, "action_{$key}");
        	}
            $datatables = app('datatables')->eloquent($this->permissions->ofPermissions()->with('roles'));
	            foreach ($roles as $key => $v) {
	            	$datatables->addColumn("action_{$key}", function($query) use ($key, $v){
	            		if(\DB::table('permission_role')->wherePermissionId($query->id)->whereRoleId($v->id)->first()){
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
	        $datatables->orderColumns(['name', 'description'], ':column $1');
	        $datatables->rawColumns($raws);
	        return $datatables->addIndexColumn()->make(true);
        }
    	return view("{$this->view}::access-controls.permissions.index", [
    		'roles' => $roles
    	]);
    }

    public function store(PermissionsRequest $request)
    {
        if($request->ajax()){
        	$role = $this->roles->findOrFail(request('roles'));
            if(!$role) return response()->failedResponse(microtime_float());
    	    if(request('status') == true){
    	    	$role->permissions()->attach([request('permissions')]);
    	    	$message = 'attach permissions successfully';
    	    }else{
    	    	$role->permissions()->detach([request('permissions')]);
    	    	$message = 'dettach permissions successfully';
    	    }
    		return response()->successResponse(microtime_float(), [], $message);
        }
    }
}
