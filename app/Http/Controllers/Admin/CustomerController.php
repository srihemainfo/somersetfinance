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
            $table->editColumn('address1', function ($row) {
                
                $address1 = $row->address1 ?? '';
                $address2 = $row->address2 ?? '';
                
                // Remove trailing comma from address1 if it exists
                $address1 = rtrim($address1, ',');
                
                // Determine the appropriate separator
                $comma = ($address1 && $address2) ? ',<br>' : '';
                return $address1.$comma.$address2 ;
                
            });

            $table->editColumn('actions', function ($row) {
                $viewFunct = 'viewCustomer';
                $editFunct = 'editCustomer';
                $deleteFunct = 'deleteCustomer';
                $viewGate = 'customer_show'; // need to change gate permission
                $editGate = 'customer_edit';
                $deleteGate = 'customer_delete';
                $crudRoutePart = 'customerusers';

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


            $table->rawColumns(['actions', 'placeholder','address1']);
            return $table->make(true);
        }

        return view('admin.customerDetails.index');

    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = CustomDetail::where(['id' => $request->id])->select('id', 'name', 'email','address1','phone','last_name', 'address2')->first();
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
                    return response()->json(['status' => false, 'data' => 'Customer Detail Already Exist.']);
                } else {
                    $store = CustomDetail::create([
                        'name' => $request->name,
                         'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address1' => $request->address1,
                        'address2' => $request->address2,
                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Customer Detail Created Successfully']);
            } else {
                $count = CustomDetail::whereNotIn('id', [$request->id])->where(['email' => $request->email])->count();
                if ($count > 0) {
                    return response()->json(['status' => false, 'data' => 'Customer Detail Already Exist.']);
                } else {
                    $update = CustomDetail::where(['id' => $request->id])->update([
                        'name' => $request->name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address1' => $request->address1,
                        'address2' => $request->address2,

                    ]);
                }
                return response()->json(['status' => true, 'data' => 'Customer detail Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Customer detail Not Created']);
        }
    }

    public function edit(Request $request)
    {
        
        
        if (isset($request->id)) {
        
            $data = CustomDetail::where(['id' => $request->id])->select('id', 'name', 'email','phone','address1', 'last_name','address2')->first();
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
                    
                $customerDetails->forceDelete();
            }else{
                  return response()->json(['status' => 'error', 'data' => 'Customer Not Delete']);
            }
            
            return response()->json(['status' => 'success', 'data' => 'Customer detail Deleted Successfully']);
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
                $customer->forceDelete();
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
