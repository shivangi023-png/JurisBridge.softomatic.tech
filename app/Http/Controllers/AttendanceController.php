<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Traits\StaffTraits;
use App\Traits\ExpenseTraits;

class AttendanceController extends Controller
{
    use StaffTraits;
    public function get_cur_date_time(Request $request)
    {
        try {
            date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
            $date = date('d-M-Y');
            $time = date('h:i A');
            return response()->json(array('status' => 'success', 'date' => $date, 'time' => $time));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function attendance(Request $request)
    {

        $v = Validator::make($request->all(), [
            'staff_id' => 'required',
            'type' => 'required'
        ]);

        if ($v->fails()) {
            return $v->errors();
        }
        try {
            log::info($request->all());
            $staff_id = $request->staff_id;
            $type = $request->type;
            $company_id = $request->company_id;
            $time = $request->time;
            $date = date('Y-m-d', strtotime($request->date));
            $location = $request->location;
            $address = $request->address;
            if ($type == 'signin') {

                $check_signin = DB::table('attendance')->where('staff_id', $staff_id)->where('signin_date', $date)->where('company', $company_id)->count();
                if ($check_signin > 0) {
                    return response()->json(array('status' => 'error', 'msg' => 'You have already sign in'));
                }
                 if($location==array("\"\"","\"\""))
                {
                    return response()->json(array('status' => 'error', 'msg' => 'Please select location first'));
                }

                $insert = DB::table('attendance')->insert([
                    'staff_id' => $staff_id,
                    'signin_time' => $time,
                    'signin_date' => $date,
                    'signin_location' => json_encode($location),
                    'signin_address' => $address,
                    'company' => $company_id
                ]);
                if ($insert) {
                    return response()->json(array('status' => 'success', 'msg' => 'signed in successfully'));
                } else {
                    return response()->json(array('status' => 'error', 'msg' => 'can`t be signed in'));
                }
            } else {

                $check_signout = DB::table('attendance')->where('staff_id', $staff_id)->where('signin_date', $date)->where('company', $company_id)->count();
                if ($check_signout == 0) {
                    return response()->json(array('status' => 'error', 'msg' => 'You have to first sign in'));
                }

                $update = DB::table('attendance')->where('staff_id', $staff_id)->where('signin_date', $date)->where('company', $company_id)->update([
                    'signout_time' => $time,
                    'signout_date' => $date,
                    'signout_location' => json_encode($location),
                    'signout_address' => $address,

                ]);
                if ($update) {
                    return response()->json(array('status' => 'success', 'msg' => 'signed out successfully'));
                } else {
                    return response()->json(array('status' => 'error', 'msg' => 'can`t be signed out'));
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
    public function get_attendance(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'staff_id' => 'required',
                'month' => 'required',
                'company_id' => 'required',
                'year' => 'required',
            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            $staff_id = $request->staff_id;
            $month = $request->month;
            $year = $request->year;
            $company_id = $request->company_id;
            $data = DB::table('attendance')->where('staff_id', $staff_id)->whereMonth('signin_date', $month)->whereYear('signin_date', $year)->orderBy('signin_date', 'desc')->get();
            $sift=DB::table('staff_shift')->where('staff_id',$staff_id)->value('from_time');
            $working_hr=DB::table('staff_shift')->where('staff_id',$staff_id)->value('total_working_hours');

            foreach ($data as $row) {
                
                $signin_date = $row->signin_date;
                $signin_time = $row->signin_time;
                $signout_date = $row->signout_date;
                $signout_time = $row->signout_time;
         
                if ($signout_time == "") {
                    $row->total_working_hr = '';
                    $row->attendance_remark = "Not Marked";
                } else {
                    $diff = intval((strtotime($row->signout_time) - strtotime($row->signin_time)) / 60);
                    $hour = intval($diff / 60);
                    $half_hr=$hour/2;
                    $minute = $diff % 60;
                    $time = $hour . '.' . $minute;
                    $row->total_working_hr = $hour . ' hr ' . $minute . ' min';
                    if ($time < $working_hr) {
                        if($time<=$half_hr)
                        {
                            $row->attendance_remark = 'Half day'; 
                        }
                        else
                        {
                            $row->attendance_remark = 'Quarter day'; 
                        } 
                    } else if (strtotime($signin_time) > strtotime($sift)) {
                        $row->attendance_remark = 'Late Mark';
                    } else {
                        $row->attendance_remark = '';
                    }
                }
                  if($row->signin_date!='' || $row->signin_date!=NULL)
                  {
                      $row->signin_date= date('d-m-Y',strtotime($row->signin_date));
                  }
                  
                   if($row->signout_date!='' || $row->signout_date!=NULL)
                   {
                        $row->signout_date= date('d-m-Y',strtotime($row->signout_date));
                   }
                 
                
            }
            return response()->json(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function raise_attendance_list()
    {
        try {
            return view('pages.attendance.raise_attendance_list');
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        }
    }

    public function raise_attendance_table()
    {
        try {
            $attendanceData = DB::table('attendance')
                ->select('attendance.*', 'staff.name', 'staff.sid', 'attendance.remark', 'attendance.created_at', 'attendance.status')
                ->join('staff', 'staff.sid', 'attendance.staff_id')
                ->where('status', 'raised')
                ->orderBy('attendance.created_at', 'desc')
                ->get();

            if ($attendanceData) {
                return view('pages.attendance.raise_attendance_table', compact('attendanceData'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Some error while fetching data'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        }
    }

    public function attendance_status_update(Request $request)
    {
        try {
            if (is_array($request->id)) {
                $attendanceStatus = DB::table('attendance')->whereIn('id', $request->id)->update([
                    'status' => $request->status,
                ]);
                $attendanceStatus = DB::table('attendance')->whereIn('id', $request->id)->update([
                    'status' => $request->status,
                ]);
            } else {
                $attendanceStatus = DB::table('attendance')->where('id', $request->id)->update([
                    'status' => $request->status,
                ]);
            }

            if ($attendanceStatus > 0) {
                return response()->json(array('status' => 'success', 'msg' => 'attendance status updated successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'attendance status can not be updated'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        }
    }

    public function reject_attendance(Request $request)
    {
        try {
            if (is_array($request->id)) {
                $RejectRaiseAttendance = DB::table('attendance')->whereIn('id', $request->id)->update(
                    ['status' => 'rejected']
                );
            } else {
                $RejectRaiseAttendance = DB::table('attendance')->where('id', $request->id)->update([
                    'status' => 'rejected'
                ]);
            }

            if ($RejectRaiseAttendance > 0) {
                return response()->json(array('status' => 'success', 'msg' => 'raised attendance status rejected successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'raised attendance status can not be rejected'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        }
    }

    public function edit_raise_attendance(Request $request)
    {
        try {
            $signin_time = $request->signin_time;
            $signin_time=str_replace(' : ',':',$signin_time);
            $signout_time = $request->signout_time;
            $signout_time=str_replace(' : ',':',$signout_time);
            $signin_location = $request->signin_location;
            $signout_location = $request->signout_location;
            $attendance_id = $request->attendance_id;


            $updateRaiseAttendance = DB::table('attendance')->where('id', $attendance_id)->update([
                'signin_time' => $signin_time,
                'signout_time' => $signout_time,
                'signin_location' => $signin_location,
                'signout_location' => $signout_location,
                'status' => 'approved'
            ]);

            if ($updateRaiseAttendance > 0) {
                return json_encode(array('status' => 'success', 'msg' => 'Attendance Updated successfully'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Attendance can`t be updated'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'something went wrong, please contact to support team'));
        }
    }
    public function raise_attendance_api(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'attendance_id' => 'required|numeric',
                'remark' => 'required'
            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            $attendance_id = $request->attendance_id;
            $remark = $request->remark;
            $update = DB::table('attendance')->where('id', $attendance_id)->update(['remark' => $remark,'status'=>'raised']);
            if ($update) {
                return response()->json(array('status' => 'success', 'msg' => 'Request Raised Successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Request can`t be raised'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
     public function raise_other_attendance(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'staff_id' => 'required|numeric',
                'remark' => 'required',
                'date'=>'required|date_format:Y-m-d',
                'company'=>'required|numeric'
            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            $staff_id = $request->staff_id;
            $remark = $request->remark;
            $date=$request->date;
            $company=$request->company;
            $check=DB::table('attendance')->where('staff_id',$staff_id)->where('signin_date',$date)->count();
            if($check>0)
            {
                return response()->json(array('status' => 'error', 'msg' => 'Attendance already marked for this date'));
            }
            $insert=DB::table('attendance')->insert(['staff_id'=>$staff_id,'signin_date'=>$date,'signout_date'=>$date,'remark' => $remark,'company'=>$company,'status'=>'raised']);
           
            if ($insert) {
                return response()->json(array('status' => 'success', 'msg' => 'Request Raised Successfully'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Request can`t be raised'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
    public function attendance_chart(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'month' => 'required|numeric',
            ]);

            if ($v->fails()) {
                return $v->errors();
            }
            $month=$request->month;
            $year=date('Y');
            $present_days=array();
            $absent_days=array();
            $leave_days=array();
            $total_days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
            $staff=$this->get_staff_list();
            $total_staff=sizeof($staff);
            foreach($staff as $row)
            {
                for($i=1;$i<=$total_days;$i++)
                {
                    $date=date('Y-m-d',mktime(0,0,0,$month,$i,$year));
                    
                    $present_count=DB::table('attendance')->where('staff_id',$row->sid)->where('signin_date',$date)->count();
                    if($present_count>0)
                    {
                        $present_days[]=$date;
                    }
                    else
                    {
                          $count_leave=DB::table('leave_table')->where('staff_id',$row->sid)->where('start_date','<=',$date)->where('end_date','>=',$date)->where('status','Approved')->count();
                            if($count_leave>0)
                            {
                                $leave_days[]=$date;
                            }
                            else
                            {
                                $absent_days[]=$date;
                            }
                    }
                  
                    
                }
               
            }

            $data=array('total_staff'=>$total_staff,'total_present'=>sizeof($present_days),'total_absent'=>sizeof($absent_days),'total_leave'=>sizeof($leave_days));
            return response()->json(array('status' => 'success', 'data' => $data));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('error' => 'Database error'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }
}
