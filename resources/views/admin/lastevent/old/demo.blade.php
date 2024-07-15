<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Hash};
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;
use App\Models\Driver;
use App\Models\Partnerlist;

class DriverAuthController extends Controller
{
    public function login(Request $request)
    {

        // return driver::find(73)
        //         ->update(['fcm_token' => $fcm_token])->Partnerlist->count();


        $validator = $this->login_validation($request);
        $fcm_token = isset($request->fcm_token) && !empty($request->fcm_token) ? $request->fcm_token : '';

        if ($validator->fails()) {
            return response([
                'error' => 'Invalid Email/PhoneNo. or Password.',
                'request_status' => false
            ], HttpStatusCode::HTTP_BAD_REQUEST);
        } else {
            $driver = Driver::where(function ($query) use ($request) {
                $query->where('email', '=', $request->user_name)
                    ->orWhere('phone', 'LIKE', '%' . $request->user_name);
            })->first();

            if (!$driver || !Hash::check($request->password, $driver->password)) {
                return response([
                    'error' => 'Invalid Email/PhoneNo. or Password.',
                    'request_status' => false
                ], HttpStatusCode::HTTP_NOT_FOUND);
            }

            if(!$fcm_token){
                return response([
                    'error' => 'FCM token missing',
                    'request_status' => false
                ], HttpStatusCode::HTTP_NOT_FOUND);
            }

               $count =  $driver ->Partnerlist->count();

               if($count == 1){

                   $partnerListMysqlId = $driver ->Partnerlist->db_key ;

            Driver::where('id', $driver->id)
                ->update(['fcm_token' => $fcm_token , 'switch_partner_account' => $partnerListMysqlId ]);
                $account = [];
               }else{

                $account =  $driver ->Partnerlist;
               }

                //



            $token = $driver->createToken('my-app-token')->plainTextToken;


            $response = [
                'token' => $token,
                'request_status' => true,
                'switch_count' => $count,
                'switch_account' => $account
            ];

            return response($response, HttpStatusCode::HTTP_ACCEPTED);
        }
    }

    public function switch(Request $request){

        $driver_id =  $request->user()->id;

        $driver = Driver::find($driver_id);

        $token = $driver->fcm_token ;
        $partnerListMysqlId = $request->switch_account;

        //    $partnerListMysqlId = $driver ->Partnerlist->db_key ;

            Driver::where('id', $driver->id)
                ->update(['switch_partner_account' => $partnerListMysqlId ]);
                $account = [];


                $response = [
                'token' => $token,
                'request_status' => true,
                'switch_count' => '',
                'switch_account' => []
            ];

            return response($response, HttpStatusCode::HTTP_ACCEPTED);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $response = [
            'message' => "You're successfully logged out.",
            'request_status' => true
        ];

        return response($response, HttpStatusCode::HTTP_ACCEPTED);
    }

    private function login_validation($request = null)
    {
        if (is_numeric($request->user_name)) {
            return Validator::make($request->all(), [
                "user_name" => ["required", "digits:10"],
                "password" => ["required"]
            ], [
                "user_name.digits" => "The user name must be valid email or 10 digits number."
            ]);
        } else {
            return Validator::make($request->all(), [
                "user_name" => ["required", "email"],
                "password" => ["required"]
            ]);
        }
    }
}
