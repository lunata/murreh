<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use App\Models\User;

class Role extends EloquentRole
{
    protected $fillable = ['slug','name','permissions'];
    
    /** Gets name of this role, takes into account locale.
     * 
     * @return String
     */
    public function getLnameAttribute() : String
    {
//        if ($locale == 'ru') {
            $name = $this->name;
/*        } else {
            $name = $this->slug;
        }*/        
        return $name;
    }
    
    // Role __has_many__ Users
    public function all_users(){
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
    
    /**
     * Gets a list of permissions for the user.
     *
     * @return string
     */
    public function permissionString()
    {
        $permissions = $this->permissions;
        $list = [];
//dd($permissions);       
        if ($permissions) {
            foreach ($permissions as $key => $value) {
                $list[] = $key;
            }
        }
        return join(', ', $list);
    }

    /** Gets list of roles
     * 
     * @return Array [1=>'admin',..]
     */
    public static function getList()
    {     
        
        $regions = self::all();
        
        $list = array();
        foreach ($regions as $row) {
            $list[$row->id] = $row->name;
        }
        asort($list);
        return $list;         
    }
}
