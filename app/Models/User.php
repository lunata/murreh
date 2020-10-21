<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use DB;

use App\Models\Role;

class User extends EloquentUser
{
    protected $fillable = ['email','first_name','last_name','permissions'];
    protected $perm_list = ['admin','edit'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    
    /** Gets name of this user
     * 
     * @return String
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' '. $this->last_name;
    }
         
    // User __has_many__ Roles
/*    public function all_roles(){
        return $this->belongsToMany(Role::class, 'role_users');
    }*/
    
    public static function registration($input) {
        $sentuser = Sentinel::register($input);
/*        
        $user = self::find($sentuser->id);
        $user->city = $input['city'];
        $user->country = $input['country'];
        $user->affilation = $input['affilation'];
        $user->save();
*/        
        return $sentuser;
    }
    
    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function getPermList()
    {
        $perms = $this->perm_list;
        $list = [];
        foreach ($perms as $p) {
            $list[$p] = \Lang::get("auth.perm.$p");
        }
        return $list;
    }

    /**
     * Gets a list of names of roles for the user.
     *
     * @return string
     */
    public function rolesNames()
    {
        $roles = $this->roles;
        $list = [];
        foreach ($roles as $role) {
            $list[] = $role->name;
        }
        return join(', ', $list);
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
        
        foreach ($permissions as $key => $value) {
            $list[] = $key;
        }
        return join(', ', $list);
    }
    
    /**
     * Gets a list of names of roles for the user.
     *
     * @param  int  $user_id
     * @return string
     */
    public static function getRolesNames(int $user_id)
    {
        return self::where('id',$user_id)->first()->rolesNames();
    }
    
    public function permValue():Array {        
        $user_perms = $this->permissions;

        $perm_value = [];
        foreach ($this->getPermList() as $perm=>$perm_t) {
            if (isset($user_perms[$perm]) && $user_perms[$perm]) {
                $perm_value[] = $perm;
            }
        }
        
        return $perm_value;
    }

    public function roleValue():Array {        
        $role_value = [];
        foreach ($this->roles as $role) {
            $role_value[] = $role->id;
        }
        return $role_value;
    }
    
    public static function authUser()
    {
        $auth_user=Sentinel::check();
        return self::find($auth_user->id);
    }
    
    /**
     * Checks access for a permission
     *
     * @param  string $permission, f.e. 'dict.edit'
     * @return boolean
     */
    public static function checkAccess($permission)
    {
        $user=Sentinel::check();
        if (!$user)
            return false;
//print "<pre>";
//var_dump($user);
        if (!is_array($permission)) {
            $permission = (array)$permission;
        }
        if ($user->hasAccess(['admin'])/* || $user->hasAccess($permission)*/)
            return true;
        return false;
    }
/*    
    public function getLastActionTime() {
        $history = DB::table('revisions')
                     ->select('updated_at')
                     ->where('user_id',$this->id)
                     ->orderBy('updated_at','desc')->first();
        
        if ($history) {
            return $history->updated_at;
        }
    }
*/    
    public static function getNameByID($id) {
        $user = User::find($id);
        if ($user) {
            return $user->name;
        }
    }      
    
    // "The permission display_name allows a user to description."
    
    // name,            display_name,       description
    // edit-user,       Edit users
    // config-system,   Configurate dictionary and corpus parameters
    // edit-dict,       Edit dictionary
    // edit-corpus,     Edit corpus
    // 
    
}
