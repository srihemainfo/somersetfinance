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
    Brand,
    Models,
    EventImage,
    PurchaseDetails
};
// use App\Http\Controllers\AllDataController;

class LatestEventController extends Controller
{


    // protected $allDataController;

    // public function __construct()
    // {
    //     $this->allDataController = new AllDataController();
    // }

    public function index()
    {




        // dd($Event = SellVehicleDetails::find(14)->PurchaseDetails);
        // dd(Brand::find(1)->sellVehicleDetails) ;
        // dd(SellVehicleDetails::find(15)->brandd) ;
        // $alldatas = $this->allDataController->index();


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


        $LatestEvent = SellVehicleDetails::orderBy('shord_order', 'ASC')->get();


        return view('admin.sellVehicleDetails.index', compact('LatestEvent'));
    }

    public function StatusUpdate(Request $request, $id)
    {
        $appointment = DB::table('sell_vehicle_details')->where('id', $id)->first();
        if (!$appointment) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found'
            ]);
        }

        DB::table('sell_vehicle_details')->where('id', $id)->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Event status has been updated'
        ]);
    }

    public function create()
    {




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
        $alldatas['vehicle_features']     = VehicleFeatures::whereNotNull('name')->get();
        $alldatas['brand'] = Brand::pluck('brand', 'id')->prepend('Select Brand', '');



        return view('admin.sellVehicleDetails.create', compact('alldatas'));
        // return view('lastevent.create_event', compact('alldatas'));
    }

    public function store(Request $request)
    {

        // dd($request);





        $customMessages = [
            'customer_name.required' => 'The Client name field is required.',
            'customer_number.required' => 'The Client contact field is required.',
            'display_date.required' => 'The Date field is required',
            'new_old.required' => 'The Vehicle status field is required',
            'fuel_type.required' => 'The Fuel type field is required',
            'engine_cc.required' => 'The Engine Capacity field is required',
            'registration_no.required' => 'The Registration Number is required',
            'millage.required' => 'The Mileage is required',
        ];

        // dd( $request->all());
        $data = $request->all();
        $this->validate($request, [
            'model' => 'required',
            'brand' => 'required',
            'year' => 'required',
            'country' => 'required',
            'city' => 'required',
            'transmission' => 'required',
            'new_old' => 'required',
            'engine_cc' => 'required',
            'fuel_type' => 'required',
            'colour' => 'required',
            'event_image' => 'nullable|image|max:2048',
            // 'status' => 'required',
            'customer_name' => 'required',
            'display_date' => 'required',
            'customer_number' => 'required',
            'vehicle_price' => 'required',
            'millage' => 'required',
            'registration_no' => 'required',
            // 'event_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            //  'event_image' => 'required|image|max:2048|dimensions:width=700,height=300',
            //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
            'content' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('content') == '<p><br></p>') {
                        $fail('The Content Filed is required.');
                    }
                }
            ],
            // 'shord_order' => 'required',
        ], $customMessages);

        $imageName = null;
        if ($request->hasFile('event_image')) {
            $file = $request->file('event_image');
            if ($file->isValid()) {
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('Event'), $imageName);
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to upload image.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'No image file selected.'])->withInput();
        }
        // dd($request->all());

        $slug = Str::slug($request->brand, '-');
        $data['brand'] =   $slug;
        $data['image'] =   $imageName;
        $data = $request->all();
        // dd(  $data);
        //   $SellVehicleDetails =   SellVehicleDetails::insert([
        //         'brand' => $request->brand,
        //         'event_slug' => $slug,
        //         'model' => $request->model,
        //         'year' => $request->year,
        //         'country' => $request->country,
        //         'city' => $request->city,
        //         'transmission' => $request->transmission,
        //         'new_old' => $request->new_old,
        //         'engine_cc' => $request->engine_cc,
        //         'fuel_type' => $request->fuel_type,
        //         'colour' => $request->colour,
        //         'image' => $imageName,
        //         'customer_name' => $request->customer_name,
        //         'display_date' => $request->display_date,
        //         'customer_number' => $request->customer_number,
        //         'vehicle_price' => $request->vehicle_price,
        //         'content' => $request->content,
        //         // 'status' => $request->status,
        //         // 'shord_order' => $request->shord_order,

        //     ]);

        //     if($request->vehicle_features != ''){

        //         $SellVehicleDetails ->SellVehicleFeatures->attach($request->vehicle_features) ;
        //     }
        // dd('test');

        $SellVehicleDetails = SellVehicleDetails::create([
            'brand' => $request->brand,
            'event_slug' => $slug,
            'model' => $request->model,
            'registration_no' => $request->registration_no,
            'year' => $request->year,
            'country' => $request->country,
            'city' => $request->city,
            'transmission' => $request->transmission,
            'new_old' => $request->new_old,
            'engine_cc' => $request->engine_cc,
            'fuel_type' => $request->fuel_type,
            'colour' => $request->colour,
            'vehicle_price' => $request->vehicle_price,
            'image' => $imageName,
            'customer_name' => $request->customer_name,
            'display_date' => $request->display_date,
            'customer_number' => $request->customer_number,
            'content' => $request->content,
            'millage' => $request->millage,
            // 'status' => $request->status,
            // 'shord_order' => $request->shord_order,
        ]);
        $ref_id = "#" . time() . $SellVehicleDetails->id;
        $SellVehicleDetails->update(['ref_id' => $ref_id]);


        if ($request->vehicle_features != '') {
            $SellVehicleDetails->SellVehicleFeatures()->attach($request->vehicle_features);
        }




        // DB::table('sell_vehicle_details')->insert([
        //         'title' => $request->title,
        //         'event_slug' => $slug,
        //         'status' => $request->status,
        //         'display_date' => $request->display_date,
        //         'content' => $request->content,
        //         // 'shord_order' => $request->shord_order,
        //         'image' => $imageName,
        //         // 'created_at' => now(),
        //         // 'updated_at' => now(),
        //     ]);






        // SellVehicleDetails::create

        // DB::table('sell_vehicle_details')->insert([
        //     'title' => $request->title,
        //     'event_slug' => $slug,
        //     'status' => $request->status,
        //     'display_date' => $request->display_date,
        //     'content' => $request->content,
        //     'shord_order' => $request->shord_order,
        //     'image' => $imageName,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        $buttonValue = $request->input('submit_button');

        if ($buttonValue === 'button2') {

            return redirect()->back()->with('success', 'Vehicle Details has been Added successfully.');

            // return "Button 1 was clicked!";
        }

        return redirect()->route('admin.sellvehicle.index')->with('success', 'Vehicle Details has been Added successfully.');
    }

    public function edit(Request $request, $id)
    {

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
        $alldatas['vehicle_features']     = VehicleFeatures::whereNotNull('name')->get();

        //  $check = VehicleFeatures::whereNotNull('name') ->group_by('Desc')->first() ;

        $EvetList = SellVehicleDetails::where('id', $id)->first();

        $data2 = $EvetList->SellVehicleFeatures;
        $data3 = $data2->map(function ($data2) {
            return $data2->id;
        })->toArray();


        // dd($EvetList->vehicleFeatures->contains('13')) ;
        // dd($EvetList->SellVehicleFeatures );
        // dd($EvetList->SellVehicleFeatures);
        $alldatas['brand'] = Brand::pluck('brand', 'id')->prepend('Select Brand', '');

        return view('admin.sellVehicleDetails.edit_event', compact('alldatas', 'EvetList', 'data3'));
    }

    public function update(Request $request, $id)
    {



        // $this->validate($request, [
        //     'title' => 'required',
        //     'status' => 'required',
        //     'display_date' => 'required',
        //     // 'event_image' => 'nullable|image|max:2048', // Change 'required' to 'nullable'
        //     //'event_image' => 'nullable|image|max:2048|dimensions:width=700,height=300',
        //     //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
        //       'event_image' => 'nullable|image|max:2048',
        //     'content' => 'required',
        //     'shord_order' => 'required',
        // ]);

        $customMessages = [
            'customer_name.required' => 'The Client name field is required.',
            'customer_number.required' => 'The Client number field is required.',
            'display_date.required' => 'The Date field is required',
            'new_old.required' => 'The Vehicle status field is required',
            'fuel_type.required' => 'The Fuel type field is required',
            'engine_cc.required' => 'The Engine Capacity field is required',
            'registration_no.required' => 'The Registration Number is required',
            'millage.required' => 'The Mileage is required',

        ];

        // dd( $request->all());
        $data = $request->all();
        $data['content'] = $data['content'] == '<p><br></p>' ? '' : $data['content'];
        // $request->content =$request ->content == '<p><br></p>' ? '' : $request -> content;
        $this->validate($request, [
            'model' => 'required',
            'brand' => 'required',
            'year' => 'required',
            'country' => 'required',
            'city' => 'required',
            'transmission' => 'required',
            'new_old' => 'required',
            'engine_cc' => 'required',
            'fuel_type' => 'required',
            'colour' => 'required',
            'event_image' => 'nullable|image|max:2048',
            // 'status' => 'required',
            'customer_name' => 'required',
            'display_date' => 'required',
            'customer_number' => 'required',
            'vehicle_price' => 'required',
            'millage' => 'required',
            'registration_no' => 'required',

            // 'event_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            //  'event_image' => 'required|image|max:2048|dimensions:width=700,height=300',
            //   'event_image' => 'nullable|image|max:2048|dimensions:width=830,height=470',
            'content' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('content') == '<p><br></p>') {
                        $fail('The Content Filed is required.');
                    }
                }
            ],
            // 'shord_order' => 'required',
        ], $customMessages);



        $event = DB::table('sell_vehicle_details')->where('id', $id)->first();

        $imageName = $event->image; // Preserve the existing image name by default

        if ($request->hasFile('event_image')) {
            $file = $request->file('event_image');
            if ($file->isValid()) {
                // Remove the old image file
                if ($imageName && file_exists(public_path('Event/' . $imageName))) {
                    unlink(public_path('Event/' . $imageName));
                }
                // Upload the new image file
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('Event'), $imageName);
            } else {
                return redirect()->back()->withErrors(['error' => 'Failed to upload image.'])->withInput();
            }
        }
        $slug = Str::slug($request->title, '-');

        $SellVehicleDetails = SellVehicleDetails::find($id);

        if (!$SellVehicleDetails) {
            // Handle case when SellVehicleDetails with given ID doesn't exist
        }


        // dd($request->country);
        $SellVehicleDetails->update([
            'brand' => $request->brand,
            'event_slug' => $slug,
            'model' => $request->model,
            'registration_no' => $request->registration_no,
            'year' => $request->year,
            'country' => $request->country,
            'city' => $request->city,
            'transmission' => $request->transmission,
            'new_old' => $request->new_old,
            'engine_cc' => $request->engine_cc,
            'fuel_type' => $request->fuel_type,
            'colour' => $request->colour,
            'millage' => $request->millage,
            'image' => $imageName,
            'customer_name' => $request->customer_name,
            'display_date' => $request->display_date,
            'customer_number' => $request->customer_number,
            'vehicle_price' => $request->vehicle_price,
            'content' => $request->content,
        ]);

        if ($request->vehicle_features != '') {
            $SellVehicleDetails->SellVehicleFeatures()->sync($request->vehicle_features);
        }


        // // dd($request->all(), $id);
        // $SellVehicleDetails =   SellVehicleDetails::where('id', $id)->update([
        //     'brand' => $request->brand,
        //     'event_slug' => $slug,
        //     'model' => $request->model,
        //     'year' => $request->year,
        //     'country' => $request->country,
        //     'city' => $request->city,
        //     'transmission' => $request->transmission,
        //     'new_old' => $request->new_old,
        //     'engine_cc' => $request->engine_cc,
        //     'fuel_type' => $request->fuel_type,
        //     'colour' => $request->colour,
        //     'image' => $imageName,
        //     'customer_name' => $request->customer_name,
        //     'display_date' => $request->display_date,
        //     'customer_number' => $request->customer_number,
        //     'vehicle_price' => $request->vehicle_price,
        //     'content' => $request->content,

        // ]);


        // if ($request->vehicle_features != '') {

        //     $SellVehicleDetails->SellVehicleFeatures()->sync($request->vehicle_features);
        // }


        // DB::table('sell_vehicle_details')->where('id', $id)->update([
        //     'title' => $request->title,
        //     'event_slug' => $slug,
        //     'status' => $request->status,
        //     'display_date' => $request->display_date,
        //     'content' => $request->content,
        //     'shord_order' => $request->shord_order,
        //      'image' => $imageName,
        //     'updated_at' => now(), // Update only 'updated_at' without modifying 'created_at'
        // ]);
        $buttonValue = $request->input('submit_button');
        if ($buttonValue === 'button2') {

            return redirect()->back()->with('success', 'Vehicle Details  has been updated successfully.');

            // return "Button 1 was clicked!";
        }
        return redirect()->route('admin.sellvehicle.index')->with('success', 'Vehicle Details  has been updated successfully.');
        //return redirect()->route('eventlist.index')->with('success', 'Event has been updated successfully.');
    }

    public function destroy($id)
    {
        $event = DB::table('sell_vehicle_details')->where('id', $id)->first();

        if ($event) {
            DB::table('sell_vehicle_details')->where('id', $id)->update([
                'deleted_at' => now(), // Set deleted_at to current timestamp
            ]);
            return redirect()->route('admin.sellvehicle.index')->with('success', 'SellVehicle Details  has been Deleted successfully.');
        }
        return redirect()->route('admin.sellvehicle.index')->with('error', 'Event not found.');
    }


    public function ImageUpload(Request $request)
    {
        $directory = 'Event';
        $imageDetails = [];
        $Event = SellVehicleDetails::where('id', $request->eventslug)->first();
        $Event->EventImages->delete();
        foreach ($request->file('service_content_image') as $image) {
            $filename = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path($directory), $filename);
            $imageDetails[] = [
                'event_id' => $Event->id,
                'image' => $filename,
                'created_at' => now() // Use Laravel's built-in now() function to get the current date and time
            ];
        }
        $ins = DB::table('event_image')->insert($imageDetails);
        return response()->json($ins == true ? ['status' => 200, 'message' => 'Successfully Uploaded'] : ['status' => 400, 'message' => 'Failed to upload']);
    }

    public function GetImage(Request $request)
    {
        $Event = SellVehicleDetails::where('id', $request->eventslug)->first();
        $service_content_images = EventImage::where('event_id', '=', $Event->id)
            ->get();
        return response()->json($service_content_images->count() > 0 ? ['status' => 200, 'data' => $service_content_images] : ['status' => 400, 'message' => 'Data Not Found']);
    }

    public function DeleteImage(Request $request)
    {

        $status =  EventImage::whereIn('id', $request->id)->delete();
        // dd($request->id)
        // $service_content_image = DB::table('event_image')->where('id', '=', $request->id)
        //     ->get();
        // $service_content_image_delete = DB::table('event_image')->where('id', '=', $request->id)
        //     ->delete();
        return response()->json($status == true ? ['status' => 200, 'message' => 'Event image deleted',] : ['status' => 400, 'message' => 'Failed',]);
    }

    public function model_get(Request $request)
    {

        $input = $request->get('option');
        $brand = Brand::find($input) ?? [];
        $model = $brand->Model() ?? [];

        return response()->json($model->get(['id', 'model']));
    }

    public function purchase(Request $request, $id)
    {

        $Event = SellVehicleDetails::where('id', $id)->first();
        return view('admin.sellVehicleDetails.purchase', compact('Event'));
    }

    public function purchaseupdate(Request $request, $id)
    {

        $sellvehicle = SellVehicleDetails::findOrFail($id);

        DB::beginTransaction();

        // dd($request->all());

        try {
            
            if (!is_array($request->input('purchase_price')) ||  !is_array($request->input('purchase_titles'))) {
                return redirect()->back()->with('error', 'Failed to update Purchase Details. Please the add the column and fill the purchase and expensive details.');
            }

            $sellvehicle->PurchaseDetails()->forceDelete();

            if (count($request->input('purchase_price')) > 0) {

                $purchaseDetails = [];
                $si = 0;
                foreach ($request->input('purchase_price') as $key => $purchase_price) {


                    $id = $request->input('purchase_id')[$si];

                    if ( $purchase_price && $request->input('purchase_titles')[$si]) {

                        $data = [
                            'sell_vehicle_details_id' => $sellvehicle->id,
                            'purchase_title' => $request->input('purchase_titles')[$si],
                            'purchase_price' => $purchase_price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        
                            $purchaseDetails[] = $data;  
                    }


                    $si++;
                }

                if (!empty($purchaseDetails)) {
                    PurchaseDetails::insert($purchaseDetails);
                }
            }

            DB::commit();

            $buttonValue = $request->input('submit_button');
            if ($buttonValue === 'button2') {
                return redirect()->back()->with('success', 'Purchase Details have been updated successfully.');
            }

            return redirect()->route('admin.sellvehicle.index')->with('success', 'Purchase Details have been updated successfully.');
        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->back()->with('error',   'Failed to update Purchase Details. Please try again.');
        }
    }
}
