<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockOutward;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockOutwardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules for incoming request
        $validator = Validator::make($request->all(), [
            'ingredient_id' => ['required', "exists:stock_inwards,ingredient_id", 'unique:stock_outwards,ingredient_id'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ], [
            "ingredient_id.unique" => "You can add more by updating with id : {$request->input('ingredient_id')}"
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        // Make sure that request quantity is not greater than available quantity
        $available = Stock::where('ingredient_id', $request->input('ingredient_id'))->sum('quantity');
        if ($available < $request->input('quantity')) {
            return response()->json(["message" => "Not enough stock"], 422);
        }

        // Create entry into database
        try {
            $entry = StockOutward::create([
                "ingredient_id" => $request->input('ingredient_id'),
                "quantity" => $request->input('quantity'),
                "reason" => "rotten",
            ]);

            return response($entry, 201);
        } catch (Exception $e) {
            return response()->json(["Message" => "Internal Server Error"], 500);
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

        $stock = Stock::findOrFail($id);
        $quantity = (int) $stock->quantity;

        if($quantity < $request->input('quantity')){
            return response()->json("Quantity is not enough",200);
        }
        // Retrieve the model instance from the Outward model
        $item = StockOutward::find($id);

        // Check if item exists
        if (!$item) {
            return response()->json(["message" => "Item not found"], 404);
        }

        // Update the model instance
        $item->quantity += $request->input('quantity'); // Update quantity to the new value
        $item->save(); // Save the changes to the database

        return response()->json(["message" => "Quantity updated successfully"], 200);
    }
}
