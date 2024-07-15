<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookIssueModel;
use App\Models\UserAlert;
use DB;

session()->start();
use App\Models\StaffBiometric;
use App\Models\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Models\NonTeachingStaff;
use App\Models\PersonalDetail;
use App\Models\TeachingStaff;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class ArtisanCmdController extends Controller
{
    public function ViewCache()
    {

        Artisan::call('view:cache');

        \Log::info("View Cached");

        return back();
    }

    public function ViewClear()
    {

        Artisan::call('view:clear');

        \Log::info("View Cleared");

        return back();
    }

    public function RouteCache()
    {

        Artisan::call('route:cache');

        \Log::info("Route Cached");

        return back();
    }

    public function RouteClear()
    {

        

        \Log::info("route Cleared");

        return back();
    }

    public function CacheClear()
    {

        Artisan::call('cache:clear');

        \Log::info("Cache Cleared");

        return back();
    }

    public function CacheForget($key)
    {
        Artisan::call('cache:forget', ['key' => $key]);

        \Log::info("Cache Forgeted");

        return back();
    }

    public function ConfigCache()
    {
        Artisan::call('config:cache');

        \Log::info("Config Cached");

        return back();
    }

    public function ConfigClear()
    {

        Artisan::call('config:clear');

        \Log::info("Config Cleared");

        return back();
    }

    public function ScheduleClearCache()
    {
        Artisan::call('schedule:clear-cache');

        \Log::info("Schedule Cache Cleared");

        return back();
    }

    public function StorageLink()
    {
        Artisan::call('storage:link');

        \Log::info("Storage Linked");

        return back();

    }
    public function overdue()
    {
        Artisan::call('overdue:update');

        \Log::info("overdue update");

        return back();

    }

    public function circle()
    {



        // $user = DB::table('non_teaching_staffs')
        //     ->whereNotNull('StaffCode')
        //     ->update(['StaffCode' => DB::raw("CONCAT('SV', StaffCode)")]);
        // $user = DB::table('users')
        //     ->whereNotNull('employID')
        //     ->update(['employID' => DB::raw('SUBSTRING(employID, 3)')]);

        // dd($user);


        // $get = PermissionRequest::where('updated_at', 'LIKE', "%2024-02-12%")->where(['status' => 2])->select('user_name_id', 'date', 'from_time', 'Permission')->get();
        // foreach ($get as $data) {
        //     if ($data->Permission == 'Personal' && $data->from_time == '08:00:00') {
        //         $permission = 'FN Permission';
        //     } elseif ($data->Permission == 'Personal' && ($data->from_time == '15:00:00' || $data->from_time == '16:00:00')) {
        //         $permission = 'AN Permission';
        //     } elseif ($data->Permission == 'On Duty') {
        //         $permission = 'OD Permission';
        //     }
        //     StaffBiometric::where(['date' => $data->date, 'user_name_id' => $data->user_name_id])->update([
        //         'permission' => $permission,
        //         'details' => $data->Permission . ' Permission',
        //         'update_status' => 2,
        //     ]);
        // }

        // $users = User::whereNotNull('employID')->get();
        // $teaching = TeachingStaff::whereNotNull('StaffCode')->get();
        // $nonteaching = NonTeachingStaff::whereNotNull('StaffCode')->get();
        // $personal = PersonalDetail::whereNotNull('StaffCode')->get();


        // if($teaching != null){
        //     foreach ($teaching as $key => $teach) {
        //         $teach->StaffCode = trim($teach->StaffCode, 'SV');
        //         $teach->StaffCode = 'SV'.$teach->StaffCode;
        //         $teach->save();
        //     }
        // }
        // if($nonteaching != null){
        //     foreach ($nonteaching as $key => $non) {
        //         $non->StaffCode = 'SV'.$non->StaffCode;
        //         $non->save();
        //     }
        // }

        // if($personal != null){
        //     foreach ($personal as $key => $per) {
        //         $per->StaffCode = trim($per->StaffCode, 'SV');
        //         $per->StaffCode = 'SV'.$per->StaffCode;
        //         $per->save();
        //     }
        // }

        // dd($personal, $nonteaching);

        return back();
    }

}
