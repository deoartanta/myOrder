<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SplitBill;
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
        
    }

    public function store(Request $request)
    {
        
    }

    public function show(SplitBill $splitbill)
    {
        
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
