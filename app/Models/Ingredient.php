<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'quant_grams'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('quantity');
    }
}
