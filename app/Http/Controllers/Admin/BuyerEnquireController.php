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
    BuyerEnquire
};
// use App\Http\Controllers\AllDataController;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;



class BuyerEnquireController extends Controller
{


    public function index(Request $request)
    {




        if ($request->ajax()) {

            $query = BuyerEnquire::query()->whereNull('deleted_at')->select('id', 'name', 'email', 'phone', 'message', 'created_at', 'status', 'ref_id');
            $table = Datatables::of($query);



            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {



                $websiteURL = env('WEB_URL', 'https://tskautos.co.uk');
                $ref_id  = str_replace('#', '', $row->ref_id);

                $buyerneed = SellVehicleDetails::where('ref_id', $row->ref_id)->select('brand', 'model')->first();
                // dd($buyerneed, $row);

                $brand_details = str_replace(' ', '_', $buyerneed->brandd->brand ?? '');
                $model_details = str_replace(' ', '_', $buyerneed->ModelV->model ?? '');
                $weburl = $websiteURL . '/viewcar/' . $brand_details . '-' . $model_details . '-' . $ref_id;
                // dd( );
                // $explode = explode('-',  $weburl) ;
                // $weburl = (count($explode ) < 3 && isset($explode['0'] ) &&  (isset($explode['1'] ) &&  $explode[1] != ''  )&& (isset($explode['2']) &&  $explode[2] != '' )&& (isset($explode['3']) &&  $explode[3] != '' ) ) ? $websiteURL : $weburl ;
                $explode = explode('-', $weburl);
                $weburl = (count($explode) >= 3 &&
                isset($explode[0]) && $explode[0] != '' &&
                isset($explode[1]) && $explode[1] != '' &&
                isset($explode[2]) && $explode[2] != '' 
                ) ? $weburl : $websiteURL;

                $viewFunct = 'viewBuyerEnquire';
                $editFunct = 'editBuyerEnquire';
                $deleteFunct = 'deleteBuyerEnquire';
                $previewGate  = 'seller_enquire_show';
                $viewGate = 'seller_enquire_show'; // need to change gate permission
                $editGate = 'buyer_enquire_edit';
                $deleteGate = 'buyer_enquire_delete';
                $crudRoutePart = 'buyer_enquire';

                return view('partials.ajaxTableActions', compact(

                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewFunct',
                    'editFunct',
                    'deleteFunct',
                    'previewGate',
                    'row',
                    'weburl'
                ));
            });


            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }

        return view('admin.buyer_enqurie.index');
    }

    public function view(Request $request)
    {
        if (isset($request->id)) {
            $data = BuyerEnquire::where(['id' => $request->id])->select('id', 'name', 'email', 'phone', 'message', 'created_at', 'status', 'ref_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }

    public function store(Request $request)
    {


        if (isset($request->status)) {
            if ($request->id == '') {
               
                    $store = BuyerEnquire::create([
                        'status' => $request->status,
                    ]);
                
                return response()->json(['status' => true, 'data' => 'Buyer Enquire Created Successfully']);
            } else {
               
                    $update = BuyerEnquire::where(['id' => $request->id])->update([
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
            $data = BuyerEnquire::where(['id' => $request->id])->select('id', 'name', 'email', 'phone', 'message', 'created_at', 'status', 'ref_id')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = BuyerEnquire::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'Brand Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = BuyerEnquire::whereIn('id', request('ids'))->delete();
        return response()->json(['status' => 'success', 'data' => 'Brands Deleted Successfully']);
    }
}
