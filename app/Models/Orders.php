<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable= ['unique_id','customer_id','customer_name','customer_email','customer_phone','address','order_date','order_time','short_notes','payment_type','transaction_id','order_total','status'];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_REJECT = 'reject';

    public static $status = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PAID => 'Paid',
        self::STATUS_REJECT => 'Reject',
    ];

    public function User(){
        return $this->belongsTo('App\Models\User','customer_id');
    }

    public function OrderItems(){
        return $this->hasMany('App\Models\OrderItems','order_id');
    }
}





