<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ApplicationImage,
    CoApplicant,
    CustomDetail,
    DocumentType,
    FormUpload,
    LoanType,
    Brand,
    ApplicationAdditional,
    EnquiryList,
    Application,
    AdditionalDocument,
    ApplicationFormImage,
    EnquiryClients,
    User

 };

use Carbon\Carbon;use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session; // use App\Http\Controllers\AllDataController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            
          $loan_type = $request->has('loan_type_filter') ? $request->get('loan_type_filter') : '' ;
          $customer_name_filter = $request->has('customer_name_filter') ? $request->get('customer_name_filter') : '' ;
          $status_filter = $request->has('status_filter') ? $request->get('status_filter') : '' ;
          $enquiry_date = $request->has('enquiry_date') ? $request->get('enquiry_date') : '' ;
          $loan_partner = $request->has('loan_partner') ? $request->get('loan_partner') : '' ;
          
          if($enquiry_date){
              
                $split_date = explode( ' - ',$enquiry_date);
                // dd($split_date);
             
                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[0])));
                $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $split_date[1])));
                
          }
          
         

            $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
           
            
            $query = EnquiryList::whereNotNull('loan_category_type')
            // ->whereNotNull('client_loan_amount')
            ->whereNotNull('client_first_name')
            ->select('*');
            
            if($loan_type){
                 $query->where('loan_category_type', $loan_type ) ;
            }
            
             if($customer_name_filter){
                 $query->where('client_first_name', 'like', '%' . $request->customer_name_filter . '%');
            }
            
              if($status_filter){
                 $query->where('status', $status_filter ) ;
            }
            
            if ($roleTitle != 1) {
               
                $query->where('created_by', $userId); 
                
            }else{
                if($loan_partner){
                $query->where('created_by', $userId); ; //created_by
            }
                
            }
            
            if($enquiry_date){
                // dd($start_date, $end_date);
                $query->whereBetween('created_date', [$start_date, $end_date]);
                // dd();
                
            }
            
            return $this -> DataTable($query, $roleTitle) ;
            
            
           
          
            
            // $table = Datatables::of($query);
            // $table->editColumn('loan_category_type', function ($row) {
                
            // return $row->loan_category_type ? ($row->loan_types->title ?? '') : '';
                
            // });
            
            // $table->addColumn('placeholder', '&nbsp;');
            // $table->addColumn('actions', '&nbsp;');
            
            //  $table->editColumn('created_by', function ($row) {
                
            //     return  $row->created_by ? $row->user->name :'';
            // });
            
            
            //  $table->editColumn('created_date', function ($row) {
                
            //     return  $row->created_date ?  date("d-m-Y", strtotime($row->created_date))  :'';
            // });
  



            // $table->editColumn('actions', function ($row) {
             

              
            //     $editGate = 'enquiry_list_edit';
            //     $viewGate = '';
            //     $editFunct ='enquiry_list_edit' ;
               

            //     return view('partials.ajaxTableActions', compact(

            //     'viewGate',
            //     'editGate',
            //     'row',
            //     'editFunct'
             
            //     ));
            // });


            // $table->rawColumns(['actions', 'placeholder']);
            // return $table->make(true);

        }
        
          $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
          
          $isEditable = false;
          $enquiry=[];
          
        $partners = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->where('role_user.role_id', 2)
         ->select('users.name', 'users.email', 'users.id')
        ->get();
      
        return view('admin.enquiry_list.index', compact('loan_types','isEditable','enquiry','partners'));
    }
    
    public function edit($id)
    {
        $enquiry = EnquiryList::findOrFail($id);
        
        if($enquiry ){
            
        if($enquiry-> status == 'Underwriting'){
            
         return  redirect()->route('admin.application-stage.edit',$enquiry->application_id) ;
            
        }
            
        $isEditable = true;
        $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
        return view('admin.enquiry_list.edit', compact('enquiry','isEditable','loan_types'));
            
        }
        
        
       
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = Brand::where(['id' => $request->id])->select('id', 'brand')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }
    
    public function create(){
        
           $isEditable = false;
           $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
            
            $enquiry=[];
        return view('admin.enquiry_list.edit',compact('isEditable','loan_types','enquiry' ));
    }

 

    public function store(Request $request)
    {

          if($request->loan_category != 'broker' && $request->loan_category != 'introducer' ){
              return  response()->json(['status' => false,'message'=> 'given Details Invalid']);
          }
          
        //   dd( $request->company_client_loan_amount);
          
            $enquiry = new EnquiryList();
            $enquiry->loan_category = $request->loan_category ?? null;
          
          
          if($request->loan_category == 'broker'){
              
              
              
                $enquiry->mortgage_status = $request->mortgage_status ?? null;
                $enquiry->purpose_loan = $request->purpose_loan ?? null;
                $enquiry->application_made = $request->application_made ?? null;
                $enquiry->loan_amount = $request->loan_amount ?? null;
                $enquiry->term_year = $request->term_year ?? null;
                $enquiry->term_month = $request->term_month ?? null;
                $enquiry->live_or_intent_property = $request->live_or_intent_property ?? null;
                
             
              if($request->application_made == 'Personal Name'){
                 
                 $enquiry->loan_category_type = $request->loan_category_type ?? null;
                 $enquiry->client_first_name = $request->client_first_name ?? null;
                 $enquiry->client_last_name = $request->client_last_name ?? null;
                 $enquiry->client_email = $request->client_email ?? null;
                 $enquiry->client_phone = $request->client_phone ?? null;
                 $enquiry->client_loan_amount = $request->client_loan_amount ?? null;
                 $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
                 $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
                 
                 $enquiry->company_name = null;
                 $enquiry->company_no = null;
                 $enquiry->company_address_1 = null;
                 $enquiry->company_address_2 = null;
                 $enquiry->company_security_address_line1 = null;
                 $enquiry->company_security_address_line2 = null;
         }else{
             
             $enquiry->loan_category_type = $request->loan_category_type2 ?? null; // loan_category_type2
             $enquiry->company_name = $request->company_name;
             $enquiry->company_no = $request->company_no;
             $enquiry->company_address_1 = $request->company_address_1;
             $enquiry->company_address_2 = $request->company_address_2;
             $enquiry->company_security_address_line1 = $request->company_security_address_line1;
             $enquiry->company_security_address_line2 = $request->company_security_address_line2;
             
             
             $enquiry->client_first_name = $request->company_client_first_name ?? null;
             $enquiry->client_last_name = $request->company_client_last_name ?? null;
             $enquiry->client_email = $request->company_client_email ?? null;
             $enquiry->client_phone = $request->company_client_phone ?? null;
             
             
             $enquiry->client_loan_amount = $request->company_client_loan_amount ?? null;
             $enquiry->client_propertity_value = $request->company_client_propertity_value ?? null;
             $enquiry->client_extra_comment = $request->company_client_extra_comment ?? null;
             
         }
              
          }
          else{
             
                $enquiry->mortgage_status = null;
                $enquiry->purpose_loan = null;
                $enquiry->application_made = null;
                $enquiry->loan_amount = null;
                $enquiry->term_year = null;
                $enquiry->term_month = null;
                $enquiry->live_or_intent_property = null;
                $enquiry->company_name = null;
                $enquiry->company_no = null;
                $enquiry->company_address_1 = null;
                $enquiry->company_address_2 = null;
                $enquiry->company_security_address_line1 = null;
                $enquiry->company_security_address_line2 = null;
                $enquiry-> loan_category = $request->loan_category ?? null;
                $enquiry->loan_category_type = $request->loan_category_type ?? null;
                 $enquiry->client_first_name = $request->client_first_name ?? null;
                 $enquiry->client_last_name = $request->client_last_name ?? null;
                 $enquiry->client_email = $request->client_email ?? null;
                 $enquiry->client_phone = $request->client_phone ?? null;
                 $enquiry->client_loan_amount = $request->client_loan_amount ?? null;
                 $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
                 $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
             
         }
          

         $enquiry->created_by = Auth::user()->id ?? null ;
         $enquiry->created_date = date("Y-m-d") ?? null ;
         $enquiry->save();
         
        //  $enquiry->enquiryClients()->detach();
         
         
        // $co_applicants = $request->company_security_first_name;
        // $co_applicants_last_name = $request->company_security_last_name;
        // $co_applicants_phones = $request->company_security_phone;
        // $co_applicants_emails = $request->company_security_email;
        
        // for ($si = 0; $si < count($co_applicants); $si++) {

        //         $check_co_applicant = EnquiryClients::where([ 'email' => $co_applicants_emails[$si]])->first();
        //         if (!$check_co_applicant) {

        //             $check_co_applicant = EnquiryClients::firstOrCreate(
        //                 [
        //                     'name' => $co_applicants[$si],
        //                     'email' => $co_applicants_emails[$si],
        //                     'phone' => $co_applicants_phones[$si],
        //                     'last_name' => trim($co_applicants_last_name[$si]),
        //                 ]
        //             );

        //         } else {

        //             EnquiryClients::where([ 'email' => $co_applicants_emails[$si]])->update([
        //                 'phone' => $co_applicants_phones[$si],
        //                 'last_name' => trim($co_applicants_last_name[$si]),
        //             ]);

        //         }

        //         // $applicationDetail = Application::find($id);

        //         // Attach the co-applicant if it doesn't already exist in the pivot table
        //         if (!$enquiry->enquiryClients()->where('enquiry_client_id', $check_co_applicant->id)->exists()) {
        //             $enquiry->enquiryClients()->attach($check_co_applicant->id);
        //         }
        //     }
         
         
          return response()->json(['status' => true]);
    }
    
    // 04-08-2024  code 
    
    public function update(Request $request) {
            $id = $request->id;
            $user = auth()->user();
            $roleTitle = $user ? ($user->roles->first()->id ?? '') : 0;
        
            if (!in_array($request->loan_category, ['broker', 'introducer'])) {
                return response()->json(['status' => false, 'message' => 'Given details invalid']);
            }
        
            $enquiry = EnquiryList::find($id);
        
            if (!$enquiry) {
                return response()->json(['status' => false, 'message' => 'Enquiry list not found']);
            }
        
            $enquiry->loan_category = $request->loan_category ?? null;
            if ($request->loan_category == 'broker') {
                $enquiry->mortgage_status = $request->mortgage_status ?? null;
                $enquiry->purpose_loan = $request->purpose_loan ?? null;
                $enquiry->application_made = $request->application_made ?? null;
                $enquiry->loan_amount = $request->loan_amount ?? null;
                $enquiry->term_year = $request->term_year ?? null;
                $enquiry->term_month = $request->term_month ?? null;
                $enquiry->live_or_intent_property = $request->live_or_intent_property ?? null;
        
                if ($request->application_made == 'Personal Name') {
                    $enquiry->loan_category_type = $request->loan_category_type ?? null;
                    $enquiry->client_first_name = $request->client_first_name ?? null;
                    $enquiry->client_last_name = $request->client_last_name ?? null;
                    $enquiry->client_email = $request->client_email ?? null;
                    $enquiry->client_phone = $request->client_phone ?? null;
                    $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
                    $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
                    $enquiry->company_name = null;
                    $enquiry->company_no = null;
                    $enquiry->company_address_1 = null;
                    $enquiry->company_address_2 = null;
                    $enquiry->company_security_address_line1 = null;
                    $enquiry->company_security_address_line2 = null;
                    
                    if ($roleTitle == 1) {
                    $customer_check  = CustomDetail::where('email',$enquiry->client_email)->first();
                 
                    if(!$customer_check ){
                        $customer_check = CustomDetail::Create(
                                            [
                                                'email' => $request->client_email,
                                                'name' => $request->client_first_name,
                                                'last_name' => $request->client_last_name,
                                                'phone' => $request->client_phone,
                                            ]
                                        );
                    }
                    
                    }
                    
                    
                } else {
                    
                    $enquiry->loan_category_type = $request->loan_category_type2 ?? null;
                    $enquiry->company_name = $request->company_name;
                    $enquiry->company_no = $request->company_no;
                    $enquiry->company_address_1 = $request->company_address_1;
                    $enquiry->company_address_2 = $request->company_address_2;
                    $enquiry->client_first_name = $request->company_client_first_name ?? null;
                    $enquiry->client_last_name = $request->company_client_last_name ?? null;
                    $enquiry->client_email = $request->company_client_email ?? null;
                    $enquiry->client_phone = $request->company_client_phone ?? null;
                    $enquiry->client_loan_amount = $request->company_client_loan_amount ?? null;
                    $enquiry->client_propertity_value = $request->company_client_propertity_value ?? null;
                    $enquiry->client_extra_comment = $request->company_client_extra_comment ?? null;
                    $enquiry->company_security_address_line1 = $request->company_security_address_line1;
                    $enquiry->company_security_address_line2 = $request->company_security_address_line2;
                    
                    $customer_check  = CustomDetail::where('email',$enquiry->company_client_email)->first();
                    
                      if ($roleTitle == 1) {
                    if(!$customer_check ){
                        $customer_check = CustomDetail::Create(
                                            [
                                                'email' => $request->company_client_email,
                                                'name' => $request->company_client_first_name,
                                                'last_name' => $request->company_client_last_name,
                                                'phone' => $request->company_client_phone,
                                            ]
                                        );
                    }
                      }
                    
                }
            } else {
                $enquiry->mortgage_status = null;
                $enquiry->purpose_loan = null;
                $enquiry->application_made = null;
                $enquiry->loan_amount = null;
                $enquiry->term_year = null;
                $enquiry->term_month = null;
                $enquiry->live_or_intent_property = null;
                $enquiry->company_name = null;
                $enquiry->company_no = null;
                $enquiry->company_address_1 = null;
                $enquiry->company_address_2 = null;
                $enquiry-> loan_category = $request->loan_category ?? null;
                $enquiry->loan_category_type = $request->loan_category_type ?? null;
                $enquiry->client_first_name = $request->client_first_name ?? null;
                $enquiry->client_last_name = $request->client_last_name ?? null;
                $enquiry->client_email = $request->client_email ?? null;
                $enquiry->client_phone = $request->client_phone ?? null;
                $enquiry->client_loan_amount = $request->client_loan_amount ?? null;
                $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
                $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
                $enquiry->company_security_address_line1 = null;
                $enquiry->company_security_address_line2 = null;
                 
                   if ($roleTitle == 1) {
                 $customer_check  = CustomDetail::where('email',$enquiry->client_email)->first();
                 
                    if(!$customer_check){
                        $customer_check = CustomDetail::Create(
                                            [
                                                'email' => $request->client_email,
                                                'name' => $request->client_first_name,
                                                'last_name' => $request->client_last_name,
                                                'phone' => $request->client_phone,
                                            ]
                                        );
                    }
                   }
                 
                 
            }
        
            // $enquiry->enquiryClients()->detach();
            // $co_applicants = $request->company_security_first_name;
            // $co_applicants_last_name = $request->company_security_last_name;
            // $co_applicants_phones = $request->company_security_phone;
            // $co_applicants_emails = $request->company_security_email;
        
            // for ($si = 0; $si < count($co_applicants); $si++) {
            //     $check_co_applicant = EnquiryClients::updateOrCreate(
            //         ['name' => $co_applicants[$si], 'email' => $co_applicants_emails[$si]],
            //         ['phone' => $co_applicants_phones[$si], 'last_name' => trim($co_applicants_last_name[$si])]
            //     );
        
            //     if (!$enquiry->enquiryClients()->where('enquiry_client_id', $check_co_applicant->id)->exists()) {
            //         $enquiry->enquiryClients()->attach($check_co_applicant->id);
            //     }
            // }
            
                   
        
                $enquiry->updated_by = Auth::id();
                $user = auth()->user();
                $roleTitle = $user ? ($user->roles->first()->id ?? '') : 0;
        
            if ($roleTitle == 1) {
                $enquiry->status = "Underwriting";
                $enquiry->save();
                
                
        
                // $customer_check = CustomDetail::updateOrCreate(
                //     ['email' => $enquiry->client_email],
                //     ['name' => $enquiry->client_first_name, 'last_name' => $enquiry->client_last_name, 'phone' => $enquiry->client_phone]
                // );
        
                $application = new Application();
                $application->created_date = date("Y-m-d") ?? null ;
                $application->loan_type_id = $enquiry->loan_category_type ?? null;
                $application->customer_id = $customer_check->id ?? null;
                $application->enquiry_id = $id ?? null;
                $application->save();
        
                $application->ref_no = 'APP' . sprintf('%05d', $application->id);
                $application->loan_category = $request->loan_category ?? null;
                // $application->client_loan_amount = $request->client_loan_amount ?? null;
                $application->client_propertity_value = $enquiry->client_propertity_value ?? null;
                $application->client_extra_comment = $enquiry->client_extra_comment ?? null;
                
            
                if ($request->loan_category == 'broker') {
                    
                        $application->mortgage_status = $enquiry->mortgage_status ?? null;
                        $application->purpose_loan = $enquiry->purpose_loan ?? null;
                        $application->application_made = $enquiry->application_made ?? null;
                        $application->loan_amount = $enquiry->loan_amount ?? null;
                        $application->term_year = $enquiry->term_year ?? null;
                        $application->term_month = $enquiry->term_month ?? null;
                        $application->live_or_intent_property = $request->live_or_intent_property ?? null;
                        $application->loan_category = $enquiry->loan_category ?? null;
                
                        if ($request->application_made == 'Personal Name') {
                            $application->loan_type_id = $enquiry->loan_category_type ?? null;
                            // $application->client_first_name = $request->client_first_name ?? null;
                            // $application->client_last_name = $request->client_last_name ?? null;
                            // $application->client_email = $request->client_email ?? null;
                            // $application->client_phone = $request->client_phone ?? null;
                            $application->client_propertity_value = $enquiry->client_propertity_value ?? null;
                            $application->client_extra_comment = $enquiry->client_extra_comment ?? null;
                            $application->company_name = null;
                            $application->company_no = null;
                            $application->company_address_1 = null;
                            $application->company_address_2 = null;
                            $enquiry->company_security_address_line1 = null;
                            $enquiry->company_security_address_line2 = null;
                        } else {
                            $application->loan_category = $enquiry->loan_category ?? null;
                            $application->loan_type_id = $enquiry->loan_category_type ?? null;
                            $application->company_name = $enquiry->company_name;
                            $application->company_no = $enquiry->company_no;
                            $application->company_address_1 = $enquiry->company_address_1;
                            $application->company_address_2 = $enquiry->company_address_2;
                            // $application->client_first_name = $request->company_client_first_name ?? null;
                            // $application->client_last_name = $request->company_client_last_name ?? null;
                            // $application->client_email = $request->company_client_email ?? null;
                            // $application->client_phone = $request->company_client_phone ?? null;
                            $application->client_loan_amount = $enquiry->company_client_loan_amount ?? null;
                            $application->loan_amount = $enquiry->company_client_loan_amount ?? null;
                            $application->client_propertity_value = $enquiry->company_client_propertity_value ?? null;
                            $application->client_extra_comment = $enquiry->company_client_extra_comment ?? null;
                            $enquiry->company_security_address_line1 = $request->company_security_address_line1;
                            $enquiry->company_security_address_line2 = $request->company_security_address_line2;
                        }
                    } else {
                        $application->mortgage_status = null;
                        $application->purpose_loan = null;
                        $application->application_made = null;
                        $application->loan_amount = null;
                        $application->term_year = null;
                        $application->term_month = null;
                        $application->live_or_intent_property = null;
                        $application->company_name = null;
                        $application->company_no = null;
                        $application->company_address_1 = null;
                        $application->company_address_2 = null;
                        $application-> loan_category = $enquiry->loan_category ?? null;
                        $application->loan_type_id =$enquiry-> loan_category_type ?? null;
                        //  $application->client_first_name = $request->client_first_name ?? null;
                        //  $application->client_last_name = $request->client_last_name ?? null;
                        //  $application->client_email = $request->client_email ?? null;
                        //  $application->client_phone = $request->client_phone ?? null;
                        $application->client_loan_amount = $enquiry->client_loan_amount ?? null;
                        $application->loan_amount = $enquiry->client_loan_amount ?? null;
                        $application->client_propertity_value = $enquiry->client_propertity_value ?? null;
                        $application->client_extra_comment = $enquiry->client_extra_comment ?? null;
                        $enquiry->company_security_address_line1 = null;
                        $enquiry->company_security_address_line2 = null;
                }
        
                $application->save();
               $ref_no = 'APP' . sprintf('%05d', $application->id) ;
               $application -> ref_no =  $ref_no ;
                $application ->save();
        
                $enquiry->application_id = $application->id ?? null;
                $enquiry->save();
        
                // for ($si = 0; $si < count($co_applicants); $si++) {
                //     $check_co_applicant = CoApplicant::updateOrCreate(
                //         ['name' => $co_applicants[$si], 'email' => $co_applicants_emails[$si]],
                //         ['phone' => $co_applicants_phones[$si], 'last_name' => trim($co_applicants_last_name[$si])]
                //     );
        
                //     if (!$application->co_applicant1()->where('co_applicant_id', $check_co_applicant->id)->exists()) {
                //         $application->co_applicant1()->attach($check_co_applicant->id);
                //     }
                // }
        
                $redirect_url = route('admin.application-stage.edit', $application->id);
            } else {
                $enquiry->save();
                $redirect_url = route('admin.enquiry_list.index');
            }

            return response()->json(['status' => true, 'data' => $redirect_url]);
    }



   

    public function delete(Request $request)
    {
        if (isset($request->id)) {
            $delete = EnquiryList::where(['id' => $request->id])->update([
                'status' => "Canceled",
            ]);
             $redirect_url = route('admin.enquiry_list.index');
            return response()->json(['status' => 'success', 'data' => $redirect_url, 'message' => 'Enquiry Canceled Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = Brand::whereIn('id', request('ids'))->delete();
        // find(request('ids'));

        // foreach ($academicYears as $academicYear) {
        //     $academicYear->delete();
        // }
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    // public function enquiryJob(Request $request){
        
    //     $userId = auth()->user()->id;
    //         $user = \App\Models\User::find($userId);
    //         $assignedRole = $user ? $user->roles->first() : null;
    //         $roleTitle = $assignedRole ? $assignedRole->id : 0;
        
        
    //      $query = EnquiryList::query()->select('*');
    //     if($roleTitle != 1){
    //         $query->where('status', 'Enquiry') ;
    //         $query->where('created_by ', $userId) ;
    //     }
        
    //   $query ->latest('created_at')->take(5) ;
    //   return $this->DataTable($query,$roleTitle);
        
        
    // }
    
    public function DataTable($query, $roleTitle){
        
        $table = Datatables::of($query);
            $table->editColumn('loan_category_type', function ($row) {
                
            return $row->loan_category_type ? ($row->loan_types->title ?? '') : '';
                
            });
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
             $table->editColumn('created_by', function ($row) {
                
                return  $row->created_by ? $row->user->name :'';
            });
            
            
             $table->editColumn('created_date', function ($row) {
                
                return  $row->created_date ?  date("d-m-Y", strtotime($row->created_date))  :'';
            });
  



            $table->editColumn('actions', function ($row) {
             

              
                $editGate = 'enquiry_list_edit';
                $viewGate = '';
                $editFunct ='enquiry_list_edit' ;
               

                return view('partials.ajaxTableActions', compact(

                'viewGate',
                'editGate',
                'row',
                'editFunct'
             
                ));
            });


            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        
        
    }
    
    public function EnquiryJob(Request $request){
        
        if($request->ajax()){
            
              $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            $query = EnquiryList::whereNotNull('loan_category_type') ;
            if($roleTitle != 1){
                $query->where('status', 'Enquiry') ;
                $query->where('created_by', $userId) ;
            }else{
                 $query->where('status', 'Enquiry') ;
            }
            
           $query ->latest('created_at')->take(5) ;
           
           return $this->DataTable($query, $roleTitle);
                
        }
    }

  

   

 

 

    

    
  
}
