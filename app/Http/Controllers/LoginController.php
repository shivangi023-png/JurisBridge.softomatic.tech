<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use  App\Models\User;

class LoginController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //TO LOGIN AND GENERATE TOKEN

        $v = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($v->fails()) {
            return $v->errors();
        }
        if ($request->app_version != null) {
                $latest_version = DB::table('app_latest_version')->value('latest_version');
                $appversion = explode('.', $request->app_version);
                $latestversion = explode('.', $latest_version);

                $toupdate = false;
                if ((int) $appversion[0] < (int) $latestversion[0]) {
                    $toupdate = true;
                } elseif ((int) $appversion[1] < (int) $latestversion[1] && (int) $appversion[0] == (int) $latestversion[0]) {
                    $toupdate = true;
                } elseif ((int) $appversion[2] < (int) $latestversion[2] && (int) $appversion[1] == (int) $latestversion[1] && (int) $appversion[0] == (int) $latestversion[0]) {
                    $toupdate = true;
                }

                if ($toupdate == true) {
                    return response()->json(array('status' => 'New Version Available', 'msg' => 'You are using ' . $request->app_version . ' version. please update with version ' . $latest_version));
                }
            }   
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(array('status' => 'failed', 'msg' => 'inavlid email format'));
        } else {
            $user = DB::table('users')->select('email', 'role_id', 'id')->where('email', '=', $request->email)->get();
            $staff = DB::table('staff')->select('sid', 'name', 'mobile', 'company')->where('emailid', '=', $request->email)->get();

            $credentials = $request->only(['email', 'password']);
            if ($token = auth()->attempt($credentials)) {
                if (count($staff) == 0) {
                    return response()->json(array(
                        'status' => 'error',
                        'msg' => 'You are not authorized'
                    ));
                }
                $check_status = DB::table('users')->where('id', $user[0]->id)->value('status');
                if ($check_status == 'active') {
                    $company_array = json_decode($staff[0]->company);
                    $company_id = $company_array[0];
                    $company_name = DB::table('company')->whereIn('id', json_decode($staff[0]->company))->get(['id', 'company_name']);
                    $check_signin = DB::table('attendance')->where('staff_id', $staff[0]->sid)->where('signin_date', date('Y-m-d'))->whereNull('signout_date')->where('company', $company_id)->value('id');
                    if ($check_signin > 0) {
                        $attendance = DB::table('attendance')->where('staff_id', $staff[0]->sid)->where('signin_date', date('Y-m-d'))->where('company', $company_id)->get();
                        $signin = 1;
                        foreach ($attendance as $atn) {
                            $signin_time = $atn->signin_time;
                            $signin_date = date('d-M-Y', strtotime($atn->signin_date));
                            $location = $atn->signin_location;
                            $address = $atn->signin_address;
                        }
                    } else {
                        $signin = 0;
                        $signin_time = "";
                        $signin_date = "";

                        $location = "";
                        $address = "";
                    }
                    $mobile_no=DB::table('staff')->where('sid',$staff[0]->sid)->value('mobile');
                    
                    $check_case_assign=DB::table('assign_cases')->join('mycases','mycases.id','assign_cases.case_id')->where('assign_cases.phone_no',$mobile_no)->where('mycases.status','finalize')->count();
                    if($check_case_assign>=1)
                    {
                        $case_assign='yes';
                    }
                    else
                    {
                        $case_assign='no';
                    }
                    return response()->json([
                        'status' => 'success',
                        'token' => $token,
                        'user_id' => $user[0]->id,
                        'email' => $user[0]->email,
                        'role_id' => $user[0]->role_id,
                        'mobile' => $staff[0]->mobile,
                        'name' => $staff[0]->name,
                        'sid' => $staff[0]->sid,
                        'company' => $company_name,
                        'signin' => $signin,
                        'signin_time' => $signin_time,
                        'signin_date' => $signin_date,
                        'location' => $location,
                        'address' => $address,
                        'mobile_no'=>$mobile_no,
                        'case_assign'=>$case_assign,
                    ], 200);
                } else {
                    return response()->json(['status' => 'failed', 'msg' => 'This username is inactive'], 500);
                }
            } else {
                return response()->json(['status' => 'failed', 'msg' => 'user not found'], 500);
            }
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }



    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 60 * 24 * 7
        ]);
    }
    
    protected function update_token_app(Request $request)
    {
         $v = Validator::make($request->all(), [
            'firebase_token' => 'required|string',
            'user_id' => 'required|numeric',
        ]);

        if ($v->fails()) {
            return $v->errors();
        }
        try {
            log::info("update_token_app=".$request->firebase_token.' sraff_id='.$request->user_id);
            $firebase_token=$request->firebase_token;
            $user_id=$request->user_id;
            $check_app = DB::table('firebase_tokens')->where('type', 'app')->where('device_token', $request->firebase_token)->count();
            if ($check_app == 0) {
                $save = DB::table('firebase_tokens')->insert(['staff_id' => $request->user_id, 'type' => 'app', 'device_token' => $request->firebase_token, 'created_at' => now()]);
            } else {
                $save = DB::table('firebase_tokens')->where('staff_id', $request->user_id)->where('device_token', $request->firebase_token)->update(['device_token' => $request->firebase_token, 'type' =>'app', 'updated_at' => now()]);
            }
           // $update=DB::table('users')->where('id',$user_id)->update(['firebase_token'=>$firebase_token]);
            if($save)
            {
                return response()->json(['status' => 'success', 'msg' => 'Token updated successfully','pagename'=>'dashboard']);
            }
            else
            {
                return response()->json(['status' => 'failed', 'msg' => 'Token can`t be updated']);
            }
        
            } 
            catch (QueryException $e) 
               {
                    Log::error('error : ' . $e->getMessage());
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong. Try again later.'));
                } catch (Exception $e) 
                {
                    Log::error('error : ' . $e->getMessage());
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong. Try again later.'));
                }
    }
    public function get_latest_version(Request $request)
    {
        try {
            
            $latest_version = DB::table('app_latest_version')->value('latest_version');
            if ($latest_version != null) {
                return response()->json(array('status' => 'success', 'latest_version' => $latest_version));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'App version not found.'));
            }
        } catch (QueryException $e) {
            Log::error('inside get_latest_version. error : ' . $e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong. Try again later.'));
        } catch (Exception $e) {
            Log::error('inside get_latest_version. error : ' . $e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong. Try again later.'));
        }
    }
}
