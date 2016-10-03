<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role permission default profile
    |--------------------------------------------------------------------------
    |
    | Set default option for your role permission system.
    |
    */

    'defaults' => [
        'profile' => 'user',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role permission profiles
    |--------------------------------------------------------------------------
    |
    | Set multiple profiles for your multiple type user profile and diffrent
    | role and permission for each users
    | 
   	| Profile name should be singular name of user/admin table name
    |
    | Example: "user", "admin"
    |
    */

    'profiles' => [

        'user' => [

        	/*
		    |--------------------------------------------------------------------------
		    | User table name
		    |--------------------------------------------------------------------------
		    */
		    'table' => 'users',

		    /*
		    |--------------------------------------------------------------------------
		    | User model
		    |--------------------------------------------------------------------------
		    */
		    'model' => 'App\User',

			/*
		    |--------------------------------------------------------------------------
		    | Ntrust Role Model
		    |--------------------------------------------------------------------------
		    |
		    | This is the Role model used by Ntrust to create correct relations.  Update
		    | the role if it is in a different namespace.
		    |
		    */
		    'role' => 'App\Role',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Roles Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the roles table used by Ntrust to save roles to the database.
		    |
		    */
		    'roles_table' => 'roles',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Permission Model
		    |--------------------------------------------------------------------------
		    |
		    | This is the Permission model used by Ntrust to create correct relations.
		    | Update the permission if it is in a different namespace.
		    |
		    */
		    'permission' => 'App\Permission',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Permissions Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the permissions table used by Ntrust to save permissions to the
		    | database.
		    |
		    */
		    'permissions_table' => 'permissions',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust permission_role Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the permission_role table used by Ntrust to save relationship
		    | between permissions and roles to the database.
		    |
		    */
		    'permission_role_table' => 'permission_role',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust role_user Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the role_user table used by Ntrust to save assigned roles to the
		    | database.
		    |
		    */
		    'role_user_table' => 'role_user',

		    /*
		    |--------------------------------------------------------------------------
		    | User Foreign key on Ntrust's role_user Table (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'user_foreign_key' => 'user_id',

		    /*
		    |--------------------------------------------------------------------------
		    | Role Foreign key on Ntrust's role_user and permission_role Tables (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'role_foreign_key' => 'role_id',

		    /*
		    |--------------------------------------------------------------------------
		    | Permission Foreign key on Ntrust's permission_role Table (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'permission_foreign_key' => 'permission_id',

        ],

        'admin' => [

        	/*
		    |--------------------------------------------------------------------------
		    | User table name
		    |--------------------------------------------------------------------------
		    */
		    'table' => 'admins',

		    /*
		    |--------------------------------------------------------------------------
		    | User model
		    |--------------------------------------------------------------------------
		    */
		    'model' => 'App\Admin',
            
			/*
		    |--------------------------------------------------------------------------
		    | Ntrust Role Model
		    |--------------------------------------------------------------------------
		    |
		    | This is the Role model used by Ntrust to create correct relations.  Update
		    | the role if it is in a different namespace.
		    |
		    */
		    'role' => 'App\AdminRole',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Roles Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the roles table used by Ntrust to save roles to the database.
		    |
		    */
		    'roles_table' => 'admin_roles',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Permission Model
		    |--------------------------------------------------------------------------
		    |
		    | This is the Permission model used by Ntrust to create correct relations.
		    | Update the permission if it is in a different namespace.
		    |
		    */
		    'permission' => 'App\AdminPermission',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust Permissions Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the permissions table used by Ntrust to save permissions to the
		    | database.
		    |
		    */
		    'permissions_table' => 'admin_permissions',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust permission_role Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the permission_role table used by Ntrust to save relationship
		    | between permissions and roles to the database.
		    |
		    */
		    'permission_role_table' => 'admin_permission_role',

		    /*
		    |--------------------------------------------------------------------------
		    | Ntrust role_user Table
		    |--------------------------------------------------------------------------
		    |
		    | This is the role_user table used by Ntrust to save assigned roles to the
		    | database.
		    |
		    */
		    'role_user_table' => 'admin_role_user',

		    /*
		    |--------------------------------------------------------------------------
		    | User Foreign key on Ntrust's role_user Table (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'user_foreign_key' => 'user_id',

		    /*
		    |--------------------------------------------------------------------------
		    | Role Foreign key on Ntrust's role_user and permission_role Tables (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'role_foreign_key' => 'role_id',

		    /*
		    |--------------------------------------------------------------------------
		    | Permission Foreign key on Ntrust's permission_role Table (Pivot)
		    |--------------------------------------------------------------------------
		    */
		    'permission_foreign_key' => 'permission_id',

        ],

    ],

];