<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category','name','sku','description','price','status'];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];

    public function Category(){
        return $this->belongsTo('App\Models\Category','category');
    }

    public function ProductImages(){
        return $this->hasMany('App\Models\ProductImages','product_id');
    }

    public function ProductCartImages(){
        return $this->hasMany('App\Models\ProductImages','product_id')->take(1);
    }
}
