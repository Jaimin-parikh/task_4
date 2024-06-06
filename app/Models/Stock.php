<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        "ingredient_id","item","quantity"];

        protected function quantity(): Attribute
        {
            return Attribute::make(
                get: fn (string $q) => $q."grams",

            );
        }
}
