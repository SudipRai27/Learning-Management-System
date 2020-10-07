<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Auth;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view)
        {
            if (Auth::check()) 
            {
                $current_user_id = Auth::id();

                $role = \Modules\User\Entities\UserRole::getCurrentUserRole($current_user_id);                
                $role_name = isset($role[0]->role_name) ? $role[0]->role_name : 'guest';
                if($role_name == "lecturer" || $role_name == "tutor")
                {
                    $role_name = "teacher";
                }                
                $current_user = (new \Modules\User\Entities\User)->getCurrentUser($current_user_id);    

                $view->with('role', $role_name)
                     ->with('current_user', $current_user)
                     ->with('role_details', $role);
            }    
            
        });       
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
                
    }
}
