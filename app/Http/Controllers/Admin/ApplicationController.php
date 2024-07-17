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
    DocumentType
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



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
        $loan_types = LoanType::pluck('title','id')->prepend('Select LoanType', '') ;
        // $document_types = DocumentType::whereNotNull('title')->pluck('title', 'id')->prepend('Select Document Type', ' ') ;
        // $formuploades = FormUpload::whereNotNull('title')->pluck('title', 'id')->prepend('Select Form Upload', ' ') ;

        return view('admin.application.index', compact('isEditable','loan_types',));



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

        dd($request);




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
        $academicYears = Brand::whereIn('id',request('ids'))->delete() ;
        // find(request('ids'));

        // foreach ($academicYears as $academicYear) {
        //     $academicYear->delete();
        // }
        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function create(){

        LoanType::whereNotNull('title')->pluck('title','id');

    }

    public function GetClients(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $clients = CustomDetail::orderby('name', 'asc')->select('id', 'name','email')->distinct()->limit(6)->get();
        } else {
            $clients = CustomDetail::orderby('name', 'asc')->select('id', 'name','email')->where('name', 'like', '%' . $search . '%')->distinct()->limit(6)->get();
        }

        $response = [];
        foreach ($clients as $client) {
            $response[] = array(
                "id" => $client->id,
                "text" => $client->name .'(' . $client->email . ')',
            );
        }
        return response()->json($response);
    }

    public function GetClientInfo(Request $request)
    {
        $client_details = CustomDetail::
            select('*')
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

    public function getLoanTypeDetails(Request $request){

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
                $formUpload_content .= '<input type="checkbox" name="document_features[]" value="' . $formUpload->id . '">';
                $formUpload_content .= '&nbsp;&nbsp;' . $formUpload->title . '<br>';
                $formUpload_content .= '</div>';
            }

           $document_typs = DocumentType::whereNotIN('id',$document_id)->pluck('title','id');
           $form_uploads_typs= FormUpload::whereNotIN('id',$formuploads_id)->pluck('title','id');
            return response()->json(['status' => true, 'document_content' => $document_content, 'formUpload_content'=> $formUpload_content, 'document_typs'=>  $document_typs, 'form_uploads_typs'=>$form_uploads_typs]);
        } else {
            return response()->json(['status' => false, 'document_content' => [], 'formUpload_content'=> [],'document_typs'=>  '', 'form_uploads_typs'=> '']);
        }




    }

    public function document_index(){
        return view('admin.application.document_index') ;
    }




}
