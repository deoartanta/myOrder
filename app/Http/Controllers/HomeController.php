<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['stores'] = Store::all()->where('user_id',Auth()->user()->id);
        $data['orders'] = Order::all()->where('user_id',Auth()->user()->id);
        return view('dashboard.index-dashboard',$data);
    }
}
