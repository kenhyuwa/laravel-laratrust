<?php

namespace App\Models;

use App\Service\Contracts\InterfaceModel;
use App\Service\Traits\Model as ModelTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait as LaratrustUserAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail, InterfaceModel
{
    use LaratrustUserAuthenticatable;
    use Notifiable;
    use ModelTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Indicates if the model should be timestamp.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be append to native types.
     *
     * @var array
     */
    protected $appends = [
        'avatar', 'last_login'
    ];

    /**
     * The routes of module users.
     *
     * @return void
     */
    public static function routes()
    {
        $route = app()->make('router');
        return [
            $route->namespace('User')->group(function() use ($route){
                $route->resource('/users', 'UserController', [
                    'except' => []
                ])->middleware(\App\Models\Permission::getPermission('users'));
                $route->delete('/users', [
                    'as' => 'users.destroyMany',
                    'uses' => 'UserController@destroyMany'
                ]);
            }),
            $route->namespace('Profile')->group(function() use ($route){
                $route->resource('/profile', 'ProfileController', [
                    'only' => ['index', 'store']
                ])->middleware(\App\Models\Permission::getPermission('profile'));
            })
        ];
    }

    /**
     * Accessor for avatar attribute.
     *
     * @return returnType
     */
    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->attributes['email'])).'.jpg?s=200&d=mm';
    }

    /**
     * Accessor for last_login attribute.
     *
     * @return returnType
     */
    public function getLastLoginAttribute()
    {
        $data = $this->userLogs()->whereType('App\Listeners\Auth\Login')->get();
        if($data->count() > 0){
            $log = $data->filter(function($v, $i){
                return $i < ($this->userLogs()->whereType('App\Listeners\Auth\Login')->count() - 1);
            })->last();
            if($log) return 'Last login ' . carbon()->parse($log->first()->created_at)->format('d F Y H:i:s');
            return 'First login ' . carbon()->parse($data->first()->created_at)->format('d F Y H:i:s');
        }
    }

    /**
     * Query scope OfUser.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, $id = null)
    {
        if(!is_null($id)){
            return $query->with('roles')->whereId($id)->first();
        }
        return $query->with('roles')->where('id', '<>', auth()->id());
    }

    /**
     * User has many UserLogs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLogs()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = user_id, localKey = id)
        return $this->hasMany(UserLog::class, 'user_id', 'id');
    }
}