<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function forgot_password(Request $request)
    {

        return view('pages.reset_password_otp');
    }
    public function send_otp(Request $request)
    {
        try {

            $email = $request->email;
            $otp = rand(10000, 99999);
            $generated_otp = $otp;
            $echeck = DB::table('users')->select('email')->where('email', $email)->count();
            $user_id = DB::table('users')->where('email', $email)->value('id');
            if ($echeck == 0) {
                return json_encode(array('status' => 'error', 'msg' => 'E-mail does not exist'));
            } else {
                $mailsent = Mail::send('pages.emails.reset_pass', ['mail' => $email, 'generated_otp' => $generated_otp], function ($message) use ($email) {
                    $message->from('contactyuvrajpawar@graduateconstituencypune.com', 'Dear Society')->bcc($email)->subject($email);
                });
                if ($mailsent) {
                    return json_encode(array('status' => 'error', 'msg' => 'Some error while send email'));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'OTP has been sent to your registered email', 'id' => $user_id, 'generated_otp' => $generated_otp));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return 'Database error';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error';
        }
    }
    public function reset_password(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $match_otp = $request->match_otp;
            $send_otp = $request->send_otp;
            $email = DB::table('users')->where('id', $user_id)->value('email');
             if ($request->wantsJson()) {
                 if (!empty($send_otp) && !empty($match_otp)) {
                      
                       if ($send_otp == $match_otp) {
                           return json_encode(array('status' => 'success'));
                       }
                       else
                       {
                           return json_encode(array('status' => 'error', 'msg' => 'OTP does not match , enter correct OTP'));
                       }
                       
                 }
                 else
                 {
                     return json_encode(array('status' => 'success', 'msg' => 'Please enter OTP'));
                 }
             }
             else
             {
                 if (!empty($send_otp) && !empty($match_otp)) {
                if ($send_otp == $match_otp) {
                    return view('pages.reset_password', compact('user_id', 'email'));
                } else {
                    return redirect()->back()->with('alert-danger', 'OTP does not match , enter correct OTP');
                }
                } else {
                    return view('pages.reset_password_otp');
                }
             }
            
        } catch (QueryException $e) {
            Log::error($e->getMessage());
             if ($request->wantsJson()) {
                   return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
             }
             else
             {
                 return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
             }
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
             if ($request->wantsJson()) {
                   return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
             }
             else
             {
                 return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
             }
        }
    }
    public function reset_password_submit(Request $request)
    {
        try {
            Log::info('inside update forget password method');
            $email = $request->email;
            $password = $request->password;

            $password1 = bcrypt($password);
            $update = DB::table('users')->where('email', $email)
                ->update([
                    'password' => $password1,
                    'updated_at' => now(),
                ]);

            if ($update) {
                  Log::info('password has been reset successfully');
                if ($request->wantsJson()) {
                      return json_encode(array('status' => 'success', 'msg' => 'password has been reset successfully'));
                }
                
                else
                {
                    return redirect('/')->with('alert-success', "password has been reset successfully");
                }
              
                
            } else {
                Log::info('Password cannot be Updated');
                  if ($request->wantsJson()) {
                        return json_encode(array('status' => 'error', 'msg' => 'password can`t be reset'));
                }
                else
                {
                return redirect()->back()->with('alert-danger', 'Password cannot be Updated!');
                }
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
              if ($request->wantsJson()) {
                   return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
             }
             else
             {
                 return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
             }
        } catch (Exception $e) {
            Log::error($e->getMessage());
              if ($request->wantsJson()) {
                   return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
             }
             else
             {
                 return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
             }
        }
    }
}