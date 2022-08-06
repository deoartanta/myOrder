<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['stores'] = Store::all()->where('user_id',Auth()->user()->id);
        $id = [];
        foreach ($data['stores'] as $val) {
            $id[] += $val->id;
        }
        $data['products'] = Product::all()->whereIn('store_id', $id);
        return view('product.index-product',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatorRule = [
            'store_id'=>'required',
            'nm_product'=>'required',
            'hrg_product'=>'required',
        ];
        $validator = Validator::make($request->all(),$validatorRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input())->with([
                'stsAction'=> 'Failed to add product',
                'iconAction'=>'error',
                'titleAction'=>'Sorry..',
                'btnAction'=>true
            ]);
        }
        Product::create($request->all());
        return redirect()->back()->with([
            'stsAction'=> 'Product added successfully',
            'iconAction'=>'success',
            'titleAction'=>'Congratulation'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatorRule = [
            'store_id'=>'required',
            'nm_product'=>'required',
            'hrg_product'=>'required',
        ];
        $validator = Validator::make($request->all(),$validatorRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input())->with([
                'stsAction'=> 'Failed to update product',
                'iconAction'=>'error',
                'titleAction'=>'Sorry..',
                'btnAction'=>true
            ]);
        }
        $product->update($request->all());
        return redirect()->back()->with([
            'stsAction'=> 'Product has been successfully updated',
            'iconAction'=>'success',
            'titleAction'=>'Congratulation'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $name = $product->nm_product;
        $product->delete();
        return redirect()->back()->with([
            'stsAction'=> $name.' has been successfully removed',
            'iconAction'=>'success',
            'titleAction'=>'Congratulation'
        ]);
    }
}
