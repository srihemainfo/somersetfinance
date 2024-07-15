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
    FormUpload,
};
use Illuminate\Support\Facades\Storage;
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;



class FormUploadController extends Controller
{


    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index(Request $request)
    {

        // dd(Models::get());

        // dd(Models::find(1)->Brand->brand) ;

        // foreach (Models::all() as $model){
        //     dd($model->Brand)
        // }

        $loan_types = LoanType::pluck('title','id')->prepend('Select LoanType', '') ;

        // $alldatas = $this->allDataController->index();

        if ($request->ajax()) {

            $query = FormUpload::select(['*']);
            $table = DataTables::of($query) ;

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewformUpload';
                $editFunct = 'editFormUpload';
                $deleteFunct = 'deleteFormUpload';
                $viewGate = 'form_upload_show'; // need to change gate permission
                $editGate = 'form_upload_edit';
                $deleteGate = 'form_upload_delete';
                $crudRoutePart = 'form_upload';

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

            $table->editColumn('loan_type_id', function ($row) {

                return $row->loanType->title ??  '';
            });
            $table ->editColumn('file_name', function($row) {
                $url = asset('formupload/' . $row->file_name);
                $fileExtension = pathinfo($url, PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), ['jpeg', 'jpg', 'png', 'gif'])) {
                    return '<img src="'.$url.'" width="100px" height="50px">';
                } elseif (strtolower($fileExtension) === 'pdf') {
                    return '<a href="'.$url.'" title="view PDF" target="_blank"><i class="fa-fw nav-icon far fa-eye"></i></a>';
                } else {
                    return '<a href="'.$url.'" target="_blank">Download File</a>';
                }
            }) ;


            $table->rawColumns(['actions', 'placeholder', 'file_name']);
            return $table->make(true);
        }

        return view('admin.form_upload.index', compact('loan_types'));


    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = FormUpload::where(['id' => $request->id])->select('id', 'title', 'loan_type_id','file_name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {

        if (isset($request->title) &&  isset($request->loan_type_id) ) {
            if ($request->id == '') {
                $count = FormUpload::where(['title' => $request->title , 'loan_type_id' => $request->loan_type_id])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Document Type Already Exist.']);
                } else {

                    $image = $request->file('file_name');
                    $fileExtension = $image->getClientOriginalExtension();
                    $fileName = $request->title . '_' . $request->loan_type_id . '.' . $fileExtension;

                    // Move the file to public/formupload directory
                    $image->move(public_path('formupload'), $fileName);

                    $store = FormUpload::create([
                        'title' => $request->title,
                        'loan_type_id' => $request->loan_type_id,
                        'file_name' => $fileName
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Document Type Created Successfully']);
            } else {
                $count = FormUpload::whereNotIn('id', [$request->id])->where(['title' => $request->title , 'loan_type_id' => $request->loan_type_id])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Document Type Already Exist.']);
                } else {

                    $fileUpload = FormUpload::find($request->id);

                    if($fileUpload){
                        if ($request->hasFile('file_name')) {

                            $image = $request->file('file_name');
                            $fileExtension = $image->getClientOriginalExtension();
                            $fileName = $request->title . '_' . $request->loan_type_id . '.' . $fileExtension;

                            // Move the new file to the formupload directory
                            $image->move(public_path('formupload'), $fileName);

                            // Update the file name in the database
                            $fileUpload->file_name = $fileName;

                        }else{

                            $oldFileName = $fileUpload->file_name;

                            // Generate the new file name
                            $fileExtension = pathinfo($oldFileName, PATHINFO_EXTENSION); // Get the old file extension
                            $newFileName = $request->title . '_' . $request->loan_type_id . '.' . $fileExtension;

                            $oldFilePath = public_path('formupload') . '/' . $oldFileName;
                            $newFilePath = public_path('formupload') . '/' . $newFileName;
                            if (File::exists($oldFilePath)) {
                                File::move($oldFilePath, $newFilePath);
                            }

                            $fileUpload->file_name = $newFileName;

                        }



                        $fileUpload->title = $request->title;
                        $fileUpload->loan_type_id = $request->loan_type_id;
                        $fileUpload->save();
                    }




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
            $data = FormUpload::where(['id' => $request->id])->select('id', 'title', 'loan_type_id','file_name')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = FormUpload::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Models Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = FormUpload::whereIn('id',request('ids'))->delete() ;
        return response()->json(['status' => 'success', 'data' => 'Models Deleted Successfully']);

    }



}
