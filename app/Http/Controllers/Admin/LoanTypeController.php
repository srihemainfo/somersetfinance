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
    LoanType,
    FormUpload,
    DocumentType,
    DocumentForm,
    FormLoan
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



class LoanTypeController extends Controller
{


    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index(Request $request)
    {


        // $loanType = LoanType::find(11);

        // Dump and die the IDs of related form uploads



        // $alldatas = $this->allDataController->index();

        if ($request->ajax()) {

            $query = LoanType::query()->select(sprintf('*', (new LoanType)->table));
            $table = Datatables::of($query);

            // $query = LoanType::query(); // Start building the query from the LoanType model
            // $table = Datatables::of($query);

            // If you want to select all columns from the LoanType table
            // $table->select('*');

            // $query = LoanType::query()->select(sprintf('%s.*', (new LoanType)->table));
            // // dd( $query );
            // $table = Datatables::of($query);

            // dd($table );

            // $query = LoanType::query()->select(sprintf('%s.*', (new LoanType)->table));
            // $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewLoanType';
                $editFunct = 'editLoanType';
                $deleteFunct = 'deleteLoanType';
                $viewGate = 'loan_type_show'; // need to change gate permission
                $editGate = 'loan_type_edit';
                $deleteGate = 'loan_type_delete';
                $crudRoutePart = 'loanType';

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

            $table->editColumn('documents', function ($row) {
                return $row->documents->map(function ($document) {
                    return $document->title;
                })->toArray();
            });

            $table->editColumn('formuploads', function ($row) {
                return $row->formuploads->map(function ($formupload) {
                    return $formupload->title;
                })->toArray();
            });


            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        $document_types = DocumentType::whereNotNull('title')->pluck('title', 'id')->prepend('Select Document Type', ' ') ;
        $formuploades = FormUpload::whereNotNull('title')->pluck('title', 'id')->prepend('Select Form Upload', ' ') ;


        return view('admin.loan_type.index', compact('document_types','formuploades'));

    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data= [];
            $data['data'] = LoanType::where(['id' => $request->id])->select('id', 'title')->first() ?? [];
            $data['documents'] = $data['data'] ->documents->pluck('id') ?? [];
            $data['formuploads'] = $data['data'] ->formuploads->pluck('id') ?? [];


            // dd($data['data'] ->documents->pluck('id'));

            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    // public function store(Request $request)
    // {
    //     if (isset($request->title) ) {
    //         if ($request->id == '') {
    //             $count = LoanType::where(['title' => $request->title])->count();
    //             if ($count > 0) {
    //                 return response()->json(['status' => false, 'data' => 'LoanType Already Exist.']);
    //             } else {
    //                 $store = LoanType::create([
    //                     'title' => $request->title,
    //                 ]);

    //                 if( $store ){
    //                     $id = $store->id;
    //                     if($request->document_id){
    //                         $documents = $request->document_id;
    //                             $datas = [];
    //                         foreach($documents as $document){
    //                             $data = ['loan_type_id' => $store->id, 'loan_document_id' => $document] ;

    //                             $count = DocumentForm::where($data)->count();

    //                             if($count == 0){
    //                                 $datas[]= ['loan_type_id' => $store->id, 'loan_document_id' => $document] ;

    //                             }

    //                         }
    //                             if($datas){

    //                                 DocumentForm::create($data) ;
    //                             }

    //                             DocumentForm::where('loan_type_id',$id)->whereNotIn($documents)->delete();


    //                     }
    //                 }




    //             }
    //             return response()->json(['status' => true, 'data' => 'LoanType Created Successfully']);
    //         } else {
    //             $count = LoanType::whereNotIn('id', [$request->id])->where(['title' => $request->title])->count();
    //             if ($count > 0) {
    //                 return response()->json(['status' => false, 'data' => 'LoanType Already Exist.']);
    //             } else {
    //                 $update = LoanType::where(['id' => $request->id])->update([
    //                     'title' => $request->title,

    //                 ]);
    //             }
    //             return response()->json(['status' => true, 'data' => 'LoanType Updated Successfully']);
    //         }
    //     } else {
    //         return response()->json(['status' => false, 'data' => 'LoanType Not Created']);
    //     }
    // }

//     public function store(Request $request)
// {
//     $validatedData = $request->validate([
//         'title' => 'required|string|max:255',
//         'document_id' => 'array', // Ensure document_id is an array if provided
//     ]);

//     // Check if title and id are set
//     if (isset($request->title) && isset($request->id)) {
//         if ($request->id == '') {
//             // Create a new LoanType
//             $count = LoanType::where(['title' => $request->title])->count();
//             if ($count > 0) {
//                 return response()->json(['status' => false, 'data' => 'LoanType Already Exist.']);
//             }

//             $store = LoanType::create([
//                 'title' => $request->title,
//             ]);

//             // Handle associated DocumentForm records
//             if ($store && !empty($request->document_id)) {
//                 $documents = $request->document_id;
//                 $existingDocuments = DocumentForm::where('loan_type_id', $store->id)->pluck('loan_document_id')->toArray();

//                 $newDocuments = [];
//                 foreach ($documents as $document) {
//                     if (!in_array($document, $existingDocuments)) {
//                         if($store->id != '' && $document!= '' ){
//                         $newDocuments[] = ['loan_type_id' => $store->id, 'loan_document_id' => $document];
//                         }
//                     }
//                 }

//                 if (!empty($newDocuments)) {
//                     DocumentForm::insert($newDocuments);
//                 }

//                 $filteredDocuments = array_filter($documents, function ($value) {
//                     return $value !== null;
//                 });

//                 // Perform the query with the filtered array
//                 $deletedRows = DocumentForm::where('loan_type_id', $store->id)
//                     ->whereNotIn('loan_document_id', $filteredDocuments)
//                     ->delete();

//                 // DocumentForm::where('loan_type_id', $store->id)->whereNotIn('loan_document_id', $documents)->delete();
//             }

//             return response()->json(['status' => true, 'data' => 'LoanType Created Successfully']);
//         } else {
//             // Update an existing LoanType

//             $id = $request->id ;
//             $count = LoanType::whereNotIn('id', [$id])->where(['title' => $request->title])->count();
//             if ($count > 0) {
//                 return response()->json(['status' => false, 'data' => 'LoanType Already Exist.']);
//             }

//             $update = LoanType::where(['id' => $id ])->update([
//                 'title' => $request->title,
//             ]);


//             // Handle associated DocumentForm records
//             if ($update && !empty($request->document_id)) {
//                 $documents = $request->document_id;
//                 $existingDocuments = DocumentForm::where('loan_type_id', $id )->pluck('loan_document_id')->toArray();

//                 $newDocuments = [];
//                 foreach ($documents as $document) {

//                     if (!in_array($document, $existingDocuments)) {
//                         if($id != '' && $document!= '' ){
//                             $newDocuments[] = ['loan_type_id' => $id , 'loan_document_id' => $document, 'created_at'=> now(), 'updated_at'=> now()];

//                         }
//                     }
//                 }

//                 if (!empty($newDocuments)) {

//                     // dd($newDocuments);
//                     DocumentForm::insert($newDocuments);
//                 }

//                 $filteredDocuments = array_filter($documents, function ($value) {
//                     return $value !== null;
//                 });

//                 // Perform the query with the filtered array
//                 $deletedRows = DocumentForm::where('loan_type_id', $id)
//                     ->whereNotIn('loan_document_id', $filteredDocuments)
//                     ->delete();


//             }

//             return response()->json(['status' => true, 'data' => 'LoanType Updated Successfully']);
//         }
//     }

//     return response()->json(['status' => false, 'data' => 'LoanType Not Created']);
// }



public function store(Request $request)
{
    $id = $request->id;

    if (isset($request->title)) {
        if (empty($id)) {
            $count = LoanType::where(['title' => $request->title])->count();
            if ($count > 0) {
                return response()->json(['status' => false, 'data' => 'LoanType Already Exists.']);
            } else {
                $loanType = LoanType::create(['title' => $request->title]);

                if ($loanType) {
                    $loanTypeId = $loanType->id;

                    if ($request->document_id) {

                        $filteredDocuments = array_filter($request->document_id, function ($value) {
                            return $value !== null;
                        });

                        // Perform the query with the filtered array
                        $deletedRows = DocumentForm::where('loan_type_id', $loanTypeId)
                            ->whereNotIn('loan_document_id', $filteredDocuments)
                            ->delete();


                        foreach ($request->document_id as $documentId) {
                            $existingDocument = DocumentForm::where('loan_type_id', $loanTypeId)
                                ->where('loan_document_id', $documentId)
                                ->withTrashed() // Include soft deleted records
                                ->first();

                            if ($existingDocument) {
                                if ($existingDocument->trashed()) {
                                    // Restore the soft deleted record
                                    $existingDocument->restore();
                                }
                            } else {
                                // Create new record if not exists
                                DocumentForm::create([
                                    'loan_type_id' => $loanTypeId,
                                    'loan_document_id' => $documentId,
                                ]);
                            }
                        }
                    }

                    if ($request->form_upload_id) {

                        $filteredDocuments = array_filter($request->form_upload_id, function ($value) {
                            return $value !== null;
                        });

                        // Perform the query with the filtered array
                        $deletedRows = FormLoan::where('loan_type_id', $loanTypeId)
                            ->whereNotIn('form_upload_id', $filteredDocuments)
                            ->delete();


                        foreach ($request->form_upload_id as $formId) {
                            $existingDocument = FormLoan::where('loan_type_id', $loanTypeId)
                                ->where('form_upload_id', $formId)
                                ->withTrashed() // Include soft deleted records
                                ->first();

                            if ($existingDocument) {
                                if ($existingDocument->trashed()) {
                                    // Restore the soft deleted record
                                    $existingDocument->restore();
                                }
                            } else {
                                // Create new record if not exists
                                FormLoan::create([
                                    'loan_type_id' => $loanTypeId,
                                    'form_upload_id' => $formId,
                                ]);
                            }
                        }
                    }


                }
                return response()->json(['status' => true, 'data' => 'LoanType Created Successfully']);
            }
        } else {
            // Update existing LoanType
            $count = LoanType::whereNotIn('id', [$id])->where(['title' => $request->title])->count();
            if ($count > 0) {
                return response()->json(['status' => false, 'data' => 'LoanType Already Exists.']);
            } else {
                $update = LoanType::where(['id' => $id])->update([
                    'title' => $request->title,
                ]);

                if ($update) {
                    $loanType = LoanType::find($id);

                    if ($loanType && $request->document_id) {
                        // Soft delete existing documents not in the updated list

                        $filteredDocuments = array_filter($request->document_id, function ($value) {
                            return $value !== null;
                        });

                        // Perform the query with the filtered array
                        $deletedRows = DocumentForm::where('loan_type_id', $id)
                            ->whereNotIn('loan_document_id', $filteredDocuments)
                            ->delete();


                        // DocumentForm::where('loan_type_id', $id)
                        //     ->whereNotIn('loan_document_id', $request->document_id)
                        //     ->delete();

                        // Add or restore documents
                        foreach ($request->document_id as $documentId) {
                            $existingDocument = DocumentForm::where('loan_type_id', $id)
                                ->where('loan_document_id', $documentId)
                                ->withTrashed() // Include soft deleted records
                                ->first();

                            if ($existingDocument) {
                                if ($existingDocument->trashed()) {
                                    // Restore the soft deleted record
                                    $existingDocument->restore();
                                }
                            } else {
                                // Create new record if not exists
                                DocumentForm::create([
                                    'loan_type_id' => $id,
                                    'loan_document_id' => $documentId,
                                ]);
                            }
                        }
                    }


                    if ($loanType && $request->form_upload_id) {
                        // Soft delete existing documents not in the updated list

                        $filteredDocuments = array_filter($request->form_upload_id, function ($value) {
                            return $value !== null;
                        });

                        // Perform the query with the filtered array
                        $deletedRows = FormLoan::where('loan_type_id', $id)
                            ->whereNotIn('form_upload_id', $filteredDocuments)
                            ->delete();


                        // FormLoan::where('loan_type_id', $id)
                        //     ->whereNotIn('loan_document_id', $request->document_id)
                        //     ->delete();

                        // Add or restore documents
                        foreach ($request->form_upload_id as $formId) {
                            $existingDocument = FormLoan::where('loan_type_id', $id)
                                ->where('form_upload_id', $formId)
                                ->withTrashed() // Include soft deleted records
                                ->first();

                            if ($existingDocument) {
                                if ($existingDocument->trashed()) {
                                    // Restore the soft deleted record
                                    $existingDocument->restore();
                                }
                            } else {
                                // Create new record if not exists
                                FormLoan::create([
                                    'loan_type_id' => $id,
                                    'form_upload_id' => $formId,
                                ]);
                            }
                        }
                    }
                }
                return response()->json(['status' => true, 'data' => 'LoanType Updated Successfully']);
            }
        }
    } else {
        return response()->json(['status' => false, 'data' => 'LoanType Not Created']);
    }
}



    public function edit(Request $request)
    {
        if (isset($request->id)) {
            // $data = LoanType::where(['id' => $request->id])->select('id', 'title')->first();
            $data= [];
            $data['data'] = LoanType::where(['id' => $request->id])->select('id', 'title')->first() ?? [];
            $data['documents'] = $data['data'] ->documents->pluck('id') ?? [];
            $data['formuploads'] = $data['data'] ->formuploads->pluck('id') ?? [];
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        
        
        if (isset($request->id)) {
            
            $loanType = LoanType::find($request->id);
            if ($loanType) {
                
                if($loanType->isApplication()->exists() || $loanType->isEnquiryList()->exists()){
                    
                     return response()->json(['status' => 'error', 'data' => 'This data are used in case list']);
                }
                    
                    $loanType->delete();
            }else{
                  return response()->json(['status' => 'error', 'data' => 'Loan Type data not found']);
            }

            return response()->json(['status' => 'success', 'data' => 'Loan Type Successfully Delete']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
        
    }

    public function massDestroy()
    {
        
        
        $ids = request('ids');
        $loanTypes = LoanType::whereIn('id', $ids)->get();
        $status = false;
    
        foreach ($loanTypes as $loanType) {
            
            if(!$loanType->isApplication()->exists() && !$loanType->isEnquiryList()->exists() ){
                $loanType->delete();
                $status = true;
            }
        }
        
        if( $status ){
            return response()->json(['status' => 'success', 'data' => 'Loan Type Successfully Deletes']);
        }else{
            return response()->json(['status' => 'error', 'data' => 'This data are used in case list']);
        }
        
        
        
        
        
        // $academicYears = LoanType::whereIn('id',request('ids'))->delete() ;


        // if($academicYears){
        //     return response()->json(['status' => 'success', 'data' => 'LoanTypes Deleted Successfully']);
        // }else{
        //     return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        // }
    }



}
