<?php

namespace App\Models;

use App\Service\Contracts\InterfaceModel;
use App\Service\Traits\Model as ModelTrait;
use Illuminate\Support\Facades\Schema;
use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission implements InterfaceModel
{
    use ModelTrait;

    protected static $__CLASS__ = __CLASS__;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The routes of module permissions.
     *
     * @return void
     */
    public static function routes()
    {
        $route = app()->make('router');
        return [
            $route->namespace('Access')->group(function() use ($route){
                $route->resource('/permissions', 'PermissionsController', [
                    'only' => ['index', 'store']
                ])->middleware(['auth', self::getPermission('permissions')]);
            }),
        ];
    }

    /**
     * Get related permissions.
     * 
     * @param  string
     * @return type
     */
    public static function getPermission($value)
    {
        if(Schema::hasTable((new static::$__CLASS__)->getTable())){
            $data = self::whereIndex($value)->get();
            $permissions = '';
            foreach($data as $v){
                $permissions .= "{$v->name}|";
            }
            return !empty($permissions) ? 'permission:' . rtrim($permissions, '|') : '';
        }
        return "";
    }

    /**
     * Query scope OfPermissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfPermissions($query)
    {
        return $query;
    }
}
