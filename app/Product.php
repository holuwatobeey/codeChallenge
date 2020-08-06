<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'quantity', 'image'
    ];
    public function categories()
    {
        return $this->hasOne(Category::class);
    }
}
