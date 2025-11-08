<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class BillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    public function get_bill_on_client_id(Request $request)
{
   
     try{
        $v = Validator::make($request->all(), ['client_id' => 'required|numeric']);
    
        if ($v->fails())
        {
           return $v->errors();
        }
           $client_id=$request->client_id;
           $bill=DB::table('bill')->where('client',$client_id)->where('status','!=','paid')->get();
          
           foreach($bill as $row)
           {
            $services_title=array();$quotation_title=array();
                $service_id=json_decode($row->service);
                if(!empty($service_id))
                {
                for($i=0;$i<sizeof($service_id);$i++)
                {
                    $service_name=DB::table('services')->where('id',$service_id[$i])->value('name');
                    array_push($services_title,$service_name);
                 
                }
                $row->services_title=$services_title;
               }
               else
               {
               
               $quotation_detail_id=json_decode($row->quotation);
                if(!empty($quotation_detail_id))
                {
                for($i=0;$i<sizeof($quotation_detail_id);$i++)
                {
                    $service_id=DB::table('quotation_details')->where('id',$quotation_detail_id[$i])->value('task_id');
                    $ser=DB::table('services')->where('id',$service_id)->value('name');
                    array_push($quotation_title,$ser);
                 
                }
                $row->services_title=$quotation_title;
               }
            }
              
           }
           
           return response()->json(array('status'=>'success','bill'=>$bill));
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
