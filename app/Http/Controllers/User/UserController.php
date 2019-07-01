<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Request as UserRequest;

class UserController extends Controller
{
    public function __construct(User $user, Role $roles)
    {
    	parent::__construct();
        $this->user = $user;
    	$this->roles = $roles;
    }

    public function index(UserRequest $request)
    {
    	if($request->ajax()){
            return app('datatables')->eloquent($this->user->ofUser())
                ->addColumn('roles', function (User $user) {
                    return $user->roles->map(function($roles) {
                        return ucwords($roles->name);
                    })->implode('[]');
                })
                ->addColumn('action', __v().'.users.datatables.action')
                ->editColumn('name', '{{ ucwords($name) }}')
                ->editColumn('email', '{{ email($email) }}')
                ->editColumn('avatar', '<img src="{{ $avatar }}" alt="{{ ucwords($name) }}" class="img-responsive avatar-on-table">')
                ->rawColumns(['avatar', 'action'])
                ->orderColumns(['name', 'email', 'roles.name'], ':column $1')
                ->make(true);
        }
    	return view("{$this->view}::users.widget", [
            'roles' => $this->roles->ofRoles()->get(),
            'users' => $this->user->ofUser()->paginate(6)
        ]);
    }

    public function create(UserRequest $request)
    {
        if($request->ajax()){
            if(request()->has('id')){
                $user = $this->user->where('email', request('email'))->where('id', '<>', request('id'))->first();
            }else{
                $user = $this->user->where(['email' => request('email')])->first();
            }
            return response()->json(
                [
                    'valid' => $user ? false : true
                ]
            );
        }
    }

    public function show(UserRequest $request, $load)
    {
        if($request->ajax()){
            $result = $this->{$load}->select("id","name as text")->where("name", 'LIKE', "{$request->get('query')}%")->where('name', '!=', 'owner')->get();
            return response()->successResponse(microtime_float(), $result);
        }
    }

    public function store(UserRequest $request)
    {
        if($request->ajax()){
            $user = $this->user->create(
                collect($request->all())->merge(
                    [
                        'email_verified_at' => carbon()->today()->toDateTimeString(),
                        'password' => bcrypt('Larav3lP0s')
                    ]
                )->toArray()
            );
            $user->roles()->attach([$request->roles]);
            return response()->successResponse(microtime_float(), $user, 'create user successfully');
        }
    }

    public function edit(UserRequest $request, $id)
    {
        if($request->ajax()){
            $data = $this->user->ofUser($id);
            return response()->successResponse(microtime_float(), $data);
        }
    }

    public function update(UserRequest $request, $id)
    {
        if($request->ajax()){
            $user = $this->user->findOrFail($id);
            $role = $this->roles->findOrFail($request->roles);
            if(!$user || !$role) return response()->failedResponse(microtime_float());
            $user->syncRoles([]);
            $user->attachRole($role);
            return response()->successResponse(microtime_float(), $user, 'update user successfully');
        }
    }

    public function destroy(UserRequest $request, $id)
    {
        if($request->ajax()){
            if($this->user->destroy($id)){
                return response()->successResponse(microtime_float(), [], 'delete user successfully');
            }
            return response()->failedResponse(microtime_float(), 'delete user unsuccessfully');
        }
    }

    public function destroyMany(UserRequest $request)
    {
        if($request->ajax()){
            $id_can_be_destroy = [];
            foreach($request->all() as $id){
                $user = $this->user->findOrFail($id);
                if($user){
                    array_push($id_can_be_destroy, $id);
                }
            }
            if($user->destroy($id_can_be_destroy)){
                return response()->successResponse(microtime_float(), [], 'delete user successfully');
            }
            return response()->failedResponse(microtime_float(), 'delete user unsuccessfully');
        }
    }
}