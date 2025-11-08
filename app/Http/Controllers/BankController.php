<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class BankController extends Controller
{
    public function get_bank(Request $request)
    {
          
         try{
           
               
               $bank=DB::table('bank_detailes')->get();
               return response()->json(array('status'=>'success','bank'=>$bank));
            }
            
            catch(\Throwable $e)
            {
                Log::error("Database error ! [".$e->getMessage()."]");
                return response()->json(array('error'=>'Database error'));
            }
            catch(Exception $e)
            {   
                Log::error($e->getMessage());
                return response()->json(array('error'=>'Error'));
            }
    }
}
