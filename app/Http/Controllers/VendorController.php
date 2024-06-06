<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate Details
        $validator = Validator::make($request->all(), [
            "name" => ["required", "bail", "alpha:ascii"],
            "email" => ["required", "bail", "email", "unique:vendors,email"],
            "phone" => ["required", "bail", "numeric", "digits:10", "unique:vendors,phone"],
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }
        //Create Vendor
        try {

            $vendor = Vendor::create($request->all());
            return response()->json($vendor, 201);
        } catch (Exception $e) {
            return response()->json(["message" => "Internal Sever Error"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return response()->json([Vendor::all()], 200);
    }
}
