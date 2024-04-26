<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
            Order::with('bus', 'driver')->select(['id', 'contact_name', 'contact_phone', 'start_rent_date', 'total_rent_days', 'bus_id', 'driver_id'])->get()
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "bus_id" => "integer|min:1|required",
            "driver_id" => "integer|min:1|required",
            "contact_name" => 'string|required',
            "contact_phone" => "string|digits:12|required",
            "start_rent_date" => "date|required|after:" . now()->toDateString(),
            "total_rent_days" => "integer|min:1|required"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "invalid field"
            ], 422);
        }

        $order = [
            "bus_id" => $request->bus_id,
            "driver_id" => $request->driver_id,
            "contact_name" => $request->contact_name,
            "contact_phone" => $request->contact_phone,
            "start_rent_date" => $request->start_rent_date,
            "total_rent_days" => $request->total_rent_days
        ];

        $orders = Order::all();

        foreach($orders as $o) {
            $orderDate = Carbon::parse($order['start_rent_date'])->toDateString();
            $oDate = Carbon::parse($o->start_rent_date)->addDays($o->total_rent_days)->toDateString();
            if(($order['bus_id'] == $o->bus_id || $order['driver_id'] == $o->driver_id) && $orderDate <= $oDate) {
                return response()->json([
                    "message" => "conflicting order or no available bus or driver"
                ], 401);
            }
        }

        Order::create($order);
        return response()->json([
            "message" => "create order success"
        ], 200);
    }
    public function delete($order_id)
    {
        Order::find($order_id)->delete();
        return response()->json([
            "message" => "delete order success"
        ], 200);

    }
}
