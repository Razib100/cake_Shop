<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable= ['coupon_name','coupon_code','amount','expire_date','min_order_amount','max_order_amount','status'];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_INACTIVE => 'inactive',
    ];

    const STATUS_VALUE = 'value';
    const STATUS_PERCENTAGE = 'percentage';

    public static $type = [
        self::STATUS_VALUE => 'value',
        self::STATUS_PERCENTAGE => 'percentage',
    ];
}
