<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class RolePermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // view()->composer(
        //     [
        //         'livewire.dashboard.*',
        //         'layouts.*'
        //     ],
        //     function ($view) {

        //         if (Auth::check()) {
        //             $submodule_access = DB::table('USR_ROLE_DETAIL')
        //                 ->whereIn('role_id', json_decode(Auth::user()->roles))
        //                 ->get();

        //             $permission_status = 0;

        //             Blade::if('permission', function ($sub_module, $flag) use ($submodule_access, $permission_status) {
        //                 foreach ($submodule_access as $access) {
        //                     if ($access->module_dtl_id == $sub_module) {
        //                         if ($access->$flag == 1) {
        //                             $permission_status = 1;
        //                             break;
        //                         }
        //                     }
        //                 }


        //                 if ($permission_status == 1) {
        //                     return true;

        //                 } else {

        //                     return false;
        //                 }
        //             });
        //         }
        //     }
        // );
    }
}