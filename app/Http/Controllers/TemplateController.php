<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function template_create(Request $request){
        try{
            Log::info($request);
            $name = $request->template_name;
            $description = $request->description;
            $type = str_replace('_',' ',$request->type);
            $csvfile = $request->file('csvFile');
            $htmlfile = $request->file('htmlFile');        
            $cssfile = $request->file('cssFile');
            $fh = fopen($csvfile,'r');            
            $path = array('html' => $htmlfile->getClientOriginalName(),'css' => $cssfile->getClientOriginalName());
            $finalPath = implode(",",$path);
            $csvData = array();
            while (($row = fgetcsv($fh, 0)) !== FALSE) {
                $csvData[] = $row;
            }
            $csvData[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $csvData[0]);

            $variablesJSON = json_encode($csvData);
            DB::table('templates')->insert([
                ['template_name'=>$name,
                'template_type'=> $type,
                'filepath'=>$finalPath,
                'variable_list'=>$variablesJSON,
                'description'=>$description]
            ]);
            $htmlfile->move(base_path('template\html'), $htmlfile->getClientOriginalName()); 
            $cssfile->move(base_path('template\css'), $cssfile->getClientOriginalName());
        }catch(Exception $e){
            Log::error($e->getMessage()); 
            return $e;
        }
        return response()->json(array('status'=>'success','msg'=>'Inserted'));

    }

    public function template_index(Request $request){
        $type = DB::table('template_type')->select('type')->get();
        $files = DB::table('templates')->where('template_type','=',str_replace('_',' ',$request->type))->get();
        //str_replace('_',' ',$request->type)
        $out = '';
        if(count($files)>0){
            foreach($files as $row){
            $out.='<div class="col-md-3 col-6">
            <div class="card border shadow-none mb-1 app-file-info" data-name="'.$row->template_name.'" data-description="'.$row->description.'" data-id="'.$row->template_id.'">
              <div class="app-file-content-logo card-img-top">
                <img class="bx bx-dots-vertical-rounded app-file-edit-icon d-block float-right"></img>
                <img class="d-block mx-auto" src="'.asset('images/icon/pdf.png').'" height="38" width="30"
                  alt="Card image cap">
              </div>
              <div class="card-body p-50">              
                <div class="app-file-details">                
                  <div class="app-file-name font-size-small font-weight-bold">'.$row->template_name.'</div>
                  <div class="app-file-type font-size-small text-muted">'.$row->description.'</div>
                </div>
              </div>
            </div>
          </div>';
            }
            return json_encode(array('msg'=>'Data found','status'=>'success','out'=>$out));
        }
        $pageConfigs = ['isContentSidebar' => true, 'bodyCustomClass' => 'file-manager-application'];
        return view('pages.app-file-manager',['pageConfigs' => $pageConfigs],compact('type','files'));
    }

    public function generate_input(Request $request){        
        Log::info($request->template_get_id);
        $client_name = DB::table('clients')->select('client_name')->where('id',$request->client_id)->get();
        $taskName = '';
        if($request->task == '1'){
            $taskName = 'New';
        }else{
            $taskName = 'Overwrite';
        }
        $check = DB::table('template_generated')->where([
            ['template_id', '=', $request->temp_id],
            ['client_id', '=', $request->client_id],
        ])->max('temp_get_id');
        if( $task = $request->template_get_id){            
            Log::info("in");
            $var = DB::table('templates')->select('variable_list','template_name')->where('template_id',$request->temp_id)->get();
            $str = strval($var);//json_decode($var);
            $filter1 = preg_replace('/[^A-Za-z0-9\-]/',',', $str);//Removing Special Characters
            $filter2 = str_replace(',,,,,,,','%',$filter1);//Removing 
            $filter3 =  str_replace(',','',$filter2);//Replace
            $filter4 = explode('%',$filter3);//Explade in array
            $final = array_slice($filter4,1,count($filter4)-2);//Removing unwanted elements
            //------------------------------------------------------------------
            if($request->template_id == 2)//If unicode decode is required
                $substr = 'u';
            else
                $substr = '';
            $attachment = "\\";
            if($request->template_get_id == []){
                Log::info("Null");
            }else{
                $values = DB::table('template_generated')->select('value')->where('temp_get_id','=',$request->template_get_id)->get();
                $str = strval($values);
                $s = explode(',',$str);
                $filter1 = preg_replace('/[^A-Za-z0-9\-]/',',', $s);//Removing Special Characters
                $filter1[0] = str_replace('value','',$filter1[0]);
                foreach ($filter1 as $key => $value) {
                    $filter1[$key] = str_replace(',,,,,','=',$filter1[$key]);
                    $filter1[$key] = str_replace(',,','',$filter1[$key]);
                    $filter1[$key] = str_replace(',',' ',$filter1[$key]);                
                }
                $filter1[0] = substr($filter1[0],1);    
                $filter2 = array(); 
                foreach ($filter1 as $key => $value) {
                    $filter1[$key] = explode('=',$filter1[$key]);
                }
                foreach ($filter1 as $key => $value) {
                    
                    $filter2[$filter1[$key][0]] = str_replace($substr, $attachment.$substr, $filter1[$key][1]);
                }
                $out = '
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel20">'.$var[0]->template_name.':'.$client_name[0]->client_name.':'.$taskName.'</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                        </button>
                    </div>
                    <div class="row modal-body" id="modalInput">
                        <input type="hidden" id="temp_id" name="temp_id" value="'.$request->temp_id.'">
                        <input type="hidden" id="client_id" name="client_id" value="'.$request->client_id.'">
                        <input type = "hidden" id="task" name="task" value="'.$request->task.'">
                        <div class="row mt-2">';
                    foreach ($final as $value) {
                        $out.='
                        
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"> 
                                <fieldset class="form-label-group">
                                    <input type="text" class="form-control" name='.strtolower($value).' id="'.strtolower($value).' " placeholder="Label-placeholder" value="'.json_decode('"'.$filter2[strtolower($value)].'"').'">
                                    <label for="'.strtolower($value).'">'.$value.'</label>
                                </fieldset>                        
                                </div>';                    
                    }
                    $out.= '</div>
                    <div class="row">
                    <div class="modal-footer">                                                 
                            <button type="submit" class="btn btn-primary ml-1">Generate Template</button>                            
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <a href="preview_pdf?temp='.$request->temp_id.'" target="_blank" ><input type="button" id="preview" name="preview" class="btn btn-primary mr-1 mb-1" Value="Preview"></a>
                            </div>                        
                    </div>
                    </div>
                ';
                return json_encode(array('msg'=>'data submitted successfully 1','status'=>'success','out'=>$out));
            }
        }else{
            Log::info('in2');
            $var = DB::table('templates')->where('template_id',$request->temp_id)->get();
            $str = strval($var);//json_decode($var);
            $filter1 = preg_replace('/[^A-Za-z0-9\-]/',',', $str);//Removing Special Characters
            $filter2 = str_replace(',,,,,,,','%',$filter1);//Removing 
            $filter3 =  str_replace(',','',$filter2);//Replace
            $filter4 = explode('%',$filter3);//Explade in array
            $final = array_slice($filter4,1,count($filter4)-2);//Removing unwanted elements
            $out = '
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel20">'.$var[0]->template_name.':'.$client_name[0]->client_name.':'.$taskName.'</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="row modal-body" id="modalInput">
                <input type="hidden" id="temp_id" name="temp_id" value="'.$request->temp_id.'">
                <input type="hidden" id="client_id" name="client_id" value="'.$request->client_id.'">
                <input type = "hidden" id="task" name="task" value="'.$request->task.'">
                <div class="row mt-2">';
            foreach ($final as $value) {
                $out.='
                
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"> 
                            <fieldset class="form-label-group">
                                <input type="text" class="form-control" name='.strtolower($value).' id="'.strtolower($value).'" placeholder="'.$value.'" value="">
                                <label for="'.strtolower($value).'">'.$value.'</label>
                            </fieldset>                        
                        </div>';                    
            }
                $out.= '</div>
                    <div class="row">
                    <div class="modal-footer">                                                 
                            <button type="submit" class="btn btn-primary ml-1">Generate Template</button>                            
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <a href="preview_pdf?temp='.$request->temp_id.'" target="_blank" ><input type="button" id="preview" name="preview" class="btn btn-primary mr-1 mb-1" Value="Preview"></a>
                            </div>                        
                    </div>
                    </div>';
            return json_encode(array('msg'=>'data submitted successfully2','status'=>'success','out'=>$out));
        }
    }

    public function template_generation_index(Request $request){
        $clients = DB::table('clients')->get();
        $out = '';

        $out .='<div class="card shadow-none mb-0 p-0 pb-1">
                    <input type="hidden" value="'.$request->temp_id.'" id="temp_id">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="mb-0">'.$request->temp_name.'</h6>
                    <div class="app-file-action-icons d-flex align-items-center">
                        <i class="bx bx-trash cursor-pointer mr-50"></i>
                        <i class="bx bx-x close-icon cursor-pointer"></i>
                    </div>
                    </div>                    
                    <div class="tab-content pl-0">
                    <input type="hidden" id="temp_id_gen" value="'.$request->temp_id.'">
                    <div class="tab-pane active" id="details" aria-labelledby="details-tab" role="tabpanel">
                        <div class="border-bottom d-flex align-items-center flex-column pb-1">
                        
                        <p class="mt-2">'.$request->temp_desc.'</p>
                        </div>
                        <div class="card-body pt-2">
                            <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Clients</label>
                                </div>
                                <select class="form-control clients" id="inputGroupSelect01">
                                <option  value="0" selected>Choose...</option>';
                                foreach($clients as $row){
                                    $out .='<option value='.$row->id.'>'.$row->client_name.'</option>';
                                }
                                $out.='</select>
                            </div>
                            </fieldset>
                            <div id="templates_generated">
                            </div>
                            <input type="button" id="btnInput" data-toggle="modal" data-target="#inputModal" class="btn btn-primary mr-1 mb-1" value="Generate PDF">
                        </div>
                    </div>
                    <div class="tab-pane pl-0" id="activity" aria-labelledby="activity-tab" role="tabpanel">
                        
                    </div>
                    </div>
                </div>';
                return json_encode(array("status"=>'success','out'=>$out));
        // if($request->client_id){
        //     $templatesGen = DB::table('template_generated')->select('temp_name','temp_get_id')
        //                 ->where([['client_id','=',$request->client_id],['template_id','=',$request->temp_id]])->get();   
                                 
        //     if($templatesGen){
        //         $out = '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">              
        //                     <div class="form-group">
        //                         <div class="form-line">
        //                             <!--Form-group Start-->
        //                             <select name="template[]" id="template" class="form-control client" required style="width: 100%">
        //                             <option value="0">--Select Template--</option>';
        //                                     foreach($templatesGen as $row){                                                          
        //                                         $out.='<option value="'.$row->temp_get_id.'">'.$row->temp_name.'</option>';
        //                                     }       
        //                             $out.='</select>
        //                                 <span class="valid_err client_err"></span>
        //                         </div>
        //                     </div>
        //                 </div>';
        //             $out.='<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">              
        //                     <div class="form-group">
        //                         <div class="form-line">
        //                             <!--Form-group Start-->
        //                             <select name="task[]" id="task" class="form-control client" required style="width: 100%">
        //                                 <option value="">--Select what to do--</option>
        //                                 <option value="1">New</option>
        //                                 <option value="2">Overwrite</option>
        //                             </select>
        //                                 <span class="valid_err client_err"></span>
        //                         </div>
        //                     </div>
        //                 </div>';
        //         return json_encode(array('status'=>'Success','out'=>$out));
        //     }     
        // }else{
        //     $templates = DB::table('templates')->select('template_id','template_name')->get();
        //     $clients = DB::table('clients')->select('id','client_name')->get();
        //     return view('template_generation',compact('templates','clients'));
        // }
    }

    public function template_list_gen(Request $request){        
        $templates = DB::table('template_generated')->select('temp_name','temp_get_id')
        ->where([['client_id','=',$request->client_id],['template_id','=',$request->temp_id]])->get();
        $out = '';
        if(count($templates)>0){
            $out.='<fieldset class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Templates</label>
                        </div>
                        <select class="form-control tempList" id="inputGroupSelect01">
                        <option value="0" selected>Choose...</option>';
                        foreach($templates as $row){
                            $out .='<option value='.$row->temp_get_id.'>'.$row->temp_name.'</option>';
                        }
                        $out.='</select>
                    </div>
                    </fieldset>
                <fieldset>
                <div class="row">
                <div class="radio col-6 mb-1">
                  <input type="radio" class="bsradio"name="bsradio" id="new" value="1">
                  <label for="new">New</label>
                </div>
                <div class="radio col-6 mb-1">
                  <input type="radio" bsradio="bsradio" name="bsradio" id="overwrite" value="2">
                  <label for="overwrite">Overwrite</label>
                </div>
            </div>
                </fieldset>
                    ';
            return json_encode(array('status'=>'success','out'=>$out));
        }else{
            $out.='<fieldset class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Templates</label>
                        </div>
                        <select class="form-control clients" id="inputGroupSelect01" disabled>
                            <option selected>No templates to select</option>
                        </select>
                    </div>
                    </fieldset>
                    <fieldset class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Templates</label>
                        </div>
                        <select class="form-control task" id="inputGroupSelect01">
                        <option value="0" selected>Choose...</option>
                        <option value="1">New</option>                                                
                    </div>
                    </fieldset>
                    ';
            return json_encode(array('status'=>'success','out'=>$out,'msg')); 
        }
    }

}