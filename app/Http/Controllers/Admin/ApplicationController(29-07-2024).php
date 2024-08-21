<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AdditionalDocument;

use App\Models\Application;

use App\Models\ApplicationFormImage;

use App\Models\{ApplicationImage,
    CoApplicant,
    CustomDetail,
    DocumentType,
    FormUpload,
    LoanType,
    Brand,
    ApplicationAdditional,
    ApplicationAdditionFormUploads


 };

use Carbon\Carbon;use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session; // use App\Http\Controllers\AllDataController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{

    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Brand::query()->select(sprintf('*', (new Brand)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewRegulation';
                $editFunct = 'editRegulation';
                $deleteFunct = 'deleteRegulation';
                $viewGate = 'brand_show'; // need to change gate permission
                $editGate = 'brand_edit';
                $deleteGate = 'brand_delete';
                $crudRoutePart = 'subjects';

                return view('partials.ajaxTableActions', compact(

                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row'
                ));
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }
        $isEditable = false;
        $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
      

        return view('admin.application.index', compact('isEditable', 'loan_types'));
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

    public function store1(Request $request, $id = null)
    {
        // Validate the request data if needed
        // $request->validate([...]);

        // Determine if we're updating an existing record or creating a new one
        $id = $request->case_id;
        if ($id) {
            $applicant = Application::findOrFail($id);
            $isUpdating = true;
        } else {
            $applicant = new Application();
            $isUpdating = false;
        }

        // Update application attributes
        $applicant->customer_id = $request->client_id;
        $applicant->loan_type_id = $request->loan_type_id;
        $applicant->save();

        if ($isUpdating) {
            // Clear existing related records for updates
            $applicant->applicationLoanDocument2()->detach();
            $applicant->applicantDocument1()->detach();
            $applicant->applicationLoanFormUpload2()->detach();
            $applicant->applicantformUpload2()->detach();
            $applicant->additionalDocument2()->detach();
        }

        // Handle co-applicants
        $co_applicants = $request->co_applicants_name;
        $co_applicants_emails = $request->co_applicants_email;
        $co_applicants_phones = $request->co_applicants_phone;
        $co_applicants_addresss = $request->co_applicants_address;

        for ($si = 0; $si < count($co_applicants); $si++) {
            $check_co_applicant = CoApplicant::firstOrCreate(
                [
                    'name' => $co_applicants[$si],
                    'email' => $co_applicants_emails[$si],
                    'phone' => $co_applicants_phones[$si],
                    'address' => $co_applicants_addresss[$si],
                ]
            );

            // Attach the co-applicant if not already attached
            if (!$applicant->co_applicant1()->where('co_applicant_id', $check_co_applicant->id)->exists()) {
                $applicant->co_applicant1()->attach($check_co_applicant->id);
            }
        }

        // Update document features if applicable
        if (isset($request->document_features) && count($request->document_features) > 0) {
            $applicant->applicationLoanDocument2()->attach($request->document_features);
        }

        // Update document type IDs if applicable
        if (isset($request->document_id) && count($request->document_id) > 0) {
            $applicant->applicantDocument1()->attach($request->document_id);
        }

        // Update form upload IDs if applicable
        if (isset($request->form_upload_id) && count($request->form_upload_id) > 0) {
            $applicant->applicationLoanFormUpload2()->attach($request->form_upload_id);
        }

        // Update upload form features if applicable
        if (isset($request->uploadForm_features) && count($request->uploadForm_features) > 0) {
            $applicant->applicantformUpload2()->attach($request->uploadForm_features);
        }

        // Update additional documents if applicable
        if (isset($request->addition_document) && count($request->addition_document) > 0) {
            foreach ($request->addition_document as $addition_document) {
                $check_co_additional_document = AdditionalDocument::firstOrCreate(['title' => $addition_document]);

                // Attach the additional document if not already attached
                if (!$applicant->additionalDocument2()->where('additional_id', $check_co_additional_document->id)->exists()) {
                    $applicant->additionalDocument2()->attach($check_co_additional_document->id);
                }
            }
        }

        // Generate reference number and save
        if (!$isUpdating) {
            $application_id = 'APP' . sprintf('%05d', $applicant->id);
            $applicant->ref_no = $application_id;
            $applicant->save();
        }

        $message = $isUpdating ? "Case Updated Successfully and application id is " . $applicant->ref_no : "Case Created Successfully and application id is " . $application_id;

        return response()->json(['status' => true, 'data' => $message]);
    }

    public function store(Request $request)
    {

        // dd($request->all());

        $customer_id = $request->client_id;
        // $customer_id = $request->client_id ;
        $co_applicants = $request->co_applicants_name;
        $co_applicants_emails = $request->co_applicants_email;
        $co_applicants_phones = $request->co_applicants_phone;
        $co_applicants_addresss = $request->co_applicants_address;
        $loan_type_id = $request->loan_type_id;

        $document_features = $request->document_features;
        $document_type_ids = $request->document_id;

        $form_upload_ids = $request->form_upload_id;
        $uploadForm_features = $request->uploadForm_features;

        $addition_documents = $request->addition_document;

        // Application::where(['customer_id'=> $customer_id]) ;

        $id = $request->case_id;
        if ($id) {
            $applicant = Application::findOrFail($id);
            $isUpdating = true;
        } else {
            $applicant = new Application();
            $isUpdating = false;
        }

        $applicant->customer_id = $request->client_id;
        $applicant->loan_type_id = $request->loan_type_id;
        $applicant->loan_amount = $request->loan_amount;
        $applicant->term = $request->term;
        $applicant->pp_ev = $request->pp_ev;
        $applicant->rate = $request->rate;
        $applicant->proc_fee = $request->proc_fee;
        $applicant->save();

        if ($isUpdating) {
            // Clear existing related records for updates
            $applicant->applicationLoanDocument2()->detach();
            $applicant->applicantDocument1()->detach();
            $applicant->applicationLoanFormUpload2()->detach();
            $applicant->applicantformUpload2()->detach();
            $applicant->additionalDocument2()->detach();
            $applicant->co_applicant1()->detach();
        }

   

        if ($applicant) {

            $id = $applicant->id;
            
            

            for ($si = 0; $si < count($co_applicants); $si++) {

                $check_co_applicant = CoApplicant::where(['name' => $co_applicants[$si], 'email' => $co_applicants_emails[$si]])->first();
                if (!$check_co_applicant) {

                    $check_co_applicant = CoApplicant::firstOrCreate(
                        [
                            'name' => $co_applicants[$si],
                            'email' => $co_applicants_emails[$si],
                            'phone' => $co_applicants_phones[$si],
                            'address' => trim($co_applicants_addresss[$si]),
                        ]
                    );

                } else {

                    CoApplicant::where(['name' => $co_applicants[$si], 'email' => $co_applicants_emails[$si]])->update([
                        'phone' => $co_applicants_phones[$si],
                        'address' => trim($co_applicants_addresss[$si]),
                    ]);

                }

                $applicationDetail = Application::find($id);

                // Attach the co-applicant if it doesn't already exist in the pivot table
                if (!$applicationDetail->co_applicant1()->where('co_applicant_id', $check_co_applicant->id)->exists()) {
                    $applicationDetail->co_applicant1()->attach($check_co_applicant->id);
                }
            }

            // with loan_type_id
            if (isset($document_features) && count($document_features) > 0) {

                $applicant->applicationLoanDocument2()->detach($document_features);

                $applicant->applicationLoanDocument2()->attach($document_features);
              
            }

            // without loan_type_id
            if (isset($document_type_ids) && count($document_type_ids) > 0) {

                $applicant->applicantDocument1()->detach($document_type_ids);
                $applicant->applicantDocument1()->attach($document_type_ids);
             
            }

            if (isset($uploadForm_features) && count($uploadForm_features) > 0) {

                $applicant->applicationLoanFormUpload2()->detach($uploadForm_features);
                $applicant->applicationLoanFormUpload2()->attach($uploadForm_features);

              
            }

            // without loan_type_id
            if (isset($form_upload_ids) && count($form_upload_ids) > 0) {

                $applicant->applicantformUpload2()->detach($form_upload_ids);
                $applicant->applicantformUpload2()->attach($form_upload_ids);

           
            }

            // AdditionalDocument

            if (isset($addition_documents) && count($addition_documents) > 0) {
                foreach ($addition_documents as $addition_document) {
                    // Check if the additional document exists or create it
                    $check_co_additional_doucment = AdditionalDocument::firstOrCreate(['title' => $addition_document]);

                    // Attach the additional document to the application if it doesn't already exist in the pivot table
                    $applicationDetail = Application::find($id);
                   
                    if (!$applicationDetail->additionalDocument2()->where('additional_id', $check_co_additional_doucment->id)->exists()) {
                        $applicationDetail->additionalDocument2()->attach($check_co_additional_doucment->id);
                    }
                }
            }

            if (!$isUpdating) {
                $application_id = 'APP' . sprintf('%05d', $applicant->id);
                $applicant->ref_no = $application_id;
                $applicant->save();
            }
            
            
            
            $titles = [];
$addition_form_document_titles = $request->addition_form_document;

    if (count($addition_form_document_titles) > 0) {
        foreach ($addition_form_document_titles as $index => $addition_form_document_title) {
            // Sanitize and format the title
            $addition_form_document = ucwords($addition_form_document_title);
    
            // Check if a file is present for the current title
            $addition_form_document_file = $request->file('addition_form_document_file')[$index] ?? null;
    
            // Check if a record already exists
            $check_data = ApplicationAdditionFormUploads::where([
                'title' => $addition_form_document,
                'application_id' => $id
            ])->first();
    
            if (!$check_data && $addition_form_document_file) {
                // Save the new file to the public/formupload directory
                $fileExtension = $addition_form_document_file->getClientOriginalExtension();
                $fileName = $id . '_' . str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
                $addition_form_document_file->move(public_path('formupload'), $fileName);
    
                // Save the file information in the database
                $check_data = new ApplicationAdditionFormUploads();
                $check_data->application_id = $id;
                $check_data->title = $addition_form_document;
                $check_data->file_path = $fileName;
                $check_data->save();
    
            } elseif ($check_data && $addition_form_document_file) {
                // File exists; Handle file update
                $fileExtension = $addition_form_document_file->getClientOriginalExtension();
                $fileName = $id . '_' . str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
                $addition_form_document_file->move(public_path('formupload'), $fileName);
    
                // Update the existing file information in the database
                $oldFilePath = public_path('formupload') . '/' . $check_data->file_path;
                if (File::exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
                $check_data->file_path = $fileName;
                $check_data->save();
            }

        // Add title to the list
        $titles[] = $addition_form_document;
        }
    }

        // Delete old files that are not in the current request
        $delete_form_additionals = ApplicationAdditionFormUploads::where('application_id', $id)
            ->whereNotIn('title', $titles)
            ->get();
        
        foreach ($delete_form_additionals as $formUpload) {
            $imagePath = public_path('formupload/' . $formUpload->file_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $formUpload->forceDelete();
        }
            
            
            
            // dd($request->file('addition_form_document_file')[0]);
            
            // //start 
            
            //  $addition_form_document_titles = $request->addition_form_document;  
            //       $titles = [];
            //       $si = 0;
            //          if(count($addition_form_document_titles) > 0){
                         
            //              foreach($addition_form_document_titles as $addition_form_document){
                             
            //                   $check_data = ApplicationAdditionFormUploads::where([
            //                     'title' => ucwords($addition_form_document), 
            //                     'application_id' => $id
            //                 ])->first();
                            
            //                 if (!$check_data) {
                                
            //                     if($request->hasFile('addition_form_document_file')[$si]){
                                 
            //                         $addition_form_document_file =   $request->file('addition_form_document_file')[$si] ;
            //                         // Save the file to the public/formupload directory
            //                         $fileExtension = $addition_form_document_file->getClientOriginalExtension();
            //                         $fileName = $id .'_'.str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
            //                         $addition_form_document_file->move(public_path('formupload'), $fileName);
                                    
            //                         // Save the file information in the database
            //                         $check_data = new ApplicationAdditionFormUploads();
            //                         $check_data->application_id = $id;
            //                         $check_data->title = ucwords($addition_form_document);
            //                         $check_data->file_path = $fileName;
            //                         $check_data->save();
                                    
            //                         $titles[] = ucwords($addition_form_document);
            //                         }
            //                     }else{
                                
            //                         if($request->hasFile('addition_form_document_file')[$si]){
            //                             // File does not exist; Handle new file upload
            //                             $fileExtension = $addition_form_document_file->getClientOriginalExtension();
            //                             $fileName = $id .'_'.str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
            //                             $addition_form_document_file->move(public_path('formupload'), $fileName);
            //                             $file_path = $check_data->file_path;
            //                             $oldFilePath = public_path('formupload') . '/' . $file_path;
                                        
            //                             if (File::exists($oldFilePath)) {
            //                                 File::move($oldFilePath, $newFilePath);
            //                             }
                                        
                                        
            //                             // Save the new file information in the database
            //                             // $fileUpload = new ApplicationAdditionFormUploads();
            //                             // $fileUpload->application_id = $id;
            //                             // $fileUpload->title = $addition_form_document;
            //                             // $fileUpload->file_path = 'formupload/' . $fileName;
            //                             $check_data->file_path =  $fileName;
            //                             $check_data->save();
            //                         }
                                    
            //                         $titles[] = ucwords($addition_form_document);
                                
                                
            //                 }
                            
            //              }
                         
                       
            //       }
                   
                   
            //       $delete_form_additionals = ApplicationAdditionFormUploads::where([
            //                     'application_id' => $id
            //                 ])->whereNotIn('title', $titles)->get();
                            
            //                  foreach ($delete_form_additionals as $formUpload) {
            
                               
            //                     $imagePath = public_path('formupload/' . $formUpload->file_path);
                        
            //                     if (File::exists($imagePath)) {
            //                         File::delete($imagePath);
            //                     }
                        
            //                     $formUpload->delete();
            //                      $status = true;
                                    
            //                 }
            
            
            

            
            
            
            
            
            
            
            
            
            //         $titles = [];
            // if ($request->hasFile('addition_form_document_file')) {
                
            //     $addition_form_document_files = $request->file('addition_form_document_file');
                
            //     if (count($addition_form_document_files) > 0) {
            //         $si = 0;
                    
            //         foreach ($addition_form_document_files as $addition_form_document_file) {
                        
            //             $addition_form_document = $request->addition_form_document[$si] ?? '';
                        
            //             if ($addition_form_document != '') {
            //                 // Check if a record already exists
            //                 $check_data = ApplicationAdditionFormUploads::where([
            //                     'title' => ucwords($addition_form_document), 
            //                     'application_id' => $id
            //                 ])->first();
                            
            //                 if (!$check_data) {
            //                     // Save the file to the public/formupload directory
            //                     $fileExtension = $addition_form_document_file->getClientOriginalExtension();
            //                     $fileName = $id .'_'.str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
            //                     $addition_form_document_file->move(public_path('formupload'), $fileName);
                                
            //                     // Save the file information in the database
            //                     $check_data = new ApplicationAdditionFormUploads();
            //                     $check_data->application_id = $id;
            //                     $check_data->title = $addition_form_document;
            //                     $check_data->file_path = $fileName;
            //                     $check_data->save();
            //                 }else{
                                
                                
            //                     // File does not exist; Handle new file upload
            //                     $fileExtension = $addition_form_document_file->getClientOriginalExtension();
            //                     $fileName = $id .'_'.str_replace(" ", '_', $addition_form_document) . '_' . time() . '.' . $fileExtension;
            //                     $addition_form_document_file->move(public_path('formupload'), $fileName);
            //                     $file_path = $check_data->file_path;
            //                     $oldFilePath = public_path('formupload') . '/' . $file_path;
                                
            //                     if (File::exists($oldFilePath)) {
            //                         File::move($oldFilePath, $newFilePath);
            //                     }
                                
                                
            //                     // Save the new file information in the database
            //                     // $fileUpload = new ApplicationAdditionFormUploads();
            //                     // $fileUpload->application_id = $id;
            //                     // $fileUpload->title = $addition_form_document;
            //                     // $fileUpload->file_path = 'formupload/' . $fileName;
            //                     $check_data->file_path =  $fileName;
            //                     $check_data->save();
                                
                                
            //                 }
                            
            //                  $titles[]=$check_data->title ;
            //             }
                        
            //             $si++;
            //         }
            //      }
            // }
            
            //                 $delete_form_additionals = ApplicationAdditionFormUploads::where([
            //                     'application_id' => $id
            //                 ])->whereNotIn('title', $titles)->get();
                            
            //                  foreach ($delete_form_additionals as $formUpload) {
            
                               
            //                     $imagePath = public_path('formupload/' . $formUpload->file_path);
                        
            //                     if (File::exists($imagePath)) {
            //                         File::delete($imagePath);
            //                     }
                        
            //                     $formUpload->delete();
            //                      $status = true;
                                    
            //                 }
                            
                            
            

            
            
            
            // if ($request->hasFile('addition_form_document_files')) {
                
            //     $addition_form_document_files =  $request->file('addition_form_document_files');
            //     if(count($addition_form_document_files) > 0){
                    
            //         $si = 0;
                    
            //         foreach($addition_form_document_files as $addition_form_document_file){
                        
            //             $addition_form_document = $request->addition_form_document[$si] ?? '' ;
                        
            //             if($addition_form_document != ''){
    
            //                 $check_data =  ApplicationAdditionFormUploads::where(['title' => $addition_form_document, 'application_id'=> $id ]);
            //                 $image = $addition_form_document_file->hasFile('')
                            
            //                      $fileExtension = $image->getClientOriginalExtension();
            //                     $fileName = str_replace(" ", '_', $title). '_'. time(). '.' . $fileExtension;
            //                     $image->move(public_path('formupload'), $fileName);
            //             }
                        
                        
            //         }
                    
                    
                    
                    
                    
            //     }
            
            // }
            
            
            
            
            

            $url = route('admin.document.index', $applicant->id);

            $message = $isUpdating ? "Case Updated Successfully and application id is " . $applicant->ref_no : "Case Created Successfully and application id is " . $application_id;

            return response()->json(['status' => true, 'data' => $message, 'url' => $url]);
        }

    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = Brand::where(['id' => $request->id])->select('id', 'brand')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
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
        $academicYears = Brand::whereIn('id', request('ids'))->delete();
        // find(request('ids'));

        // foreach ($academicYears as $academicYear) {
        //     $academicYear->delete();
        // }
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function create()
    {

        LoanType::whereNotNull('title')->pluck('title', 'id');
    }

    public function GetClients(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $clients = CustomDetail::orderby('name', 'asc')->select('id', 'name', 'email')->distinct()->limit(6)->get();
        } else {
            $clients = CustomDetail::orderby('name', 'asc')->select('id', 'name', 'email')->where('name', 'like', '%' . $search . '%')->distinct()->limit(6)->get();
        }

        $response = [];
        foreach ($clients as $client) {
            $response[] = array(
                "id" => $client->id,
                "text" => $client->name . '(' . $client->email . ')',
            );
        }
        return response()->json($response);
    }

    public function GetClientInfo(Request $request)
    {
        $client_details = CustomDetail::select('*')
            ->where('id', '=', $request->id)
            ->get();
        return response()->json($client_details);
    }

    public function customerStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => ["required"],
            // "cmpny_name" => ["required"],
            'email' => ["required", "email", Rule::unique('customer_details')->ignore($request->customer_id)],
            "phone" => ["required", "numeric", Rule::unique('customer_details')->ignore($request->customer_id)],
            "address1" => ["nullable"],
            // "remarks" => ["nullable"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            
            // dd(CustomDetail::where('id', $request->customer_id)->first()) ;
            
            $data = CustomDetail::updateOrCreate(
                ['id' => $request->customer_id],
                [
                    'name' => $request->name,
                    // 'company_name' => $request->cmpny_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address1' => $request->address1,
                    // 'remark' => $request->remarks,
                ]
            );
            
              $isNew = $data->wasRecentlyCreated;

            return response()->json($data->id ? ['status' => 200, 'data' => $data, 'errors' => null,  'is_new' => $isNew] : ['status' => 400, 'data' => null, 'errors' => null,  'is_new' => '']);
        }
    }

    public function getLoanTypeDetails(Request $request)
    {

        $id = $request->loan_type_id;

        $loanType = LoanType::find($id);
        if ($loanType) {
            $documentTypes = $loanType->documents;
            $document_id = $loanType->documents->pluck('id') ?? [];

            $document_content = '';
            foreach ($documentTypes as $documentType) {
                $document_content .= '<div class="form-group col-md-3">';
                $document_content .= '<input type="checkbox" name="document_features[]" value="' . $documentType->id . '">';
                $document_content .= '&nbsp;&nbsp;' . $documentType->title . '<br>';
                $document_content .= '</div>';
            }

            $formUploads = $loanType->formuploads;
            $formuploads_id = $loanType->formuploads->pluck('id') ?? [];
            $formUpload_content = '';

            foreach ($formUploads as $formUpload) {
                $formUpload_content .= '<div class="form-group col-md-3">';
                $formUpload_content .= '<input type="checkbox" name="uploadForm_features[]" value="' . $formUpload->id . '">';
                $formUpload_content .= '&nbsp;&nbsp;' . $formUpload->title . '<br>';
                $formUpload_content .= '</div>';
            }

            $document_typs = DocumentType::whereNotIN('id', $document_id)->pluck('title', 'id');
            $form_uploads_typs = FormUpload::whereNotIN('id', $formuploads_id)->pluck('title', 'id');
            return response()->json(['status' => true, 'document_content' => $document_content, 'formUpload_content' => $formUpload_content, 'document_typs' => $document_typs, 'form_uploads_typs' => $form_uploads_typs]);
        } else {
            return response()->json(['status' => false, 'document_content' => [], 'formUpload_content' => [], 'document_typs' => '', 'form_uploads_typs' => '']);
        }
    }

    public function document_index($id)
    {

        $id = intval($id);
        $data = Application::find($id);
        $isdocument = false;
        if (!$data || $data == null) {
            return view('admin.application.document_index', compact('isdocument'));
        }

        $documentImages = ApplicationImage::where('application_id', $id)->whereIn('document_id', $data->applicationLoanDocument2->pluck('id'))->get();
        $documentImages2 = ApplicationImage::where('application_id', $id)->whereIn('document_id', $data->applicantDocument1->pluck('id'))->get();
        $documentImages3 = ApplicationFormImage::where('application_id', $id)->whereIn('form_id', $data->applicantformUpload2->pluck('id'))->get();
        $documentImages4 = ApplicationFormImage::where('application_id', $id)->whereIn('form_id', $data->applicationLoanFormUpload2->pluck('id'))->get();
        $documentImages5 = ApplicationAdditional::where('application_id', $id)->whereIn('additional_id', $data->additionalDocument2->pluck('id'))->get();
        // $documentImages5 = ApplicationAdditionFormDocument::where('application_id', $id)->whereIn('additional_form_id', $data->ApplicationAdditionFormUploads->pluck('id'))->get();
    // dd(  $documentImages5);
        $isdocument = true;
        return view('admin.application.document_index', compact('data', 'isdocument', 'documentImages', 'documentImages2', 'documentImages3', 'documentImages4','documentImages5'));
    }

    public function uploadImageGet(Request $request)
    {

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $status_image_path = '';

        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationImage::where([
                    'application_id' => $request->applicationId,
                    'document_id' => $request->documentId,
                    'id' => $request->imageId,
                ])->first();

                $index = intval($request->index);
                $index++;

                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                   $status = $applicationImage->status == "Rejected" ? 'Reupload' : $applicationImage->status ;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath;
                        $applicationImage->status = $status;
                        $applicationImage->save();
                        
                        $status_image_path = $this ->statusImage($status);

                        
                        // if($status == 'Rejected'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Reupload'){
                        //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Accepted'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Upload'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                            
                        // }
                        
                        $message = 'Successful image updated';
                        $status = true;


                        $fileExtension = pathinfo('formupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                            if($fileExtension == 'pdf'){
                                $ImagePath = asset('formupload/pdf_preview.png') ;

                            }else{
                                $ImagePath =  asset('formupload/'. $applicationImage->file_path) ;

                            }

                    $html = '' ;

                    $html .= '<input type="checkbox" class="delete-checkbox" data-image-id="'.$applicationImage->id.'" data-file-id="uploaded_file_'.$index.'">';
                    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                    $html .= '<button id="upload_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-eye"></i></button>';
                    $html .= '<button id="upload_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="far fa-edit"></i></button>';
                    $html .= '<button id="upload_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-trash"></i></button>';
                    $html .= '</div>';
                    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                    $html .= '<div class="form-group">';
                    // if($role != 1){
                    //     $html .= '<div><input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_'.$index.'" placeholder="Remark" value="'.$applicationImage->remark.'">';
                    // }
                    // $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id.'">';
                    // $html .= '</div></div></div>';
                    $hide = '';
                    if($role == 1){
                        $hide = 'readonly';
                    }
                    $html .= '<div>';
                    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' .$applicationImage->remark. '" ' . $hide . '>';
                    $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id .'">';
                    $html .= '</div>';
                    $html .= '</div></div></div>';

                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $html, 'image_path'=> $status_image_path]);
            }
        }

        if (isset($request->applicationId) && isset($request->documentId) && isset($request->file)) {

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName =  intval($user ->id). '_'.intval($request->currentImageCount).'_'.time() . '.' . $image->getClientOriginalExtension();
               
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage = ApplicationImage::create(
                    ['application_id' => $request->applicationId, 'document_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                );
                
                $data = ApplicationImage::find($ApplicationImage->id) ?? '';


                //start
                $html = '';
                $fileExtension = pathinfo('formupload/' .$ApplicationImage ->file_path, PATHINFO_EXTENSION);
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('formupload/'. $ApplicationImage ->file_path) ;

                }
                
                
                
                 $status = $ApplicationImage->status ?? null ;
                 $status_image_path = $this ->statusImage($status);
                // if($status == 'Rejected'){
                //             $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Reupload'){
                //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Accepted'){
                //     $status_image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Upload'){
                //     $status_image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                    
                // }
                
                
                
                $index = intval($request->currentImageCount) ?? 0  ;
                   $index ++ ;

                $html .= '<div class="col-6 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox" data-image-id="'.$ApplicationImage ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';

                $hide = '' ;
                if($role == 1){
                    $hide = 'readonly';
                }
                $html .= '<div>';
                $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $ApplicationImage ->remark . '" ' . $hide . '>';
                $html .= '<input type="hidden" name="imageData" value="' . $ApplicationImage ->id . '">';
                $html .= '</div>';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;


                return response()->json(['status' => true, 'data' => $data, 'image_path' => $status_image_path]);
            }
        }

        $data = ApplicationImage::where(['application_id' => $request->applicationId, 'document_id' => $request->documentId])->get();



        $html = '';


        // Loop through each item in $dynamicData
        foreach ($data as $index => $item) {
            $index++;

            $fileExtension = pathinfo('formupload/' .$item['file_path'], PATHINFO_EXTENSION);
            if($fileExtension == 'pdf'){
                $ImagePath = asset('formupload/pdf_preview.png') ;

            }else{
                $ImagePath =  asset('formupload/'. $item['file_path']) ;

            }
            $reject_class = '' ;
            if($item['status'] == "Rejected"){
                $reject_class= 'border_red' ;

            }

            $html .= '<div class="col-6 my-2 all_file_data">';
            $html .= '<form>';
            $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative '.$reject_class.'">';
            $html .= '<input type="checkbox" class="delete-checkbox" data-image-id="'.$item['id'].'" data-file-id="uploaded_file_'.$index.'">';
            $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
            $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
            $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
            $html .= '<button id="upload_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-eye"></i></button>';
            $html .= '<button id="upload_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="far fa-edit"></i></button>';
            $html .= '<button id="upload_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-trash"></i></button>';
            $html .= '</div>';
            $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
            $html .= '<div class="form-group">';

            $hide = '' ;
            if($role == 1){
                $hide = 'readonly';
            }
            $html .= '<div>';
            $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $item['remark'] . '" ' . $hide . '>';
            $html .= '<input type="hidden" name="imageData" value="' . $item['id'] . '">';
            $html .= '</div>';
            $html .= '</div></div></div></form></div>';
        }

        $data = $html ;



        return response()->json(['status' => true, 'data' => $data]);
    }

//22-07-2024
    public function uploadAdditionImageGet(Request $request)
    {

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $image_path = '';

        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationAdditional::where([
                    'application_id' => $request->applicationId,
                    'additional_id' => $request->documentId,
                    'id' => $request->imageId,
                ])->first();

                $index = intval($request->index);
                $index++;

                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                    $status = $applicationImage->status == "Rejected" ? 'Reupload' : $applicationImage->status ;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath;
                        $applicationImage->status = $status;
                        $applicationImage->save();
                        
                         $status_image_path = $this ->statusImage($status);

                        // if($status == 'Rejected'){
                        //     $image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Reupload'){
                        //   $image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Accepted'){
                        //     $image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Upload'){
                        //     $image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                            
                        // }
                        $message = 'Successful image updated';
                        $status = true;


                        $fileExtension = pathinfo('formupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                            if($fileExtension == 'pdf'){
                                $ImagePath = asset('formupload/pdf_preview.png') ;

                            }else{
                                $ImagePath =  asset('formupload/'. $applicationImage->file_path) ;

                            }

                    $html = '' ;


                    $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$applicationImage->id.'" data-file-id="uploaded_file_'.$index.'">';
                    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                    $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-eye"></i></button>';
                    $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="far fa-edit"></i></button>';
                    $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-trash"></i></button>';
                    $html .= '</div>';
                    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                    $html .= '<div class="form-group">';
                    // if($role != 1){
                    //     $html .= '<div><input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_'.$index.'" placeholder="Remark" value="'.$applicationImage->remark.'">';
                    // }
                    // $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id.'">';
                    // $html .= '</div></div></div>';

                    if($role == 1){
                        $hide = 'readonly';
                    }
                    $html .= '<div>';
                    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' .$applicationImage->remark. '" ' . $hide . '>';
                    $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id .'">';
                    $html .= '</div>';
                    $html .= '</div></div></div>';

                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $html, 'image_path'=> $image_path ]);
            }
        }

        if (isset($request->applicationId) && isset($request->documentId) && isset($request->file)) {

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName =  intval($user ->id). '_'.intval($request->currentImageCount).'_'. time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage = ApplicationAdditional::create(
                    ['application_id' => $request->applicationId, 'additional_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                );
                $data = ApplicationAdditional::find($ApplicationImage->id) ?? '';


                //start
                $html = '';
                $fileExtension = pathinfo('formupload/' .$ApplicationImage ->file_path, PATHINFO_EXTENSION);
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('formupload/'. $ApplicationImage ->file_path) ;

                }
                $index = intval($request->currentImageCount) ?? 0 ;
                   $index ++ ;

                $html .= '<div class="col-6 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$ApplicationImage ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';

                $hide = '' ;
                if($role == 1){
                    $hide = 'readonly';
                }
                $html .= '<div>';
                $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $ApplicationImage ->remark . '" ' . $hide . '>';
                $html .= '<input type="hidden" name="imageData" value="' . $ApplicationImage ->id . '">';
                $html .= '</div>';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;


                return response()->json(['status' => true, 'data' => $data]);
            }
        }

        $data = ApplicationAdditional::where(['application_id' => $request->applicationId, 'additional_id' => $request->documentId])->get();



        $html = '';

        // Loop through each item in $dynamicData
        // foreach ($data as $index => $item) {
        //     $index++;

        //     $fileExtension = pathinfo('formupload/' .$item['file_path'], PATHINFO_EXTENSION);
        //     if($fileExtension == 'pdf'){
        //         $ImagePath = asset('formupload/pdf_preview.png') ;

        //     }else{
        //         $ImagePath =  asset('formupload/'. $item['file_path']) ;

        //     }

        //     $reject_class = '' ;
        //     if($item['status']== "Rejected"){
        //         $reject_class= 'border_red' ;

        //     }



        //     // $html .= '<div class="col-6 my-2 all_file_data 1_">';
        //     // $html .= '<form>';
        //     // $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative ' .$reject_class. '">';
        //     // $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$item['id'].'" data-file-id="uploaded_file_'.$index.'">';
        //     // $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
        //     // $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
        //     // $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
        //     // $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-eye"></i></button>';
        //     // $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="far fa-edit"></i></button>';
        //     // $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-trash"></i></button>';
        //     // $html .= '</div>';
        //     // $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
        //     // $html .= '<div class="form-group">';

        //     // $hide = '' ;
        //     // if($role == 1){
        //     //     $hide = 'readonly';
        //     // }
        //     // $html .= '<div>';
        //     // $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $item['remark'] . '" ' . $hide . '>';
        //     // $html .= '<input type="hidden" name="imageData" value="' . $item['id'] . '">';
        //     // $html .= '</div>';
        //     // $html .= '</div></div></div></form></div>';
            
        //     $html .= '<div class="col-6 my-2 all_file_data 1_">';
        //     $html .= '<form>';
        //     $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative ' .$reject_class. '">';
        //     $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$item['id'].'" data-file-id="uploaded_file_'.$index.'">';
        //     $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
        //     $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
        //     $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
        //     $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-eye"></i></button>';
        //     $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="far fa-edit"></i></button>';
        //     $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-trash"></i></button>';
        //     $html .= '</div>';
        //     $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
        //     $html .= '<div class="form-group">';

        //     $hide = '';
        //     if($role == 1){
        //         $hide = 'readonly';
        //     }
        //     $html .= '<div>';
        //     $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $item['remark'] . '" ' . $hide . '>';
        //     $html .= '<input type="hidden" name="imageData" value="' . $item['id'] . '">';
        //     $html .= '</div>';
        //     $html .= '</div>';
        //     $html .= '</div>';
        //     $html .= '</div>';
        //     $html .= '</form>';
        //     $html .= '</div>';

        // }
        
        foreach ($data as $index => $item) {
    $index++;

    $fileExtension = pathinfo('formupload/' .$item['file_path'], PATHINFO_EXTENSION);
    if ($fileExtension == 'pdf') {
        $ImagePath = asset('formupload/pdf_preview.png');
    } else {
        $ImagePath = asset('formupload/'. $item['file_path']);
    }

    $reject_class = '';
    if ($item['status'] == "Rejected") {
        $reject_class = 'border_red';
    }

    $html .= '<div class="col-6 my-2 all_file_data 1_">';
    $html .= '<form>';
    $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative ' .$reject_class. '">';
    $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$item['id'].'" data-file-id="uploaded_file_'.$index.'">';
    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
    $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-eye"></i></button>';
    $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="far fa-edit"></i></button>';
    $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-trash"></i></button>';
    $html .= '</div>';
    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
    $html .= '<div class="form-group">';

    $hide = '';
    if ($role == 1) {
        $hide = 'readonly';
    }
    $html .= '<div>';
    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $item['remark'] . '" ' . $hide . '>';
    $html .= '<input type="hidden" name="imageData" value="' . $item['id'] . '">';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</form>';
    $html .= '</div>';
}


        $data = $html ;
        // dd(  $data );



        return response()->json(['status' => true, 'data' => $data]);
    }

// 22-07-2024



    public function uploadFormImageGet(Request $request)
    {

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $status_image_path  = '' ;
        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationFormImage::where([
                    'application_id' => $request->applicationId,
                    'form_id' => $request->documentId,
                    'id' => $request->imageId,
                ])->first();

                $index = intval($request->index);
                $index++;


                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;
                    
                    $status_  = $applicationImage->status == "Rejected" ? "Reupload" : $applicationImage->status ;
                        // if($role==1){
                        //     $status_ = "Upload" ;
                        // }else{
                        //     $status_ = "Upload" ;
                        // }
                        
                //   dd ($status_);

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath;
                        $applicationImage->status =  $status_ ;
                        $applicationImage->save();
                       
                        
                        $status_image_path = $this ->statusImage($status_);

                        $message = 'Successful image updated';
                        $status = true;


                        $fileExtension = pathinfo('formupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                        // dd($fileExtension);
                            if($fileExtension == 'pdf'){
                                $ImagePath = asset('formupload/pdf_preview.png') ;

                            }else{
                                $ImagePath =  asset('formupload/'. $applicationImage->file_path) ;

                            }
                            // $reject_class = '' ;

                            $html = '' ;
                    //         if($applicationImage->status == "Rejected"){
                    //             $reject_class= 'border_red' ;
                    //         }

                    // $reject_class = 'border_red' ;
                    $html .= '<input type="checkbox" class="delete-checkbox form_delete-checkbox" data-image-id="'.$applicationImage->id.'" data-file-id="uploaded_file_'.$index.'">';
                    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                    $html .= '<button id="upload1_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-eye"></i></button>';
                    $html .= '<button id="upload1_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="far fa-edit"></i></button>';
                    $html .= '<button id="upload1_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-trash"></i></button>';
                    $html .= '</div>';
                    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                    $html .= '<div class="form-group">';
                    // if($role != 1){
                    //     $html .= '<div><input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_'.$index.'" placeholder="Remark" value="'.$applicationImage->remark.'">';
                    // }
                    // $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id.'">';
                    // $html .= '</div></div></div>';
                    $hide = '';
                    if($role == 1){
                        $hide = 'readonly';
                    }
                    $html .= '<div>';
                    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' .$applicationImage->remark. '" ' . $hide . '>';
                    $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id .'">';
                    $html .= '</div>';
                    $html .= '</div></div></div>';

                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                // if ($applicationImage) {
                //     $oldFilePath = $applicationImage->file_path;
                //     $image = $request->file('file');
                //     $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                //     $destinationPath = public_path('/formupload');
                //     $newFilePath = $imageName;

                //     if ($image->move($destinationPath, $imageName)) {
                //         // Update the file path in the database
                //         $applicationImage->file_path = $newFilePath;
                //         $applicationImage->save();

                //         $message = 'Successful image updated';
                //         $status = true;
                //     } else {
                //         $message = 'Failed to move the uploaded file';
                //     }
                // } else {
                //     return response()->json(['status' => false, 'message' => 'Image path not found']);
                // }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $html, 'image_path'=> $status_image_path ]);
            }
        }

        if (isset($request->applicationId) && isset($request->documentId) && isset($request->file)) {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName =  intval($user ->id). '_'.intval($request->currentImageCount).'_'.time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage = ApplicationFormImage::create(
                    ['application_id' => $request->applicationId, 'form_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                );
                $data = ApplicationFormImage::find($ApplicationImage->id) ?? '';
                $data_id = $data->id ;

                //start
                $html = '';
                $fileExtension = pathinfo('formupload/' .$ApplicationImage ->file_path, PATHINFO_EXTENSION);
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('formupload/'. $ApplicationImage ->file_path) ;

                }
                $status = $ApplicationImage ->status;
                
                $status_image_path = $this ->statusImage($status);
                
                $index = intval($request->currentImageCount) ?? 0  ;
               
                    $index ++ ;
                    
                $html .= '<div class="col-6 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox form_delete-checkbox" data-image-id="'.$ApplicationImage ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload1_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload1_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload1_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$data ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';

                $hide = '' ;
                if($role == 1){
                    $hide = 'readonly';
                }
                $html .= '<div>';
                $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $ApplicationImage ->remark . '" ' . $hide . '>';
                $html .= '<input type="hidden" name="imageData" value="' . $ApplicationImage ->id . '">';
                $html .= '</div>';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;

                // Save image path in the database if needed
                // Example: Image::create(['path' => $imageName]);

                return response()->json(['status' => true, 'data' => $data , 'image_path'=> $status_image_path ]);
            }
        }

        $data = ApplicationFormImage::where(['application_id' => $request->applicationId, 'form_id' => $request->documentId, ])->get();
    }
    public function uploadAdditionalImageGet(Request $request)
    {

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $status_image_path = '';
        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
          
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationAdditional::where([
                    'application_id' => $request->applicationId,
                    'additional_id' => $request->documentId,
                    'id' => $request->imageId,
                ])->first();

                $index = $request->index;


                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                    $status = $applicationImage->status == "Rejected" ? 'Reupload' :  $applicationImage->status;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath; //$status
                        $applicationImage->status = $status;
                        $applicationImage->save();
                        

                        $status_image_path = $this ->statusImage($status);
                        
                        // if($status == 'Rejected'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Reupload'){
                        //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Accepted'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Upload'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                            
                        // }

                        $message = 'Successful image updated';
                        $status = true;

                        $fileExtension = pathinfo('formupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                            if($fileExtension == 'pdf'){
                                $ImagePath = asset('formupload/pdf_preview.png') ;

                            }else{
                                $ImagePath =  asset('formupload/'. $applicationImage->file_path) ;

                            }

                    $html = '' ;

                    // $reject_class = '' ;
                    // if($applicationImage->status == "Rejected"){
                    //     $reject_class= 'border_red' ;
                    // }


                    $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$applicationImage->id.'" data-file-id="uploaded_file_'.$index.'">';
                    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                    $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-eye"></i></button>';
                    $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="far fa-edit"></i></button>';
                    $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-trash"></i></button>';
                    $html .= '</div>';
                    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                    $html .= '<div class="form-group">';
                    // if($role != 1){
                    //     $html .= '<div><input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_'.$index.'" placeholder="Remark" value="'.$applicationImage->remark.'">';
                    // }
                    // $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id.'">';
                    // $html .= '</div></div></div>';

                    $hide = '';
                    if($role == 1){
                        $hide = 'readonly';
                    }
                    $html .= '<div>';
                    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' .$applicationImage->remark. '" ' . $hide . '>';
                    $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id .'">';
                    $html .= '</div>';
                    $html .= '</div></div></div>';

                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                // if ($applicationImage) {
                //     $oldFilePath = $applicationImage->file_path;
                //     $image = $request->file('file');
                //     $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                //     $destinationPath = public_path('/formupload');
                //     $newFilePath = $imageName;

                //     if ($image->move($destinationPath, $imageName)) {
                //         // Update the file path in the database
                //         $applicationImage->file_path = $newFilePath;
                //         $applicationImage->save();

                //         $message = 'Successful image updated';
                //         $status = true;
                //     } else {
                //         $message = 'Failed to move the uploaded file';
                //     }
                // } else {
                //     return response()->json(['status' => false, 'message' => 'Image path not found']);
                // }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $html, 'image_path'=> $status_image_path]);
            }
        }

        if (isset($request->applicationId) && isset($request->documentId) && isset($request->file)) {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName = intval($user ->id). '_'.intval($request->currentImageCount).'_'.time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage = ApplicationAdditional::create(
                    ['application_id' => $request->applicationId, 'additional_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                );
                $data = ApplicationAdditional::find($ApplicationImage->id) ?? '';

                //start
                $html = '';
                $fileExtension = pathinfo('formupload/' .$ApplicationImage ->file_path, PATHINFO_EXTENSION);
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('formupload/'. $ApplicationImage ->file_path) ;

                }
                
                $status = $ApplicationImage->status ?? null ;
                 $status_image_path = $this ->statusImage($status);
                 
                // if($status == 'Rejected'){
                //             $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Reupload'){
                //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Accepted'){
                //     $status_image_path == '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Upload'){
                //     $status_image_path == '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                    
                // }
                
                
                $index = intval($request->currentImageCount)?? 0 + 1 ;

                $html .= '<div class="col-6 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox addition_delete_checkbox" data-image-id="'.$ApplicationImage ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload2_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload2_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload2_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';

                $hide = '' ;
                if($role == 1){
                    $hide = 'readonly';
                }
                $html .= '<div>';
                $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $ApplicationImage ->remark . '" ' . $hide . '>';
                $html .= '<input type="hidden" name="imageData" value="' . $ApplicationImage ->id . '">';
                $html .= '</div>';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;

                // Save image path in the database if needed
                // Example: Image::create(['path' => $imageName]);

                return response()->json(['status' => true, 'data' => $data, 'image_path' => $status_image_path]);
            }
        }

        // $data = ApplicationFormImage::where(['application_id' => $request->applicationId, 'form_id' => $request->documentId])->get();
    }

    public function uploadImageGet1(Request $request)
    {


        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $status_image_path = '';

        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationFormImage::where([
                    'application_id' => $request->applicationId,
                    'form_id' => $request->documentId,
                    'id' => $request->imageId,
                ])->first();

                $index = $request->index;

                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                     $status = $applicationImage->status == "Rejected" ? 'Reupload' :  $applicationImage->status;
                    // dd($applicationImage->status);
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath; //$status
                        $applicationImage->status = $status;
                        $applicationImage->save();

                         $status_image_path = $this ->statusImage($status);
                         
                        // if($status == 'Rejected'){
                        //             $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Reupload'){
                        //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Accepted'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                            
                        // }elseif($status == 'Upload'){
                        //     $status_image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                            
                        // }
                        
                        $message = 'Successful image updated';
                        $status = true;


                        $fileExtension = pathinfo('formupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                        if($fileExtension == 'pdf'){
                            $ImagePath = asset('formupload/pdf_preview.png') ;

                        }else{
                            $ImagePath =  asset('formupload/'. $applicationImage->file_path) ;

                        }

                    $html = '' ;

                    $html .= '<input type="checkbox" class="delete-checkbox form_delete-checkbox" data-image-id="'.$applicationImage->id.'" data-file-id="uploaded_file_'.$index.'">';
                    $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                    $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                    $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                    $html .= '<button id="upload1_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-eye"></i></button>';
                    $html .= '<button id="upload1_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="far fa-edit"></i></button>';
                    $html .= '<button id="upload1_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$applicationImage->id.'" data-file-type="'.$applicationImage->fileExtension.'"><i class="fas fa-trash"></i></button>';
                    $html .= '</div>';
                    $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                    $html .= '<div class="form-group">';
                    // if($role != 1){
                    //     $html .= '<div><input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_'.$index.'" placeholder="Remark" value="'.$applicationImage->remark.'">';
                    // }
                    // $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id.'">';
                    // $html .= '</div></div></div>';

                    if($role == 1){
                        $hide = 'readonly';
                    }
                    $html .= '<div>';
                    $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' .$applicationImage->remark. '" ' . $hide . '>';
                    $html .= '<input type="hidden" name="imageData" value="'.$applicationImage->id .'">';
                    $html .= '</div>';
                    $html .= '</div></div></div>';

                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $html, 'imaget_path' =>$status_image_path ]);
            }
        }

        if (isset($request->applicationId) && isset($request->documentId) && isset($request->file)) {

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage = ApplicationFormImage::create(
                    ['application_id' => $request->applicationId, 'form_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                );
                $data = ApplicationFormImage::find($ApplicationImage->id) ?? '';


                //start
                $html = '';
                $fileExtension = pathinfo('formupload/' .$ApplicationImage ->file_path, PATHINFO_EXTENSION);
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('formupload/'. $ApplicationImage ->file_path) ;

                }
                
                $status = $ApplicationImage->status ?? null ;
                $status_image_path = $this ->statusImage($status);
                
                // if($status == 'Rejected'){
                //             $status_image_path = '<img src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Reupload'){
                //   $status_image_path = '<img src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Accepted'){
                //     $status_image_path = '<img src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
                    
                // }elseif($status == 'Upload'){
                //     $status_image_path = '<img src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
                    
                // }
                
                
                
                $index = intval($request->currentImageCount)?? 0 + 1 ;

                $html .= '<div class="col-6 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox form_delete-checkbox" data-image-id="'.$ApplicationImage ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload1_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload1_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload1_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$ApplicationImage ->id.'" data-file-type="'.$ApplicationImage ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';

                $hide = '' ;
                if($role == 1){
                    $hide = 'readonly';
                }
                $html .= '<div>';
                $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $ApplicationImage ->remark . '" ' . $hide . '>';
                $html .= '<input type="hidden" name="imageData" value="' . $ApplicationImage ->id . '">';
                $html .= '</div>';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;


                return response()->json(['status' => true, 'data' => $data, 'image_path' => $status_image_path]);
            }
        }

        $data = ApplicationFormImage::where(['application_id' => $request->applicationId, 'form_id' => $request->documentId])->get();



        $html = '';
        $reject_class = '' ;
          //         if($applicationImage->status == "Rejected"){
                    //             $reject_class= 'border_red' ;
                    //         }

                    // $reject_class = 'border_red' ;

        // Loop through each item in $dynamicData
        foreach ($data as $index => $item) {
            $index++;

            $fileExtension = pathinfo('formupload/' .$item['file_path'], PATHINFO_EXTENSION);
            if($fileExtension == 'pdf'){
                $ImagePath = asset('formupload/pdf_preview.png') ;

            }else{
                $ImagePath =  asset('formupload/'. $item['file_path']) ;

            }

            $reject_class = '' ;
            if($item['status'] == "Rejected"){
                $reject_class= 'border_red' ;

            }

            $html .= '<div class="col-6 my-2 all_file_data">';
            $html .= '<form>';
            $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative '.$reject_class .'">';
            $html .= '<input type="checkbox" class="delete-checkbox form_delete-checkbox" data-image-id="'.$item['id'].'" data-file-id="uploaded_file_'.$index.'">';
            $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
            $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
            $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
            $html .= '<button id="upload1_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-eye"></i></button>';
            $html .= '<button id="upload1_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="far fa-edit"></i></button>';
            $html .= '<button id="upload1_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$item['id'].'" data-file-type="'.$item['fileExtension'].'"><i class="fas fa-trash"></i></button>';
            $html .= '</div>';
            $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
            $html .= '<div class="form-group">';

            $hide = '' ;
            if($role == 1){
                $hide = 'readonly';
            }
            $html .= '<div>';
            $html .= '<input type="text" name="input_remark" class="form-control form-control-sm" id="input_remark_' . $index . '" placeholder="Remark" value="' . $item['remark'] . '" ' . $hide . '>';
            $html .= '<input type="hidden" name="imageData" value="' . $item['id'] . '">';
            $html .= '</div>';
            $html .= '</div></div></div></form></div>';
        }

        $data = $html ;


        // $data = ApplicationFormImage::where(['application_id' => $request->applicationId, 'form_id' => $request->documentId])->get();

        // dd($data );

        return response()->json(['status' => true, 'data' => $data]);
    }

    public function uploadImageDelete(Request $request)
    {

        // $data  = ApplicationImage::where(['application_id'=> $request->applicationId,'document_id' => $request->documentId , 'id' =>$request->image_id ])->delete();
        $image = ApplicationImage::findOrFail($request->image_id);

        // Determine the path to the image file
        $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure

        // Delete the image file from the public directory
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        // Delete the image record from the database
        // $image->delete();
        return response()->json(['status' => true, 'data' => 'true']);
    }

    public function uploadImageDelete1(Request $request)
    {

        // $data  = ApplicationImage::where(['application_id'=> $request->applicationId,'document_id' => $request->documentId , 'id' =>$request->image_id ])->delete();
        $image = ApplicationFormImage::findOrFail($request->image_id);

        // Determine the path to the image file
        $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure

        // Delete the image file from the public directory
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        // Delete the image record from the database
        // $image->delete();
        return response()->json(['status' => true, 'data' => 'true']);
    }
    public function uploadImageDelete2(Request $request)
    {

        // $data  = ApplicationImage::where(['application_id'=> $request->applicationId,'document_id' => $request->documentId , 'id' =>$request->image_id ])->delete();
        $image = ApplicationAdditional::findOrFail($request->image_id);

        // Determine the path to the image file
        $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure

        // Delete the image file from the public directory
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        // Delete the image record from the database
        // $image->delete();
        return response()->json(['status' => true, 'data' => 'true']);
    }

    public function uploadImageDeletes(Request $request)
    {

        // Fetch image records based on provided IDs
        $imageIds = $request->input('image_ids', []);

        // Ensure image IDs are provided
        if (empty($imageIds)) {
            return response()->json(['status' => false, 'message' => 'No image IDs provided.']);
        }

        // Retrieve the images from the database
        $images = ApplicationImage::whereIn('id', $imageIds)->get();

        // Iterate through each image record
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path);

            // Delete the image file from the public directory if it exists
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Delete the image record from the database
            $image->delete();
        }

        return response()->json(['status' => true, 'message' => 'Images deleted successfully.']);
    }
    public function uploadFormImageGetDeletes(Request $request)
    {

        // Fetch image records based on provided IDs
        $imageIds = $request->input('image_ids', []);

        // Ensure image IDs are provided
        if (empty($imageIds)) {
            return response()->json(['status' => false, 'message' => 'No image IDs provided.']);
        }

        // Retrieve the images from the database
        $images = ApplicationFormImage::whereIn('id', $imageIds)->get();

        // Iterate through each image record
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path);

            // Delete the image file from the public directory if it exists
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Delete the image record from the database
            $image->delete();
        }

        return response()->json(['status' => true, 'message' => 'Images deleted successfully.']);
    }
    public function uploadAdditionalImageGetDeletes(Request $request)
    {

        // Fetch image records based on provided IDs
        $imageIds = $request->input('image_ids', []);

        // Ensure image IDs are provided
        if (empty($imageIds)) {
            return response()->json(['status' => false, 'message' => 'No image IDs provided.']);
        }

        // Retrieve the images from the database
        $images = ApplicationAdditional::whereIn('id', $imageIds)->get();

        // Iterate through each image record
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path);

            // Delete the image file from the public directory if it exists
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Delete the image record from the database
            $image->delete();
        }

        return response()->json(['status' => true, 'message' => 'Images deleted successfully.']);
    }

    public function remarkUpdated(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $inputNames = json_decode($request->input('inputNames'), true);

        if (!$applicationId || !$documentId || !$inputNames) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }
        // Initialize an array to hold parsed data
        $parsedData = [];

        // Iterate through each serialized string and parse it
        foreach ($inputNames as $serializedString) {
            parse_str($serializedString, $parsedArray);
            $parsedData[] = $parsedArray;
        }

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';

        if($role != 1){

            // Process each parsed form data
        foreach ($parsedData as $formData) {
                
            if(isset($formData['imageData']) && $formData['imageData']!= '' ){

                // Example: Save each form data to the database
                $model = ApplicationImage::where([
                    'application_id' => $applicationId,
                    'document_id' => $documentId,
                    'id' => $formData['imageData'],
                ])->update([
                    'remark' => $formData['input_remark'],
                ]);
                
         }
            }
        }

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }
    public function formRemarkUpdated(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $inputNames = json_decode($request->input('inputNames'), true);

        if (!$applicationId || !$documentId || !$inputNames) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }
        // Initialize an array to hold parsed data
        $parsedData = [];

        // Iterate through each serialized string and parse it
        foreach ($inputNames as $serializedString) {
            parse_str($serializedString, $parsedArray);
            $parsedData[] = $parsedArray;
        }

        // Process each parsed form data
        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';

        if($role != 1){
        foreach ($parsedData as $formData) {

            // Example: Save each form data to the database
            $model = ApplicationFormImage::where([
                'application_id' => $applicationId,
                'form_id' => $documentId,
                'id' => $formData['imageData'],
            ])->update([
                'remark' => $formData['input_remark'],
            ]);
        }
    }

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }

    public function additionalremarkUpdated(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $inputNames = json_decode($request->input('inputNames'), true);
        // dd($inputNames);

        if (!$applicationId || !$documentId || !$inputNames) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }
        // Initialize an array to hold parsed data
        $parsedData = [];

        // Iterate through each serialized string and parse it
        foreach ($inputNames as $serializedString) {
            parse_str($serializedString, $parsedArray);
            $parsedData[] = $parsedArray;
        }
        
        //  dd($parsedData);

        // Process each parsed form data
        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';

        if($role != 1){

        // Process each parsed form data
        foreach ($parsedData as $formData) {
            // dd($formData);
            
            if(isset($formData['imageData']) && $formData['imageData']!= '' ){
                
            // Example: Save each form data to the database
            $model = ApplicationAdditional::where([
                'application_id' => $applicationId,
                'additional_id' => $documentId,
                'id' => $formData['imageData'],
            ])->update([
                'remark' => $formData['input_remark'],
            ]);
            }

        }
     }

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }

    public function remarkUpdatedadmin(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $imageId = $request->input('imageId');
        $radio = $request->input('radio');
        $admin_remark = $request->input('admin_remark');
        $image_path = '';

        $data = [
            'admin_remark' => $admin_remark,
        ];

        // Check if 'radio' input exists and is not empty
        if ($request->has('radio') && !empty($request->input('radio'))) {
            $radio = $request->input('radio');
            $data['status'] = $radio;
            
            $image_path = $this->statusImage($data['status'] );

        }

        if (!$applicationId || !$documentId || !$imageId ) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }

        // Example: Save each form data to the database
        $model = ApplicationImage::where([
            'application_id' => $applicationId,
            'document_id' => $documentId,
            'id' => $imageId,
        ])->update($data);

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true, 'image_path'=> $image_path]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }

    public function remarkUpdatedadmin2(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $imageId = $request->input('imageId');
        $radio = $request->input('radio');
        $admin_remark = $request->input('admin_remark');
        $image_path = '';

        $data = [
            'admin_remark' => $admin_remark,
        ];

        // Check if 'radio' input exists and is not empty
        if ($request->has('radio') && !empty($request->input('radio'))) {
            $radio = $request->input('radio');
            $data['status'] = $radio;
             $image_path = $this->statusImage($data['status'] );
        }

        if (!$applicationId || !$documentId || !$imageId ) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }

        // Example: Save each form data to the database
        $model = ApplicationAdditional::where([
            'application_id' => $applicationId,
            'additional_id' => $documentId,
            'id' => $imageId,
        ])->update($data);

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true, 'image_path'=> $image_path]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }
    public function formRemarkUpdatedAdmin(Request $request)
    {


        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $imageId = $request->input('imageId');
        $radio = $request->input('radio');
        $admin_remark = $request->input('admin_remark');
        $image_path = '';

        if (!$applicationId || !$documentId || !$imageId) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }

        $data = [
            'admin_remark' => $admin_remark,
        ];

         // Check if 'radio' input exists and is not empty
         if ($request->has('radio') && !empty($request->input('radio'))) {
            $radio = $request->input('radio');
            $data['status'] = $radio;
             $image_path = $this->statusImage($data['status'] );
        }

        if (!$applicationId || !$documentId || !$imageId ) {
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data' => false]);
        }

        // Example: Save each form data to the database
        $model = ApplicationFormImage::where([
            'application_id' => $applicationId,
            'form_id' => $documentId,
            'id' => $imageId,
        ])->update($data);

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data' => true, 'image_path' => $image_path]);

        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

    }

    public function documenUploadPreview(Request $request){


        $data = ApplicationImage::where(['application_id' => $request->appId, 'document_id' => $request->documentId, 'id'=>$request->imageId])->first();

        if(!$data){
            return response()->json(['status' => false, 'data' => $data]) ;
        }

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $html = '';
        $file_path = explode('.',$data->file_path)[1] ?? '' ;

            if($file_path == 'pdf'){

                $ImagePath =  asset('formupload/'. $data->file_path) ;
               $html .= ' <iframe id="pdfViewer"  src="' . $ImagePath  . '" width="100%" height="100%"></iframe>' ;

            }else{
                $ImagePath =  asset('formupload/'. $data->file_path) ;
                $html .= '<img src="' . $ImagePath  . '" style="width: 100%; height: auto;">';

            }

            $html .= '<div class="container-fluid" id="new_added_content_' . $data->id . '">';
            if( $role == 1){
                // Pane section with radio buttons
                $html .= '<div class="pane m-3">';
                // $html .= '<label class="label"><span>None</span><input id="idk_' . $data->id . '" class="input idk" name="radio" type="radio"' . ($data->status == '' ? 'checked="checked"' : '') .  '  value=""></label>';
                $html .= '<label class="label"><span>Accept</span><input id="yes_' . $data->id . '" class="input yes" name="radio" type="radio"' . ($data->status == 'Verified' ? 'checked="checked"' : '') .  ' value="Verified"></label>';
                $html .= '<label class="label"><span>Reject</span><input id="no_' . $data->id . '" class="input no" name="radio" type="radio" ' . ($data->status == 'Rejected' ? 'checked="checked"' : '') .  ' value="Rejected"></label>';
                $html .= '<span class="preview_selection"></span>';
                $html .= '</div>';

                // Admin remark input section
                $html .= '<div class="extra-input m-3" id="extra-input_' . $data->id . '">';
                $html .= '<input type="text" name="admin_remark" id="extra-text_' . $data->id . '" class="form-control extra_input" placeholder="Reason" value="' . $data->admin_remark . '">';
                $html .= '</div>';
            }else if($role != 1 && $data->status == "Rejected" ){

                // Admin remark input section
                $html .= '<div class="extra-input m-3" id="extra-input_' . $data->id . '">';
                $html .= '<input type="text" name="admin_remark" id="extra-text_' . $data->id . '" class="form-control extra_input" placeholder="Reason" value="' . $data->admin_remark . '" readonly>';
                $html .= '</div>';

            }
            $html .= '</div>'; // Closing container-fluid


        $data = $html ;

        return response()->json(['status' => true, 'data' => $data]);


    }
    public function formUploadPreview(Request $request){


        $data = ApplicationFormImage::where(['application_id' => $request->appId, 'form_id' => $request->documentId , 'id' => $request->imageId ])->first();

        if(!$data){
            return response()->json(['status' => false, 'data' => $data]) ;
        }

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $html = '';
        $file_path = explode('.',$data->file_path)[1] ?? '' ;

            if($file_path == 'pdf'){

                $ImagePath =  asset('formupload/'. $data->file_path) ;
               $html .= ' <iframe id="pdfViewer"  src="' . $ImagePath  . '" width="100%" height="100%"></iframe>' ;

            }else{
                $ImagePath =  asset('formupload/'. $data->file_path) ;
                $html .= '<img src="' . $ImagePath  . '" style="width: 100%; height: auto;">';

            }

            $html .= '<div class="container-fluid" id="new_added_content_' . $data->id . '">';
            if( $role == 1){
                // Pane section with radio buttons
                $html .= '<div class="pane m-3">';
                // $html .= '<label class="label"><span>Nonek</span><input id="idk_' . $data->id . '" class="input idk" name="radio" type="radio"' . ($data->status == '' ? 'checked="checked"' : '') .  '  value=""></label>';
                $html .= '<label class="label"><span>Accept</span><input id="yes_' . $data->id . '" class="input yes" name="radio" type="radio"' . ($data->status == 'Verified' ? 'checked="checked"' : '') .  ' value="Verified"></label>';
                $html .= '<label class="label"><span>Reject</span><input id="no_' . $data->id . '" class="input no" name="radio" type="radio" ' . ($data->status == 'Rejected' ? 'checked="checked"' : '') .  ' value="Rejected"></label>';
                $html .= '<span class="preview_selection"></span>';
                $html .= '</div>';

                // Admin remark input section
                $html .= '<div class="extra-input m-3" id="extra-input_' . $data->id . '">';
                $html .= '<input type="text" name="admin_remark" id="extra-text_' . $data->id . '" class="form-control extra_input" placeholder="Reason" value="' . $data->admin_remark . '">';
                $html .= '</div>';
            }
            $html .= '</div>'; // Closing container-fluid


        $data = $html ;

        return response()->json(['status' => true, 'data' => $data]);


    }
    public function AdditioanlUploadPreview(Request $request){


        $data = ApplicationAdditional::where(['application_id' => $request->appId, 'additional_id' => $request->documentId, 'id' => $request->imageId])->first();

        if(!$data){
            return response()->json(['status' => false, 'data' => $data]) ;
        }

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $html = '';
        $file_path = explode('.',$data->file_path)[1] ?? '' ;

            if($file_path == 'pdf'){

                $ImagePath =  asset('formupload/'. $data->file_path) ;
               $html .= ' <iframe id="pdfViewer"  src="' . $ImagePath  . '" width="100%" height="100%"></iframe>' ;

            }else{
                $ImagePath =  asset('formupload/'. $data->file_path) ;
                $html .= '<img src="' . $ImagePath  . '" style="width: 100%; height: auto;">';

            }

            $html .= '<div class="container-fluid" id="new_added_content_' . $data->id . '">';
            if( $role == 1){
                // Pane section with radio buttons
                $html .= '<div class="pane m-3">';
                // $html .= '<label class="label"><span>None</span><input id="idk_' . $data->id . '" class="input idk" name="radio" type="radio"' . ($data->status == '' ? 'checked="checked"' : '') .  '  value=""></label>';
                $html .= '<label class="label"><span>Accept</span><input id="yes_' . $data->id . '" class="input yes" name="radio" type="radio"' . ($data->status == 'Verified' ? 'checked="checked"' : '') .  ' value="Verified"></label>';
                $html .= '<label class="label"><span>Reject</span><input id="no_' . $data->id . '" class="input no" name="radio" type="radio" ' . ($data->status == 'Rejected' ? 'checked="checked"' : '') .  ' value="Rejected"></label>';
                $html .= '<span class="preview_selection"></span>';
                $html .= '</div>';

                // Admin remark input section
                $html .= '<div class="extra-input m-3" id="extra-input_' . $data->id . '">';
                $html .= '<input type="text" name="admin_remark" id="extra-text_' . $data->id . '" class="form-control extra_input" placeholder="Reason" value="' . $data->admin_remark . '">';
                $html .= '</div>';
            }
            $html .= '</div>'; // Closing container-fluid


        $data = $html ;

        return response()->json(['status' => true, 'data' => $data]);


    }
    
    public function uploadDocumentDelete(Request $request)
    {
        // Validate the request
        $request->validate([
            'applicationId' => 'required|integer',
            'documentId' => 'required|integer',
        ]);
    
        $applicationId = $request->applicationId;
        $documentId = $request->documentId;
    
        // Find the application record
        $application = Application::find($applicationId);
    
        if (!$application) {
            return response()->json(['status' => false, 'message' => 'Application not found']);
        }
    
        // Detach related records with the specific applicationId and documentId
        $application->applicationLoanDocument2()->detach($documentId);
        $application->applicantDocument1()->detach($documentId);
    
        // Find all images associated with the given applicationId and documentId
        $images = ApplicationImage::where('application_id', $applicationId)
                                       ->where('document_id', $documentId)
                                       ->get();
                                    
    if(count($images)> 0){
        
   
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure
    
            // Delete the image file from the public directory
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
    
            // Delete the image record from the database
            $image->delete();
        }
        
    }
    
        return response()->json(['status' => true, 'message' => 'All images and related records deleted successfully']);

    }
    
     public function uploadFormDelete(Request $request)
    {
        // Validate the request
        $request->validate([
            'applicationId' => 'required|integer',
            'documentId' => 'required|integer',
        ]);
    
        $applicationId = $request->applicationId;
        $documentId = $request->documentId;
    
        // Find the application record
        $application = Application::find($applicationId);
    
        if (!$application) {
            return response()->json(['status' => false, 'message' => 'Application not found']);
        }
    
        // Detach related records with the specific applicationId and documentId
        $application->applicationLoanFormUpload2()->detach($documentId);
        $application->applicantformUpload2()->detach($documentId);
    
        // Find all images associated with the given applicationId and documentId
        $images = ApplicationFormImage::where('application_id', $applicationId)
                                       ->where('form_id', $documentId)
                                       ->get();
                                    
    if(count($images)> 0){
        
   
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure
    
            // Delete the image file from the public directory
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
    
            // Delete the image record from the database
            $image->delete();
        }
        
    }
    
        return response()->json(['status' => true, 'message' => 'All images and related records deleted successfully']);

    }
    
    public function uploadAdditionalDocumentDelete(Request $request){
        
         // Validate the request
        $request->validate([
            'applicationId' => 'required|integer',
            'documentId' => 'required|integer',
        ]);
    
        $applicationId = $request->applicationId;
        $documentId = $request->documentId;
    
        // Find the application record
        $application = Application::find($applicationId);
    
        if (!$application) {
            return response()->json(['status' => false, 'message' => 'Application not found']);
        }
    
        // Detach related records with the specific applicationId and documentId
        $application->additionalDocument2()->detach($documentId);
        
    
        // Find all images associated with the given applicationId and documentId
        $images = ApplicationAdditional::where('application_id', $applicationId)
                                       ->where('additional_id', $documentId)
                                       ->get();
                                    
    if(count($images)> 0){
        
   
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('formupload/' . $image->path); // Adjust the path based on your structure
    
            // Delete the image file from the public directory
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
    
            // Delete the image record from the database
            $image->delete();
        }
        
    }
    
        return response()->json(['status' => true, 'message' => 'All images and related records deleted successfully']);
        
    }
    
    private function statusImage($status){
        
        $image_path = '';
        if($status == 'Rejected'){
            $image_path = '<img title="Rejected" src="' . asset('formupload/file_cancel.png') . '" class="document_status_img">';
                            
        }elseif($status == 'Reupload'){
           $image_path = '<img title="Reuploaded" src="' . asset('formupload/stage_reupload.png') . '" class="document_status_img">';
            
        }elseif($status == 'Accepted'){
            $image_path = '<img title="Accepted" src="' . asset('formupload/file_success.png') . '" class="document_status_img">';
            
        }elseif($status == 'Upload'){
            $image_path = '<img title="Uploaded" src="' . asset('formupload/file_uploaded.png') . '" class="document_status_img">';
            
        }
        return $image_path ;
                        
    }
    
    
    // uploadFormDelete
}
