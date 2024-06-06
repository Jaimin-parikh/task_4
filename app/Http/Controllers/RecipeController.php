<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["message" => Recipe::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:recipes,name'],
        ],);

        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()], 422);
        }

        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }


    /*
        This function will assignIngredients to the Recipes 
    */
    public function addIngredients(Request $request, Recipe $recipe)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'ingredient_id' => 'required|exists:ingredients,id',
                'quantity' => 'required|numeric|min:0',
            ]);

            // Check if the ingredient already exists 
            $existingIngredient = $recipe->ingredients()->where('ingredient_id', $validatedData['ingredient_id'])->first();

            if ($existingIngredient) {
                // If not then update the quantity
                $existingIngredient->pivot->update(['quantity' => $validatedData['quantity']]);
            } else {
                // If the ingredient doesn't exist, attach it to the recipe
                $recipe->ingredients()->attach($validatedData['ingredient_id'], ['quantity' => $validatedData['quantity']]);
            }

            return response()->json(['message' => 'Ingredient added to recipe'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error response
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            // Return error response if recipe not found
            return response()->json(['error' => 'Recipe not found'], 404);
        }
    }
}
