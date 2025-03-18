<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
 function index (){
    return Inertia::render('HomePage');
}
 function test (){
    return Inertia::render('TestPage');
}
}
