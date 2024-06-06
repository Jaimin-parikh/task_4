<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["message" => Ingredient::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation Rules
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'alpha:ascii', 'max:255', 'unique:ingredients,name'],
            'price' => ['required', 'numeric', 'between:0.00,9999.99'],
            'quant_grams' => ['required', "numeric", "between:10,10000",],
        ], ["name.unique" => "you've already ordered this"]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        try {

            $ingredient = Ingredient::create([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'quant_grams' => $request->input('quant_grams'),
            ]);
            if ($ingredient) {
                return response()->json(["message" => $ingredient], 201);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ingredient)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'alpha:ascii', 'max:255', 'exists:ingredients,name'],
            'price' => ['required', 'numeric', 'between:0.00,9999.99'],
            'quant_grams' => ['required', "numeric", "between:10,10000",],
        ], ["name.exists" => "You do not have order this item "]);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        $item = Ingredient::where("name", $ingredient);

        if ($item->update($request->all()))
            return response()->json(["message" => $item->get()], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ingredient)
    {
        try {
            $item = Ingredient::where("name", $ingredient)->first();
            if ($item) {
                $item->delete();
                return response()->json("Ingredient Deleted", 200);
            } else {
                return response()->json("The ingredient does not exist", 404);
            }
        } catch (Exception $e) {
            return response()->json(["message" => "Internal Server Error"], 500);
        }
    }
}
