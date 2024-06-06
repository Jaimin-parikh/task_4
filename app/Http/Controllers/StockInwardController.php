<?php

namespace App\Http\Controllers;

use App\Models\StockInward;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockInwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = StockInward::with('ingredient', 'vendor')->get();
        $count = $items == null ? 0 : $items->count();

        return response()->json([
            "total_records" => $count,
            "data" => $items,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'vendor_id' => ['required', "exists:vendors,id"],
            'ingredient_id' => ['required', "exists:ingredients,id", "unique:stock_inwards,ingredient_id"],
            'quantity' => ['required', "between:1,10000"],
        ], ["ingredient_id.unique" => "You've already bhought this"]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        //Store into data
        try {
            $stockInward = StockInward::create($request->all());
            return response()->json($stockInward, 201);
        } catch (Exception $e) {
            return response()->json(["message" => "Internal server Eroor"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'numeric', 'between:1,10000'],
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        // Retrieve the model instance
        $item = StockInward::find($id);

        // Check if item exists
        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        // Update the model instance
        $item->update($validator->validated());

        return response()->json(["message" => $item], 200);
    }
}
