<?php

namespace App\Models;

use App\Service\Contracts\InterfaceModel;
use App\Service\Traits\Model as ModelTrait;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole implements InterfaceModel
{
    use ModelTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
        'name', 'display_name', 'description',
    ];

    /**
     * The routes of module roles.
     *
     * @return void
     */
    public static function routes()
    {
        $route = app()->make('router');
        return [
            $route->namespace('Access')->group(function() use ($route){
                $route->resource('/roles', 'RolesController', [
                    'except' => ['show']
                ])->middleware(['auth', \App\Models\Permission::getPermission('roles')]);
                $route->delete('/roles', [
                    'as' => 'roles.destroyMany',
                    'uses' => 'RolesController@destroyMany'
                ])->middleware(['auth']);
            }),
        ];
    }

    /**
     * Mutator for display_name attribute.
     *
     * @return void
     */
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = is_null($value) ? $this->attributes['name'] : '';
    }

    /**
     * Mutator for description attribute.
     *
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = is_null($value) ? 'role of ' . $this->attributes['name'] : $value;
    }

    /**
     * Role belongs to many Menu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function menus()
    {
        // belongsToMany(RelatedModel, foreignKey = menu_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Menu::class);
    }

    /**
     * Attach menu to roles.
     * 
     * @param  string
     * @return void
     */
    public function roleGiveMenu($value)
    {
        if(self::isUuid($value)){
            return $this->menus()->attach(Menu::findOrFail($value));
        }
        return false;
    }

    /**
     * Dettach menu from roles.
     * 
     * @param  string
     * @return void
     */
    public function roleRemoveMenu($value)
    {
        if(self::isUuid($value)){
            return $this->menus()->detach(Menu::findOrFail($value));
        }
        return false;
    }

    /**
     * Cek related menu
     * 
     * @param  string
     * @return boolean
     */
    public function hasMenu($value)
    {
        foreach ($this->menus as $menu)
            if ($menu->id === $value) return true;
        return false;
    }

    /**
     * Cek instance of uuid.
     * 
     * @param  string
     * @return boolean
     */
    public function isUuid($str)
    {
        try {
            $uuid = \Ramsey\Uuid\Uuid::fromString($str);

            if ($uuid->getVersion() === \Ramsey\Uuid\Uuid::UUID_TYPE_RANDOM) {
                return true;
            }
        } catch (InvalidUuidStringException $e) {
            return false;
        }
    }

    /**
     * Query scope OfRoles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfRoles($query)
    {
        return $query->where('id', '<>', auth()->user()->roles->first()->id)->whereNotIn('name', [config('laravelia.default_role')]);
    }
}
