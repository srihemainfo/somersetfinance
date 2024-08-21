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
    Models,
    Brand,
    DocumentType,
    LoanType,
    FormUpload
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



class DocumenTypeController extends Controller
{


    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index(Request $request)
    {

      
        $loan_types = LoanType::pluck('title','id')->prepend('Select LoanType', '') ;

        if ($request->ajax()) {

            $query = DocumentType::select(['*']);
            $table = DataTables::of($query) ;
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewDocumentType';
                $editFunct = 'editDocumentType';
                $deleteFunct = 'deleteDocumentType';
                $viewGate = 'document_type_show'; // need to change gate permission
                $editGate = 'document_type_edit';
                $deleteGate = 'document_type_delete';
                $crudRoutePart = 'document_type';

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

        return view('admin.document_type.index', compact('loan_types'));

}
    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = DocumentType::where(['id' => $request->id])->select('id', 'title')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {
        if (isset($request->title)) {
            if ($request->id == '') {
                $count = DocumentType::where(['title' => $request->title ])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Document Type Already Exist.']);
                } else {
                    $store = DocumentType::create([
                        'title' => $request->title,
                        // 'loan_type_id' => $request->loan_type_id,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Document Type Created Successfully']);
            } else {
                $count = DocumentType::whereNotIn('id', [$request->id])->where(['title' => $request->title ])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Document Type Already Exist.']);
                } else {
                    $update = DocumentType::where(['id' => $request->id])->update([
                        'title' => $request->title,
                        // 'loan_type_id' => $request->loan_type_id,

                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Document Type Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Document Type Not Created']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = DocumentType::where(['id' => $request->id])->select('id', 'title')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        

        
         if (isset($request->id)) {
            
            $documentType = DocumentType::find($request->id);
          
            if ($documentType) {
                
                if($documentType->documentLoan()->exists() || $documentType->isDocumentLoan()->exists()){
                    
                     return response()->json(['status' => 'error', 'data' => 'This data are used in case list']);
                }
                    
                    $documentType->delete();
            }else{
                  return response()->json(['status' => 'error', 'data' => 'Document Type data not found']);
            }

            return response()->json(['status' => 'success', 'data' => 'Document Type Successfully Delete']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
        
        
        
    }

    public function massDestroy()
    {

        
        $ids = request('ids');
        $documentTypes = DocumentType::whereIn('id', $ids)->get();
        $status = false;
    
        foreach ($documentTypes as $documentType) {
            
            if(!$documentType->documentLoan()->exists() && !$documentType->isDocumentLoan()->exists() ){
                $documentType->delete();
                $status = true;
            }
            
            
        }
        
        if( $status ){
            return response()->json(['status' => 'success', 'data' => 'Document Type Successfully Deletes']);
        }else{
            return response()->json(['status' => 'error', 'data' => 'This data are used in case list']);
        }
   
    }



}
