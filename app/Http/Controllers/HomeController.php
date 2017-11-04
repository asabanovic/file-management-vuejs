<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileManagement\Props;

class HomeController extends Controller
{	
	/**
	 * Load homepage
	 * 
	 * @param  Request $request 
	 * @return view           
	 */
    public function index(Request $request)
    {	
    	$props = Props::get();

    	return view('index', ['props' => $props]);
    }

    public function store(Request $request)
    {
    	 $attachments = $request->file('attachments');

    	 foreach ($attachments as $key => $attachment) {
    	 	dump($attachment); // Each of these is a UploadedFile object instance
    	 }
    }
}
