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
    Brand
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

        return view('admin.application.index', compact('isEditable'));



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
            $clients = CustomDetail::orderby('f_name', 'asc')->select('id', 'f_name')->distinct()->limit(6)->get();
        } else {
            $clients = CustomDetail::orderby('f_name', 'asc')->select('id', 'f_name')->where('f_name', 'like', '%' . $search . '%')->distinct()->limit(6)->get();
        }

        $response = [];
        foreach ($clients as $client) {
            $response[] = array(
                "id" => $client->id,
                "text" => $client->f_name
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
            "first_name" => ["required"],
            // "cmpny_name" => ["required"],
            'email' => ["nullable", "email", Rule::unique('customer_details')->ignore($request->customer_id)],
            "phone" => ["required", "numeric", Rule::unique('customer_details')->ignore($request->customer_id)],
            "address1" => ["nullable"],
            "remarks" => ["nullable"],
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
                    'name' => $request->first_name,
                    // 'company_name' => $request->cmpny_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address1' => $request->address1,
                    'remark' => $request->remarks,
                ]
            );

            return response()->json($data->id ? ['status' => 200, 'data' => $data, 'errors' => NULL] : ['status' => 400, 'data' => NULL, 'errors' => NULL]);
        }
    }




}
