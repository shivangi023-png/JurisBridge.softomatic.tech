<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function get_services(Request $request)
    {
      
      try
        {
            $services=DB::table('services')->get(['id','name']);
            return response()->json(array('status'=>'success','data'=>$services));
            
        }
     catch(QueryException $e)
        {
        
            Log::error($e->getMessage());
            return response()->json(array('status'=>'error','msg'=>'something went wrong. try again later'));
            
            
        }
     catch(Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(array('status'=>'error','msg'=>'something went wrong. try again later'));
            
        }
    
    } 
    public function get_property(Request $request)
    {
      
      try
        {
            $property=DB::table('property_type')->get(['id','type']);
            return response()->json(array('status'=>'success','data'=>$property));
            
        }
     catch(QueryException $e)
        {
        
            Log::error($e->getMessage());
            return response()->json(array('status'=>'error','msg'=>'something went wrong. try again later'));
            
            
        }
     catch(Exception $e)
        {
            Log::error($e->getMessage());
            return response()->json(array('status'=>'error','msg'=>'something went wrong. try again later'));
            
        }
    }
}
