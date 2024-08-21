<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SystemCalendarController;
use App\Models\Address;
use App\Models\Award;
use App\Models\BookDataModal;
use App\Models\BookIssueModel;
use App\Models\ClassTimeTableTwo;
use App\Models\CollegeCalender;
use App\Models\CourseEnrollMaster;
use App\Models\Document;
use App\Models\EventOrganized;
use App\Models\EventParticipation;
use App\Models\HrmRequestLeaf;
use App\Models\IndustrialExperience;
use App\Models\IndustrialTraining;
use App\Models\Intern;
use App\Models\Iv;
use App\Models\NonTeachingStaff;
use App\Models\OnlineCourse;
use App\Models\Patent;
use App\Models\PersonalDetail;
use App\Models\PhdDetail;
use App\Models\{PublicationDetail,
    SellVehicleDetails,
    SellerEnquire,
    BuyerEnquire,
    Application
};

session()->start();
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;

class HomeController extends SystemCalendarController
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $get_staff = DB::table('role_user')->where(['user_id' => $user_id])->first();
        $roleTypeId = null;
        if (auth()->user()->roles->isNotEmpty()) {
            $roleTypeId = auth()->user()->roles[0]->type_id ?? null;
        }
        $user = isset($get_staff->user_id) ? $get_staff->user_id : '';
        $role = isset($get_staff->role_id) ? $get_staff->role_id : '';
                
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        $assignedRole = $user ? $user->roles->first() : null;
        $roleTitle = $assignedRole ? $assignedRole->id : 0;
        
        // Get the count of each status for all stages
         $query = Application::query()
            ->selectRaw('status, 
                        COUNT(CASE WHEN status = "Enquiry" THEN 1 END) as Enquiry,
                         COUNT(CASE WHEN status = "Underwriting" THEN 1 END) as Underwriting,
                         COUNT(CASE WHEN status = "Processing" THEN 1 END) as Processing,
                         COUNT(CASE WHEN status = "Assigned" THEN 1 END) as Assigned,
                         COUNT(CASE WHEN status = "Completions" THEN 1 END) as Completions,
                         COUNT(CASE WHEN status = "Completed" THEN 1 END) as Completed')
            ->groupBy('status');
            
            if ($roleTitle != 1) {
            $query->where('assigned_client_id', $userId); // Assuming assigned_client_id matches role id
             }
         $statusCounts =   $query ->first(); // You can use get() if you expect multiple rows
        
            // Check if result is null and set default values
            $statusCounts = $statusCounts ?: (object) [
                'Enquiry' => 0,
                'Underwriting' => 0,
                'Processing' => 0,
                'Assigned' => 0,
                'Completions' => 0,
                'Completed' => 0,
            ];

                    // Now you can use these counts as needed


            // $query = Application::query()->select('*');

            //   $sellvehiclecount =  SellVehicleDetails::where('status', 'Active')->count();
            //   $sellerEnquirecount =  SellerEnquire::where('status', 'Pending')->count();
            //   $buyerEnquirecount =  BuyerEnquire::where('status', 'Pending')->count();
                return view('home', compact('statusCounts'));
            }
        

    

}
