<?php namespace Klaravel\Ntrust\Traits;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

trait NtrustRoleTrait
{
    //Big block of caching functionality.
    public function cachedPermissions()
    {
        $rolePrimaryKey = $this->primaryKey;
        $cacheKey = 'ntrust_permissions_for_role_'.$this->$rolePrimaryKey;

        if (Cache::getStore() instanceof TaggableStore) {
            return Cache::tags(Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_role_table'))
                ->remember($cacheKey, Config::get('cache.ttl', 1440), function () {
                    return $this->perms()->get();
                });
        } 
        else
            return $this->perms()->get();
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            Config::get('ntrust.profiles.' . self::$roleProfile . '.model'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.role_user_table'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.role_foreign_key'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.user_foreign_key'));
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(
            Config::get('ntrust.profiles.' . self::$roleProfile . '.permission'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_role_table'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.role_foreign_key'),
            Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_foreign_key'));
    }

    /**
     * Trait boot method
     * 
     * @return void
     */
    protected static function bootNtrustRoleTrait()
    {
        static::saved(function() {
            self::clearCache();
        });

        static::deleted(function($role) {
            self::clearCache($role);
        });

        if(method_exists(self::class, 'restored')) {
            static::restored(function($role) {
                self::clearCache($role);
            });
        }
    }
    
    /**
     * Checks if the role has a permission by its name.
     *
     * @param string|array $name       Permission name or array of permission names.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedPermissions() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return void
     */
    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->perms()->sync($inputPermissions);
        } else {
            $this->perms()->detach();
        }
    }

    /**
     * Attach permission to current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->attach($permission);

        self::clearCache();
    }

    /**
     * Detach permission from current role.
     *
     * @param object|array $permission
     *
     * @return void
     */
    public function detachPermission($permission)
    {
        if (is_object($permission))
            $permission = $permission->getKey();

        if (is_array($permission))
            $permission = $permission['id'];

        $this->perms()->detach($permission);

        self::clearCache();
    }

    /**
     * Attach multiple permissions to current role.
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions = null)
    {
        if (!$permissions) $permissions = $this->perms()->get();

        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * Clear cache
     */
    public static function clearCache($role = null) 
    {
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('ntrust.profiles.' . self::$roleProfile . '.permission_role_table'))
                ->flush();

            if ($role) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }
        }
    }
}

