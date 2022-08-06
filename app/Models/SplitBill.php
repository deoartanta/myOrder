<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplitBill extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function orderdetail(){
        return $this->belongsTo(OrderDetail::class);
    }
}
