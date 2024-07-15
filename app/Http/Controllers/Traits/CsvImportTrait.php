<?php

namespace App\Http\Controllers\Traits;

use App\Models\AcademicDetail;
use App\Models\AcademicYear;
use App\Models\Address;
use App\Models\AdmissionMode;
use App\Models\Batch;
use App\Models\BloodGroup;
use App\Models\BookDataModal;
use App\Models\BookModel;
use App\Models\Community;
use App\Models\CourseEnrollMaster;
use App\Models\EducationalDetail;
use App\Models\Examattendance;
use App\Models\ExamattendanceData;
use App\Models\ExamRegistration;
use App\Models\ExamResultPublish;
use App\Models\ExamTimetableCreation;
use App\Models\ExperienceDetail;
use App\Models\GenreModel;
use App\Models\GradeMaster;
use App\Models\MediumofStudied;
use App\Models\MotherTongue;
use App\Models\NonTeachingStaff;
use App\Models\ParentDetail;
use App\Models\PersonalDetail;
use App\Models\Religion;
use App\Models\Role;
use App\Models\Semester;
use App\Models\StaffBiometric;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectAllotment;
use App\Models\SubjectCategory;
use App\Models\SubjectRegistration;
use App\Models\SubjectType;
use App\Models\TeachingStaff;
use App\Models\TeachingType;
use App\Models\ToolsCourse;
use App\Models\ToolsDepartment;
use App\Models\ToolssyllabusYear;
use App\Models\User;
use App\Models\UserAlert;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SpreadsheetReader;

ini_set('max_execution_time', 3600);
trait CsvImportTrait
{
    public function processCsvImport(Request $request)
    {
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == "App\Models\Student") {
                // dd($row);
                // foreach ($for_insert[0] as $insert) {
                //     if ($insert['Date_Of_Birth'] != null && $insert['Date_Of_Birth'] != '') {
                //         $check = Student::where(['register_no' => $insert['Register_No']])->select('user_name_id')->first();
                //         if ($check != '') {
                //             $given_date = $insert['Date_Of_Birth'];
                //             $formattedDob = $detectedFormat = $formattedDate = $age = null;

                //             $formats = [
                //                 'd-m-Y',
                //                 'm/d/Y',
                //                 'd/m/Y',
                //             ];

                //             for ($i = 0; $i < count($formats); $i++) {
                //                 $dateTime = DateTime::createFromFormat($formats[$i], $given_date);
                //                 if ($dateTime != false) {
                //                     $formattedDob = $dateTime->format('Y-m-d');
                //                     $presentDate = new DateTime();
                //                     break;
                //                 }
                //             }
                //             $update = PersonalDetail::where(['user_name_id' => $check->user_name_id])->update([
                //                 'dob' => $formattedDob,
                //             ]);
                //         }
                //     }
                // }
                $import_status = null;
                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['Name'] != '' && $insert['Register_No'] != '' && $insert['Student_Email'] != '') {
                        // $email_validate = DB::select("SELECT * FROM users WHERE email = :email", ['email' => $insert['Student_Email']]);
                        // //    dd($email_validate);
                        // $user_name = null;

                        // if (count($email_validate) > 0) {
                        //     $user_name = $email_validate[0]->name;
                        // }

                        // if (count($email_validate) < 1) {
                        //     // $check_user = User::where(['register_no' => $insert['Register_No']])->first();
                        //     $reg_validate = DB::select("SELECT * FROM users WHERE register_no = :register_no", ['register_no' => $insert['Register_No']]);
                        //     //    dd($email_validate);
                        //     if (count($reg_validate) > 0) {
                        //         $user_name = $reg_validate[0]->name;
                        //     }
                        //     if (empty($reg_validate)) {

                        //         // dd('stop');

                        //         $user = new User;
                        //         $user->name = $insert['Name'];
                        //         $user->register_no = $insert['Register_No'];
                        //         $user->email = $insert['Student_Email'];
                        //         $user->password = bcrypt($insert['Student_Phone_No']);
                        //         $user->save();

                        //         $admin = Role::select('id')
                        //             ->where('title', 'Student')
                        //             ->latest()
                        //             ->first();
                        //         $role_id = $admin->id;
                        //         $user->roles()->sync($request->input('roles', $role_id));
                        //         // $rols

                        //         $get_short_form = $insert['Admitted_Course'] ?? null;

                        //         if ($get_short_form != null) {
                        //             $get_course = ToolsCourse::where('short_form', 'LIKE', "{$get_short_form}")->first();
                        //         } else {
                        //             $get_course = '';
                        //         }

                        //         // if ($get_course != '') {
                        //         //     $course = $get_course->name ?? null;
                        //         // } else {
                        //         //     $course = null;
                        //         // }

                        //         $batch = $insert['Batch'] ?? null;
                        //         $accademicYear = $insert['Academic_Year'] ?? null;
                        //         $semester = $insert['Current_Semester'] ?? null;
                        //         $section = $insert['Section'] ?? null;

                        //         $enrollMaster = $batch . '/' . $get_course->short_form . '/' . $accademicYear . '/' . $semester . '/' . $section;
                        //         $id = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->latest()
                        //             ->first();
                        //         $enroll = null;

                        //         if ($id != null) {
                        //             $enroll = $id->id;
                        //         } else {
                        //             $enroll = null;
                        //         }

                        //         $studentcreate = new Student;
                        //         $studentcreate->name = $insert['Name'];
                        //         $studentcreate->register_no = $insert['Register_No'] ?? null;
                        //         $studentcreate->student_phone_no = $insert['Student_Phone_No'] ?? null;
                        //         $studentcreate->student_email_id = $insert['Student_Email'] ?? null;
                        //         $studentcreate->student_batch = $batch ?? null;
                        //         $studentcreate->admitted_course = $get_course->short_form ?? null;
                        //         $studentcreate->enroll_master_id = $enroll;
                        //         $studentcreate->user_name_id = $user->id;
                        //         $studentcreate->save();

                        //         // dd($studentcreate);
                        //         $formattedDob = $blood_group = $mother_tongue = $religion = $community = null;

                        //         if ($insert['Date_Of_Birth'] != null && $insert['Date_Of_Birth'] != '') {
                        //             $given_date = $insert['Date_Of_Birth'];
                        //             $formattedDate = null;

                        //             $formats = [
                        //                 'd-m-Y',
                        //                 'm/d/Y',
                        //                 'd/m/Y',
                        //             ];

                        //             for ($i = 0; $i < count($formats); $i++) {
                        //                 $dateTime = DateTime::createFromFormat($formats[$i], $given_date);
                        //                 if ($dateTime != false) {
                        //                     $formattedDob = $dateTime->format('Y-m-d');
                        //                     break;
                        //                 }
                        //             }
                        //         }

                        //         if ($insert['Blood_Group'] != null && $insert['Blood_Group'] != '') {
                        //             $blood = $insert['Blood_Group'];
                        //             $get_blood_id = BloodGroup::where('name', 'like', "%{$blood}%")->first();
                        //             if ($get_blood_id != '') {
                        //                 $blood_group = $get_blood_id->id;
                        //             }
                        //         }

                        //         if ($insert['Mother_Tongue'] != null && $insert['Mother_Tongue'] != '') {
                        //             $mt = $insert['Mother_Tongue'];
                        //             $get_mother_tongue_id = MotherTongue::where('mother_tongue', 'like', "%{$mt}%")->first();
                        //             if ($get_mother_tongue_id != '') {
                        //                 $mother_tongue = $get_mother_tongue_id->id;
                        //             }
                        //         }

                        //         if ($insert['Religion'] != null && $insert['Religion'] != '') {
                        //             $relig = $insert['Religion'];
                        //             $get_religion_id = Religion::where('name', 'like', "%{$relig}%")->first();
                        //             if ($get_religion_id != '') {
                        //                 $religion = $get_religion_id->id;
                        //             }
                        //         }

                        //         if ($insert['Community'] != null && $insert['Community'] != '') {
                        //             $commu = $insert['Community'];
                        //             $get_community_id = Community::where('name', 'like', "%{$commu}%")->first();
                        //             if ($get_community_id != '') {
                        //                 $community = $get_community_id->id;
                        //             }
                        //         }
                        //         $admittedMode = null;
                        //         if ($insert['Admitted_Mode'] != null && $insert['Admitted_Mode'] != '') {
                        //             $getAdmittedMode = AdmissionMode::where(['name' => $insert['Admitted_Mode']])->select('id')->first();
                        //             if ($getAdmittedMode != '') {
                        //                 $admittedMode = $getAdmittedMode->id;
                        //             }
                        //         }

                        //         $personalDetails = new PersonalDetail;
                        //         $personalDetails->user_name_id = $user->id;
                        //         $personalDetails->name = $insert['Name'];
                        //         $personalDetails->mobile_number = $insert['Student_Phone_No'] ?? null;
                        //         $personalDetails->aadhar_number = $insert['Aadhar_Card_No'] ?? null;
                        //         $personalDetails->email = $insert['Student_Email'] ?? null;
                        //         $personalDetails->dob = $formattedDob;
                        //         $personalDetails->caste = $insert['Caste'] ?? null;
                        //         $personalDetails->later_entry = $insert['Later_Entry'] ?? 'No';
                        //         $personalDetails->first_graduate = $insert['First_Graduate'] ?? 'No';
                        //         $personalDetails->different_abled_person = $insert['Different_Abled_Person'] ?? 'No';
                        //         $personalDetails->day_scholar_hosteler = $insert['Dayscholar_or_Hosteler'] ?? null;
                        //         $personalDetails->gender = $insert['Gender'] ?? null;
                        //         $personalDetails->blood_group_id = $blood_group;
                        //         $personalDetails->mother_tongue_id = $mother_tongue;
                        //         $personalDetails->religion_id = $religion;
                        //         $personalDetails->community_id = $community;
                        //         $personalDetails->state = $insert['State'] ?? null;
                        //         $personalDetails->country = $insert['Nationality'] ?? null;
                        //         $personalDetails->whatsapp_no = $insert['Whatsapp_No'] ?? null;
                        //         $personalDetails->annual_income = $insert['Annual_Income'] ?? null;
                        //         $personalDetails->save();

                        //         $academicDetails = new AcademicDetail();
                        //         $academicDetails->register_number = $insert['Register_No'] ?? null;
                        //         $academicDetails->emis_number = $insert['Emis_Number'] ?? null;
                        //         $academicDetails->admitted_course = $get_course->id ?? null;
                        //         $academicDetails->admitted_mode = $admittedMode;
                        //         $academicDetails->enroll_master_number_id = $enroll;
                        //         $academicDetails->user_name_id = $user->id;
                        //         $academicDetails->save();

                        //         $medium = MediumofStudied::get();

                        //         $sslc_medium = $hsc_medium = $dip_medium = $medium_sslc = $medium_hsc = $medium_dip = null;

                        //         if ($insert['Medium_Of_Studied(SSLC)'] != '') {
                        //             $sslc_medium = $insert['Medium_Of_Studied(SSLC)'];
                        //         }
                        //         if ($insert['Medium_Of_Studied(HSC)'] != '') {
                        //             $hsc_medium = $insert['Medium_Of_Studied(HSC)'];
                        //         }
                        //         if ($insert['Medium_Of_Studied(DIPLOMA)'] != '') {
                        //             $dip_medium = $insert['Medium_Of_Studied(DIPLOMA)'];
                        //         }
                        //         foreach ($medium as $data) {
                        //             if ($sslc_medium == $data->medium) {
                        //                 $medium_sslc = $data->id;
                        //             }
                        //             if ($hsc_medium == $data->medium) {
                        //                 $medium_hsc = $data->id;
                        //             }
                        //             if ($dip_medium == $data->medium) {
                        //                 $medium_dip = $data->id;
                        //             }
                        //         }

                        //         if (isset($insert['Education_Type(SSLC)'])) {
                        //             $stu_education_1 = EducationalDetail::create([
                        //                 'education_type_id' => 2,
                        //                 'user_name_id' => $user->id,
                        //                 'institute_name' => $insert['Institute_Name(SSLC)'],
                        //                 'institute_location' => $insert['Institute_Location(SSLC)'],
                        //                 'board_or_university' => $insert['Board(SSLC)'],
                        //                 'medium_id' => $medium_sslc,
                        //                 'register_number' => $insert['Register_Number(SSLC)'],
                        //                 'marks' => $insert['Total_Marks(SSLC)'],
                        //                 'cutoffmark' => $insert['Cutoff_Mark(SSLC)'],
                        //                 'marks_in_percentage' => $insert['Total_Marks_In_Percentage(SSLC)'],
                        //                 'passing_year' => $insert['Passing_Year(SSLC)'],
                        //                 'subject_1' => $insert['Subject_1(SSLC)'],
                        //                 'mark_1' => $insert['Mark_1(SSLC)'],
                        //                 'subject_2' => $insert['Subject_2(SSLC)'],
                        //                 'mark_2' => $insert['Mark_2(SSLC)'],
                        //                 'subject_3' => $insert['Subject_3(SSLC)'],
                        //                 'mark_3' => $insert['Mark_3(SSLC)'],
                        //                 'subject_4' => $insert['Subject_4(SSLC)'],
                        //                 'mark_4' => $insert['Mark_4(SSLC)'],
                        //                 'subject_5' => $insert['Subject_5(SSLC)'],
                        //                 'mark_5' => $insert['Mark_5(SSLC)'],
                        //             ]);
                        //         }

                        //         if (isset($insert['Education_Type(HSC)'])) {
                        //             $stu_education_2 = EducationalDetail::create([
                        //                 'education_type_id' => 1,
                        //                 'user_name_id' => $user->id,
                        //                 'institute_name' => $insert['Institute_Name(HSC)'],
                        //                 'institute_location' => $insert['Institute_Location(HSC)'],
                        //                 'board_or_university' => $insert['Board(HSC)'],
                        //                 'medium_id' => $medium_hsc,
                        //                 'register_number' => $insert['Register_Number(HSC)'],
                        //                 'marks' => $insert['Total_Marks(HSC)'],
                        //                 'cutoffmark' => $insert['Cutoff_Mark(HSC)'],
                        //                 'marks_in_percentage' => $insert['Total_Marks_In_Percentage(HSC)'],
                        //                 'passing_year' => $insert['Passing_Year(HSC)'],
                        //                 'subject_1' => $insert['Subject_1(HSC)'],
                        //                 'mark_1' => $insert['Mark_1(HSC)'],
                        //                 'subject_2' => $insert['Subject_2(HSC)'],
                        //                 'mark_2' => $insert['Mark_2(HSC)'],
                        //                 'subject_3' => $insert['Subject_3(HSC)'],
                        //                 'mark_3' => $insert['Mark_3(HSC)'],
                        //                 'subject_4' => $insert['Subject_4(HSC)'],
                        //                 'mark_4' => $insert['Mark_4(HSC)'],
                        //                 'subject_5' => $insert['Subject_5(HSC)'],
                        //                 'mark_5' => $insert['Mark_5(HSC)'],
                        //                 'subject_6' => $insert['Subject_6(HSC)'],
                        //                 'mark_6' => $insert['Mark_6(HSC)'],
                        //             ]);
                        //         }

                        //         if (isset($insert['Education_Type(DIPLOMA)'])) {
                        //             $stu_education_3 = EducationalDetail::create([
                        //                 'education_type_id' => 5,
                        //                 'user_name_id' => $user->id,
                        //                 'institute_name' => $insert['Institute_Name(DIPLOMA)'],
                        //                 'institute_location' => $insert['Institute_Location(DIPLOMA)'],
                        //                 'board_or_university' => $insert['Board_or_University(DIPLOMA)'],
                        //                 'medium_id' => $medium_dip,
                        //                 'register_number' => $insert['Register_Number(DIPLOMA)'],
                        //                 'marks' => $insert['Total_Marks(DIPLOMA)'],
                        //                 'cutoffmark' => $insert['Cutoff_Mark(DIPLOMA)'],
                        //                 'marks_in_percentage' => $insert['Total_Marks_In_Percentage(DIPLOMA)'],
                        //                 'passing_year' => $insert['Passing_Year(DIPLOMA)'],
                        //             ]);
                        //         }

                        //         $stu_parent = new ParentDetail;
                        //         $stu_parent->father_name = $insert['Father_Name'] ?? null;
                        //         $stu_parent->mother_name = $insert['Mother_Name'] ?? null;
                        //         $stu_parent->guardian_name = $insert['Guardian_Name'] ?? null;
                        //         $stu_parent->father_mobile_no = $insert['Father_Mobile_No'] ?? null;
                        //         $stu_parent->father_email = $insert['Father_Email'] ?? null;
                        //         $stu_parent->mother_mobile_no = $insert['Mother_Mobile_No'] ?? null;
                        //         $stu_parent->mother_email = $insert['Mother_Email'] ?? null;
                        //         $stu_parent->guardian_mobile_no = $insert['Guardian_Mobile_No'] ?? null;
                        //         $stu_parent->guardian_email = $insert['Guardian_Email'] ?? null;
                        //         $stu_parent->fathers_occupation = $insert['Father_Occupation'] ?? null;
                        //         $stu_parent->mothers_occupation = $insert['Mother_Occupation'] ?? null;
                        //         $stu_parent->father_off_address = $insert['Father_Off_Address'] ?? null;
                        //         $stu_parent->mother_off_address = $insert['Mother_Off_Address'] ?? null;
                        //         $stu_parent->gaurdian_occupation = $insert['Guardian_Occupation'] ?? null;
                        //         $stu_parent->guardian_off_address = $insert['Guardian_Off_Address'] ?? null;
                        //         $stu_parent->user_name_id = $user->id;
                        //         $stu_parent->save();

                        //         if (isset($insert['Address_Type(Permanent)'])) {
                        //             $stu_address = new Address;
                        //             $stu_address->address_type = 'Permanent';
                        //             $stu_address->room_no_and_street = $insert['Room_No_and_Street(Permanent)'] ?? null;
                        //             $stu_address->area_name = $insert['Area_Name(Permanent)'] ?? null;
                        //             $stu_address->district = $insert['District(Permanent)'] ?? null;
                        //             $stu_address->pincode = $insert['Pincode(Permanent)'] ?? null;
                        //             $stu_address->state = $insert['State(Permanent)'] ?? null;
                        //             $stu_address->country = $insert['Country(Permanent)'] ?? null;
                        //             $stu_address->name_id = $user->id;
                        //             $stu_address->save();
                        //         }

                        //         if (isset($insert['Address_Type(Temporary)'])) {
                        //             $stu_address = new Address;
                        //             $stu_address->address_type = 'Temporary';
                        //             $stu_address->room_no_and_street = $insert['Room_No_and_Street(Temporary)'] ?? null;
                        //             $stu_address->area_name = $insert['Area_Name(Temporary)'] ?? null;
                        //             $stu_address->district = $insert['District(Temporary)'] ?? null;
                        //             $stu_address->pincode = $insert['Pincode(Temporary)'] ?? null;
                        //             $stu_address->state = $insert['State(Temporary)'] ?? null;
                        //             $stu_address->country = $insert['Country(Temporary)'] ?? null;
                        //             $stu_address->name_id = $user->id;
                        //             $stu_address->save();
                        //         }
                        //         $balance_row--;
                        //     } else {
                        //         $inserted_rows = $rows - $balance_row;
                        //         $import_status = 'Error';
                        //         session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        //         return redirect($request->input('redirect'))->with('error', 'Register No : ' . $insert['Register_No'] . ' Already Registered For  ' . $user_name);
                        //     }
                        // } else {
                        //     // dd($email_validate); 
                        //     // continue;
                        //     // $inserted_rows = $rows - $balance_row;
                        //     // $import_status = 'Error';
                        //     // session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        //     // return redirect($request->input('redirect'))->with('error', 'Email : ' . $insert['Student_Email'] . ' Already Registered For  ' . $user_name);
                        // }

                        $check = User::where('register_no', $insert['Register_No'])->first();
                        if ($check != null) {
                            $course = $insert['Admitted_Course'];
                            $batch = $insert['Batch'] ?? null;
                            $ay = $insert['Academic_Year'] ?? null;
                            $sem = $insert['Current_Semester'] ?? null;
                            $sec = $insert['Section'] ?? null;

                            $enrollMaster = $batch . '/' . $course . '/' . $ay . '/' . $sem . '/' . $sec;
                            $id = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->latest()->first();
                            if ($id != null) {
                                $enroll = $id->id;
                            } else {
                                $enroll = null;
                            }
                            // dd($enroll);
                            $student = Student::where('register_no', $insert['Register_No'])->update([
                                'enroll_master_id' => $enroll
                            ]);

                            $AcademicDetail = AcademicDetail::where('register_number', $insert['Register_No'])->update([
                                'enroll_master_number_id' => $enroll
                            ]);


                        }
                        $balance_row--;


                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($request->modelName == 'TeachingStaff') {
                $import_status = null;
                $balance_row = $rows;
                $inserted_rows = $rows - $balance_row;

                foreach ($for_insert[0] as $insert) {
                    if ($insert['name'] != '' && $insert['name'] != null) {
                        // dd($insert['name']);
                        $check_user = User::where(['employID' => $insert['StaffCode']])->count();
                        // dd($check_user, $insert);
                        if ($check_user > 0) {
                            continue;
                        } else {

                            $get_Dept = ToolsDepartment::where('name', 'LIKE', $insert['Dept'])->first();
                            // dd($get_Dept->id);
                            if ($get_Dept != '' || $get_Dept != null) {

                                // dd('here');
                                if ($check_user == 0) {
                                    // dd('hii');
                                    $balance_row--;

                                    $user = new User;
                                    $user->name = $insert['name'];
                                    $user->email = $insert['EmailIDOffical'];
                                    $user->employID = $insert['StaffCode'];
                                    $user->dept = $get_Dept->name;
                                    $user->password = bcrypt($insert['ContactNo']);
                                    $user->save();

                                    $role_id = Role::where(['title' => $insert['Designation']])->value('id');
                                    $user->roles()->sync($request->input('roles', $role_id));
                                    $role_type = TeachingType::where(['name' => $insert['role_type']])->value('id');
                                    $formats = [
                                        'd-m-y',
                                        'd-m-Y',
                                        'd/m/y',
                                        'd/m/Y',
                                    ];
                                    $formattedDate = null;
                                    foreach ($formats as $i => $format) {
                                        try {
                                            $the_date = Carbon::createFromFormat($format, $insert['DOJ']);

                                            $dateOnly = $the_date->format('Y-m-d');
                                            if ($dateOnly != '') {
                                                $formattedDate = $dateOnly;
                                                break;
                                            }
                                        } catch (Exception $e) {

                                        }
                                    }
                                    // dd($role_id,$role_type,$formattedDate);
                                    $staffCreate = new TeachingStaff;
                                    $staffCreate->name = $insert['name'];
                                    $staffCreate->StaffCode = $insert['StaffCode'];
                                    $staffCreate->Designation = $insert['Designation'];
                                    $staffCreate->Dept = $get_Dept->name;
                                    $staffCreate->BiometricID = $insert['BiometricID'];
                                    $staffCreate->ContactNo = $insert['ContactNo'];
                                    $staffCreate->EmailIDOffical = $insert['EmailIDOffical'];
                                    $staffCreate->role_type = $role_type;
                                    $staffCreate->user_name_id = $user->id;
                                    $staffCreate->save();

                                    $personalDetails = new PersonalDetail();
                                    $personalDetails->name = $insert['name'];
                                    // $personalDetails->last_name = $insert['last_name'];
                                    $personalDetails->email = $insert['EmailIDOffical'];
                                    $personalDetails->mobile_number = $insert['ContactNo'];
                                    $personalDetails->StaffCode = $insert['StaffCode'];
                                    $personalDetails->DOJ = $formattedDate;
                                    $personalDetails->user_name_id = $user->id;
                                    $personalDetails->save();
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    $import_status = 'Error';
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Staff Code : ' . $insert['StaffCode'] . ' Already Registered For ' . $check_user->name);
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                $import_status = 'Error';
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', $insert['Dept'] . 'Not Found In Deparments For ' . $insert['name']);
                            }
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($request->modelName == 'BookModel') {
                $import_status = null;
                $balance_row = $rows;
                $inserted_rows = $rows - $balance_row;

                foreach ($for_insert[0] as $insert) {
                    if ($insert['name'] != '' && $insert['name'] != null || $insert['book_count'] != null || $insert['isbn'] != null) {
                        $check_book = BookModel::where(['isbn' => $insert['isbn']])->get();
                        if (count($check_book) > 0) {
                            continue;
                        } else {
                            if ($insert['name'] != null) {
                                $check = BookModel::where(['name' => $insert['name']])->count();
                                if ($check > 0) {
                                    continue;
                                } else {
                                    $balance_row--;

                                    if (str_contains($insert['genre'], ',')) {
                                        $get_genre = array_map('trim', explode(',', $insert['genre']));
                                    } else {
                                        $get_genre[] = $insert['genre'];
                                    }

                                    $genre = GenreModel::whereIn('genre', $get_genre)->pluck('id')->toJson();
                                    // dd($genre, $get_genre, str_contains($insert['genre'], ','));
                                    $book = BookModel::create([
                                        'name' => strtoupper($insert['name']),
                                        'isbn' => $insert['isbn'],
                                        'genre' => $genre,
                                        'author' => strtoupper($insert['author']),
                                        'publication' => strtoupper($insert['publication']),
                                        'book_count' => $insert['book_count']
                                    ]);

                                    $count = BookDataModal::where('book_id', $book->id)->count();

                                    if ($insert['book_count'] != '' || $insert['book_count'] != 0) {
                                        for ($i = 1; $i <= $insert['book_count']; $i++) {
                                            //Barcode...
                                            // $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                            // $image = $generator->getBarcode('https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count += 1, $generator::TYPE_CODE_128);
                                            // $image = $generator->getBarcode('https://127.0.0.1:8001/admin/book-issue/get-book-info/' . $request->book_code . '-' . $count += 1, $generator::TYPE_CODE_128);
                                            // File::put(public_path('barcodes/' . $request->book_code . '-' . $count . '.png'), $image);

                                            $data = QrCode::size(300)
                                                ->format('png')
                                                ->style('dot')
                                                ->eye('circle')
                                                ->margin(1)
                                                ->errorCorrection('M')
                                                ->generate(
                                                    'https://svcet.kalvierp.com/admin/book-issue/get-book-info/' . $insert['isbn'] . '-' . $count += 1,
                                                    // 'http://127.0.0.1:8001/admin/book-issue/get-book-info/' . $insert['isbn'] . '-' . $count
                                                );

                                            File::put(public_path('qrcodes/' . $insert['isbn'] . '-' . $count . '.png'), $data);

                                            $books = BookDataModal::create([
                                                'book_id' => $book->id,
                                                'book_code' => $insert['isbn'] . '-' . $count,
                                                'status' => 'Available',
                                                'availability' => 'Yes',
                                                // 'barcode_image' => $request->book_code . '-' . $count . '.png',
                                                'qrcode_image' => $insert['isbn'] . '-' . $count . '.png'
                                            ]);

                                        }
                                    }
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                $import_status = 'Error';
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Book Name is Empty...');
                            }
                        }
                    } else {
                        continue;
                    }
                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($request->modelName == 'StaffBiometric') {

                // $allRows = StaffBiometric::all();
                // dd($for_insert);
                $balance_row = $rows;

                foreach ($for_insert as $insert_data) {
                    foreach ($insert_data as $insert) {
                        $balance_row--;
                        if ((isset($insert['date']) && isset($insert['staff_code'])) && ($insert['date'] != '' && $insert['staff_code'])) {
                            $in_time = isset($insert['in_time']) && $insert['in_time'] != '' ? $insert['in_time'] : '00:00:00';
                            $out_time = isset($insert['out_time']) && $insert['out_time'] != '' ? $insert['out_time'] : '00:00:00';
                            // dd($insert);
                            $permission = '';

                            if ($in_time != '00:00:00' && $out_time != '00:00:00') {
                                $in = strtotime($in_time);
                                $out = strtotime($out_time);

                                $duration_seconds = $out - $in;

                                $total_hours = gmdate('H:i:s', $duration_seconds);
                            } else {
                                $total_hours = null;
                            }

                            if (strtotime($in_time) > strtotime('08:00:00') && strtotime($in_time) < strtotime('08:15:00')) {
                                $details = 'Late';
                            } else if (strtotime($in_time) > strtotime('08:15:00')) {
                                $details = 'Too Late';
                            } else {
                                $details = null;
                            }

                            if ($in_time != '00:00:00' && $out_time != '00:00:00') {
                                $status = 'Present';
                            } else {
                                $status = 'Absent';
                            }
                            $day_punches = $insert['day_punches'] == '' ? null : $insert['day_punches'];
                            $staff_code = $insert['staff_code'];

                            $staff_code = preg_replace('/\s+/', '', $staff_code);

                            $given_date = $insert['date'];
                            $formattedDate = null;

                            $formats = [
                                'd-m-y',
                                'd-m-Y',
                                'd/m/y',
                                'd/m/Y',
                            ];

                            foreach ($formats as $i => $format) {
                                try {
                                    $the_date = Carbon::createFromFormat($format, $given_date);

                                    // Extract only the date part
                                    $dateOnly = $the_date->format('Y-m-d');
                                    //   echo 'no: '.$i;
                                    if ($dateOnly != '') {
                                        $formattedDate = $dateOnly;
                                        break;
                                    }
                                } catch (Exception $e) {
                                    // Do nothing, just continue to the next format
                                }
                            }

                            // dd($formattedDate);
                            if ($formattedDate != null) {
                                $check_date = Carbon::createFromFormat('Y-m-d', $formattedDate);
                                if ($check_date->dayOfWeek == Carbon::SUNDAY) {
                                    $details = 'Sunday';
                                }
                                $check = StaffBiometric::where(['date' => $formattedDate, 'staff_code' => $insert['staff_code']])->first();
                                if ($check) {
                                    if ($check->update_status == null) {

                                        $shift = $check->shift;
                                        if ($details != 'Sunday') {
                                            if ($shift == 1) {
                                                if ($out_time != '00:00:00') {
                                                    if (strtotime($out_time) < strtotime('16:00:00')) {
                                                        if ($details == 'Late') {
                                                            $details = 'Late & Early Out';
                                                        } elseif ($details == 'Too Late') {
                                                            $details = 'Too Late & Early Out';
                                                        } else {
                                                            $details = 'Early Out';
                                                        }
                                                    }
                                                }
                                            } else if ($shift == 2) {
                                                if ($out_time != '00:00:00') {
                                                    if (strtotime($out_time) < strtotime('17:00:00')) {
                                                        if ($details == 'Late') {
                                                            $details = 'Late & Early Out';
                                                        } elseif ($details == 'Too Late') {
                                                            $details = 'Too Late & Early Out';
                                                        } else {
                                                            $details = 'Early Out';
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        StaffBiometric::where('date', $formattedDate)
                                            ->where('staff_code', $insert['staff_code'])
                                            ->update([
                                                'day_punches' => $day_punches,
                                                'in_time' => $in_time,
                                                'out_time' => $out_time,
                                                'total_hours' => $total_hours,
                                                'details' => $details,
                                                'status' => $status,
                                                'created_at' => Carbon::now(),
                                            ]);
                                    } else {
                                        StaffBiometric::where('date', $formattedDate)
                                            ->where('staff_code', $insert['staff_code'])
                                            ->update([
                                                'in_time' => $in_time,
                                                'out_time' => $out_time,
                                                'total_hours' => $total_hours,
                                                'status' => $status,
                                                'created_at' => Carbon::now(),
                                            ]);
                                    }
                                }
                            }
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\SubjectMaster") {

                $balance_row = $rows;
                foreach ($for_insert[0] as $insert) {
                    // dd($insert);
                    if ($insert['Subject_Code'] != '' && $insert['Subject_Name'] != '' && $insert['Credits'] != '') {
                        $subject = null;
                        $semester = $course = $dept = $regulation = $subject_type = $subject_cat = null;

                        // $check_subject = Subject::where(['subject_code' => $insert['Subject_Code'], 'course_id' => $get_course->id])->get();
                        // $check_subject1 = Subject::where(['subject_code' => $insert['Subject_Code'], 'department_id' => $get_dept->id])->get();
                        // if (count($check_subject) > 0 || count($check_subject1) > 0) {
                        // dd($check_subject, $check_subject1);
                        // continue;
                        // $inserted_rows = $rows - $balance_row;
                        // session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => 'Subject Master']));
                        // return redirect($request->input('redirect'))->with('error', 'Subject Code : ' . $insert['Subject_Code'] . ' Already Registered For ' . $check_subject[0]->name);
                        // } else {


                        // dd($get_regulation);
                        // $get_dept = ToolsDepartment::where('name', 'like', "%{$insert['Department']}%")->first();
                        // $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['Course']}")->first();
                        // $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}%")->first();

                        // dd($get_regulation, $get_dept, $get_course, $get_subject_type, $get_semester, $get_ay);

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['Course']}")->first();
                        $get_dept = ToolsDepartment::where('name', 'like', "%{$insert['Department']}%")->first();
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['Regulation']}%")->first();
                        $get_subject_type = SubjectType::where('name', 'like', "%{$insert['Subject_Type']}%")->first();
                        $get_subject_cat = SubjectCategory::where('name', 'like', "%{$insert['Subject_Category']}%")->first();
                        $get_semester = Semester::where('semester', $insert['Semester'])->first();

                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        }

                        if ($get_dept != '') {
                            $dept = $get_dept->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }

                        if ($get_subject_type != '') {
                            $subject_type = $get_subject_type->id;
                        }

                        if ($get_semester != '') {
                            $semester = $get_semester->id;
                        }
                        if ($get_subject_cat != '') {
                            $subject_cat = $get_subject_cat->id;
                        }
                        $subject = new Subject;

                        $check = $subject->where([
                            'subject_code' => $insert['Subject_Code'],
                            'name' => $insert['Subject_Name'],
                            'regulation_id' => $regulation,
                            'department_id' => $dept,
                            'course_id' => $course,
                            'semester_id' => $semester,
                            'subject_type_id' => $subject_type,
                            'subject_cat_id' => $subject_cat,
                        ])->get();
                        // dd(count($check));
                        if (count($check) > 0) {
                            continue;
                        } else {
                            $subject->subject_code = $insert['Subject_Code'] ?? null;
                            $subject->name = $insert['Subject_Name'] ?? null;
                            $subject->regulation_id = $regulation ?? null;
                            $subject->department_id = $dept ?? null;
                            $subject->course_id = $course ?? null;
                            $subject->semester_id = $semester ?? null;
                            $subject->subject_type_id = $subject_type ?? null;
                            $subject->subject_cat_id = $subject_cat ?? null;
                            $subject->lecture = $insert['Lecture'] ?? null;
                            $subject->tutorial = $insert['Tutorial'] ?? null;
                            $subject->practical = $insert['Practical'] ?? null;
                            $subject->credits = $insert['Credits'] ?? null;
                            $subject->contact_periods = $insert['Contact_Periods'] ?? null;
                            $subject->created_at = Carbon::now();
                            $subject->save();
                        }



                        $balance_row--;
                        // }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));

            } elseif ($model == 'App\Models\SubjectAllotment') {
                $balance_row = $rows;
                // dd($for_insert[0]);
                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['department'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['subject_type'] != '') {

                        $semester = $course = $dept = $regulation = $subject_type = $ay = null;

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        $get_dept = ToolsDepartment::where('name', 'like', "%{$insert['department']}%")->first();
                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject_type = SubjectType::where('name', 'like', "%{$insert['subject_type']}%")->first();
                        $get_semester = Semester::where('semester', $insert['semester'])->first();
                        $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}%")->first();

                        // dd($get_regulation, $get_dept, $get_course, $get_subject_type, $get_semester, $get_ay);

                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        }

                        if ($get_dept != '') {
                            $dept = $get_dept->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }

                        if ($get_subject_type != '') {
                            $subject_type = $get_subject_type->id;
                        }

                        if ($get_semester != '') {
                            $semester = $get_semester->id;
                        }

                        if ($get_ay != '') {
                            $ay = $get_ay->id;
                        }

                        // if ($insert['category'] == 'Regular Subject') {
                        //     $limit = 0;
                        // } else {
                        //     $limit = $insert['option_limits'];
                        // }
                        $limit = 0;
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();
                        // dd($insert['subject_code'], $regulation,$get_subject);

                        if ($get_subject != '') {
                            $checkAllot = SubjectAllotment::where(['regulation' => $regulation, 'department' => $dept, 'course' => $course, 'academic_year' => $ay, 'semester' => $semester, 'subject_id' => $get_subject->id])->select('id')->get();
                            if (count($checkAllot) <= 0) {
                                $subject_allotment = new SubjectAllotment;
                                $subject_allotment->regulation = $regulation;
                                $subject_allotment->department = $dept;
                                $subject_allotment->course = $course;
                                $subject_allotment->academic_year = $ay;
                                $subject_allotment->semester_type = $insert['semester_type'];
                                $subject_allotment->semester = $semester;
                                $subject_allotment->category = $insert['category'];
                                $subject_allotment->subject_id = $get_subject->id;
                                $subject_allotment->credits = $get_subject->credits;
                                $subject_allotment->option_limits = $limit == '' ? null : null;
                                $subject_allotment->save();
                                // dd($subject_allotment);
                                $balance_row--;
                            }
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\SubjectRegistration") {
                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['student_name'] != '' && $insert['register_no'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['section'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['regulation'] != '') {
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $regulation = null;
                        }

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_ay = AcademicYear::where('name', 'like', "%{$insert['academic_year']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();

                        $course = null;

                        if ($get_course != '') {
                            $course = $get_course->name;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Course Not Found.');
                        }
                        if ($get_ay != '' && $regulation != null) {
                            $checkAllotment = SubjectAllotment::where(['course' => $get_course->id, 'semester' => $insert['semester'], 'academic_year' => $get_ay->id, 'regulation' => $regulation])->count();
                            if ($checkAllotment <= 0) {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Allotment Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'AY Not Found.');
                        }
                        $enrollMaster = $insert['batch'] . '/' . $course . '/' . $insert['academic_year'] . '/' . $insert['semester'] . '/' . $insert['section'];

                        $enroll_master = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->select('id')->first();

                        if ($enroll_master != '') {
                            $get_student = Student::where(['register_no' => $insert['register_no'], 'enroll_master_id' => $enroll_master->id])->select('name', 'register_no', 'user_name_id')->first();
                            if ($get_student != '') {
                                if ($get_subject != '') {
                                    $check_registration = SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->get();

                                    if (count($check_registration) <= 0) {
                                        $registration = new SubjectRegistration;
                                        $registration->student_name = $get_student->name;
                                        $registration->register_no = $get_student->register_no;
                                        $registration->regulation = $regulation;
                                        $registration->user_name_id = $get_student->user_name_id;
                                        $registration->enroll_master = $enroll_master->id;
                                        $registration->category = $insert['category'];
                                        $registration->subject_id = $get_subject->id;
                                        $registration->status = 2;
                                        $registration->save();

                                        $userAlert = new UserAlert;
                                        $userAlert->alert_text = 'Your Subject Registration Done!';
                                        $userAlert->alert_link = url('admin/subject-registration/student');
                                        $userAlert->save();
                                        $userAlert->users()->sync($get_student->user_name_id);

                                        $balance_row--;
                                    }
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Class Couldn\'t Found For ' . $insert['student_name'] . '.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\BulkOD") {
                $balance_row = $rows;

                $students = [];
                foreach ($for_insert[0] as $insert) {
                    if ($insert['register_no'] != '') {

                        $get_student = Student::where(['register_no' => $insert['register_no']])->first();
                        if ($get_student != '') {
                            $user_name_id = $get_student->user_name_id;
                            $enroll_master = $get_student->enroll_master_id;

                            $get_enroll = CourseEnrollMaster::where(['id' => $enroll_master])->first();
                            if ($get_enroll != '') {
                                $explode = explode('/', $get_enroll->enroll_master_number);
                                $get_course = $explode[1];
                                $get_semester = $explode[3];
                                $get_section = $explode[4];
                                // dd($explode);
                                $get_dept = ToolsCourse::where(['name' => $get_course])->first();
                                if ($get_dept != '') {
                                    $dept = ToolsDepartment::where(['id' => $get_dept->department_id])->first();
                                    if ($dept != '') {
                                        $get_Dept = $dept->name;
                                    } else {
                                        $get_Dept = '';
                                    }
                                } else {
                                    $get_Dept = '';
                                }

                                array_push($students, ['name' => $get_student->name, 'user_name_id' => $get_student->user_name_id, 'register_no' => $get_student->register_no, 'dept' => $get_Dept, 'course' => $get_course, 'semester' => $get_semester, 'section' => $get_section]);
                            }
                        }

                        $balance_row--;
                    }
                }
                $inserted_rows = $rows - $balance_row;

                // $data = ['students' => json_encode($students), 'row_count' => $inserted_rows];
                return response()->json(['students' => $students, 'rows' => $inserted_rows]);
            } elseif ($model == "App\Models\NonTeachingStaff") {

                $import_status = null;
                $balance_row = $rows;
                $inserted_rows = $rows - $balance_row;

                foreach ($for_insert[0] as $insert) {
                    if ($insert['name'] != '' && $insert['StaffCode'] != '') {
                        $check_user = User::where(['employID' => $insert['StaffCode']])->count();

                        if ($check_user == 0) {

                            $balance_row--;

                            $user = new User;
                            $user->name = ($insert['name'] != '' ? $insert['name'] : '');
                            $user->email = $insert['email'] ?? null;
                            $user->employID = $insert['StaffCode'];
                            $user->password = bcrypt($insert['phone']);
                            $user->save();

                            $role_id = Role::where(['title' => $insert['Designation']])->value('id');
                            $user->roles()->sync($request->input('roles', $role_id));
                            $role_type = TeachingType::where(['name' => $insert['role_type']])->value('id');

                            $yearMonth = substr($insert['DOJ'], 0, 7);
                            $explode = explode('-', $insert['DOJ']);

                            // dd($explode);
                            $year = (int) $explode[0];
                            $month = (int) $explode[1];
                            $day = (int) $explode[2];

                            $casual_leave = 0;

                            if ($yearMonth == date('Y-m') && $day == 1) {

                                $casual_leave = 1;
                            } elseif (($year == (int) date('Y') || $year == (int) date('Y') - 1) && (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) && $day >= 26) {

                                $casual_leave = 1;
                            }

                            $personal_permission = 0;
                            if ($yearMonth == date('Y-m') && ($day >= 1 && $day <= 25)) {
                                $personal_permission = 1;
                            } elseif (
                                ($year == (int) date('Y') || $year == (int) date('Y') - 1) &&
                                (($year == (int) date('Y') - 1 && $month == (int) date('m', strtotime('last month'))) || ($year == (int) date('Y') && $month < (int) date('m'))) &&
                                $day >= 26
                            ) {
                                $personal_permission = 1;
                            }


                            $staffCreate = new NonTeachingStaff;
                            $staffCreate->name = $insert['name'] ?? null;
                            $staffCreate->last_name = $insert['last_name'] ?? null;
                            $staffCreate->StaffCode = $insert['StaffCode'] ?? null;
                            $staffCreate->Designation = $insert['Designation'] ?? null;
                            $staffCreate->phone = $insert['phone'] ?? null;
                            $staffCreate->casual_leave = $casual_leave;
                            $staffCreate->personal_permission = $personal_permission;
                            $staffCreate->role_type = $role_type;
                            $staffCreate->email = $insert['email'] ?? null;
                            $staffCreate->user_name_id = $user->id;
                            $staffCreate->save();

                            $formats = [
                                'd-m-y',
                                'd-m-Y',
                                'd/m/y',
                                'd/m/Y',
                            ];
                            $formattedDate = null;
                            foreach ($formats as $i => $format) {
                                try {
                                    $the_date = Carbon::createFromFormat($format, $insert['DOJ']);

                                    $dateOnly = $the_date->format('Y-m-d');
                                    if ($dateOnly != '') {
                                        $formattedDate = $dateOnly;
                                        break;
                                    }
                                } catch (Exception $e) {

                                }
                            }

                            $personalDetails = new PersonalDetail();
                            $personalDetails->name = $insert['name'] ?? null;
                            $personalDetails->last_name = $insert['last_name'] ?? null;
                            $personalDetails->email = $insert['email'] ?? null;
                            $personalDetails->mobile_number = $insert['phone'] ?? null;
                            $personalDetails->StaffCode = $insert['StaffCode'] ?? null;
                            $personalDetails->DOJ = $formattedDate;
                            $personalDetails->user_name_id = $user->id;
                            $personalDetails->save();

                            $experience_details = new ExperienceDetail();
                            $experience_details->user_name_id = $user->id;
                            $experience_details->doj = $insert['DOJ'];
                            $experience_details->save();
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            $import_status = 'Error';
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Staff Code : ' . $insert['StaffCode'] . ' Already Registered For ' . $check_user->name);
                        }
                    }

                }
                $inserted_rows = $rows - $balance_row;
                if ($import_status == null) {
                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                }
            } elseif ($model == 'App\Models\ExamRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['register_no'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['credits'] != '' && $insert['subject_type'] != '' && $insert['subject_sem'] != '' && $insert['exam_type'] != '' && $insert['exam_fee'] != '' && $insert['exam_month'] != '' && $insert['exam_year'] != '') {

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $regulation = null;
                        }
                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->select('id')->first();
                        $get_ay = AcademicYear::where(['name' => $insert['academic_year']])->select('id')->first();
                        $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();
                        $course = null;
                        $ay = null;
                        $batch = null;
                        if ($get_ay != '') {
                            $ay = $get_ay->id;
                        }

                        if ($get_course != '') {
                            $course = $get_course->id;
                        }
                        if ($get_batch != '') {
                            $batch = $get_batch->id;
                        }

                        $get_student = Student::where(['register_no' => $insert['register_no']])->select('user_name_id')->first();

                        if ($get_student != '' && $get_student != null) {
                            if ($get_subject != '' && $get_subject != null) {
                                // $check_subRegistration = SubjectRegistration::where(['user_name_id' => $get_student->user_name_id,'register_no' => $insert['register_no'], 'subject_id' => $get_subject->id,'regulation' => $regulation])->select('id')->get();
                                // $check_subRegistration = SubjectRegistration::where(['user_name_id' => $get_student->user_name_id,'register_no' => $insert['register_no'], 'subject_id' => $get_subject->id])->select('id')->get();
                                // if (count($check_subRegistration) > 0) {

                                $check_examRegistration = ExamRegistration::where(['subject_id' => $get_subject->id, 'user_name_id' => $get_student->user_name_id])->select('id')->get();
                                // if ($i == 9) {
                                //     dd($get_subject, $get_student, $check_examRegistration);
                                // }

                                if (count($check_examRegistration) <= 0) {

                                    $registration = new ExamRegistration;
                                    $registration->regulation = $regulation;
                                    $registration->academic_year = $ay;
                                    $registration->batch = $batch;
                                    $registration->course = $course;
                                    $registration->semester = $insert['semester'];
                                    $registration->user_name_id = $get_student->user_name_id;
                                    $registration->subject_id = $get_subject->id;
                                    $registration->subject_name = $insert['subject_name'];
                                    $registration->subject_type = $insert['subject_type'];
                                    $registration->subject_sem = $insert['subject_sem'];
                                    $registration->credits = $insert['credits'];
                                    $registration->exam_type = $insert['exam_type'];
                                    $registration->exam_fee = $insert['exam_fee'];
                                    $registration->exam_month = $insert['exam_month'];
                                    $registration->exam_year = $insert['exam_year'];
                                    $registration->uploaded_date = Carbon::now()->format('Y-m-d');
                                    $registration->save();

                                    $balance_row--;
                                } else {

                                    $update = ExamRegistration::where(['id' => $check_examRegistration[0]->id])->update([
                                        'regulation' => $regulation,
                                        'academic_year' => $ay,
                                        'batch' => $batch,
                                        'course' => $course,
                                        'semester' => $insert['semester'],
                                        'subject_id' => $get_subject->id,
                                        'subject_name' => $insert['subject_name'],
                                        'subject_type' => $insert['subject_type'],
                                        'subject_sem' => $insert['subject_sem'],
                                        'credits' => $insert['credits'],
                                        'exam_type' => $insert['exam_type'],
                                        'exam_fee' => $insert['exam_fee'],
                                        'exam_month' => $insert['exam_month'],
                                        'exam_year' => $insert['exam_year'],
                                    ]);

                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == 'App\Models\ExamResultPublish') {

                $balance_row = $rows;

                $course = null;
                $regulation = null;
                $ay = null;
                $batch = null;
                $formattedDate = null;
                $sub_1 = null;
                $sub_2 = null;
                $sub_3 = null;
                $sub_4 = null;
                $sub_5 = null;
                $sub_6 = null;
                $sub_7 = null;
                $sub_8 = null;
                $sub_9 = null;
                $sub_10 = null;
                $semester = null;
                $exam_month = null;
                $exam_year = null;
                $result_type = null;
                $gradeMaster = [];

                foreach ($for_insert[0] as $i => $insert) {

                    if ($i == 0) {
                        if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['exam_month'] != '' && $insert['exam_year'] != '' && $insert['result_type'] != '' && $insert['publish_date'] != '') {
                            $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                            if ($get_regulation != '') {
                                $regulation = $get_regulation->id;
                                $getGrades = GradeMaster::where(['regulation_id' => $regulation])->pluck('id', 'grade_letter');
                                $gradeMaster = $getGrades->toArray();
                            } else {
                                $regulation = null;
                            }
                            $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                            $get_ay = AcademicYear::where(['name' => $insert['academic_year']])->select('id')->first();
                            $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();

                            if ($get_ay != '') {
                                $ay = $get_ay->id;
                            }

                            if ($get_course != '') {
                                $course = $get_course->id;
                            }
                            if ($get_batch != '') {
                                $batch = $get_batch->id;
                            }

                            $given_date = $insert['publish_date'];

                            $formats = [
                                'd-m-y',
                                'd-m-Y',
                                'd/m/y',
                                'd/m/Y',
                            ];

                            foreach ($formats as $i => $format) {
                                try {
                                    $the_date = Carbon::createFromFormat($format, $given_date);

                                    $dateOnly = $the_date->format('Y-m-d');
                                    if ($dateOnly != '') {
                                        $formattedDate = $dateOnly;
                                        break;
                                    }
                                } catch (Exception $e) {
                                }
                            }
                            $semester = $insert['semester'];
                            $exam_month = $insert['exam_month'];
                            $exam_year = $insert['exam_year'];
                            $result_type = $insert['result_type'];
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                        }
                    } elseif ($i == 2) {
                        $subject_code_1 = $insert['academic_year'];
                        $subject_code_2 = $insert['course'];
                        $subject_code_3 = $insert['semester'];
                        $subject_code_4 = $insert['regulation'];
                        $subject_code_5 = $insert['exam_month'];
                        $subject_code_6 = $insert['exam_year'];
                        $subject_code_7 = $insert['result_type'];
                        $subject_code_8 = $insert['publish_date'];
                        $subject_code_9 = $insert['subjectcode_9'];
                        $subject_code_10 = $insert['subjectcode_10'];

                        if ($subject_code_1 != '') {
                            $get_subject_1 = Subject::where(['subject_code' => $subject_code_1, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_1 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_1 . ') Couldn\'t Found For.');
                            } else {
                                $sub_1 = $get_subject_1->id;
                            }
                        }

                        if ($subject_code_2 != '') {
                            $get_subject_2 = Subject::where(['subject_code' => $subject_code_2, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_2 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_2 . ') Couldn\'t Found.');
                            } else {
                                $sub_2 = $get_subject_2->id;
                            }
                        }

                        if ($subject_code_3 != '') {
                            $get_subject_3 = Subject::where(['subject_code' => $subject_code_3, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_3 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_3 . ') Couldn\'t Found.');
                            } else {
                                $sub_3 = $get_subject_3->id;
                            }
                        }

                        if ($subject_code_4 != '') {
                            $get_subject_4 = Subject::where(['subject_code' => $subject_code_4, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_4 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_4 . ') Couldn\'t Found.');
                            } else {
                                $sub_4 = $get_subject_4->id;
                            }
                        }

                        if ($subject_code_5 != '') {
                            $get_subject_5 = Subject::where(['subject_code' => $subject_code_5, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_5 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_5 . ') Couldn\'t Found.');
                            } else {
                                $sub_5 = $get_subject_5->id;
                            }
                        }

                        if ($subject_code_6 != '') {
                            $get_subject_6 = Subject::where(['subject_code' => $subject_code_6, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_6 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_6 . ') Couldn\'t Found.');
                            } else {
                                $sub_6 = $get_subject_6->id;
                            }
                        }

                        if ($subject_code_7 != '') {
                            $get_subject_7 = Subject::where(['subject_code' => $subject_code_7, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_7 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_7 . ') Couldn\'t Found.');
                            } else {
                                $sub_7 = $get_subject_7->id;
                            }
                        }

                        if ($subject_code_8 != '') {
                            $get_subject_8 = Subject::where(['subject_code' => $subject_code_8, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_8 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_8 . ') Couldn\'t Found.');
                            } else {
                                $sub_8 = $get_subject_8->id;
                            }
                        }

                        if ($subject_code_9 != '') {
                            $get_subject_9 = Subject::where(['subject_code' => $subject_code_9, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_9 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_9 . ') Couldn\'t Found.');
                            } else {
                                $sub_9 = $get_subject_9->id;
                            }
                        }

                        if ($subject_code_10 != '') {
                            $get_subject_10 = Subject::where(['subject_code' => $subject_code_10, 'regulation_id' => $regulation])->select('id')->first();
                            if ($get_subject_10 == '') {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Code (' . $subject_code_10 . ') Couldn\'t Found.');
                            } else {
                                $sub_10 = $get_subject_10->id;
                            }
                        }
                    } elseif ($i > 2) {

                        if ($course != null && $regulation != null && $batch != null && $ay != null && $formattedDate != null && $semester != null && $exam_month != null && $exam_year != null && $result_type != null) {
                            $get_student = Student::where(['register_no' => $insert['batch']])->select('user_name_id')->first();
                            $gradeStatus = true;
                            if (count($gradeMaster) > 0) {
                                if ($insert['academic_year'] != '') {
                                    if (array_key_exists($insert['academic_year'], $gradeMaster)) {
                                        $grade_1 = $gradeMaster[$insert['academic_year']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_1 = null;
                                }
                                if ($insert['course'] != '') {
                                    if (array_key_exists($insert['course'], $gradeMaster)) {
                                        $grade_2 = $gradeMaster[$insert['course']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_2 = null;
                                }

                                if ($insert['semester'] != '') {
                                    if (array_key_exists($insert['semester'], $gradeMaster)) {
                                        $grade_3 = $gradeMaster[$insert['semester']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_3 = null;
                                }

                                if ($insert['regulation'] != '') {
                                    if (array_key_exists($insert['regulation'], $gradeMaster)) {
                                        $grade_4 = $gradeMaster[$insert['regulation']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_4 = null;
                                }

                                if ($insert['exam_month'] != '') {
                                    if (array_key_exists($insert['exam_month'], $gradeMaster)) {
                                        $grade_5 = $gradeMaster[$insert['exam_month']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_5 = null;
                                }

                                if ($insert['exam_year'] != '') {
                                    if (array_key_exists($insert['exam_year'], $gradeMaster)) {
                                        $grade_6 = $gradeMaster[$insert['exam_year']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_6 = null;
                                }

                                if ($insert['result_type'] != '') {
                                    if (array_key_exists($insert['result_type'], $gradeMaster)) {
                                        $grade_7 = $gradeMaster[$insert['result_type']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_7 = null;
                                }

                                if ($insert['publish_date'] != '') {
                                    if (array_key_exists($insert['publish_date'], $gradeMaster)) {
                                        $grade_8 = $gradeMaster[$insert['publish_date']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_8 = null;
                                }

                                if ($insert['subjectcode_9'] != '') {
                                    if (array_key_exists($insert['subjectcode_9'], $gradeMaster)) {
                                        $grade_9 = $gradeMaster[$insert['subjectcode_9']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_9 = null;
                                }

                                if ($insert['subjectcode_10'] != '') {
                                    if (array_key_exists($insert['subjectcode_10'], $gradeMaster)) {
                                        $grade_10 = $gradeMaster[$insert['subjectcode_10']];
                                    } else {
                                        $gradeStatus = false;
                                    }
                                } else {
                                    $grade_10 = null;
                                }

                                if ($gradeStatus == false) {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                    return redirect($request->input('redirect'))->with('error', 'Grade Not Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Grade Master Not Found.');
                            }

                            if ($get_student != '' && $get_student != null) {

                                $check_resultPublish = ExamResultPublish::where(['user_name_id' => $get_student->user_name_id, 'result_type' => $result_type, 'academic_year' => $ay, 'regulation' => $regulation, 'batch' => $batch, 'course' => $course, 'semester' => $semester, 'exam_month' => $exam_month, 'exam_year' => $exam_year])->select('id')->get();

                                if (count($check_resultPublish) <= 0) {

                                    $resultPublish = new ExamResultPublish;
                                    $resultPublish->regulation = $regulation;
                                    $resultPublish->academic_year = $ay;
                                    $resultPublish->batch = $batch;
                                    $resultPublish->course = $course;
                                    $resultPublish->semester = $semester;
                                    $resultPublish->register_no = $insert['batch'];
                                    $resultPublish->user_name_id = $get_student->user_name_id;
                                    $resultPublish->subject_1 = $sub_1;
                                    $resultPublish->grade_1 = $grade_1;
                                    $resultPublish->subject_2 = $sub_2;
                                    $resultPublish->grade_2 = $grade_2;
                                    $resultPublish->subject_3 = $sub_3;
                                    $resultPublish->grade_3 = $grade_3;
                                    $resultPublish->subject_4 = $sub_4;
                                    $resultPublish->grade_4 = $grade_4;
                                    $resultPublish->subject_5 = $sub_5;
                                    $resultPublish->grade_5 = $grade_5;
                                    $resultPublish->subject_6 = $sub_6;
                                    $resultPublish->grade_6 = $grade_6;
                                    $resultPublish->subject_7 = $sub_7;
                                    $resultPublish->grade_7 = $grade_7;
                                    $resultPublish->subject_8 = $sub_8;
                                    $resultPublish->grade_8 = $grade_8;
                                    $resultPublish->subject_9 = $sub_9;
                                    $resultPublish->grade_9 = $grade_9;
                                    $resultPublish->subject_10 = $sub_10;
                                    $resultPublish->grade_10 = $grade_10;
                                    $resultPublish->result_type = $result_type;
                                    $resultPublish->exam_month = $exam_month;
                                    $resultPublish->exam_year = $exam_year;
                                    $resultPublish->publish_date = $formattedDate;
                                    $resultPublish->uploaded_date = Carbon::now()->format('Y-m-d');
                                    $resultPublish->uploaded_by = auth()->user()->id;

                                    $resultPublish->save();

                                    $balance_row--;
                                } else {

                                    $update = ExamResultPublish::where(['id' => $check_resultPublish[0]->id])->update([
                                        'subject_1' => $sub_1,
                                        'grade_1' => $grade_1,
                                        'subject_2' => $sub_2,
                                        'grade_2' => $grade_2,
                                        'subject_3' => $sub_3,
                                        'grade_3' => $grade_3,
                                        'subject_4' => $sub_4,
                                        'grade_4' => $grade_4,
                                        'subject_5' => $sub_5,
                                        'grade_5' => $grade_5,
                                        'subject_6' => $sub_6,
                                        'grade_6' => $grade_6,
                                        'subject_7' => $sub_7,
                                        'grade_7' => $grade_7,
                                        'subject_8' => $sub_8,
                                        'grade_8' => $grade_8,
                                        'subject_9' => $sub_9,
                                        'grade_9' => $grade_9,
                                        'subject_10' => $sub_10,
                                        'grade_10' => $grade_10,
                                        'publish_date' => $formattedDate,
                                        'uploaded_date' => Carbon::now()->format('Y-m-d'),
                                        'uploaded_by' => auth()->user()->id,
                                    ]);

                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student (' . $insert['batch'] . ') Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } elseif ($model == "App\Models\ExamattendanceData") {
                $balance_row = $rows;
                $course = null;
                $ay = null;
                $batch = null;
                $semester = null;
                $section = null;
                $exam_id = null;
                $exam_name = null;
                $subject_id = null;
                $examDate = null;

                foreach ($for_insert[0] as $i => $insert) {

                    if ($i == 0) {
                        try {
                            if ($insert['ay'] == '') {
                                throw new Exception("AY Can't Be Empty");
                            } else if ($insert['batch'] == '') {
                                throw new Exception("Batch Can't Be Empty");
                            } else if ($insert['course'] == '') {
                                throw new Exception("Course Can't Be Empty");
                            } else if ($insert['semester'] == '') {
                                throw new Exception("Semester Can't Be Empty");
                            } else if ($insert['section'] == '') {
                                throw new Exception("Section Can't Be Empty");
                            }
                            $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                            $get_ay = AcademicYear::where(['name' => $insert['ay']])->select('id')->first();
                            $get_batch = Batch::where(['name' => $insert['batch']])->select('id')->first();

                            if ($get_ay != '') {
                                $ay = $get_ay->id;
                            }
                            if ($get_course != '') {
                                $course = $get_course->id;
                            }
                            if ($get_batch != '') {
                                $batch = $get_batch->id;
                            }

                            $semester = $insert['semester'];
                            $section = $insert['section'];
                        } catch (Exception $e) {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', $e->getMessage());
                        }
                    } elseif ($i == 1) {
                        if ($insert['ay'] != '' && $insert['semester'] != '' && $insert['section'] != '') {

                            $getSubject = Subject::where(['subject_code' => $insert['section']])->select('id')->first(); //->where('name','LIKE',"%{$insert['semester']}%")

                            if ($getSubject != '') {
                                $subject_id = $getSubject->id;
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Subject Not Found.');
                            }
                            $getTimeTable = ExamTimetableCreation::where(['exam_name' => $insert['ay'], 'course' => $course, 'semester' => $semester, 'accademicYear' => $ay, 'sections' => $section])->select('id', 'subject')->first();
                            if ($getTimeTable != '') {
                                $exam_id = $getTimeTable->id;
                                $exam_name = $insert['ay'];

                                $examDates = unserialize($getTimeTable->subject);
                                foreach ($examDates as $dat) {
                                    if (array_key_exists($subject_id, $dat)) {
                                        $examDate = $dat[$subject_id];
                                    }
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Exam Time Table Not Found.');
                            }

                            $getExamAtt = Examattendance::where(['examename' => $insert['ay'], 'exame_id' => $exam_id, 'course' => $course, 'sem' => $semester, 'acyear' => $ay, 'section' => $section, 'subject' => $subject_id])->select('id', 'mark_entereby')->first();
                            if ($getExamAtt != '') {
                                $theExamId = $getExamAtt->id;
                                if ($getExamAtt->mark_entereby == null) {
                                    $updateExamAtt = Examattendance::where(['id' => $getExamAtt->id])->update([
                                        'mark_date' => Carbon::now(),
                                        'mark_entereby' => auth()->user()->id,
                                    ]);
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Exam Attendance Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Exam Name / Subject Details Missing.');
                        }
                    } elseif ($i > 1) {

                        if ($course != null && $batch != null && $ay != null && $semester != null && $theExamId != null && $subject_id != null && $examDate != null && $insert['co_4'] != null && $insert['co_5'] != null) {

                            $get_student = Student::where(['register_no' => $insert['course']])->select('user_name_id', 'enroll_master_id')->first();

                            if ($get_student != '' && $get_student != null) {

                                $checkData = ExamattendanceData::where(['student_id' => $get_student->user_name_id, 'examename' => $theExamId, 'subject' => $subject_id, 'class_id' => $get_student->enroll_master_id])->select('id', 'attendance')->get();

                                if (count($checkData) <= 0) {
                                    $store = ExamattendanceData::create([
                                        'date' => Carbon::now(),
                                        'enteredby' => 1,
                                        'class_id' => $get_student->enroll_master_id,
                                        'subject' => $subject_id,
                                        'attendance' => 'Present',
                                        'examename' => $theExamId,
                                        'student_id' => $get_student->user_name_id,
                                        'exame_date' => $examDate,
                                        'co_4' => $insert['co_4'],
                                        'co_5' => $insert['co_5'],
                                    ]);
                                    $balance_row--;
                                } else {
                                    $temp_attendance = 'Present';
                                    $tempco4 = $insert['co_4'];
                                    $tempco5 = $insert['co_5'];
                                    if ($checkData[0]->attendance == 'Absent') {
                                        $temp_attendance = 'Absent';
                                        $tempco4 = 999;
                                        $tempco5 = 999;
                                    }

                                    $update = ExamattendanceData::where(['student_id' => $get_student->user_name_id, 'examename' => $theExamId, 'subject' => $subject_id, 'class_id' => $get_student->enroll_master_id])->update([
                                        'attendance' => $temp_attendance,
                                        'co_4' => $tempco4,
                                        'co_5' => $tempco5,
                                    ]);
                                    $balance_row--;
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                                return redirect($request->input('redirect'))->with('error', 'Student (' . $insert['course'] . ') Not Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                            return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                        }
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            } else {

                $balance_row = $rows;

                foreach ($for_insert as $insert_item) {
                    $balance_row--;
                    $model::insert($insert_item);
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
            }
            return redirect($request->input('redirect'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImport(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.processCsvImport';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function removeProcessCsvImport(Request $request)
    {

        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\ExamRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['regulation'] != '' && $insert['academic_year'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['semester'] != '' && $insert['register_no'] != '' && $insert['subject_code'] != '' && $insert['subject_name'] != '' && $insert['credits'] != '' && $insert['subject_type'] != '' && $insert['subject_sem'] != '' && $insert['exam_type'] != '' && $insert['exam_fee'] != '') {

                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Regulation Couldn\'t Found.');
                        }

                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->select('id')->first();

                        $get_student = Student::where(['register_no' => $insert['register_no']])->select('user_name_id')->first();

                        if ($get_student != '' && $get_student != null) {
                            if ($get_subject != '' && $get_subject != null) {

                                $check_examRegistration = ExamRegistration::where(['subject_id' => $get_subject->id, 'user_name_id' => $get_student->user_name_id])->select('id')->get();

                                if (count($check_examRegistration) > 0) {

                                    $update = ExamRegistration::where(['id' => $check_examRegistration[0]->id])->delete();
                                    $balance_row--;
                                } else {

                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                                    return redirect($request->input('redirect'))->with('error', 'Exam Registration Couldn\'t Found.');
                                }
                            } else {
                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                                return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                            }
                        } else {
                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $inserted_rows, 'table' => $table]));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Missing.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Exam Registration']));
                return redirect($request->input('redirect'));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImportOD(Request $request)
    {
        // phpinfo();
        // dd($request);
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.processCsvImport';

        return response()->json(['headers' => $headers, 'filename' => $filename, 'fillables' => $fillables, 'hasHeader' => $hasHeader, 'modelName' => $modelName, 'lines' => $lines, 'redirect' => $redirect, 'routeName' => $routeName]);
    }

    public function parseCsvRemovalExamReg(Request $request)
    {
        // phpinfo();
        // dd($request);
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.removeProcessCsvImport';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }
    public function parseCsvRemovalSubjectReg(Request $request)
    {
        // dd($request);
        // phpinfo();
        // dd($request);
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $destinationPath = storage_path('app/csv_import');

        $file->move($destinationPath, $filename);

        $modelName = $request->input('model', false);

        $fullModelName = "App\\Models\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.removeProcessCsvImportSub';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    public function removeProcessCsvImportSub(Request $request)
    {
        // dd($request);
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/' . $filename);
            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);

            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);
            $model = "App\Models\\" . $modelName;
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {

                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 10000);

            $count = count($for_insert[0]);

            $rows = count($insert);

            $table = Str::plural($modelName);

            File::delete($path);

            if ($model == 'App\Models\SubjectRegistration') {

                $balance_row = $rows;

                foreach ($for_insert[0] as $insert) {

                    if ($insert['student_name'] != '' && $insert['register_no'] != '' && $insert['batch'] != '' && $insert['course'] != '' && $insert['academic_year'] != '' && $insert['semester'] != '' && $insert['section'] != '' && $insert['category'] != '' && $insert['subject_code'] != '' && $insert['regulation'] != '') {
                        $get_regulation = ToolssyllabusYear::where('name', 'like', "%{$insert['regulation']}%")->first();
                        if ($get_regulation != '') {
                            $regulation = $get_regulation->id;
                        } else {
                            $regulation = null;
                        }

                        $get_course = ToolsCourse::where('short_form', 'like', "%{$insert['course']}")->first();
                        $get_subject = Subject::where(['subject_code' => $insert['subject_code'], 'regulation_id' => $regulation])->first();

                        $course = null;

                        if ($get_course != '') {
                            $course = $get_course->name;
                        }
                        $enrollMaster = $insert['batch'] . '/' . $course . '/' . $insert['academic_year'] . '/' . $insert['semester'] . '/' . $insert['section'];

                        $enroll_master = CourseEnrollMaster::where('enroll_master_number', 'LIKE', "%{$enrollMaster}%")->select('id')->first();

                        if ($enroll_master != '') {
                            $get_student = Student::where(['register_no' => $insert['register_no'], 'enroll_master_id' => $enroll_master->id])->select('name', 'register_no', 'user_name_id')->first();

                            if ($get_student != '') {
                                if ($get_subject != '') {
                                    $check_registration = SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->get();

                                    if (count($check_registration) > 0) {

                                        SubjectRegistration::where(['register_no' => $get_student->register_no, 'enroll_master' => $enroll_master->id, 'subject_id' => $get_subject->id])->update([
                                            'deleted_at' => Carbon::now(),
                                        ]);

                                        $userAlert = new UserAlert;
                                        $userAlert->alert_text = 'Your Subject Registration Removed ';
                                        $userAlert->alert_link = url('admin/subject-registration/student');
                                        $userAlert->save();
                                        $userAlert->users()->sync($get_student->user_name_id);

                                        $balance_row--;
                                    }
                                } else {
                                    $inserted_rows = $rows - $balance_row;
                                    session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                                    return redirect($request->input('redirect'))->with('error', 'Subject Couldn\'t Found.');
                                }
                            } else {

                                $inserted_rows = $rows - $balance_row;
                                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                                return redirect($request->input('redirect'))->with('error', 'Student Couldn\'t Found.');
                            }
                        } else {

                            $inserted_rows = $rows - $balance_row;
                            session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                            return redirect($request->input('redirect'))->with('error', 'Class Couldn\'t Found For ' . $insert['student_name'] . '.');
                        }
                    } else {
                        $inserted_rows = $rows - $balance_row;
                        session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
                        return redirect($request->input('redirect'))->with('error', 'Required Details Not Found.');
                    }
                }
                $inserted_rows = $rows - $balance_row;
                session()->flash('message', trans('global.app_removed_rows_from_table', ['rows' => $inserted_rows, 'table' => 'Subject Registration']));
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
