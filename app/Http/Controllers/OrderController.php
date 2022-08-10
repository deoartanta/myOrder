<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\SplitBill;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $data['orders'] = Order::all()->where('user_id',Auth()->user()->id)->where('hrg_grandtotal','<>',null);
        return view('order.list-order',$data);
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
            dd($order->count());
        }else if($order->count()==0){
            $order_all = Order::all();
            $order_id = $order_all->count()==0?1:$order_all->last()->id+1;
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
        $data['orders'] = $order->first();
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
            $order->hrg_subtotal = $request->input('hrg_total');
            $order->save();
            
            $data['order_id'] = $order->id ;
            $data['product_id'] = $request->input('product_id');
            $data['nm_customer']= $request->input('nm_customer');
            $data['qty']= $request->input('qty');
            $data['hrg_total']= $request->input('hrg_total');
            OrderDetail::create($data);
        }else{
            $data['order_id'] = $orderNew->last()->id ;
            $data['product_id'] = $request->input('product_id');
            $data['nm_customer']= $request->input('nm_customer');
            $data['qty']= $request->input('qty');
            $data['hrg_total']= $request->input('hrg_total');
            OrderDetail::create($data);
        }
        return redirect()->back()->withInput(['nm_customer'=>$request->input('nm_customer')]);
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
        $total_discount =0;
        $form_dis = $request->input('formatDiscount');//Format Discount 1(presentase), 2(absolute)
        $discount = $request->input('discount');
        $hrg_subtotal = $request->input('hrg_subtotal');
        $hrg_grandtotal = $request->input('hrg_grandtotal');
        $min = $request->input('min');
        $max = $request->input('max');
        $ongkir = $request->input('ongkir');
        $used_discount = false;

        $discount2 = 0;
        if($min<=$hrg_subtotal){
            if ($form_dis==1) {
                $used_discount = true;
                $discount2 = $discount/100;
                $total_discount = $discount2*$hrg_subtotal;
                $data['terms_discount'] = 'Pembelian minimal '.sprintf('Rp. %s', number_format($min)).', potongan '.($discount2*100).'%'.' (maksimal '.sprintf('Rp. %s', number_format($max)).')';
            }else if($form_dis==2){
                $used_discount = true;
                $total_discount = $discount;
                $data['terms_discount'] = 'Pembelian minimal '.sprintf('Rp. %s', number_format($min)).', potongan '.sprintf('Rp. %s', number_format($total_discount));
            }else{
                $used_discount = false;
                $data['terms_discount'] = "-";
                $total_discount = 0;
            }
        }else{
            $data['terms_discount'] = "-";
            $used_discount = false;
        }
        $data['hrg_grandtotal'] = $hrg_grandtotal;
        $data['hrg_subtotal'] = $hrg_subtotal;
        $data['ongkir'] = $ongkir;
        $data['discount'] = $total_discount;
        $data['item_total'] = 0;
        foreach ($order->orderdetails as $val) {
            $data['item_total'] +=(int)$val->qty;
        }

        $order->update($data);
        $data_bills = [];
        $finish_bills = [];
        $nm_customer = "";
        $key_i = -1;
        foreach ($order->orderdetails->sortBy('nm_customer') as $val) {
            if($val->nm_customer==$nm_customer){
                $hrg_total = $data_bills[$key_i]['bill_total'];
                $item_total = $data_bills[$key_i]['item_total'];
                $data_bills[$key_i]['bill_total'] = $hrg_total+($val->qty*$val->product->hrg_product);
                $data_bills[$key_i]['item_total'] = $val->qty+$item_total;
            }else{
                $key_i++;
                $data_bills[$key_i]['order_detail_id'] = $val->id;
                $data_bills[$key_i]['item_total'] = $val->qty;
                $data_bills[$key_i]['bill_total'] = $val->qty*$val->product->hrg_product;
                $data_bills[$key_i]['created_at'] = Carbon::now();
                $data_bills[$key_i]['updated_at'] = Carbon::now();
                $nm_customer = $val->nm_customer;
            }
        }

        foreach ($data_bills as $key => $val) {
            if ($used_discount) {
                if ($discount2<=1) {
                    $finish_bills[$key]['bill_total'] = ($val['bill_total']/$hrg_subtotal)*($hrg_grandtotal-$ongkir)+($ongkir/count($data_bills));
                }else{
                    $finish_bills[$key]['bill_total'] =
                    ($hrg_grandtotal-$ongkir)-($total_discount/count($data_bills))+($ongkir/count($data_bills));
                }
            }else{
                $finish_bills[$key]['bill_total'] = $val['bill_total'] + ($ongkir/count($data_bills));
            }

            $finish_bills[$key]['order_detail_id'] = $val['order_detail_id'];
            $finish_bills[$key]['item_total'] = $val['item_total'];
            $finish_bills[$key]['created_at'] = $val['created_at'];
            $finish_bills[$key]['updated_at'] = $val['updated_at'];
        }
        // dd($data_bills);
        // dd($finish_bills);
        SplitBill::insert($finish_bills);
        return redirect()->back()->with([
            'stsAction'=> 'order successfully saved',
            'iconAction'=>'success',
            'titleAction'=>'Congratulation'
        ]);
    }

    public function destroy($id)
    {
        $orderdetail = OrderDetail::findOrFail($id);
        $name = $orderdetail->nm_customer;
        $orderdetail->delete();
        return redirect()->back()->with([
            'stsAction'=> $name.' has been successfully removed',
            'iconAction'=>'success',
            'titleAction'=>'Congratulation'
        ]);
    }
}
