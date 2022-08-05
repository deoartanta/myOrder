<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
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
        return view('store.index-store',$data);
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
            'nm_store'=>'required',
            'address'=>'required',
        ];
        $validator = Validator::make($request->all(),$validatorRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input())->with(['stsAction'=> 'Failed to add store data','iconAction'=>'error','titleAction'=>'Sorry..','btnAction'=>true]);
        }
        $data['user_id'] = Auth()->user()->id;
        $data['nm_store'] = $request->input('nm_store');
        $data['address'] = $request->input('address');
        Store::create($data);
        return redirect()->back()->with(['stsAction'=> 'store data added successfully','iconAction'=>'success','titleAction'=>'Congratulation']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $validatorRule = [
            'nm_store'=>'required',
            'address'=>'required',
        ];
        $validator = Validator::make($request->all(),$validatorRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input())->with(['stsAction'=> 'Failed to update store data','iconAction'=>'error','titleAction'=>'Sorry..','btnAction'=>true]);
        }
        $data['nm_store'] = $request->input('nm_store');
        $data['address'] = $request->input('address');
        $store->update($data);
        return redirect()->back()->with(['stsAction'=> 'Store data has been successfully updated','iconAction'=>'success','titleAction'=>'Congratulation']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $name = $store->nm_store;
        $store->delete();
        return redirect()->back()->with(['stsAction'=> $name.' has been successfully removed','iconAction'=>'success','titleAction'=>'Congratulation']);
    }
}
