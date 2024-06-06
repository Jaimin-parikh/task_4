<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\StockOutward;

class StockOutwardObserver
{

    public function created(StockOutward $outward)
    {
        $this->updateStock($outward);
    }

    public function updated(StockOutward $outward)
    {
        $this->updateStock($outward);
    }

    protected function updateStock(StockOutward $outward)
    {
        $ingredient_id = $outward->ingredient_id;
        $quantity = $outward->quantity;
        $stock = Stock::firstOrNew(['ingredient_id' => $ingredient_id]);
        $stock->quantity -= $quantity;
        $stock->save();
    }
}
