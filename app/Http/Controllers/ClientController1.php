<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class ClientController extends Controller
{
   

    public function get_clients(Request $request){
     
        try{
          
           
              $clients=DB::table('clients')->get(['id','client_name','case_no']);
       
              return response()->json(array('status'=>'success','clients'=>$clients));
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
         public function get_city(Request $request){
     
        try{
          
           
              $city=DB::table('city')->get();
       
              return response()->json(array('status'=>'success','city'=>$city));
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
     public function get_client_on_id(Request $request){
        $v = Validator::make($request->all(), [
            'client_id'=> 'required|numeric',
        ]);
        if ($v->fails())
        {
           return $v->errors();
        }
     
        try{
             $client_id=$request->client_id;
             
              $clients=DB::table('clients')
              ->join('client_contacts','clients.id','client_contacts.client_id')
              ->select('clients.*','client_contacts.name','client_contacts.contact','client_contacts.whatsapp','client_contacts.email')
              ->where('clients.id',$client_id)
              ->get();
       
              return response()->json(array('status'=>'success','data'=>$clients));
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
     public function mobile_client_add(Request $request){ //TO INSERT CONTACT DETAILS
       
       log::info($request->all());
        $v = Validator::make($request->all(), [
        'client_name'=>'required|string',
        'services'=>'required|array',
        'no_of_units'=>'required|numeric',
        'property_type'=>'required|numeric',
        'address'=>'required|string',
        'city'=>'required|numeric',
        'pincode'=>'required|numeric',
       ]);
        if ($v->fails())
        {
           return $v->errors();
        }
        try
        {
        $client_name=$request->client_name;
        $services=$request->services;
        $no_of_units=$request->no_of_units;
        $property_type=$request->property_type;
        $area=$request->area;
        $address=$request->address;
        $city=$request->city;
        $pincode=$request->pincode;
        $remarks=$request->remarks;
  
       if($city==68)
       {
           $company=1;
       }
       else
       {
           $company=2;
       }
       $max_id=DB::table('clients')->max('id');
       $case_no='LEAD_'.$max_id;
       $status='active';
       $date=date('Y-m-d');
       
       	$insert = DB::table('clients')->insert([
       	    'client_name'=>$client_name,
       	    'company'=>$company,
       	    'case_no'=>$case_no,
       	    'address'=>$address,
       	    'city'=>$city,
       	    'area'=>$area,
       	    'pincode'=>$pincode,
       	    'no_of_units'=>$no_of_units,
       	    'property_type'=>$property_type,
            'status'=>$status,
            'services'=>json_encode($services),
            'date'=>$date,
            'remarks'=>$remarks,
            'created_at'=>now()]);
               
        if($insert )
        {
             return response()->json(array('status'=>'success','msg'=>'Leads inserted successfully'));
        }
        else
        {
            return response()->json(array('status'=>'error','msg'=>'Leads can`t be inserted'));
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
     public function clientContact_insert(Request $request){ //TO INSERT CONTACT DETAILS
       
        $v = Validator::make($request->all(), [
            'name'=> 'required|array',
            'contact'=>'required|array',
            'whatsapp'=>'required|array',
            'email'=>'required|array',        
        ]);
    
        if ($v->fails())
        {
           return $v->errors();
        }
        echo $request['email'][0];
        for($i = 0; $i < count($request['name']); $i++){
            $id = DB::table('clients')->max('id');//Geting max id from client
            $inId=DB::table('client_contacts')->insertGetId(['client_id'=>$id, 'name'=>$request['name'][$i],
            'contact'=>$request['contact'][$i],'whatsapp'=>$request['whatsapp'][$i],'email'=>$request['email'][$i]]); //Inserting in the max ID recived
        }
        
        return response()->json('Inserted');
    }

    public function clientContact_fetch(Request $request){ //TO RECIVE CONTACT DETAILS BASED ON CLIENT ID   
        $v = Validator::make($request->all(), [
            'client_id' => 'required|numeric',
            
        ]);
    
        if ($v->fails())
        {
           return $v->errors();
        }

        $contactDetails = DB::table('client_contacts')->where('client_id', $request->client_id)->get();

        return response()->json($contactDetails);
    }
    public function clients_add_index(Request $request)
    {
        try{
                 Log::Info('inside getclient');
                  if(session::get('username')!='')
                    {
                       
                        
                        $services=DB::table('services')->get();
                        $cities=DB::table('city')->get();
                        $company=DB::table('company')->get();
                        $property_type=DB::table('property_type')->get();
                        return view('pages.client-add',compact('services','cities','company','property_type'));
                        
                    }else
                        {
                            return redirect('/')->with('status',"Please login First");
                        }
        		}
	            catch(QueryException $e)
                {
                     Log::error("Database error ! [".$e->getMessage()."]");
                     
                     if ($request->wantsJson()){
                            return response()->json(array('status'=>'failure','error'=>'Database error'));
                     }else{
                            Log::error($e->getMessage());
                            return redirect()->back()->with('alert-danger','something went wrong. try again later');
                     }
                }
            catch(Exception $e)
            {
                Log::error($e->getMessage());
                
                    if ($request->wantsJson()){
                        return response()->json(array('status'=>'failure','error'=>'Database error'));
                    }else{
                        return redirect()->back()->with('alert-danger','something went wrong. try again later');
                    }
                  
            }
    }

    public function client(Request $request)
    {
       
        Log::Info('Inside get_clients');
        $date = date('Y-m-d');
        $active_clients = DB::table('clients')->select("clients.*", DB::raw("DATE_FORMAT(clients.date, '%d-%b-%Y') as date"))->where('status','active')->get();
        
        $visitors_clients = DB::table('clients')->select("clients.*", DB::raw("DATE_FORMAT(clients.date, '%d-%b-%Y') as date"))->where('status','open')->get();
                      
        return view('pages.clients',compact('active_clients'));   
    }


      
     public function autocomplete_client_name(Request $request)
    {
       
  
                $clients=DB::table('clients')->get();
                    $client_name_array=array();
                    foreach($clients as $row)
                    {
                        array_push($client_name_array, $row->client_name); 
                    }
                return $client_name_array=$client_name_array;
   

    }  
    public function get_exist_client(Request $request)
	{
       log::info("Get already exist client");
		try
		{
            $client_name=$request->client_name;
            $data=DB::table('clients')->where('client_name',$client_name)->get();
            foreach($data as $row)
            {
                $row->services_id=json_decode($row->services);
                $row->company_id=json_decode($row->company);
                $row->date_format=date('d/m/Y',strtotime($row->date));
                $row->client_visits=DB::table('client_visit')->where('client_id',$row->id)->value('enquery_details');
                $count_contact=DB::table('client_contacts')->where('client_id',$row->id)->count();
                if($count_contact>0)
                {
                    $row->client_contacts=DB::table('client_contacts')->where('client_id',$row->id)->get();
                }
                else
                {
                    $row->client_contacts='';
                }
                
            }
            return $data;
        }
        catch(QueryException $e)
        {
             Log::error("Database error ! [".$e->getMessage()."]");
             
             if ($request->wantsJson()){
                    return response()->json(array('status'=>'failure','error'=>'Database error'));
             }else{
                    Log::error($e->getMessage());
                    return redirect()->back()->with('alert-danger','something went wrong. try again later')->withInput($request->all);
             }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            
             if ($request->wantsJson()){
                    return response()->json(array('status'=>'failure','error'=>'Database error'));
             }else{
                    return redirect()->back()->with('alert-danger','something went wrong. try again later')->withInput($request->all);
             }
        }
}  
public function client_add(Request $request)
{
   log::info("Get already exist client");
    try
    {
     
        $client_name=$request->client_name;
        $company=$request->company;
        $name=$request->name;
        $email=$request->email;
        $contact=$request->contact;
        $whatsapp=$request->whatsapp;
        $address=$request->address;
        $city=$request->city;
        $var=$request->start_date; 
        $date = str_replace('/', '-', $var);
        $date=date('Y-m-d',strtotime($date));
        $client_enquiry=$request->client_enquiry;
        $service=$request->service;
        $no_of_units=$request->no_of_units;
        $property_type=$request->property_type;
        $find_client_name=DB::table('clients')->where('client_name',$client_name)->count();
       
        if($find_client_name>0)
        {
         return json_encode(array('status'=>'error','msg'=>'client name already exist'));
        }
       
        $count_company=DB::table('clients')->where('company',$company)->count();
        $company_name=DB::table('company')->where('id',$company)->value('company_name');
        $words = explode(" ", $company_name);
        $acronym = "";

        foreach ($words as $w) {
                $acronym .= $w[0];
        }
         $acronym=strtoupper($acronym);
         $case_no=$acronym.'/'.date('Y').'/'.($count_company+1);
      
       
        $client_id = DB::table('clients')->insertGetId(['client_name'=>$client_name,'company'=>$company,'case_no'=>$case_no,'address'=>$address,'city'=>$city,'no_of_units'=>$no_of_units,'property_type'=>$property_type,
        'status'=>'open','services'=>json_encode($service),'date'=>$date,'remarks'=>$client_enquiry,'created_at'=>now()]);
       
        if($client_id)
        {
            if($name!=[null])
            {
               $j=0;
                for($i=0;$i<sizeof($name);$i++)
                {
                    $client_contacts=DB::table('client_contacts')->insert([
                        'client_id'=>$client_id,
                         'name'	=>$name[$i],
                         'contact'=>$contact[$i],	
                         'whatsapp'	=>$whatsapp[$i],
                         'email'=> $email[$i],	
                         'created_at'=>now()
                    ]);
                    if($client_contacts)
                    {
                        $j++;
                    }
                }

               

                if($i==$j)
            {
                Log::info("Client and client conatact Add Successfully");
                
                return json_encode(array('status'=>'success','msg'=>'Client detail inserted successfully'));
               
            }
            else
            {
                Log::error("Client and client conatact can Not be add");
                
                    return json_encode(array('status'=>'error','msg'=>'Client detail can`t be inserted'));
                
            }
            }
          
           
               
          
           
        }
        if($client_id)
        {
            Log::info("Client conatact Add Successfully");
            
            return json_encode(array('status'=>'success','msg'=>'Client detail inserted successfully'));
           
        }
        else
        {
            Log::error("Client conatact can Not be add");
            
                return json_encode(array('status'=>'error','msg'=>'Client detail can`t be inserted'));
            
        }
        
    
    }
    catch(QueryException $e)
    {
         Log::error("Database error ! [".$e->getMessage()."]");
         
         if ($request->wantsJson()){
                return response()->json(array('status'=>'failure','error'=>'Database error'));
         }else{
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger','something went wrong. try again later')->withInput($request->all);
         }
    }
    catch(Exception $e)
    {
        Log::error($e->getMessage());
        
         if ($request->wantsJson()){
                return response()->json(array('status'=>'failure','error'=>'Database error'));
         }else{
                return redirect()->back()->with('alert-danger','something went wrong. try again later')->withInput($request->all);
         }
    }
}   
public function update_clients(Request $request)
{
    
    try{
        // if(Session::get('username')!='')
        // {
        Log::Info("Inside Update_client");
       
        $contact_id=$request->contact_id;
        $client_id=$request->client_id;
        $client_name=$request->client_name;
        $case_no=$request->case_no;
        $service=$request->service;
        $var=$request->start_date; 
        $date = str_replace('/', '-', $var);
        $date=date('Y-m-d',strtotime($date));
        $city=$request->city;
        $address=$request->address;
        $name=$request->name;
        $email=$request->email;
        $contact=$request->contact;
        $whatsapp=$request->whatsapp;
      
        $no_of_units=$request->no_of_units;
        $property_type=$request->property_type;
        $company=$request->company;
        $client_enquiry=$request->client_enquiry;
        if($contact_id!='')
        {
             $find_not_contact_id=DB::table('client_contacts')->where('client_id',$client_id)->whereNotIn('id',$contact_id)->get(['id']);
                if( $find_not_contact_id!='' || $find_not_contact_id!='[]')
                {
                    $delete_extra_contact=DB::table('client_contacts')->where('client_id',$client_id)->whereNotIn('id',$contact_id)->delete();
                   
                }
       
          
        }
      

        $update=DB::table('clients')->where('id',$client_id)->update([
            'client_name'=>$client_name,
            'services'=>json_encode($service),
            'address'=>$address,
            'city'=>$city,
            'date'=>$date,
            'company'=>$company,
            'no_of_units'=>$no_of_units,
            'property_type'=>$property_type,
            'remarks'=>$client_enquiry,
            'updated_at'=>now(),
            'status'=>'active'
            ]);
            $j=0;
          
            if($update)
            {
               if($contact_id!='')
               {
                  
                  $size=sizeof($contact_id);
                    log::info('size '.$size);
                for($i=0;$i<sizeof($contact_id);$i++)
                {
                    if($contact_id[$i]!="" || $contact_id[$i]!=null)
                    {
                        $update_contact=DB::table('client_contacts')->where('id',$contact_id[$i])->update([
                                'name'=>$name[$i],
                                'contact'=>$contact[$i],
                                'whatsapp'=>$whatsapp[$i],
                                'email'=>$email[$i],
                                'updated_at'=>now(),
                            ]);
                            if($update_contact)
                            {
                                $j++;
                            }
                    }
                    else
                    {
                        $insert=DB::table('client_contacts')->insert([
                            'client_id'=>$client_id,
                            'name'=>$name[$i],
                            'contact'=>$contact[$i],
                            'whatsapp'=>$whatsapp[$i],
                            'email'=>$email[$i],
                            'created_at'=>now()
                        ]);
                        if($insert)
                        {
                            $j++;
                        }
                    }
                   
                }
                 Log::info($update." updated Successfully").'<br>';   
                 log::info($client_id);
                 log::info('j '.$j);
    
                 if($j==$size)
                    {
                        Log::info("Client Updated Successfully");
                        
                        return json_encode(array('status'=>'success','msg'=>'Client Updated Successfully'));
                       
                    }
                    else
                    {
                        Log::error("Client can Not be Update");
                        return json_encode(array('status'=>'error','msg'=>'Client can`t be Updated'));
                        
                    }
            }
            
            }
          if($update)
          {
               return json_encode(array('status'=>'success','msg'=>'Client Updated Successfully'));
          }
          else
          {
                return json_encode(array('status'=>'error','msg'=>'Client can`t be Updated'));
          }
       
            
        }
        catch(QueryException $e)
        {
            Log::error("Database error ! [".$e->getMessage()."]");
      
            return json_encode(array('status'=>'error','msg'=>'Database error'));
        }
        catch(Exception $e)
        {   
            
                    return json_encode(array('status'=>'error','msg'=>'error'));
        }
           

    }        
    public function clients_list(Request $request)
    {
        
        try
        {	
          $client_list = DB::table('clients')->where('company',session('company_id'))->get();  
            foreach($client_list as $row)
            {
                $quotation_total=DB::table('quotation')
                ->join('quotation_details','quotation_details.quotation_id','quotation.id')
                ->where('quotation.client_id',$row->id)->where('quotation_details.finalize','yes')->sum('amount');
                $row->finalize_quotation=$quotation_total;$que_bill_arr=array();$add_bill_arr=array();
                
                $row->bill_on_quotation=DB::table('bill')->where('client',$row->id)->where('quotation','!=','null')->sum('total_amount');
                $que_bill_id=DB::table('bill')->where('client',$row->id)->where('quotation','!=','null')->get(['id']);
                
                if($que_bill_id!='[]')
                {
                   
                    foreach($que_bill_id as $qb)
                    {
                       
                        $que_bill_arr[]=$qb->id;
                    }
                    $payment_received_on_quo=DB::table('bill_payment_mapping')
                    ->join('payment','payment.id','bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.bill_id',$que_bill_arr)->where('bill_payment_mapping.active','yes')->where('payment.status','approved')
                    ->sum('bill_payment_mapping.paid_amount');
                    $row->payment_on_quo=$payment_received_on_quo;
                }
                else
                {
                   
                    $row->payment_on_quo=0;
                }
               

                $row->additional_bill=DB::table('bill')->where('client',$row->id)->where('service','!=','null')->sum('total_amount');
                $add_bill_id=DB::table('bill')->where('client',$row->id)->where('service','!=','null')->get(['id']);
                
                if($add_bill_id!='[]')
                {
                    
                    foreach($add_bill_id as $ab)
                    {
                        $add_bill_arr[]=$ab->id;
                    }
                    $payment_received_on_additional=DB::table('bill_payment_mapping') ->join('payment','payment.id','bill_payment_mapping.payment_id')
                    ->whereIn('bill_payment_mapping.bill_id',$add_bill_arr)
                    ->where('bill_payment_mapping.active','yes')->where('payment.status','approved')
                    ->sum('bill_payment_mapping.paid_amount');
                    $row->payment_on_add=$payment_received_on_additional;
                }
                else
                {
                    $row->payment_on_add=0;
                }
                $pstatus='approved';$bstatus='unpaid';$active='yes';$row->due_amt=0;
               $due_bill_amount=DB::table('bill')->where('client',$row->id)->where('status','unpaid')->sum('total_amount');
               log::info('client_id='.$row->id.'bill_amount='.$due_bill_amount);
               $row->unapproved_payment=DB::table('payment')->where('client_id',$row->id)->where('status','!=','approved')->where('active','yes')->sum('payment');
               //log::info('client_id='.$row->id.'payment_amount='.$unapproved_payment);
               $row->due_amt=$due_bill_amount;
               $row->future_invoices=$row->finalize_quotation-$row->bill_on_quotation;
               
            }
            
             $out="";$i=1;$total_dues=0;$total_unapproved=0;
                foreach($client_list as $client_item)
                {
                    $total_dues+=$client_item->due_amt;
                    $total_unapproved+=$client_item->unapproved_payment;
               $out.='<tr>
                               
                                <td  align="center">'.$client_item->case_no.'</td>
                                <td>'.$client_item->client_name.'</td>';
                               
                                if(session('role_id')==1 || session('role_id')==3) 
                                {
                                $out.='<td align="right"  style="background-color: #e6e6ea">'.number_format($client_item->finalize_quotation,2).'</td>'; 
                                $out.='<td align="right"  style="background-color: #e6e6ea">'.number_format($client_item->future_invoices,2).'</td>'; 
                                $out.='<td align="right"  style="background-color: #dcedc1">'.number_format($client_item->bill_on_quotation,2).'</td>';
                                $out.='<td align="right"  style="background-color: #dcedc1">'.number_format($client_item->payment_on_quo,2).'</td>';  
                                $out.='<td align="right"  style="background-color: #eee3e7">'.number_format($client_item->additional_bill,2).'</td>'; 
                                $out.='<td align="right"  style="background-color: #eee3e7">'.number_format($client_item->payment_on_add,2).'</td>';   
                                $out.='<td align="right" >'.number_format($client_item->due_amt,2).'</td>'; 
                                $out.='<td align="right" >'.number_format($client_item->unapproved_payment,2).'</td>'; 
                                }                 
                                $out.='<td>
                                <div class="header" style="border-bottom:none;padding:0px">
                                <ul class="header-dropdown m-r--5" style="top:0px">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a type="button" class=" waves-effect waves-block updateModal_btn" data-toggle="modal" data-target="#updateModal" data-no_of_units="'.$client_item->no_of_units.'" data-property_type="'.$client_item->property_type.'" data-cid="'.$client_item->id.'" data-city="'.$client_item->city.'" data-company="'.$client_item->company.'" data-cname="'.$client_item->client_name.'"  data-caddress="'.$client_item->address.'"  data-ccity="'.$client_item->city.'" data-cdate="'.$client_item->date.'" data-services='.$client_item->services.' data-case_no="'.$client_item->case_no.'">Edit</a></li>
                                        <li><a  class=" waves-effect waves-block delete" data-id="'.$client_item->id.'">Delete</a></li>
                                        <li><a href="active_inactive_cilent" class=" waves-effect waves-block">Active/Inactive</a></li>
                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block followup_btn" data-toggle="modal" data-target="#followupmodal" data-name="'.$client_item->client_name.'" data-id="'.$client_item->id.'">Follow Up</a></li>
                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block quotation_btn" data-toggle="modal" data-target="#quotationmodal" data-name="'.$client_item->client_name.'" data-id="'.$client_item->id.'">Quotations</a></li>
                                    </ul>
                                </li>
                            </ul>
                            </div>
                                </td>
                            </tr>';
                }
                return json_encode(array('out'=>$out,'total_dues'=>number_format($total_dues,2),'total_anapporevd'=>number_format($total_unapproved,2)));
               
        }
        catch(QueryException $e)
        {
            Log::error("Database error ! [".$e->getMessage()."]");
            return 'Database error';
        }
        catch(Exception $e)
        {	
            Log::error($e->getMessage());
            return 'Error';
        }

    
    }
           
}
