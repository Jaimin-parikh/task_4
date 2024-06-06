<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return response()->json([
            "Available Items" => Stock::all()->pluck('quantity','item')
        ], 200);
    }
}
