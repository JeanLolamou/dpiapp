<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
     public function controllerMethod(){

     	 return response()->json([
     	 	'msg'=>'We should return a json']);
    	
    }

     public function testMethod(){

     	 return 'it work!';
    	
    }
}
