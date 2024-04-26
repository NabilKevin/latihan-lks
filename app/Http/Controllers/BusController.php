<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use Illuminate\Support\Facades\Validator;

class BusController extends Controller
{
    public function index()
    {
        return response()->json([
            Bus::select(['id', 'plate_number', 'brand', 'seat', 'price_per_day'])->get()
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "plate_number" => "string|required",
            "brand" => "in:mercedes,fuso,scania|required",
            "seat" => "integer|min:1|required",
            "price_per_day" => "integer|min:1|required"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "invalid field"
            ], 422);
        }

        $bus = [
            "plate_number" => $request->plate_number,
            "brand" => $request->brand,
            "seat" => $request->seat,
            "price_per_day" => $request->price_per_day
        ];

        Bus::create($bus);

        return response()->json([
            "message" => "create bus success"
        ], 200);
    }
    public function update($bus_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "plate_number" => "string",
            "brand" => "in:mercedes,fuso,scania",
            "seat" => "integer|min:1",
            "price_per_day" => "integer|min:1"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "invalid field"
            ], 422);
        }

        $bus = [
            "plate_number" => $request->plate_number,
            "brand" => $request->brand,
            "seat" => $request->seat,
            "price_per_day" => $request->price_per_day
        ];

        Bus::find($bus_id)->update($bus);

        return response()->json([
            "message" => "update bus success"
        ], 200);

    }
    public function delete($bus_id)
    {
        Bus::find($bus_id)->delete();
        return response()->json([
            "message" => "delete bus success"
        ], 200);
    }
}
