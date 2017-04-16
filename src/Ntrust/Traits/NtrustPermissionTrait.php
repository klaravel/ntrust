<?php namespace Klaravel\Ntrust\Traits;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

trait NtrustPermissionTrait
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('ntrust.profiles.' . self::$roleProfile . '.role'), 
            Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_role_table'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_foreign_key'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.role_foreign_key')
            );
    }

    /**
     * Trait boot method
     * 
     * @return void
     */
    protected static function bootNtrustPermissionTrait()
    {
        /**
         * Attach event listener to remove the many-to-many records when trying to delete
         *  Will NOT delete any records if the permission model uses soft deletes.
         */
        static::deleted(function($permission)
        {
            if(Cache::getStore() instanceof TaggableStore) {
                Cache::tags(Config::get('ntrust.profiles.' . self::$roleProfile . '.permission'))
                    ->flush();

                $permission->roles()->sync([]);
            }
        });
    }
}
