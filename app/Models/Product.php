<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','description','price','stock_quantity','alert_qty','image','status'];
    protected $casts = ['status' => 'boolean'];
}
