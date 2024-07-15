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
    SellerEnquire,
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



class SellerEnquireController extends Controller
{


    public function index(Request $request)
    {




        if ($request->ajax()) {

            $query = SellerEnquire::query()->whereNull('deleted_at')->select('id', 'first_name', 'email', 'phone', 'model','brand', 'created_at','status');
            $table = Datatables::of($query);



            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {

                $viewFunct = 'viewBuyerEnquire';
                $editFunct = 'editBuyerEnquire';
                $deleteFunct = 'deleteBuyerEnquire';
                $viewGate = 'seller_enquire_show'; // need to change gate permission
                $editGate = 'seller_enquire_edit';
                $deleteGate = 'seller_enquire_delete';
                $crudRoutePart = 'buyer_enquire';

                return view('partials.ajaxTableActions', compact(

                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'row',
                ));
            });

            $table->editColumn('created_at', function ($row) {

                return $row->created_at ? date("d-m-Y", strtotime($row->created_at)) : ''; 
                
            });

            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.seller_enqurie.index');
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = SellerEnquire::where(['id' => $request->id])->select('id','first_name','last_name','phone','email', 'post_code', 'brand', 'model','registration_no','year','millage','colour','fuel_type','transmission','service_history','service_book_history','last_service_date','last_service_millage','number_of_owner','car_modified','tyre_brand','tyre_condition','damage_report','outstanding_finance','desired_value','status')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {


        if (isset($request->status)) {
            if ($request->id == '') {
              
                    $store = SellerEnquire::create([
                        'status' => $request->status,
                    ]);
                
                return response()->json(['status' => true, 'data' => 'Buyer Enquire Created Successfully']);
            } else {
               
                    $update = SellerEnquire::where(['id' => $request->id])->update([
                        'status' => $request->status,
                    ]);
                
                return response()->json(['status' => true, 'data' => 'Buyer Enquire Updated Successfully']);
            }
        } else {
            return response()->json(['status' => false, 'data' => 'Buyer Enquire Not Created']);
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->id)) {
            $data = SellerEnquire::where(['id' => $request->id])->select('id','first_name','last_name','phone','email', 'post_code', 'brand', 'model','registration_no','year','millage','colour','fuel_type','transmission','service_history','service_book_history','last_service_date','last_service_millage','number_of_owner','car_modified','tyre_brand','tyre_condition','damage_report','outstanding_finance','desired_value','status')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = SellerEnquire::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Brand Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = SellerEnquire::whereIn('id', request('ids'))->delete();
        return response()->json(['status' => 'success', 'data' => 'Brands Deleted Successfully']);
    }
}
