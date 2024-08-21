<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Redirect,
    Session,
    DB,
    Auth
    
};
use App\Common;
use Illuminate\Support\Str;
use App\Models\{
    Application,
    LoanType,
    User,
    Role,
    ApplicationImage,
    ApplicationFormImage,
    ApplicationAdditional,
    CustomDetail
    
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Gate;



class ApplicationStageController extends Controller
{

    public function index(Request $request)
    {
            
            abort_if(Gate::denies('case_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        if ($request->ajax()) {

            $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $loan_type = $request->has('Loan_type_filter') ? $request->get('Loan_type_filter') : '' ;
            $customer_filter = $request->has('customer_filter') ? $request->get('customer_filter') : '' ;
            $status_filter = $request->has('status_filter') ? $request->get('status_filter') : '' ;
            $partner_filter = $request->has('partner_filter') ? $request->get('partner_filter') : '' ;
            $case_date = $request->has('case_date') ? $request->get('case_date') : '' ;
            $loan_partner = $request->has('loan_partner') ? $request->get('loan_partner') : '' ;
          
              if($case_date){
                  
                    $split_date = explode( ' - ',$case_date);
                    $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[0])));
                    $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[1])));
                    
              }
            
            

            $query = Application::query()->select('*');
            
            // dd($roleTitle );
            
            if ($roleTitle != 1) {
                $query->where('assigned_client_id', $userId)->where('status','Processing'); // Assuming assigned_client_id matches role id
            }else{
                 if($customer_filter){
                 $query->where('customer_id',$customer_filter);
                }
            }
            
             if($loan_type){
                 $query->where('loan_type_id', $loan_type ) ;
            }
            
            if($customer_filter){
                 $query->where('customer_id',$customer_filter);
            }
            
              if($status_filter){
                 $query->where('status', $status_filter ) ;
            }
            
            if($case_date){
                // dd($start_date, $end_date);
                $query->whereBetween('created_date', [$start_date, $end_date]);
                // dd();
                
            }
             return $this->DataTable($query,$roleTitle) ;
        }
        
        $isEditable = false;
        
        $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
        $partners = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->where('role_user.role_id', 2)
         ->select('users.name', 'users.email', 'users.id')
        ->get();
        
        $customer_details = CustomDetail::whereNotNull('email')->select('email','id', 'name')->get();

        return view('admin.application_stage.index', compact('isEditable','loan_types','partners','customer_details'));

    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;

          $data =  Application::find($id) ;
          if($data){

            $customer_name =  $data->customerdetail->name  ?? '' ;
            $customer_email = $data->customerdetail->email ?? '' ;
            $customer_phone = $data->customerdetail->phone ?? '' ;
            $customer_address = $data->customerdetail->address ?? '' ;

            if($data->co_applicant1){

                $coApplicants = $data->co_applicant1 ;

                foreach($coApplicants as $coappicant){
                    dd($coApplicants);
                }


            }

          }

          $url = route('admin.document.index',  $id) ;
       
            return response()->json(['status' => true, 'data' => true,'url'=> $url,]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function uploadcheck(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            $data =  Application::find($id) ;
            $condtion_status = false;
            $url = '';
            if($data){

                if(isset($data->applicantDocument1) || isset($data->applicationLoanDocument2) || isset($data->applicantformUpload2) || isset($data->applicationLoanFormUpload2) || isset($data->additionalDocument2)  ){
                    $condtion_status = true;

                }

                $url = route('admin.document.index',  $id) ;
               
          }


           
            return response()->json(['status' => true, 'data' => true,'url'=> $url,]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {
        return route('admin.home');
    }

 

    public function edit(Request $request, $id){
        
        
        abort_if(Gate::denies('case_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
         $id =  intVal($request->id) ? ($id ?? '') : '' ;
         $data = Application::find($id) ;
         if(!$data){
            return redirect()->route('admin.application.index');
         }
         


        $isEditable = true;
        $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');

        return view('admin.application.index', compact('isEditable','loan_types','data')) ;


    }


    public function destroy(Request $request)
    {
           return route('admin.home');
    }

    public function massDestroy()
    {
            return route('admin.home');
    }

    public function clientGets(Request $request){
        
        abort_if(Gate::denies('case_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $role = Role::where('id', 2)->first() ;
        $data = [];

            if($role){

                $users = $role->user() ;
                if($users){
                    $data = $users->select('name','id','email')->get() ;
                }
            }
            return response()->json(['status' => 'true', 'data' => $data ]);
        }

    public function assignClient(Request $request){
            
            abort_if(Gate::denies('case_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            
                $client_id = $request->client_id ;
                $job_id = $request->job_id ;
                $user = user::find(intVal($client_id ));
    
                 if(!$user){
                 return response()->json(['status' => false, 'message' => 'Client Not Found']);
    
                 }
                $data = Application::where(['id'=>$job_id, 'assigned_client_id'=> null ])->update([ 'assigned_client_id' => $client_id, 'status'=> "Processing"]) ;
                if(!$data){
                    return response()->json(['status' => false, 'message' => 'Case not Assigned']);
                    $message = 'Job not Assigned';
                }
    
                return response()->json(['status' => true, 'message' => 'Assigned Successful']);
        }

    public function removeClientGet(Request $request){

            abort_if(Gate::denies('case_assign'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $caseId = $request->caseId ;
            $client_id = $request->clientId ;
            $user = user::find(intVal($client_id ));

             if(!$user){
             return response()->json(['status' => false, 'message' => 'Client Not Found']);

             }
            $data = Application::where(['id'=>$caseId, 'assigned_client_id'=> $client_id ])->update([ 'assigned_client_id' => null , 'status'=> "Underwriting"]) ;
            if(!$data){
                return response()->json(['status' => false, 'message' => 'Assigned Job Not removed']);
            }

            return response()->json(['status' => true, 'message' => 'Assigned job  removed']);


        }
        
        
    public function openJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
           

            $query = Application::query()->select('*');
            
            
            if($roleTitle != 1){
                $query->where('assigned_client_id', $userId ) ;
                $query->where('status', 'Processing') ;
            }else{
                $query->where('status', 'Underwriting') ;
            }
        
            $query ->latest('created_at')->take(5);
            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    
    public function CompletedJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $query = Application::query()->select('*');
                if($roleTitle != 1){
                    $query->where('assigned_client_id', $userId ) ;
                    $query->where('status', 'Submitted') ;
                }else{
                    $query->where('status', 'Completed') ;
                }
        
            $query->latest('created_at')->take(5);
            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    public function CanceledJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $query = Application::query()->select('*');
                if($roleTitle != 1){
              $query->where('assigned_client_id', $userId ) ;
            }
        
        $query->where('status', 'Canceled')->latest('created_at')->take(5)->get();

            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    
    public function openEdgeJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = $request->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
           

            $query = Application::query()->select('*');
            
            
            if($roleTitle != 1){
                $query->where('assigned_client_id', $userId ) ;
                $query->where('status', 'Processing') ;
            }else{
                $query->where('status', 'Underwriting') ;
            }
        
            $query ->latest('created_at')->take(5);
            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    
    public function CompletedEdgeJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = $request->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $query = Application::query()->select('*');
                if($roleTitle != 1){
                    $query->where('assigned_client_id', $userId ) ;
                    $query->where('status', 'Submitted') ;
                }else{
                    $query->where('status', 'Completed') ;
                }
        
            $query->latest('created_at')->take(5);
            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    public function CanceledEdgeJob(Request $request){
        
           if ($request->ajax()) {
               
                $userId = $request->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $query = Application::query()->select('*');
                if($roleTitle != 1){
              $query->where('assigned_client_id', $userId ) ;
            }
        
        $query->where('status', 'Canceled')->latest('created_at')->take(5)->get();

            
            return $this->DataTable($query,$roleTitle ) ;
        }
        
    }
    
    public function DataTable($query,$roleTitle){
        
        
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
    $stages = ["Submitted ", "Completed", "Canceled"];

    if ($client_id != '') {
        $user_name = $row->user ? $row->user->name : '';
        $userId = $row->user ? $row->user->id : '';
        

        if ($roleTitle == 1) {
            $data = '<div class="input-group">'
                        . $user_name .'&nbsp;' ;
                        
                        if(!in_array($status,$stages )){
                            
                         $data .= '  <div class="input-group-append">
                                <button class="newDeleteBtn removeClient" data-case-id="' . $row->id . '" data-client-id="' . $userId . '" title="Remove">
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
