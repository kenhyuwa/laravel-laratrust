<?php

namespace App\Models;

use App\Service\Contracts\InterfaceModel;
use App\Service\Traits\Model as ModelTrait;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model implements InterfaceModel
{
    use ModelTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = [
        'parent',
        'queue',
        'en_name',
        'id_name',
        'icon',
        'route'
    ];

    /**
     * The routes of module menu.
     *
     * @return void
     */
    public static function routes()
    {
        $route = app()->make('router');
        return [
            $route->namespace('Access')->group(function() use ($route){
                $route->resource('/access', 'AccessController', [
                    'only' => ['index', 'store']
                ])->middleware(['auth', \App\Models\Permission::getPermission('access')]);
            }),
            $route->namespace('Setting')->group(function() use ($route){
                $route->resource('/menu', 'NavigationController', [
                 'except' => ['show', 'destroy']
                ])->middleware(['auth', \App\Models\Permission::getPermission('menu')]);
            }),
        ];
    }

    /**
     * Menu belongs to many Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        // belongsToMany(RelatedModel, foreignKey = role_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Role::class);
    }

    /**
     * Query scope navigation menu.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNavigationMenu($query)
    {
        return $query->whereNull('parent')->ofChildren()->ofRole()->orderBy('queue', 'asc');
    }

    /**
     * Query scope child menu.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfChildren($query)
    {
        return $query->with([
            'children' => function($query){
                $query->ofRole();
                $this->scopeOfChildren($query)->orderBy('queue', 'asc');
            }
        ]);
    }

    /**
     * Menu has many Children menu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = parent, localKey = id)
        return $this->hasMany(Menu::class, 'parent', 'id')->orderBy('queue', 'asc');
    }

    /**
     * Menu belongs to Parent menu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        // belongsTo(RelatedModel, foreignKey = parent, keyOnRelatedModel = id)
        return $this->belongsTo(Menu::class, 'parent', 'id');
    }

    /**
     * Query scope OfRole.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfRole($query)
    {
        return $query->with([
            'role' => function($query){
                $query->where('id', auth()->user()->roles->first()->id);
            }
        ]);
    }
}
