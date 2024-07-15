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
    CustomDetail,
    // CustomDetail,
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
            $data = CustomDetail::where(['id' => $request->id])->select('id', 'name', 'email','phone')->first();
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false, 'data' => 'Required Details Not Found']);
        }
    }


    public function destroy(Request $request)
    {
        if (isset($request->id)) {
            $delete = CustomDetail::where(['id' => $request->id])->update([
                'deleted_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'data' => 'CustomDetail Deleted Successfully']);
        } else {
            return response()->json(['status' => 'error', 'data' => 'Technical Error']);
        }
    }

    public function massDestroy()
    {
        $academicYears = CustomDetail::whereIn('id',request('ids'))->delete() ;
        // find(request('ids'));

        // foreach ($academicYears as $academicYear) {
        //     $academicYear->delete();
        // }
        return response(null, Response::HTTP_NO_CONTENT);
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
