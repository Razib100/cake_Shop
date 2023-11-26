<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_id','price','quantity','sub_total'];

    public function Product(){
        return $this->belongsTo('App\Models\Products','product_id');
    }
}
