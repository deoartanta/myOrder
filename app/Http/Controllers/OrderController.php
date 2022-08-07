<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     *<Order table column>
     * order_cd
     * user_id
     * item_total
     * hrg_subtotal
     * discount
     * terms_discount
     * hrg_grandtotal
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
        $cd_order = "";
        $nameArr = explode(" ",Auth()->user()->fullname);
        $nameSm = count($nameArr)<2?($nameArr[0][0].$nameArr[0][1]):($nameArr[0][0].$nameArr[1][0]);
        $data['stores'] = Store::all()->where('user_id',Auth()->user()->id);

        $order = Order::all()
                        ->where('user_id',Auth()->user()->id)
                        ->where('hrg_grandtotal',null);
        if($order->count()>1){
            dd($order);
        }else if($order->count()==0){
            $order_id = $order->count()==0?1:$order->last()->id+1;
            $cd_order = $nameSm.date("ymd").$order_id.Auth()->user()->id;
            $cd_order = $nameSm.date("ymd").$order_id.Auth()->user()->id;
        }else{
            $cd_order = $order->last()->order_cd;
        }
        
        $id = [];
        
        foreach ($data['stores'] as $val) {
            $id[] += $val->id;
        }
        $data['products'] = Product::all()->whereIn('store_id', $id);
        $data['orders'] = $order;
        $data['cd_order'] = strtolower($cd_order);
        return view('order.index-order',$data);
    }

    public function store(Request $request)
    {
        $validatorRule = [
            'order_cd'=>'required',
            'nm_customer'=>'required',
            'qty'=>'required',
        ];

        $validator = Validator::make($request->all(),$validatorRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input())->with([
                'stsAction'=> 'Failed to add order',
                'iconAction'=>'error',
                'titleAction'=>'Sorry..',
                'btnAction'=>true]);
        }
        $order = new Order();
        $orderNew = $order->where('order_cd',$request->input('order_cd'))->get();
        // dd($subtotal->count());
        if($orderNew->count()==0){
            $order->order_cd = $request->input('order_cd');
            $order->user_id = Auth()->user()->id;
            $order->item_total = 1;
            $order->hrg_subtotal = $request->input('hrg_subtotal');
            // $order->discount = $request->input('discount');
            // $order->terms_discount = $request->input('terms_discount');
            // $order->hrg_grandtotal = $request->input('hrg_subtotal');
            $order->save();
            // dd($order->id);
            $data['order_id'] = $order->id ;
            $data['product_id'] = $request->input('product_id');
            $data['nm_customer']= $request->input('nm_customer');
            $data['qty']= $request->input('qty');
            $data['hrg_total']= $request->input('hrg_subtotal');
            OrderDetail::create($data);
        }else{
            $data['order_id'] = $orderNew->last()->id ;
            $data['product_id'] = $request->input('product_id');
            $data['nm_customer']= $request->input('nm_customer');
            $data['qty']= $request->input('qty');
            $data['hrg_total']= $request->input('hrg_subtotal');
            OrderDetail::create($data);
        }
        return redirect()->back()->withInput(['nm_customer'=>$request->input('nm_customer')]);
    }
    public function updateOrder($data = []){

    }
    /**
     * <Order detail table column>
     * order_id
     * product_id
     * nm_customer
     * qty
     * hrg_total
     */

    public function show(Order $order)
    {

    }

    public function edit(Order $order)
    {

    }

    public function update(Request $request, Order $order)
    {

    }

    public function destroy(Order $order)
    {

    }
}
