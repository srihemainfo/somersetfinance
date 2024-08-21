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
    CustomDetail
    
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



class CustomerController extends Controller
{


    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index(Request $request)
    {



        // $alldatas = $this->allDataController->index();

        if ($request->ajax()) {

            // customer_details

            $query = CustomDetail::query()->select(sprintf('*', (new CustomDetail)->table));
            $table = Datatables::of($query);

            // $query = CustomDetail::query(); // Start building the query from the CustomDetail model
            // $table = Datatables::of($query);

            // If you want to select all columns from the CustomDetail table
            // $table->select('*');

            // $query = CustomDetail::query()->select(sprintf('%s.*', (new CustomDetail)->table));
            // // dd( $query );
            // $table = Datatables::of($query);

            // dd($table );

            // $query = CustomDetail::query()->select(sprintf('%s.*', (new CustomDetail)->table));
            // $table = Datatables::of($query);

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

        return view('admin.customerDetails.index');


        // $adminid = Session::get('adminid');

        // if (!(isset($adminid))) {
        //     return Redirect::to("/");
        // }

        // $getmyprofiledatas     = DB::select("select * from adminusers where email = '" . $adminid . "' limit 0,1");
        // $create_by             = $getmyprofiledatas[0]->id;

        // $success                        = '';
        // $currenttab                        = 1;
        // $editid                            = '';
        // $successtype                    = '';


        // $alldatas['userinfo']             = Common::userinfo();
        // $alldatas['toprightmenu']         = Common::toprightmenu();
        // $alldatas['mainmenu']             = Common::mainmenu();
        // $alldatas['footer']             = Common::footer();
        // $alldatas['sidenavbar']         = Common::sidenavbar();
        // $alldatas['rightsidenavbar']     = Common::rightsidenavbar();

        // $alldatas['fetchalldepartment']     = Common::fetchalldepartment();


        //     $LatestEvent = DB::table('sell_vehicle_details')->whereNull('deleted_at')->orderBy('shord_order', 'ASC')->get();


        //     return view('lastevent.eventlist', compact('alldatas', 'LatestEvent'));
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = CustomDetail::where(['id' => $request->id])->select('id', 'name', 'email','phone')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {
        if (isset($request->email) ) {
            if ($request->id == '') {
                $count = CustomDetail::where(['email' => $request->email])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'CustomDetail Already Exist.']);
                } else {
                    $store = CustomDetail::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'CustomDetail Created Successfully']);
            } else {
                $count = CustomDetail::whereNotIn('id', [$request->id])->where(['email' => $request->email])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'CustomDetail Already Exist.']);
                } else {
                    $update = CustomDetail::where(['id' => $request->id])->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,

                    ]);
                }
                return response()->json(['status' => true, 'data' => 'CustomDetail Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'CustomDetail Not Created']);
        }
    }

    public function edit(Request $request)
    {
        
        
        if (isset($request->id)) {
        
            $data = CustomDetail::where(['id' => $request->id])->select('id', 'name', 'email','phone','address1')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            
             $customerDetails = CustomDetail::find($request->id);
          
            if ($customerDetails) {
                
                if($customerDetails->application2()->exists()){
                    
                     return response()->json(['status' => 'error', 'data' => 'This Customer are used in case list']);
                }
                    
                $customerDetails->delete();
            }else{
                  return response()->json(['status' => 'error', 'data' => 'Customer Not Delete']);
            }
            
            return response()->json(['status' => 'success', 'data' => 'CustomDetail Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $ids = request('ids');
        $customers = CustomDetail::whereIn('id', $ids)->get();
        $status = false;
    
        foreach ($customers as $customer) {
            if (!$customer->application2()->exists()) {
                $customer->delete();
                $status = true;
            }
        }
    
        if ($status) {
            return response()->json(['status' => 'success', 'data' => 'Customer(s) Successfully Deleted']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'This Customer are used in case list']);
        }
    }





}
