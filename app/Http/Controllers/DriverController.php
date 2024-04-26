<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        return response()->json([
            Driver::select(['id', 'name', 'age', 'id_number'])->get()
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string|required",
            "age" => "integer|min:18|required",
            "id_number" => "string|min:16|required",
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "invalid field"
            ], 422);
        }

        $driver = [
            "name" => $request->name,
            "age" => $request->age,
            "id_number" => $request->id_number,
        ];

        Driver::create($driver);

        return response()->json([
            "message" => "create driver success"
        ], 200);
    }
    public function update($driver_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "age" => "integer|min:18",
            "id_number" => "string|min:16",
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "invalid field"
            ], 422);
        }

        $driver = [
            "name" => $request->name,
            "age" => $request->age,
            "id_number" => $request->id_number,
        ];

        Driver::find($driver_id)->update($driver);

        return response()->json([
            "message" => "update driver success"
        ], 200);

    }
    public function delete($driver_id)
    {
        Driver::find($driver_id)->delete();
        return response()->json([
            "message" => "delete driver success"
        ], 200);
    }
}
