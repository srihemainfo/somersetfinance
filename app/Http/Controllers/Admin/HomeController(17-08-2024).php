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
    Application,
    EnquiryList
};

session()->start();
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingStaff;
use App\Models\User;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Redirect,
    Session,
    DB,
    Auth
    
};

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class HomeController extends SystemCalendarController
{
    public function index()
    {
        
        
     
        
        
        
        
        
        
        $user_id = auth()->user()->id ;
        $user = \App\Models\User::find($user_id);
        $assignedRole = $user ? $user->roles->first() : null;
        $roleTitle = $assignedRole ? $assignedRole->id : 0;
        
        
        $query =  EnquiryList::where('status', 'Enquiry') ;
        
         if ($roleTitle != 1) {
            $query->where('created_by', $user_id); // Assuming assigned_client_id matches role id
        }
        
      $enquiry_count =  $query->count();

        
        
        
        $query = Application::query()
                ->selectRaw('
                    COUNT(CASE WHEN status = "Enquiry" THEN 1 END) as Enquiry,
                    COUNT(CASE WHEN status = "Underwriting" THEN 1 END) as Underwriting,
                    COUNT(CASE WHEN status = "Processing" THEN 1 END) as Processing,
                    COUNT(CASE WHEN status = "Assigned" THEN 1 END) as Assigned,
                    COUNT(CASE WHEN status = "Submitted" THEN 1 END) as Completions,
                    COUNT(CASE WHEN status = "Completed" THEN 1 END) as Completed
                ') ;
        
        if ($roleTitle != 1) {
            $query->where('assigned_client_id', $user_id); // Assuming assigned_client_id matches role id
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
        $user = User::find($user_id);
        
        //open case 
        
        $openCases = Application::select('id','customer_id','assigned_client_id') ;
        if($roleTitle != 1){
            $openCases->where('assigned_client_id', $user_id ) ;
            $openCases->where('status', 'Processing') ;
        }else{
            $openCases->where('status', 'Underwritting') ;
        }
        
        $openCases ->latest('created_at')->take(5)->get();
        
        // processing Case
        if($roleTitle == 1){
            
        $processingCases = Application::where('status', 'Processing')->select('id','customer_id','assigned_client_id')->latest('created_at')->take(5)->get();
            
        }else{
            $processingCases = [];
        }
        
        
        
        return view('home', compact('statusCounts', 'user','enquiry_count'));
    }
    
    public function dataTable(Request $request){
        
           if ($request->ajax()) {
               
                $user_id = auth()->user()->id ;
                $user = \App\Models\User::find($user_id);
                $assignedRole = $user ? $user->roles->first() : null;
                $roleTitle = $assignedRole ? $assignedRole->id : 0;
        if (auth()->user()->roles->isNotEmpty()) {
            $roleTitle = auth()->user()->roles[0]->type_id ?? null;
        }
            
             $openCases = new Application();
            if($roleTitle != 1){
                $openCases->where('assigned_client_id', $user_id ) ;
                $openCases->where('status', 'Processing') ;
            }else{
                $openCases->where('status', 'Underwritting') ;
            }
            
             $openCases->latest('created_at')->take(5) ;
        
            // $openCases ->take(5);
            
    
            // $loan_type = $request->has('Loan_type_filter') ? $request->get('Loan_type_filter') : '' ;
            // $customer_filter = $request->has('customer_filter') ? $request->get('customer_filter') : '' ;
            // $status_filter = $request->has('status_filter') ? $request->get('status_filter') : '' ;
            // $partner_filter = $request->has('partner_filter') ? $request->get('partner_filter') : '' ;
            // $case_date = $request->has('case_date') ? $request->get('case_date') : '' ;
            // $loan_partner = $request->has('loan_partner') ? $request->get('loan_partner') : '' ;
          
            //   if($case_date){
                  
            //         $split_date = explode( ' - ',$case_date);
            //         $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[0])));
            //         $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[1])));
                    
            //   }
            $query = $openCases ;
            
          
            

            // $query = Application::query()->select('*');
            
            // dd($roleTitle );
            
            // if ($roleTitle != 1) {
            //     $query->where('assigned_client_id', $user_id)->where('status','Processing'); // Assuming assigned_client_id matches role id
            // }else{
            //     //  if($customer_filter){
            //     //  $query->where('customer_id',$customer_filter);
            //     // }
            // }
            
            //  if($loan_type){
            //      $query->where('loan_type_id', $loan_type ) ;
            // }
            
            // if($customer_filter){
            //      $query->where('customer_id',$customer_filter);
            // }
            
            //   if($status_filter){
            //      $query->where('status', $status_filter ) ;
            // }
            
            // if($case_date){
            //     // dd($start_date, $end_date);
            //     $query->whereBetween('created_date', [$start_date, $end_date]);
            //     // dd();
                
            // }
            
            
            
             
            
            

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('customer_id', function ($row) {
                if($row->customer_id){
                    $customer_name = $row->customerdetail->name ?? '' ;
                    $customer_email = $row->customerdetail->email ?? '' ;
                    if($customer_name && $customer_email){
                        return  $customer_name . '(' . $customer_email. ')' ;
                    }else if($customer_name){
                        return  $customer_name ;

                    }
                }
                    return  '';
            });

            $table->editColumn('loan_type_id', function ($row) {
                    return  $row->loanType->title ?? '';
            });


            $table->editColumn('address1', function ($row) {
                return  $row->customerdetail->address1 ?? '';
            });
            
             $table->editColumn('created_date', function ($row) {
                return  $row->created_date ? date('d-m-Y', strtotime($row->created_date)) :'';
            });
            
            
             $table->editColumn('ref_no', function ($row) use($roleTitle) {
                $ref_no = $row->ref_no ?? '';
                $data = '';
                    if ($roleTitle == 1) {
                        if ($ref_no) { // Assuming you want to check if assigned_client_id exists
                            $data = '<a href="' . route("admin.application-stage.edit", $row->id) . '">' .$ref_no . '</a>';
                        }
                    }else{
                        $data = $ref_no  ;
                    }
            
                return $data;
            });
             
                
                $table->editColumn('assigned_client_id', function ($row) use($roleTitle) {
    $data = '';
    $client_id = $row->assigned_client_id;
    $status = $row->status ?? '';
    $stages = ["Submitted ", "Completed"];

    if ($client_id != '') {
        $user_name = $row->user ? $row->user->name : '';
        $user_id = $row->user ? $row->user->id : '';
        

        if ($roleTitle == 1) {
            $data = '<div class="input-group">'
                        . $user_name .'&nbsp;' ;
                        
                        if(!in_array($status,$stages )){
                            
                         $data .= '  <div class="input-group-append">
                                <button class="newDeleteBtn removeClient" data-case-id="' . $row->id . '" data-client-id="' . $user_id . '" title="Remove">
                                    <i class="fa-fw nav-icon fa fa-user-times"></i>
                                </button>
                            </div>' ;
                        }
                        
                   $data .= ' </div>';
        } else {
            $data = $user_name;
        }
    } else {
        if ($roleTitle == 1) {
            $data = '<div class="input-group">
                        Assign &nbsp;' ;
                        
                        if(!in_array($status,$stages )){
                            
                         $data .= '  <div class="input-group-append">
                            <button class="newViewBtn assignClient" data-case-id="' . $row->id . '" data-ref-id="' . $row->ref_no . '" title="Assign">
                                <i class="fa-fw nav-icon fa fa-user-plus"></i>
                            </button>
                        </div>' ;
                        }
                  $data .= '</div>';
        }
    }

    return $data;
});




            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewCase';
                $viewFunct1= false ;
                $viewFunct2 = false;

                if($row->status == 'Underwriting' && !$row->assigned_client_id){
                    $viewFunct1 = 'viewCase1';
                }else if($row->assigned_client_id && $row->status != 'Completed' ){
                    $viewFunct2 = 'cancelAssigned_case';
                }
                
                 $data = Application::find($row->id);
                 
                $documentImages = [];
                $documentImages2 = [];
                $documentImages3 = [];
                $documentImages4 = [];
                $documentImages5 = [];
                $rejectedCount1 = 0 ;
                $rejectedCount2 = 0 ;
                $rejectedCount3 = 0 ;
                $rejectedCount4 = 0;
                $rejectedCount5 = 0;
                $rejectedCount6 = 0;
               
                $isdocument = false;
                
                if ($data || $data != null) {
                    $documentImages = ApplicationImage::where('application_id', $row->id)
                        ->whereIn('document_id', $row->applicationLoanDocument2->pluck('id'))
                        ->get();
                        
                    $documentImages2 = ApplicationImage::where('application_id', $row->id)
                        ->whereIn('document_id', $row->applicantDocument1->pluck('id'))
                        ->get();
                        
                    $documentImages3 = ApplicationFormImage::where('application_id', $row->id)
                        ->whereIn('form_id', $row->applicantformUpload2->pluck('id'))
                        ->get();
                        
                    $documentImages4 = ApplicationFormImage::where('application_id', $row->id)
                        ->whereIn('form_id', $row->applicationLoanFormUpload2->pluck('id'))
                        ->get();
                        
                    $documentImages5 = ApplicationAdditional::where('application_id', $row->id)
                        ->whereIn('additional_id', $row->additionalDocument2->pluck('id'))
                    ->get();
                }
                
                $rejectedCount1 = $rejectedCount2 = $rejectedCount3 = $rejectedCount4 = $rejectedCount5 = 0;
                $rejectedDocumentTitles = [];
                
                if ($documentImages->isNotEmpty()) {
                    foreach ($row->applicationLoanDocument2 as $document) {
                        $rejectedDocuments = $documentImages->where('document_id', $document->id)
                            ->where('status', 'Rejected');
                        $rejectedCount1 += $rejectedDocuments->count();
                        
                        if ($rejectedDocuments->isNotEmpty()) {
                            $rejectedDocumentTitles[] = $document->title;
                        }
                    }
                }
                
                if ($documentImages2->isNotEmpty()) {
                    foreach ($row->applicantDocument1 as $document) {
                        $rejectedDocuments = $documentImages2->where('document_id', $document->id)
                            ->where('status', 'Rejected');
                        $rejectedCount2 += $rejectedDocuments->count();
                        
                        if ($rejectedDocuments->isNotEmpty()) {
                            $rejectedDocumentTitles[] = $document->title;
                        }
                    }
                }

                if ($documentImages3->isNotEmpty()) {
                    foreach ($row->applicantformUpload2 as $document) {
                        $rejectedDocuments = $documentImages3->where('form_id', $document->id)
                            ->where('status', 'Rejected');
                        $rejectedCount3 += $rejectedDocuments->count();
                        
                        if ($rejectedDocuments->isNotEmpty()) {
                            $rejectedDocumentTitles[] = $document->title;
                        }
                    }
                }

                if ($documentImages4->isNotEmpty()) {
                    foreach ($row->applicationLoanFormUpload2 as $document) {
                        $rejectedDocuments = $documentImages4->where('form_id', $document->id)
                            ->where('status', 'Rejected');
                        $rejectedCount4 += $rejectedDocuments->count();
                        
                        if ($rejectedDocuments->isNotEmpty()) {
                            $rejectedDocumentTitles[] = $document->title;
                        }
                    }
                }

                if ($documentImages5->isNotEmpty()) {
                    foreach ($row->additionalDocument2 as $document) {
                        $rejectedDocuments = $documentImages5->where('additional_id', $document->id)
                            ->where('status', 'Rejected');
                        $rejectedCount5 += $rejectedDocuments->count();
                        
                        if ($rejectedDocuments->isNotEmpty()) {
                            $rejectedDocumentTitles[] = $document->title;
                        }
                    }
                }
                
               
                if(isset($row->applicationAdditionFormDocuments) && count($row->applicationAdditionFormDocuments) > 0){
                    
                    foreach ($row->applicationAdditionFormDocuments as $document) {
                        
                        if($document-> status == "Rejected"){
                            $rejectedCount6++  ;
                            $rejectedDocumentTitles[] = $document->applicationAdditionFormUploads->title ?? '';
                        }
            
                    }

                   
                }

                $count = $rejectedCount1 + $rejectedCount2 + $rejectedCount3 + $rejectedCount4 + $rejectedCount5+ $rejectedCount6;
              
                
                
                // Implode the rejected document titles into a single string
                $rejectedDocumentTitlesString = implode(', ', $rejectedDocumentTitles);


                if($count > 0){
                    
                    $viewError = true;
                }else{
                     $viewError = false;
                }
                
                
                
                
                // dd($count);

                
        
                
                //  $documentImagesForDocument = $documentImages->where('document_id', $document->id);

                $editFunct = 'editCase';
                $uploadFunct = 'uploaddocument';
                $deleteFunct = 'deleteCase';
                $viewGate = 'case_show1'; // need to change gate permission
                $editGate = 'case_edit';
                $uploadGate = 'case_upload';
                $deleteGate = 'case_delete1';
                $caseAssignGate = 'case_assign';
                // $crudRoutePart = 'subjects';

                return view('partials.ajaxTableActions', compact(

                    'viewGate',
                'editGate',
                'deleteGate',
                'uploadFunct',
                'viewFunct1',
                // 'crudRoutePart',
                'viewFunct',
                'editFunct',
                'uploadGate',
                'deleteFunct',
                'row',
                'viewFunct2',
                'caseAssignGate',
                'count',
                'viewError',
                'rejectedDocumentTitlesString'
                ));
            });


            $table->rawColumns(['actions', 'placeholder', 'assigned_client_id','ref_no']);
            return $table->make(true);

        }
        
    }
        

    

}
