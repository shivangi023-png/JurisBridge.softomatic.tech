<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class VisitController extends Controller
{
   
    public function save_leads(Request $request){
       
       $v = Validator::make($request->all(), [
       
        'start_time'=>'string|required',
        'remark'=>'string|required',
        'staff_id'=>'numeric|required',
    ]);
    if ($v->fails())
    {
       return $v->errors();
    }
   try
        { 
           
            $client_id=$request->client_id;
            $department_id=$request->department_id;
            $start_time=$request->start_time;
            $end_time=$request->end_time;
            $remark=$request->remark;
            $staff_id=$request->staff_id;
            $date=date('Y-m-d');
            $insert=DB::table('leads')->insert([
                'client_id'=>$client_id,
                'department_id'=>$department_id,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'remark'=>$remark,
                'staff_id'=>$staff_id,
                'date'=>$date
            ]);
            if($insert)
            {
                return response()->json(['status'=>'success','mag'=>'Data inserted successfully']);
            }
            else
            {
                return response()->json(['status'=>'error','mag'=>'Data can`t be inserted']);
            }
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
   
    public function get_leads(Request $request){
       
        $v = Validator::make($request->all(),[
         'staff_id'=>'numeric|required',
         'date'=>'required',
        
     ]);
     if ($v->fails())
     {
        return $v->errors();
     }
    try
         { 
            
           
             
             $staff_id=$request->staff_id;
             $date=date('Y-m-d',strtotime($request->date));
             $data=DB::table('leads')->where('staff_id',$staff_id)->where('date',$date)->get();
            foreach($data as $row)
            {
                $row->client_name=DB::table('clients')->where('id',$row->client_id)->value('client_name');
                $row->case_no=DB::table('clients')->where('id',$row->client_id)->value('case_no');
                $row->department=DB::table('dept_address')->where('id',$row->department_id)->value('department_name');
                $row->department_address=DB::table('dept_address')->where('id',$row->department_id)->value('address');
            }
                 return response()->json(['status'=>'success','data'=>$data]);
            
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
