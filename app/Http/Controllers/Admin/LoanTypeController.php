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
            $delete = LoanType::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'LoanType Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = LoanType::whereIn('id',request('ids'))->delete() ;


        if($academicYears){
            return response()->json(['status' => 'success', 'data' => 'LoanTypes Deleted Successfully']);
        }else{
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }




    // public function StatusUpdate(Request $request, $id)
    // {
    //     $appointment = DB::table('sell_vehicle_details')->where('id', $id)->first();
    //     if (!$appointment) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Event not found'
    //         ]);
    //     }

    //     DB::table('sell_vehicle_details')->where('id', $id)->update([
    //         'status' => $request->status
    //     ]);

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Event status has been updated'
    //     ]);
    // }

    // public function create()
    // {




    //     $adminid = Session::get('adminid');

    //     if (!(isset($adminid))) {
    //         return Redirect::to("/");
    //     }

    //     $getmyprofiledatas     = DB::select("select * from adminusers where email = '" . $adminid . "' limit 0,1");
    //     $create_by             = $getmyprofiledatas[0]->id;

    //     $success                        = '';
    //     $currenttab                        = 1;
    //     $editid                            = '';
    //     $successtype                    = '';


    //     $alldatas['userinfo']             = Common::userinfo();
    //     $alldatas['toprightmenu']         = Common::toprightmenu();
    //     $alldatas['mainmenu']             = Common::mainmenu();
    //     $alldatas['footer']             = Common::footer();
    //     $alldatas['sidenavbar']         = Common::sidenavbar();
    //     $alldatas['rightsidenavbar']     = Common::rightsidenavbar();
    //     $alldatas['fetchalldepartment']     = Common::fetchalldepartment();
    //     $alldatas['vehicle_features']     = VehicleFeatures::whereNotNull('name')->get();

    //     return view('layouts.admin.sellVehicleDetails.create', compact('alldatas'));
    //     // return view('lastevent.create_event', compact('alldatas'));
    // }

    // public function store(Request $request)
    // {

    //     // dd($request);

    //     $customMessages = [
    //         'customer_name.required' => 'The Client name field is required.',
    //         'customer_number.required' => 'The Client contact field is required.',
    //         'display_date.required' => 'The Date field is required',
    //         'new_old.required' => 'The Vehicle status field is required',
    //         'fuel_type.required' => 'The Fuel type field is required',
    //         'engine_cc.required' => 'The Engine Capacity field is required',
    //         'registration_no.required' => 'The Registration Number is required',
    //     ];

    //     // dd( $request->all());
    //     $data = $request->all();
    //     $this->validate($request, [
    //         'model' => 'required',
    //         'brand' => 'required',
    //         'year' => 'required',
    //         'country' => 'required',
    //         'city' => 'required',
    //         'transmission' => 'required',
    //         'new_old' => 'required',
    //         'engine_cc' => 'required',
    //         'fuel_type' => 'required',
    //         'colour' => 'required',
    //         'event_image' => 'nullable|image|max:2048',
    //         // 'status' => 'required',
    //         'customer_name' => 'required',
    //         'display_date' => 'required',
    //         'customer_number' => 'required',
    //         'vehicle_price' => 'required',
    //         'millage' => 'required',
    //         'registration_no' => 'required',
    //         // 'event_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //         //  'event_image' => 'required|image|max:2048|dimensions:width=700,height=300',
    //         //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
    //         'content' => [
    //             'required',
    //             function ($attribute, $value, $fail) use ($request) {
    //                 if ($request->input('content') == '<p><br></p>') {
    //                     $fail('The Content Filed is required.');
    //                 }
    //             }
    //         ],
    //         // 'shord_order' => 'required',
    //     ], $customMessages);

    //     $imageName = null;
    //     if ($request->hasFile('event_image')) {
    //         $file = $request->file('event_image');
    //         if ($file->isValid()) {
    //             $imageName = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('Event'), $imageName);
    //         } else {
    //             return redirect()->back()->withErrors(['error' => 'Failed to upload image.'])->withInput();
    //         }
    //     } else {
    //         return redirect()->back()->withErrors(['error' => 'No image file selected.'])->withInput();
    //     }
    //     // dd($request->all());

    //     $slug = Str::slug($request->brand, '-');
    //     $data['brand'] =   $slug;
    //     $data['image'] =   $imageName;
    //     $data = $request->all();
    //     // dd(  $data);
    //     //   $SellVehicleDetails =   SellVehicleDetails::insert([
    //     //         'brand' => $request->brand,
    //     //         'event_slug' => $slug,
    //     //         'model' => $request->model,
    //     //         'year' => $request->year,
    //     //         'country' => $request->country,
    //     //         'city' => $request->city,
    //     //         'transmission' => $request->transmission,
    //     //         'new_old' => $request->new_old,
    //     //         'engine_cc' => $request->engine_cc,
    //     //         'fuel_type' => $request->fuel_type,
    //     //         'colour' => $request->colour,
    //     //         'image' => $imageName,
    //     //         'customer_name' => $request->customer_name,
    //     //         'display_date' => $request->display_date,
    //     //         'customer_number' => $request->customer_number,
    //     //         'vehicle_price' => $request->vehicle_price,
    //     //         'content' => $request->content,
    //     //         // 'status' => $request->status,
    //     //         // 'shord_order' => $request->shord_order,

    //     //     ]);

    //     //     if($request->vehicle_features != ''){

    //     //         $SellVehicleDetails ->SellVehicleFeatures->attach($request->vehicle_features) ;
    //     //     }


    //     $SellVehicleDetails = SellVehicleDetails::create([
    //         'brand' => $request->brand,
    //         'event_slug' => $slug,
    //         'model' => $request->model,
    //         'registration_no' => $request->registration_no,
    //         'year' => $request->year,
    //         'country' => $request->country,
    //         'city' => $request->city,
    //         'transmission' => $request->transmission,
    //         'new_old' => $request->new_old,
    //         'engine_cc' => $request->engine_cc,
    //         'fuel_type' => $request->fuel_type,
    //         'colour' => $request->colour,
    //         'vehicle_price' => $request->vehicle_price,
    //         'image' => $imageName,
    //         'customer_name' => $request->customer_name,
    //         'display_date' => $request->display_date,
    //         'customer_number' => $request->customer_number,
    //         'content' => $request->content,
    //         'millage' => $request->millage,
    //         // 'status' => $request->status,
    //         // 'shord_order' => $request->shord_order,
    //     ]);

    //     if ($request->vehicle_features != '') {
    //         $SellVehicleDetails->SellVehicleFeatures()->attach($request->vehicle_features);
    //     }




    //     // DB::table('sell_vehicle_details')->insert([
    //     //         'title' => $request->title,
    //     //         'event_slug' => $slug,
    //     //         'status' => $request->status,
    //     //         'display_date' => $request->display_date,
    //     //         'content' => $request->content,
    //     //         // 'shord_order' => $request->shord_order,
    //     //         'image' => $imageName,
    //     //         // 'created_at' => now(),
    //     //         // 'updated_at' => now(),
    //     //     ]);






    //     // SellVehicleDetails::create

    //     // DB::table('sell_vehicle_details')->insert([
    //     //     'title' => $request->title,
    //     //     'event_slug' => $slug,
    //     //     'status' => $request->status,
    //     //     'display_date' => $request->display_date,
    //     //     'content' => $request->content,
    //     //     'shord_order' => $request->shord_order,
    //     //     'image' => $imageName,
    //     //     'created_at' => now(),
    //     //     'updated_at' => now(),
    //     // ]);

    //     return redirect()->route('eventlist.index')->with('success', 'Event has been Added successfully.');
    // }

    // public function edit(Request $request, $id)
    // {

    //     $adminid = Session::get('adminid');

    //     if (!(isset($adminid))) {
    //         return Redirect::to("/");
    //     }

    //     $getmyprofiledatas     = DB::select("select * from adminusers where email = '" . $adminid . "' limit 0,1");
    //     $create_by             = $getmyprofiledatas[0]->id;

    //     $success                        = '';
    //     $currenttab                        = 1;
    //     $editid                            = '';
    //     $successtype                    = '';


    //     $alldatas['userinfo']             = Common::userinfo();
    //     $alldatas['toprightmenu']         = Common::toprightmenu();
    //     $alldatas['mainmenu']             = Common::mainmenu();
    //     $alldatas['footer']             = Common::footer();
    //     $alldatas['sidenavbar']         = Common::sidenavbar();
    //     $alldatas['rightsidenavbar']     = Common::rightsidenavbar();

    //     $alldatas['fetchalldepartment']     = Common::fetchalldepartment();
    //     $alldatas['vehicle_features']     = VehicleFeatures::whereNotNull('name')->get();

    //     //  $check = VehicleFeatures::whereNotNull('name') ->group_by('Desc')->first() ;

    //     $EvetList = SellVehicleDetails::where('id', $id)->first();

    //     $data2 = $EvetList->SellVehicleFeatures;
    //     $data3 = $data2->map(function ($data2) {
    //         return $data2->id;
    //     })->toArray();


    //     // dd($EvetList->vehicleFeatures->contains('13')) ;
    //     // dd($EvetList->SellVehicleFeatures );
    //     // dd($EvetList->SellVehicleFeatures);

    //     return view('lastevent.edit_event', compact('alldatas', 'EvetList', 'data3'));
    // }

    // public function update(Request $request, $id)
    // {



    //     // $this->validate($request, [
    //     //     'title' => 'required',
    //     //     'status' => 'required',
    //     //     'display_date' => 'required',
    //     //     // 'event_image' => 'nullable|image|max:2048', // Change 'required' to 'nullable'
    //     //     //'event_image' => 'nullable|image|max:2048|dimensions:width=700,height=300',
    //     //     //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
    //     //       'event_image' => 'nullable|image|max:2048',
    //     //     'content' => 'required',
    //     //     'shord_order' => 'required',
    //     // ]);

    //     $customMessages = [
    //         'customer_name.required' => 'The Client name field is required.',
    //         'customer_number.required' => 'The Client number field is required.',
    //         'display_date.required' => 'The Date field is required',
    //         'new_old.required' => 'The Vehicle status field is required',
    //         'fuel_type.required' => 'The Fuel type field is required',
    //         'engine_cc.required' => 'The Engine Capacity field is required',
    //         'registration_no.required' => 'The Registration Number is required',

    //     ];

    //     // dd( $request->all());
    //     $data = $request->all();
    //     $data['content'] = $data['content'] == '<p><br></p>' ? '' : $data['content'];
    //     // $request->content =$request ->content == '<p><br></p>' ? '' : $request -> content;
    //     $this->validate($request, [
    //         'model' => 'required',
    //         'brand' => 'required',
    //         'year' => 'required',
    //         'country' => 'required',
    //         'city' => 'required',
    //         'transmission' => 'required',
    //         'new_old' => 'required',
    //         'engine_cc' => 'required',
    //         'fuel_type' => 'required',
    //         'colour' => 'required',
    //         'event_image' => 'nullable|image|max:2048',
    //         // 'status' => 'required',
    //         'customer_name' => 'required',
    //         'display_date' => 'required',
    //         'customer_number' => 'required',
    //         'vehicle_price' => 'required',
    //         'millage' => 'required',
    //         'registration_no' => 'required',

    //         // 'event_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //         //  'event_image' => 'required|image|max:2048|dimensions:width=700,height=300',
    //         //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
    //         'content' => [
    //             'required',
    //             function ($attribute, $value, $fail) use ($request) {
    //                 if ($request->input('content') == '<p><br></p>') {
    //                     $fail('The Content Filed is required.');
    //                 }
    //             }
    //         ],
    //         // 'shord_order' => 'required',
    //     ], $customMessages);



    //     $event = DB::table('sell_vehicle_details')->where('id', $id)->first();

    //     $imageName = $event->image; // Preserve the existing image name by default

    //     if ($request->hasFile('event_image')) {
    //         $file = $request->file('event_image');
    //         if ($file->isValid()) {
    //             // Remove the old image file
    //             if ($imageName && file_exists(public_path('Event/' . $imageName))) {
    //                 unlink(public_path('Event/' . $imageName));
    //             }
    //             // Upload the new image file
    //             $imageName = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('Event'), $imageName);
    //         } else {
    //             return redirect()->back()->withErrors(['error' => 'Failed to upload image.'])->withInput();
    //         }
    //     }
    //     $slug = Str::slug($request->title, '-');

    //     $SellVehicleDetails = SellVehicleDetails::find($id);

    //     if (!$SellVehicleDetails) {
    //         // Handle case when SellVehicleDetails with given ID doesn't exist
    //     }

    //     $SellVehicleDetails->update([
    //         'brand' => $request->brand,
    //         'event_slug' => $slug,
    //         'model' => $request->model,
    //         'registration_no' => $request->registration_no,
    //         'year' => $request->year,
    //         'country' => $request->country,
    //         'city' => $request->city,
    //         'transmission' => $request->transmission,
    //         'new_old' => $request->new_old,
    //         'engine_cc' => $request->engine_cc,
    //         'fuel_type' => $request->fuel_type,
    //         'colour' => $request->colour,
    //         'millage' => $request->millage,
    //         'image' => $imageName,
    //         'customer_name' => $request->customer_name,
    //         'display_date' => $request->display_date,
    //         'customer_number' => $request->customer_number,
    //         'vehicle_price' => $request->vehicle_price,
    //         'content' => $request->content,
    //     ]);

    //     if ($request->vehicle_features != '') {
    //         $SellVehicleDetails->SellVehicleFeatures()->sync($request->vehicle_features);
    //     }


    //     // // dd($request->all(), $id);
    //     // $SellVehicleDetails =   SellVehicleDetails::where('id', $id)->update([
    //     //     'brand' => $request->brand,
    //     //     'event_slug' => $slug,
    //     //     'model' => $request->model,
    //     //     'year' => $request->year,
    //     //     'country' => $request->country,
    //     //     'city' => $request->city,
    //     //     'transmission' => $request->transmission,
    //     //     'new_old' => $request->new_old,
    //     //     'engine_cc' => $request->engine_cc,
    //     //     'fuel_type' => $request->fuel_type,
    //     //     'colour' => $request->colour,
    //     //     'image' => $imageName,
    //     //     'customer_name' => $request->customer_name,
    //     //     'display_date' => $request->display_date,
    //     //     'customer_number' => $request->customer_number,
    //     //     'vehicle_price' => $request->vehicle_price,
    //     //     'content' => $request->content,

    //     // ]);


    //     // if ($request->vehicle_features != '') {

    //     //     $SellVehicleDetails->SellVehicleFeatures()->sync($request->vehicle_features);
    //     // }


    //     // DB::table('sell_vehicle_details')->where('id', $id)->update([
    //     //     'title' => $request->title,
    //     //     'event_slug' => $slug,
    //     //     'status' => $request->status,
    //     //     'display_date' => $request->display_date,
    //     //     'content' => $request->content,
    //     //     'shord_order' => $request->shord_order,
    //     //      'image' => $imageName,
    //     //     'updated_at' => now(), // Update only 'updated_at' without modifying 'created_at'
    //     // ]);
    //     return redirect()->route('eventlist.index')->with('success', 'Event has been updated successfully.');
    //     //return redirect()->route('eventlist.index')->with('success', 'Event has been updated successfully.');
    // }

    // public function destroy($id)
    // {
    //     $event = DB::table('sell_vehicle_details')->where('id', $id)->first();

    //     if ($event) {
    //         DB::table('sell_vehicle_details')->where('id', $id)->update([
    //             'deleted_at' => now(), // Set deleted_at to current timestamp
    //         ]);
    //         return redirect()->route('eventlist.index')->with('success', 'Event has been Deleted successfully.');
    //     }
    //     return redirect()->route('eventlist.index')->with('error', 'Event not found.');
    // }


    // public function ImageUpload(Request $request)
    // {
    //     $directory = 'Event';
    //     $imageDetails = [];
    //     $Event = DB::table('sell_vehicle_details')->where('event_slug', $request->eventslug)->first();
    //     foreach ($request->file('service_content_image') as $image) {
    //         $filename = uniqid() . '_' . $image->getClientOriginalName();
    //         $image->move(public_path($directory), $filename);
    //         $imageDetails[] = [
    //             'event_id' => $Event->id,
    //             'image' => $filename,
    //             'created_at' => now() // Use Laravel's built-in now() function to get the current date and time
    //         ];
    //     }
    //     $ins = DB::table('event_image')->insert($imageDetails);
    //     return response()->json($ins == true ? ['status' => 200, 'message' => 'Successfully Uploaded'] : ['status' => 400, 'message' => 'Failed to upload']);
    // }

    // public function GetImage(Request $request)
    // {
    //     $Event = DB::table('sell_vehicle_details')->where('event_slug', $request->eventslug)->first();
    //     $service_content_images = DB::table('event_image')->where('event_id', '=', $Event->id)
    //         ->get();
    //     return response()->json($service_content_images->count() > 0 ? ['status' => 200, 'data' => $service_content_images] : ['status' => 400, 'message' => 'Data Not Found']);
    // }

    // public function DeleteImage(Request $request)
    // {
    //     $service_content_image = DB::table('event_image')->where('id', '=', $request->id)
    //         ->get();
    //     $service_content_image_delete = DB::table('event_image')->where('id', '=', $request->id)
    //         ->delete();
    //     return response()->json($service_content_image_delete == true ? ['status' => 200, 'message' => 'Event image deleted',] : ['status' => 400, 'message' => 'Failed',]);
    // }
}
