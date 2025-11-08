<?php

namespace App\Console\Commands;

use App\Http\Controllers\PhpMailerController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoSignOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AutoSignOut:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      try 
      {
        log::info('auto signout cron');
        $curdate=date('Y-m-d');
        $not_sign_out_id=DB::table('attendance')->where('signin_date',$curdate)->whereNull('signout_date')->whereNull('signout_time')->get(['id']);
        foreach($not_sign_out_id as $row)
        {
             $update=DB::table('attendance')->where('id',$row->id)->update(['signout_date'=>$curdate,'signout_time'=>'12:00 AM']);
             if($update)
             {
             log:info('auto logout done for id'.$row->id);
             }
         }
      } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
  
}
}
