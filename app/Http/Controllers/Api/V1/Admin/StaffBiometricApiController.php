<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StaffBiometricApiController extends Controller
{
    public function check(Request $request)
    {

        // Read the input stream
        // $body = file_get_contents("php://input");
        $body = $request->getContent();
        // Decode the JSON object
        // $object = json_decode($body, true);

        // Perform necessary operations with the $object data
        // For example, insert data into the 'api_biometric' table
        $insert = DB::table('api_biometric')->insert([
           'response' => $body
        ]);

        // Return a response indicating the status of the operation
        if ($insert) {
            return response()->json(['message' => 'Data inserted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to insert data'], 500);
        }

    }
}
