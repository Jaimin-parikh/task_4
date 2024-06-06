<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\StockInward;

class StockInwardObserver
{
    public function created(StockInward $inward)
    {
        $this->updateStock($inward);
    }

    public function updated(StockInward $inward)
    {
        $this->updateStock($inward);
    }

    protected function updateStock(StockInward $inward)
    {
        $ingredient_id = $inward->ingredient_id;
        $item = $inward->ingredient->name;
        $quantity = $inward->quantity;

        $stock = Stock::firstOrNew(['ingredient_id' => $ingredient_id]);
        $stock->item = $item;
        $stock->quantity += $quantity;
        $stock->save();
    }
}
