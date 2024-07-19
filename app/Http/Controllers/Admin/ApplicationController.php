<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\{Validator, DB};
use Illuminate\Validation\Rule;
use App\Common;
use Illuminate\Support\Str;
use App\Models\{
    LoanType,
    CustomDetail,
    Brand,
    FormUpload,
    DocumentType,
    Application,
    CoApplicant,
    ApplicationCoapplicant,
    ApplicationDocument,
    ApplicationLoanDocument,
    ApplicationFormUpload,
    ApplicationLoanFormUpload,
    AdditionalDocument,
    ApplicationAdditional,
    ApplicationImage
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;



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
        // LoanType::whereNotNull('title')->pluck('title', 'id');
        $loan_types = LoanType::pluck('title', 'id')->prepend('Select LoanType', '');
        // $document_types = DocumentType::whereNotNull('title')->pluck('title', 'id')->prepend('Select Document Type', ' ') ;
        // $formuploades = FormUpload::whereNotNull('title')->pluck('title', 'id')->prepend('Select Form Upload', ' ') ;

        return view('admin.application.index', compact('isEditable', 'loan_types',));
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

    public function store(Request $request)
    {


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

        $applicant =  Application::create([
            'customer_id' => $customer_id,
            'loan_type_id' => $loan_type_id
        ]);
        // dd($applicant);

        if ($applicant) {

            $id = $applicant->id;


            // for($si=0; $si < count($co_applicants); $si++ ){
            //  $check_co_applicant =   CoApplicant::where(['name'=> $co_applicants[$si],'email'=> $co_applicants_emails[$si], 'phone'=>$co_applicants_phones[$si], 'address'=>$co_applicants_addresss[$si]])->first();
            //     if( !$check_co_applicant){
            //         $check_co_applicant =  CoApplicant::create(['name'=> $co_applicants[$si],'email'=> $co_applicants_emails[$si], 'phone'=>$co_applicants_phones[$si], 'address'=>$co_applicants_addresss[$si]]);
            //     }
            //     $check_ApplicationCoapplicant= ApplicationCoapplicant::where(['application_detail_id' => $id, 'co_applicant_id'=> $check_co_applicant->id])->first();
            //     if(!$check_ApplicationCoapplicant){
            //         ApplicationCoapplicant::create(['application_detail_id' => $id, 'co_applicant_id'=> $check_co_applicant->id]);
            //     }
            // }
            for ($si = 0; $si < count($co_applicants); $si++) {
                $check_co_applicant = CoApplicant::firstOrCreate(
                    [
                        'name' => $co_applicants[$si],
                        'email' => $co_applicants_emails[$si],
                        'phone' => $co_applicants_phones[$si],
                        'address' => $co_applicants_addresss[$si]
                    ]
                );

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
                // dd('test');

                // foreach($document_features as $document_feature){

                //     $check_Documents= ApplicationLoanDocument::where(['application_id' => $id, 'document_id'=> $document_feature])->first();
                //     if(!$check_Documents){
                //         ApplicationLoanDocument::create(['application_id' => $id, 'document_id'=> $document_feature]);
                //     }

                // }
            }

            // without loan_type_id
            if (isset($document_type_ids) && count($document_type_ids) > 0) {

                $applicant->applicantDocument1()->detach($document_type_ids);
                $applicant->applicantDocument1()->attach($document_type_ids);
                // dd('test11');


                // foreach($document_type_ids as $document_type_id){
                //     // dd($id);

                //     $check_Documents= ApplicationDocument::where(['application_id' => $id, 'document_id'=> $document_type_id])->first();
                //     // dd($check_Documents);
                //     if(!$check_Documents){
                //         ApplicationDocument::create(['application_id' => $id, 'document_id'=> $document_type_id]);
                //     }

                // }
            }


            if (isset($uploadForm_features) && count($uploadForm_features) > 0) {

                $applicant->applicationLoanFormUpload2()->detach($uploadForm_features);
                $applicant->applicationLoanFormUpload2()->attach($uploadForm_features);

                // foreach($uploadForm_features as $form_upload_feature){

                //     $check_Documents= ApplicationLoanFormUpload::where(['application_id' => $id, 'form_upload_id'=> $form_upload_feature])->first();
                //     if(!$check_Documents){
                //         ApplicationLoanFormUpload::create(['application_id' => $id, 'form_upload_id'=> $form_upload_feature]);
                //     }

                // }
            }

            // without loan_type_id
            if (isset($form_upload_ids) && count($form_upload_ids) > 0) {


                $applicant->applicantformUpload2()->detach($form_upload_ids);
                $applicant->applicantformUpload2()->attach($form_upload_ids);


                // foreach($form_upload_ids as $form_upload_type_id){
                //     // dd($id);

                //     $check_Documents= ApplicationFormUpload::where(['application_id' => $id, 'form_upload_id'=> $form_upload_type_id])->first();
                //     // dd($check_Documents);
                //     if(!$check_Documents){
                //         // dd($id);
                //         ApplicationFormUpload::create(['application_id' => $id, 'form_upload_id'=> $form_upload_type_id]);
                //     }

                // }
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



            // if(isset($addition_documents ) && count($addition_documents) > 0){

            //     foreach($addition_documents as $addition_document){

            //         $check_co_additional_doucment =   AdditionalDocument::where(['title'=> $addition_document])->first();
            //             if( !$check_co_additional_doucment){
            //                 $check_co_additional_doucment =  AdditionalDocument::create(['title'=> $addition_document]);
            //             }
            //             // dd($check_co_additional_doucment->id);
            //             $check_applicationAdditioal= ApplicationAdditional::where(['application_id' => $id, 'additional_id'=> $check_co_additional_doucment->id])->first();
            //             if(!$check_applicationAdditioal){

            //                 ApplicationAdditional::create(['application_id' => $id, 'additional_id'=> $check_co_additional_doucment->id]);
            //             }


            //         // dd($id);

            //         // $check_Documents= ApplicationFormUpload::where(['application_id' => $id, 'form_upload_id'=> $form_upload_type_id])->first();
            //         // // dd($check_Documents);
            //         // if(!$check_Documents){
            //         //     ApplicationFormUpload::create(['application_id' => $id, 'form_upload_id'=> $form_upload_type_id]);
            //         // }

            //     }

            // }



            $id = $applicant->id;
            $application_id = 'APP' . sprintf('%05d', $id);
            $applicant->ref_no = $application_id;
            $applicant->save();

            $message = "Case Create Successful and application id is " . $application_id;

            return response()->json(['status' => true, 'data' => $message]);

            // dd('test');

        }








        // if (isset($request->brand) ) {
        //     if ($request->id == '') {
        //         $count = Brand::where(['brand' => $request->brand])->count();
        //         if ($count > 0) {
        //             return response()->json(['status' => false, 'data' => 'Brand Already Exist.']);
        //         } else {
        //             $store = Brand::create([
        //                 'brand' => $request->brand,
        //             ]);
        //         }
        //         return response()->json(['status' => true, 'data' => 'Brand Created Successfully']);
        //     } else {
        //         $count = Brand::whereNotIn('id', [$request->id])->where(['brand' => $request->brand])->count();
        //         if ($count > 0) {
        //             return response()->json(['status' => false, 'data' => 'Brand Already Exist.']);
        //         } else {
        //             $update = Brand::where(['id' => $request->id])->update([
        //                 'brand' => $request->brand,

        //             ]);
        //         }
        //         return response()->json(['status' => true, 'data' => 'Brand Updated Successfully']);
        //     }
        // } else {
        //     return response()->json(['status' => false, 'data' => 'Brand Not Created']);
        // }
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
                'errors' => $validator->errors()
            ]);
        } else {
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

            return response()->json($data->id ? ['status' => 200, 'data' => $data, 'errors' => NULL] : ['status' => 400, 'data' => NULL, 'errors' => NULL]);
        }
    }

    public function getLoanTypeDetails(Request $request)
    {

        $id = $request->loan_type_id;

        $loanType = LoanType::find($id);
        if ($loanType) {
            $documentTypes = $loanType->documents;
            $document_id =  $loanType->documents->pluck('id') ??  [];

            $document_content = '';
            foreach ($documentTypes as $documentType) {
                $document_content .= '<div class="form-group col-md-3">';
                $document_content .= '<input type="checkbox" name="document_features[]" value="' . $documentType->id . '">';
                $document_content .= '&nbsp;&nbsp;' . $documentType->title . '<br>';
                $document_content .= '</div>';
            }

            $formUploads = $loanType->formuploads;
            $formuploads_id =  $loanType->formuploads->pluck('id') ??  [];
            $formUpload_content = '';

            foreach ($formUploads as $formUpload) {
                $formUpload_content .= '<div class="form-group col-md-3">';
                $formUpload_content .= '<input type="checkbox" name="uploadForm_features[]" value="' . $formUpload->id . '">';
                $formUpload_content .= '&nbsp;&nbsp;' . $formUpload->title . '<br>';
                $formUpload_content .= '</div>';
            }

            $document_typs = DocumentType::whereNotIN('id', $document_id)->pluck('title', 'id');
            $form_uploads_typs = FormUpload::whereNotIN('id', $formuploads_id)->pluck('title', 'id');
            return response()->json(['status' => true, 'document_content' => $document_content, 'formUpload_content' => $formUpload_content, 'document_typs' =>  $document_typs, 'form_uploads_typs' => $form_uploads_typs]);
        } else {
            return response()->json(['status' => false, 'document_content' => [], 'formUpload_content' => [], 'document_typs' =>  '', 'form_uploads_typs' => '']);
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

        $documentImages = ApplicationImage::whereIn('document_id', $data->applicationLoanDocument2->pluck('id'))->get();

        $isdocument = true;
        return view('admin.application.document_index', compact('data', 'isdocument', 'documentImages'));
    }


    public function uploadImageGet(Request $request)
    {


        if (isset($request->file) && isset($request->applicationId) && isset($request->documentId) && isset($request->imageId)) {
            $status = false;
            $message = '';
            $applicationImage = '';

            if ($request->hasFile('file')) {
                $applicationImage = ApplicationImage::where([
                    'application_id' => $request->applicationId,
                    'document_id' => $request->documentId,
                    'id' => $request->imageId
                ])->first();

                if ($applicationImage) {
                    $oldFilePath = $applicationImage->file_path;
                    $image = $request->file('file');
                    $imageName = pathinfo($oldFilePath, PATHINFO_FILENAME) . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/formupload');
                    $newFilePath = $imageName;

                    if ($image->move($destinationPath, $imageName)) {
                        // Update the file path in the database
                        $applicationImage->file_path = $newFilePath;
                        $applicationImage->save();

                        $message = 'Successful image updated';
                        $status = true;
                    } else {
                        $message = 'Failed to move the uploaded file';
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Image path not found']);
                }

                return response()->json(['status' => $status, 'message' => $message, 'data' => $applicationImage]);
            }
        }


        if (isset($request->applicationId) &&  isset($request->documentId) &&  isset($request->file)) {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/formupload');
                $image->move($destinationPath, $imageName);

                $ApplicationImage =  ApplicationImage::create(
                    ['application_id' => $request->applicationId, 'document_id' => $request->documentId, 'file_path' => $imageName, 'status' => 'Upload']
                ); 
              $data =  ApplicationImage::find($ApplicationImage->id) ?? '' ;

                // Save image path in the database if needed
                // Example: Image::create(['path' => $imageName]);

                return response()->json(['status' => true, 'data' => $data ]);
            }
        }



        $data  = ApplicationImage::where(['application_id' => $request->applicationId, 'document_id' => $request->documentId])->get();

        // dd($data );

        return response()->json(['status' => true, 'data' => $data]);

        dd($request->all());
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

    public function remarkUpdated(Request $request)
    {

        
        
        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $inputNames = json_decode($request->input('inputNames'), true);
        
        if(!$applicationId || !$documentId || !$inputNames  ){
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data'=>false]);
        }
            // Initialize an array to hold parsed data
            $parsedData = [];

            // Iterate through each serialized string and parse it
            foreach ($inputNames as $serializedString) {
                parse_str($serializedString, $parsedArray);
                $parsedData[] = $parsedArray;
            }

            // Process each parsed form data
            foreach ($parsedData as $formData) {

                // Example: Save each form data to the database
                $model =  ApplicationImage::where([
                    'application_id' => $applicationId,
                    'document_id' => $documentId,
                    'id' => $formData['imageData']
                ])->update([
                    'remark' =>  $formData['input_remark']
                ]); 
            }

        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data'=> true]);


        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

        
    }
    public function remarkUpdatedadmin(Request $request)
    {

        $applicationId = $request->input('applicationId');
        $documentId = $request->input('documentId');
        $imageId = $request->input('imageId');
        $radio =$request->input('radio');
        $admin_remark =$request->input('admin_remark');
        
        if(!$applicationId || !$documentId || !$imageId ){
            return response()->json(['status' => true, 'message' => 'Data Not found.', 'data'=>false]);
        }

         // Example: Save each form data to the database
         $model =  ApplicationImage::where([
            'application_id' => $applicationId,
            'document_id' => $documentId,
            'id' => $imageId
        ])->update([
            'admin_remark' =>  $admin_remark
        ]); 
          
        return response()->json(['status' => true, 'message' => 'successfully updated stauts.', 'data'=> true]);


        $inputNames = $request->input('inputNames', []); // Get the inputNames array from the request

        
    }
}
