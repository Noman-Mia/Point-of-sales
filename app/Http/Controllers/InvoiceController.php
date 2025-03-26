<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function InvoiceCreate(Request $request){    
        dd($request->all());
    }
}
