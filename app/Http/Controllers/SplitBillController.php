<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\SplitBill;
use Illuminate\Support\Carbon;

class SplitBillController extends Controller
{
    /**
     * <Split table column>
     * order_detail_id
     * nm_customer
     * bill_total
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
    }

    public function create()
    {
        $data['orders'] = Order::all()->where('user_id',Auth()->user()->id);
        return view('bill.index-bill',$data);
    }

    public function store(Request $request)
    {
        
    }

    public function show($id)
    {

        $data['orders'] = Order::all()->where('user_id',Auth()->user()->id);
        $data['order'] = $data['orders']->where('order_cd',$id)->first();
        $nm_customer = "";
        $key_i = -1;
        $id = [];
        foreach ($data['order']->orderdetails as $val) {
            if($val->nm_customer!=$nm_customer){
                $id[] += $val->id;
                $nm_customer = $val->nm_customer;
            }
        }
        $data['bills'] = SplitBill::all()->whereIn('order_detail_id', $id);
        $data['updated_at'] = Carbon::parse($data['order']->updated_at)->format('Y-m-d');
        return view('bill.index-bill',$data);
    }

    public function edit(SplitBill $splitbill)
    {
        
    }

    public function update(Request $request, SplitBill $splitbill)
    {
        
    }

    public function destroy(SplitBill $splitbill)
    {

    }
}
