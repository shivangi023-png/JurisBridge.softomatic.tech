<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\TableLog;
trait TaskTraits
{
    public static function table_log($primary_id,$table_name,$old_value,$new_value,$description,$updated_col)
    {
      
        $table_log = new TableLog;
        $table_log->primary_id = $primary_id;
        $table_log->table_name = $table_name;
        $table_log->updated_col = $updated_col;
        $table_log->old_value = $old_value;
        $table_log->new_value = $new_value;
        $table_log->description = $description;
        $table_log->save();
        return 'success';
    }
    public static function datesarr($month,$year)
    {
        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $dates=array();
        for($i=1;$i<=$d;$i++)
        {
            $date=$year.'-'.$month.'-'.$i;
            $dates[]=date('Y-m-d',strtotime($date));
        }
        return $dates;
    }
    function getAllDates($startingDate, $endingDate,$month,$year)
    {
        $datesArray = [];

        $startingDate = strtotime($startingDate);
        $endingDate = strtotime($endingDate);
             
        for ($currentDate = $startingDate; $currentDate <= $endingDate; $currentDate += (86400)) {
            $date = date('Y-m-d', $currentDate);
            if(date('m',strtotime($date))==$month && date('Y',strtotime($date))==$year)
            {
                $datesArray[] = $date;

            }
            
        }
  
        return sizeof($datesArray);
    }
  
   

}