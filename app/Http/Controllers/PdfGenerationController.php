<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;
use PDF;

class PdfGenerationController extends BaseController
{
    // public function index(){
    //     return view('template_generation');        
    // }   

    public function generate_pdf(Request $request){
        require_once base_path('vendor/autoload.php');                
        $values = json_encode($request->all());
        if($request->task == 1){            
            Log::info("inserting");
            DB::table('template_generated')->insert(
                ['template_id'=>$request->temp_id,
                'temp_name' =>$request->templatename,
                'client_id'=>$request->client_id,
                'value'=>$values]
            );
        }else{                   
            Log::info("Updating");
            DB::table('template_generated')->where('temp_get_id','=',$request->template_get_id)->update(['value'=> $values]);
        }
        $id = $request->temp_id;
        $regex = "/(?<=\{\{).*(?=\}\})/";
        $path = DB::table('templates')->select('filepath')->where('template_id','=',$id)->get();
        $test = explode(",",$path[0]->filepath);
        $baseDirHtml = base_path("template/html/".$test[0]);
        $baseDirCss = base_path("template/css/".$test[1]);
        $stylesheet = file_get_contents($baseDirCss);
        $html = file_get_contents($baseDirHtml);
        $filter = str_split($html);
        $variables = '';
        $i = 0;
        $matches = array();
        $j = 0;
        $mm = array();
        $final = array();
        for($i = 0; $i < count($filter); $i++){
            if($filter[$i] == '{' && $filter[$i+1]=='{'){
                for($i ; $i <= count($filter); $i++){
                    if($filter[$i+2] == '}'){
                        break;
                    }else{
                        $matches[$i] = $filter[$i+2];
                    }
                    
                }
                $mm[$j] = implode("",$matches);
                $final[$j] = preg_replace("~_~","",$mm[$j]);
                $j++;
                $matches = [];
            }            
        }
        
        $filter = str_split($html);
        $j = 0;
        for($i = 0; $i < count($filter); $i++){
            if($filter[$i] == '{' && $filter[$i+1]=='{'){
                do{ 
                    $filter[$i+2] = '';                   
                    $i++;                                
                    $filter[$i+2] = '';                   
                }while($filter[$i+3] != '}');
                $filter[$i-1] = $request[$final[$j]];
                $j++;
            }            
        }     
        $finalHtml = implode('',$filter);
        $finalHtml = str_replace('{{','',$finalHtml);
        $finalHtml = str_replace('}}','',$finalHtml);
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('P');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($finalHtml,\Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output('PDF.pdf', \Mpdf\Output\Destination::DOWNLOAD);
            
    } 

    public function preview_pdf(Request $request){
        require_once base_path('vendor/autoload.php');
        $id = $request->temp;
        $mpdf = new \Mpdf\Mpdf();    
        $path = DB::table('templates')->select('filepath')->where('template_id','=',$id)->get();
        $test = explode(",",$path[0]->filepath);
        $baseDirHtml = base_path("template/html/".$test[0]);
        $baseDirCss = base_path("template/css/".$test[1]);
        $stylesheet = file_get_contents($baseDirCss);
        $html = file_get_contents($baseDirHtml);
        $mpdf->AddPage('P');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->Output();        
    }
}