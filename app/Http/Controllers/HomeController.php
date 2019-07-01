<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    public function index(Request $request)
    {
        return view("{$this->view}::welcome");
    }
}
