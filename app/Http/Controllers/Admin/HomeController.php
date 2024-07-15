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
    BuyerEnquire
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



        // $document = Document::where(['nameofuser_id' => $user_id, 'fileName' => 'Profile'])->first();
        // if (!is_null($document)) {
        //     $profile = $document->filePath;
        // } else {
        //     $profile = null;
        // }
      
            //  $teachingStaffs = TeachingStaff::count('id');
                // $nonTeachingStaffs = NonTeachingStaff::count('id');
                $userCounts = User::count('id');
                $currentDate = date('Y-m-d');
                // $calender = CollegeCalender::where('from_date', '<=', $currentDate)
                //     ->where('to_date', '>=', $currentDate)
                //     ->first();
                // if (!empty($calender->from_date)) {

                //     $events = DB::table('college_calenders_preview')
                //         ->select('date', 'dayorder')
                //         ->where('date', '>=', $calender->from_date)
                //         ->where('date', '<=', $calender->to_date)
                //         ->get();
                //     // dd($events);
                //     $dateTime = new DateTime($currentDate);
                //     $month = $dateTime->format('m');
                //     $year = $dateTime->format('Y');

                //     $date = new DateTime("$year-$month-01");
                //     $numDays = $date->format('t');
                //     $firstDayOfWeek = $date->format('w');
                //     $weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                //     $staff_leaves = HrmRequestLeaf::where('leave_type', 1)
                //         ->where('status', 'Pending')
                //         ->when($role == 4, function ($query) {
                //             return $query->where('level', '0');
                //         })
                //         ->count();

                //     $staff_od = HrmRequestLeaf::where('leave_type', [2, 3, 4])->where('status', 'Pending')->when($role == 4, function ($query) {
                //         return $query->where('level', '0');
                //     })->count();
                //     $check = 'nil';
                // } else {
                //     $check = 'empty';
                //     $month = '';
                //     $year = '';
                //     $numDays = '';
                //     $firstDayOfWeek = '';
                //     $weekdays = '';
                //     $events = [];
                //     $staff_leaves = 0;
                //     $staff_od = 0;
                // }

                // dd(SellVehicleDetails::get());

              $sellvehiclecount =  SellVehicleDetails::where('status', 'Active')->count();
              $sellerEnquirecount =  SellerEnquire::where('status', 'Pending')->count();
              $buyerEnquirecount =  BuyerEnquire::where('status', 'Pending')->count();
                return view('home', compact('userCounts', 'sellvehiclecount','sellerEnquirecount','buyerEnquirecount'));
            }
        

    

}
