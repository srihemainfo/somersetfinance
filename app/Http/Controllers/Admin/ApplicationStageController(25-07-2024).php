<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Common;
use Illuminate\Support\Str;
use App\Models\{
    SellVehicleDetails,
    VehicleFeatures,
    Brand,
    Models,
    Application,
    LoanType,
    User,
    Role,
    ApplicationImage,
    ApplicationFormImage,
    ApplicationAdditional
    
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;



class ApplicationStageController extends Controller
{



    // protected $allDataController;

    // public function __construct()
    // {
        //     $this->allDataController = new AllDataController();
        // }

        public function index(Request $request)
        {



        if ($request->ajax()) {

            $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;



            $query = Application::query()->select('*');
            
            // dd($roleTitle );
            
            

            if ($roleTitle != 1) {
                $query->where('assigned_client_id', $userId); // Assuming assigned_client_id matches role id
            }

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
            
            $table->editColumn('assigned_client_id', function ($row) {
                return  $row->user->name ?? '';
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

                $count = $rejectedCount1 + $rejectedCount2 + $rejectedCount3 + $rejectedCount4 + $rejectedCount5;
                
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


            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);

        }
        $isEditable = false;
        
        

        return view('admin.application_stage.index', compact('isEditable'));

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
          dd($url);

            // $data = Brand::where(['id' => $request->id])->select('id', 'brand')->first();
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

                // if(isset($data->applicationLoanDocument2)){
                //     $condtion_status = true;
                // }

                // if(isset($data->additionalDocument2)){
                //     $condtion_status = true;
                // }

                // if(isset($data->applicantformUpload2)){
                //     $condtion_status = true;
                // }
                // if(isset($data->applicationLoanFormUpload2)){
                //     $condtion_status = true;
                // }


                // $customer_name =  $data->customerdetail->name  ?? '' ;
                // $customer_email = $data->customerdetail->email ?? '' ;
                // $customer_phone = $data->customerdetail->phone ?? '' ;
                // $customer_address = $data->customerdetail->address ?? '' ;

                // if($data->co_applicant1){

                //     $coApplicants = $data->co_applicant1 ;

                //     foreach($coApplicants as $coappicant){
                //         // dd($coApplicants);
                //     }

                // }

                $url = route('admin.document.index',  $id) ;
                // dd($url);
          }


            // $data = Brand::where(['id' => $request->id])->select('id', 'brand')->first();
            return response()->json(['status' => true, 'data' => true,'url'=> $url,]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {
        if (isset($request->brand) ) {
            if ($request->id == '') {
                $count = Brand::where(['brand' => $request->brand])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Brand Already Exist.']);
                } else {
                    $store = Brand::create([
                        'brand' => $request->brand,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Brand Created Successfully']);
            } else {
                $count = Brand::whereNotIn('id', [$request->id])->where(['brand' => $request->brand])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Brand Already Exist.']);
                } else {
                    $update = Brand::where(['id' => $request->id])->update([
                        'brand' => $request->brand,

                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Brand Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Brand Not Created']);
        }
    }

    // public function edit(Request $request)
    // {
    //     if (isset($request->id)) {
    //         $data = Brand::where(['id' => $request->id])->select('id', 'brand')->first();
    //         return response()->json(['status' => true, 'data' => $data]);
    //     } else {
    //         return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
    //     }
    // }

    public function edit(Request $request){

         $id =  intVal($request->id) ?? '' ;
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
        if (isset($request->id)) {
            $delete = Brand::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Brand Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = Brand::whereIn('id',request('ids'))->delete() ;
        // find(request('ids'));

        // foreach ($academicYears as $academicYear) {
        //     $academicYear->delete();
        // }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function clientGets(Request $request){

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


}
