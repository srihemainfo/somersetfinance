<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Agreement,
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

class PartnerEdgeController extends Controller
{

    public function index(Request $request)
    {
        
        
        $name = '';
        $user = '';

        return view('admin.edges.partner', compact('name','user'));

        if ($request->ajax()) {
            
            
            
          $loan_type = $request->has('Loan_type_filter') ? $request->get('Loan_type_filter') : '' ;
          $customer_name_filter = $request->has('customer_name_filter') ? $request->get('customer_name_filter') : '' ;
          $status_filter = $request->has('status_filter') ? $request->get('status_filter') : '' ;
         

            $userId = auth()->user()->id;
            $user = \App\Models\User::find($userId);
            $assignedRole = $user ? $user->roles->first() : null;
            $roleTitle = $assignedRole ? $assignedRole->id : 0;
            
            $query = EnquiryList::whereNotNull('loan_category_type')
            ->whereNotNull('client_loan_amount')
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
            }
            
            $table = Datatables::of($query);
            $table->editColumn('loan_category_type', function ($row) {
                
            return $row->loan_category_type ? ($row->loan_types->title ?? '') : '';
                
            });
            
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            
             $table->editColumn('created_by', function ($row) {
                
                return  $row->created_by ? $row->user->name :'';
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
      $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
      
        return view('admin.enquiry_list.index', compact('loan_types'));
    }
    
    public function show($request){
        
       
            $strpos = strpos($request, '(');
            if ($strpos < 5) {
                return back()->withErrors('Given Data Not Valid');
            }
            $explode = explode('(', $request);
            $staff_email = trim(substr($explode[1], 0, -1));
         
            $user = User::where('email', $staff_email)->first();
            
            if ($user == null || $user == '') {
                return back()->withErrors('Partner Not Found');
            }
            $name = $request;
            
        
        return view('admin.edges.partner', compact('user', 'name'));
    }
    
    public function edit($id)
    {
        $enquiry = EnquiryList::findOrFail($id);
        
        if($enquiry ){
            
        if($enquiry-> status == 'Underwritting'){
            
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
        return view('admin.enquiry_list.create',compact('isEditable','loan_types' ));
    }

 

    public function store(Request $request)
    {

          if($request->loan_category != 'broker' && $request->loan_category != 'introducer' ){
              return  response()->json(['status' => false,'message'=> 'given Details Invalid']);
          }
          
          $enquiry = new EnquiryList();
          
          
          if($request->loan_category == 'broker'){
              
             $enquiry->mortgage_status = $request->mortgage_status ?? null;
            //  $enquiry->loan_type = $request->loan_type ?? null;
             $enquiry->purpose_loan= $request->purpose_loan ?? null;
             $enquiry->application_made= $request->application_made ?? null;
             $enquiry->loan_amount = $request->loan_amount ?? null;
             $enquiry->term_year = $request->term_year ?? null;
             $enquiry->term_month = $request->term_month ?? null;
             $enquiry->live_or_intent_property = $request->live_or_intent_property ?? null;
              
          }
          
         
         $enquiry-> loan_category = $request->loan_category ?? null;
         $enquiry->loan_category_type = $request->loan_category_type ?? null;
         $enquiry->client_first_name = $request->client_first_name ?? null;
         $enquiry->client_last_name = $request->client_last_name ?? null;
         $enquiry->client_email = $request->client_email ?? null;
         $enquiry->client_phone = $request->client_phone ?? null;
         $enquiry->client_loan_amount = $request->client_loan_amount ?? null;
         $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
         $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
         $enquiry->created_by = Auth::user()->id ?? null ;
         $enquiry->save();
         
         
          return response()->json(['status' => true]);
    }
    
    public function update(Request $request){
        
        // dd($request->all());
        
        $id = $request->id;
        
        if($request->loan_category != 'broker' && $request->loan_category != 'introducer' ){
              return  response()->json(['status' => false,'message'=> 'given Details Invalid']);
          }
          
          $enquiry = EnquiryList::find($request->id);
          
          if(! $enquiry){
               return  response()->json(['status' => false,'message'=> 'Enquiry list Not found']);
          }
          
          
          if($request->loan_category == 'broker'){
              
             $enquiry->mortgage_status = $request->mortgage_status ?? null;
            //  $enquiry->loan_type = $request->loan_type ?? null;
             $enquiry->purpose_loan= $request->purpose_loan ?? null;
             $enquiry->application_made= $request->application_made ?? null;
             $enquiry->loan_amount = $request->loan_amount ?? null;
             $enquiry->term_year = $request->term_year ?? null;
             $enquiry->term_month = $request->term_month ?? null;
             $enquiry->live_or_intent_property = $request->live_or_intent_property ?? null;
              
          }else{
              
                $enquiry->mortgage_status =  null;
                //  $enquiry->loan_type = $request->loan_type ?? null;
                 $enquiry->purpose_loan=  null;
                 $enquiry->application_made=  null;
                 $enquiry->loan_amount =  null;
                 $enquiry->term_year =  null;
                 $enquiry->term_month =  null;
                 $enquiry->live_or_intent_property =  null;
              
          }
          
         
         $enquiry-> loan_category = $request->loan_category ?? null;
         $enquiry->loan_category_type = $request->loan_category_type ?? null;
         $enquiry->client_first_name = $request->client_first_name ?? null;
         $enquiry->client_last_name = $request->client_last_name ?? null;
         $enquiry->client_email = $request->client_email ?? null;
         $enquiry->client_phone = $request->client_phone ?? null;
         $enquiry->client_loan_amount = $request->client_loan_amount ?? null;
         $enquiry->client_propertity_value = $request->client_propertity_value ?? null;
         $enquiry->client_extra_comment = $request->client_extra_comment ?? null;
         $enquiry->updated_by = Auth::user()->id ?? null ;
         $enquiry->status = "Underwritting";
         $enquiry->save();
         
         $customer_check  = CustomDetail::where('email',$enquiry->client_email)->first();
         
            if(!$customer_check ){
                $customer_check = CustomDetail::Create(
                                    [
                                        'email' => $enquiry->client_email,
                                        'name' => $enquiry->client_first_name,
                                        'last_name' => $enquiry->client_last_name,
                                        'phone' => $enquiry->client_phone,
                                    ]
                                );
            }
         
        //  $customer_check = CustomDetail::firstOrCreate(
        //                         ['email' => $enquiry->client_email],
        //                         [
        //                             'name' => $enquiry->client_first_name,
        //                             'last_name' => $enquiry->client_last_name,
        //                             'phone' => $enquiry->client_phone,
        //                         ]
        //                     );
                            
                            $application = new Application();
                            $application->loan_type_id = $request->loan_category_type ?? null ;
                            $application->customer_id = $customer_check->id ?? null;
                            $application->enquiry_id = $id ?? null;
                            $application->save();
                            
                            $application_ref_no = 'APP' . sprintf('%05d', $application->id);
                            $application->ref_no = $application_ref_no  ?? null;
                            $application-> loan_category = $request->loan_category ?? null;
                            $application->client_loan_amount = $request->client_loan_amount ?? null;
                            $application->client_propertity_value = $request->client_propertity_value ?? null;
                            $application->client_extra_comment = $request->client_extra_comment ?? null;
                            
                            if($request->loan_category == 'broker'){
              
                                 $application->mortgage_status = $request->mortgage_status ?? null;
                                //  $enquiry->loan_type = $request->loan_type ?? null;
                                 $application->purpose_loan= $request->purpose_loan ?? null;
                                 $application->application_made= $request->application_made ?? null;
                                 $application->loan_amount = $request->loan_amount ?? null;
                                 $application->term_year = $request->term_year ?? null;
                                 $application->term_month = $request->term_month ?? null;
                                 $application->live_or_intent_property = $request->live_or_intent_property ?? null;
                                  
                              }else{
                                  
                                    $application->mortgage_status =  null;
                                    //  $enquiry->loan_type = $request->loan_type ?? null;
                                     $application->purpose_loan=  null;
                                     $application->application_made=  null;
                                     $application->loan_amount =  null;
                                     $application->term_year =  null;
                                     $application->term_month =  null;
                                     $application->live_or_intent_property =  null;
                                  
                              }
                            
                            
                            $application->save();
                            
                            
                            $enquiry->application_id = $application->id ?? null ;
                            $enquiry->save();
                            
                             
                            
                            // Application::create 
                            $redirect_url = route('admin.application-stage.edit', $application->id);
                            
                            
                            
                           
                
          return response()->json(['status' => true, 'data'=>  $redirect_url ]);
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
    
    public function geter(Request $request){
        
        //  $staff = User::select('name', 'email')->get();
        

        // $array = [];

        // if ($staff->count() > 0) {
        //     for ($i = 0; $i < count($staff); $i++) {
        //         array_push($array, $staff[$i]->name . '  ( ' . $staff[$i]->email . ')');
        //     }
        // }
       

        // return response()->json(['staff' => $array]);
        
        
        $search = $request->search;

        if ($search == '') {
            $clients = User::orderby('name', 'asc')->select('id', 'name', 'email')->distinct()->limit(6)->get();
        } else {
            $clients = User::orderby('name', 'asc')->select('id', 'name', 'email')->where('name', 'like', '%' . $search . '%')->distinct()->limit(6)->get();
        }

        $response = [];
        foreach ($clients as $client) {
            $response[] = array(
                "id" => $client->name . '(' . $client->email . ')' ,
                "text" => $client->name . '(' . $client->email . ')',
            );
        }
        return response()->json($response);
        
    }
    
    
    public function agreements(Request $request){
        
        
        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $status_image_path = '';

        if (isset($request->file) && isset($request->id)  && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = Agreement::where([
                    'user_id' => $request->id,
                    'id' => $request->imageId,
                ])->first();

                $index = intval($request->index);
                
                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                //   $status = $applicationImage->status == "Rejected" ? 'Reupload' : $applicationImage->status ;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/fileupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath;
                        // $applicationImage->status = $status;
                        $applicationImage->save();
                        
                        // $status_image_path = $this ->statusImage($status);
                        
                        $message = 'Successful image updated';
                        $status = true;


                        $fileExtension = pathinfo('fileupload/' .$applicationImage->file_path, PATHINFO_EXTENSION);
                        
                            $doc = ['doc', 'docx'];
                        
                            if(in_array($fileExtension  ,$doc)){
                                $ImagePath = asset('formupload/word_preview.png') ;
                                
                            }else
                            if($fileExtension == 'pdf'){
                                $ImagePath = asset('formupload/pdf_preview.png') ;

                            }else{
                                $ImagePath =  asset('fileupload/'. $applicationImage->file_path) ;

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

        if (isset($request->id)  && isset($request->file)) {

            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName =  intval($user ->id). '_'.intval($request->currentImageCount).'_'.time() . '.' . $image->getClientOriginalExtension();
               
                $destinationPath = public_path('/fileupload');
                $image->move($destinationPath, $imageName);

                $Agreement = Agreement::create(
                    ['user_id' => $request->id,  'file_path' => $imageName, 'status' => 'Upload']
                );
                
                $data = Agreement::find($Agreement->id) ?? '';


                //start
                $html = '';
                $fileExtension = pathinfo('fileupload/' .$Agreement ->file_path, PATHINFO_EXTENSION);
                 $doc = ['doc', 'docx'];
            
                if(in_array($fileExtension  ,$doc)){
                    $ImagePath = asset('formupload/word_preview.png') ;
                    
                }else
                if($fileExtension == 'pdf'){
                    $ImagePath = asset('formupload/pdf_preview.png') ;

                }else{
                    $ImagePath =  asset('fileupload/'. $Agreement ->file_path) ;

                }
                
                
                
                 $status = $Agreement->status ?? null ;
                 $status_image_path = $this ->statusImage($status);
               
                
                
                
                $index = intval($request->currentImageCount) ?? 0  ;
                   $index ++ ;

                $html .= '<div class="col-12 col-md-3 col-sm-6 col-xl-3 my-2 all_file_data">';
                $html .= '<form>';
                $html .= '<div id="uploaded_file_'.$index.'" class="uploaded_file position-relative">';
                $html .= '<input type="checkbox" class="delete-checkbox" data-image-id="'.$Agreement ->id.'" data-file-id="uploaded_file_'.$index.'">';
                $html .= '<input type="hidden" name="uploadform" value="uploaded_file_'.$index.'">';
                $html .= '<img src="'.$ImagePath.'" style="max-width: 100%; height: 100%;">';
                $html .= '<div id="upload_file_button_'.$index.'" class="d-flex justify-content-center upload_file_button">';
                $html .= '<button id="upload_preview_'.$index.'" class="btn btn-outline-primary btn-sm" title="Preview" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'" data-file-id="uploaded_file_'.$index.'" data-image-id="'.$Agreement ->id.'" data-file-type="'.$Agreement ->fileExtension.'"><i class="fas fa-eye"></i></button>';
                $html .= '<button id="upload_change_'.$index.'" class="btn btn-outline-warning btn-sm" title="Change" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$Agreement ->id.'" data-file-type="'.$Agreement ->fileExtension.'"><i class="far fa-edit"></i></button>';
                $html .= '<button id="upload_delete_'.$index.'" class="btn btn-outline-danger btn-sm" title="Delete" data-app-id ="' .$request->applicationId .'" data-document-id ="' .$request->documentId .'"  data-file-id="uploaded_file_'.$index.'" data-image-id="'.$Agreement ->id.'" data-file-type="'.$Agreement ->fileExtension.'"><i class="fas fa-trash"></i></button>';
                $html .= '</div>';
                $html .= '<div id="upload_remark_'.$index.'" class="upload_remark">';
                $html .= '<div class="form-group">';
                $html .= '</div></div></div></form></div>';
                //end
                $data = $html;


                return response()->json(['status' => true, 'data' => $data, 'image_path' => $status_image_path]);
            }
        }

        $data = Agreement::where(['user_id' => $request->id]) ;
        
        $data = $data ->get();
        

        $html = '';


        // Loop through each item in $dynamicData
        foreach ($data as $index => $item) {
            $index++;

            $fileExtension = pathinfo('fileupload/' .$item['file_path'], PATHINFO_EXTENSION);
             $doc = ['doc', 'docx'];
                        
            if(in_array($fileExtension  ,$doc)){
                $ImagePath = asset('formupload/word_preview.png') ;
                
            }else
            if($fileExtension == 'pdf'){
                $ImagePath = asset('formupload/pdf_preview.png') ;

            }else{
                $ImagePath =  asset('fileupload/'. $item['file_path']) ;

            }
            $reject_class = '' ;
            $html .= '<div class="col-12 col-md-3 col-sm-6 col-xl-3 my-2 all_file_data">';
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
            $html .= '</div></div></div></form></div>';
        }

        $data = $html ;



        return response()->json(['status' => true, 'data' => $data]);
        
        
         
    
    }
    
     private function statusImage($status){
        
        $image_path = '';
        if($status == 'Rejected'){
            $image_path = '<img title="Rejected" src="' . asset('fileupload/file_cancel.png') . '" class="document_status_img">';
                            
        }elseif($status == 'Reupload'){
           $image_path = '<img title="Reuploaded" src="' . asset('fileupload/stage_reupload.png') . '" class="document_status_img">';
            
        }elseif($status == 'Verified'){
            $image_path = '<img title="Accepted" src="' . asset('fileupload/file_success.png') . '" class="document_status_img">';
            
        }elseif($status == 'Upload'){
            $image_path = '<img title="Uploaded" src="' . asset('fileupload/file_uploaded.png') . '" class="document_status_img">';
            
        }
        return $image_path ;
                        
    }
    
     public function filedelete(Request $request)
    {
        
        

        $image = Agreement::findOrFail($request->image_id);

        // Determine the path to the image file
        $imagePath = public_path('filepload/' . $image->file_path); // Adjust the path based on your structure

        // Delete the image file from the public directory
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the image record from the database
        $image->forceDelete();

        // Delete the image record from the database
        // $image->delete();
        return response()->json(['status' => true, 'data' => 'true']);
    }
    
     public function filedeletes(Request $request)
    {

        // Fetch image records based on provided IDs
        $imageIds = $request->input('image_ids', []);

        // Ensure image IDs are provided
        if (empty($imageIds)) {
            return response()->json(['status' => false, 'message' => 'No image IDs provided.']);
        }

        // Retrieve the images from the database
        $images = Agreement::whereIn('id', $imageIds)->get();

        // Iterate through each image record
        foreach ($images as $image) {
            // Determine the path to the image file
            $imagePath = public_path('fileupload/' . $image->path);

            // Delete the image file from the public directory if it exists
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // Delete the image record from the database
            $image->forceDelete();
        }

        return response()->json(['status' => true, 'message' => 'Images deleted successfully.']);
    }
    
    public function UploadPreview(Request $request){


        $data = Agreement::where(['user_id' => $request->id, 'id'=>$request->imageId])->first();

        if(!$data){
            return response()->json(['status' => false, 'data' => $data]) ;
        }

        $user = Auth::user()  ??  ''  ;
        $role = $user  ?  ($user->roles[0]->id ?? '' ): '';
        $html = '';
        $file_path = explode('.',$data->file_path)[1] ?? '' ;
        
            $doc = ['doc', 'docx'];
                        
            if(in_array($file_path  ,$doc)){
                $ImagePath = asset('fileupload/' . $data->file_path);
                
                $html .= '<iframe id="pdfViewer" src="https://docs.google.com/gview?url=' . $ImagePath . '&embedded=true" width="100%" height="100%"></iframe>';
                
            }else
            if($file_path == 'pdf'){

                $ImagePath =  asset('fileupload/'. $data->file_path) ;
               $html .= ' <iframe id="pdfViewer"  src="' . $ImagePath  . '" width="100%" height="100%"></iframe>' ;

            }else{
                $ImagePath =  asset('fileupload/'. $data->file_path) ;
                $html .= '<img src="' . $ImagePath  . '" style="width: 100%; height: auto;">';

            }

            
            $data = $html ;

         return response()->json(['status' => true, 'data' => $data]);


    }
    
    

  

   

 

 

    

    
  
}
