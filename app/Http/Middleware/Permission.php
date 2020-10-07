<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $route_parameters = $request->route()->action;
        $module_name = $route_parameters['module'];
        $permission_type = $route_parameters['permission_type'];
        $user_role = (new \Modules\User\Entities\UserRole)->getCurrentUserRole(Auth::id());        
        $allowed = false;
        foreach($user_role as $index => $role)        
        {
            if($role->role_id == 1 || $role->role_name == "admin")
            {
                return $next($request);
            }

            $permission = Permission::allowedOrNot($module_name, $permission_type, $role->role_id);
            if($permission)            
            {
                $allowed = true;
                break;
            }            
        }
        
        if($allowed)
        {
            return $next($request);    
        }
        else
        {
            abort(403);
        }        
    }

    public function allowedOrNot($module_name, $permission_type, $user_role_id)
    {
        $file = \File::get(base_path().'/Modules/'.$module_name.'/access.json');
        $file = json_decode($file, true);
                
        if(in_array($user_role_id, $file[$permission_type]))
        {
            return true;
            
        }
        return false;        
    }
}
