<?php

namespace App\Models;

use App\Observers\StockInwardObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// #[ObservedBy(StockInwardObserver::class)]
class StockInward extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'ingredient_id', 'quantity'];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
