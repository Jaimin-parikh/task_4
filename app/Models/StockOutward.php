<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOutward extends Model
{
    use HasFactory;
    
    protected $fillable = ['ingredient_id', 'quantity','reason'];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
