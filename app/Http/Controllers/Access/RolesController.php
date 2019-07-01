<?php

namespace App\Http\Controllers\Access;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\Roles\Request as RolesRequest;

class RolesController extends Controller
{
    public function __construct(Role $roles)
    {
    	parent::__construct();
    	$this->roles = $roles;
    }

    public function index(RolesRequest $request)
    {
    	if($request->ajax()){
            return app('datatables')->eloquent($this->roles->ofRoles())
	            ->addColumn('action', __v().'.access-controls.roles.datatables.action')
                ->editColumn('name', '{{ ucwords($name) }}')
                ->editColumn('description', '{{ ucfirst($description) }}')
	            ->rawColumns(['action'])
	            ->orderColumns(['name', 'description'], ':column $1')
                ->addIndexColumn()
	            ->make(true);
        }
    	return view("{$this->view}::access-controls.roles.index");
    }

    public function create(RolesRequest $request)
    {
        if($request->ajax()){
            if(request()->has('id')){
                $roles = $this->roles->where('name', request('name'))->where('id', '<>', request('id'))->first();
            }else{
                $roles = $this->roles->where(['name' => request('name')])->first();
            }
            return response()->json(
                [
                    'valid' => $roles ? false : true
                ]
            );
        }
    }

    public function store(RolesRequest $request)
    {
        if($request->ajax()){
            $roles = $this->roles->create($request->all());
            return response()->successResponse(microtime_float(), $roles, 'create role successfully');
        }
    }

    public function edit(RolesRequest $request, $id)
    {
        if($request->ajax()){
            $roles = $this->roles->findOrFail($id);
            return response()->successResponse(microtime_float(), $roles);
        }
    }

    public function update(RolesRequest $request, $id)
    {
        if($request->ajax()){
            $roles = $this->roles->find($id)->update($request->all());
            return response()->successResponse(microtime_float(), $roles, 'update role successfully');
        }
    }

    public function destroy(RolesRequest $request, $id)
    {
        if($request->ajax()){
            $roles = $this->roles->findOrFail($id);
            if(!$roles || $roles->users->count() > 0){
                return response()->failedResponse(microtime_float(), 'delete roles unsuccessfully');
            }
            if($roles->destroy($id)){
                return response()->successResponse(microtime_float(), [], 'delete roles successfully');
            }
            return response()->failedResponse(microtime_float(), 'delete roles unsuccessfully');
        }
    }

    public function destroyMany(RolesRequest $request)
    {
        if($request->ajax()){
            $id_can_be_destroy = [];
            foreach($request->all() as $id){
                $roles = $this->roles->findOrFail($id);
                if($roles && $roles->users->count() < 1){
                    array_push($id_can_be_destroy, $id);
                }
            }
            if($roles->destroy($id_can_be_destroy)){
                return response()->successResponse(microtime_float(), [], 'delete roles successfully');
            }
            return response()->failedResponse(microtime_float(), 'delete roles unsuccessfully');
        }
    }
}