<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WebLoginController extends Controller
{
    public function showlogin(Request $request)
    {

        if (session('username') == '') {

            return view('pages.auth-login');
        } else {
            $email = session('email');
            // $count = app('firebase.firestore')->database()->collection('user')->where('email','=',$email)  
            // ->documents()->size();
            // if($count==0)
            // {

            //     $mobile=session('mobile');
            //     $designation=session('designation');
            //     $user_id=session('user_id');
            //     $firestore = app('firebase.firestore')
            //     ->database()
            //     ->collection('user')
            //     ->newDocument();
            //             $firestore->set([
            //                     'client_id'=>'',
            //                     'email'    =>$email,
            //                     'mobile_no'=>$mobile,
            //                     'name'     =>$designation,
            //                     'uid'      =>$user_id
            //                  ]);
            // }

            return Redirect::to('dashboard');
        }
    }
    public function weblogin(Request $request)
    {


        $v = Validator::make($request->all(), [
            'username' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if ($v->fails()) {

            return $v->errors();
        } else {
            if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
                $user = Auth::user();
                $check_status = DB::table('users')->where('id', $user->id)->value('status');
                if ($check_status == 'active') {

                    $response = array();
                    $response['status'] = 'success';

                    $role_id = $user->role_id;
                    $username = $user->email;
                    $user_id = $user->id;
                    $role = DB::table('role')->where('id', $user->role_id)->value('role');
                    try {
                        $sid = DB::table('staff')->where('emailid', $user->email)->value('sid');
                        $company_id = DB::table('staff')->where('sid', $sid)->value('company');
                        $company_id = json_decode($company_id);
                        $company = DB::table('company')->whereIn('id', $company_id)->get();
                        $email = DB::table('staff')->where('sid', $sid)->value('emailid');
                        $mobile = DB::table('staff')->where('sid', $sid)->value('mobile');
                        $name = DB::table('staff')->where('sid', $sid)->value('name');

                        $qualification = DB::table('staff')->where('sid', $sid)->value('qualification');
                        $address = DB::table('staff')->where('sid', $sid)->value('address');
                        $city_id = DB::table('staff')->where('sid', $sid)->value('city');
                        $city = DB::table('city')->where('id', $city_id)->value('city_name');
                        $date_of_joining = DB::table('staff')->where('sid', $sid)->value('date_of_joning');
                        $doj = date('d-M-Y', strtotime($date_of_joining));
                        $dob1 = DB::table('staff')->where('sid', $sid)->value('dob');
                        $dob = date('d-M-Y', strtotime($dob1));
                        $designation = DB::table('staff')->where('sid', $sid)->value('designation');
                        $image = DB::table('staff')->where('sid', $sid)->value('image');
                        $company1 = DB::table('staff')->where('sid', $sid)->value('company');
                        $company2 = json_decode($company1);
                        $com = [];
                        for ($i = 0; $i < sizeof($company2); $i++) {
                            $com[] = DB::table('company')->where('id', $company2[$i])->value('id');
                            $staff_company = implode(',', $com);
                        }
                        $company_full = DB::table('company')->whereIn('id', $com)->get();
                        $company_name = DB::table('company')->where('id', $company_id[0])->value('company_name');
                        $short_code = DB::table('company')->where('id', $company_id[0])->value('short_code');

                        $pan_no = DB::table('company')->where('id', $company_id[0])->value('pan_no');
                        $gst_no = DB::table('company')->where('id', $company_id[0])->value('gst_no');
                        $company_email = DB::table('company')->where('id', $company_id[0])->value('company_email');
                        $company_contact = DB::table('company')->where('id', $company_id[0])->value('company_contact');
                        $company_address = DB::table('company')->where('id', $company_id[0])->value('company_address');
                        $head_office = DB::table('company')->where('id', $company_id[0])->value('head_office');
                        $website_url = DB::table('company')->where('id', $company_id[0])->value('website_url');
                        $facebook_url = DB::table('company')->where('id', $company_id[0])->value('facebook_url');
                        $youtube_url = DB::table('company')->where('id', $company_id[0])->value('youtube_url');
                        $company_logo = DB::table('company')->where('id', $company_id[0])->value('company_logo');
                        $tds_applicable = DB::table('company')->where('id', $company_id[0])->value('tds_applicable');
                        $tax_applicable = DB::table('company')->where('id', $company_id[0])->value('tax_applicable');
                        $company_branch1 = DB::table('company')->where('id', $company_id[0])->value('company_branch');
                        if (!empty($company_branch1)) {
                            $company_branch2 = json_decode($company_branch1);
                            $branch = [];
                            for ($i = 0; $i < sizeof($company_branch2); $i++) {
                                $branch[] = $company_branch2[$i];
                                $company_branch = implode(' | ', $branch);
                            }
                        } else {
                            $company_branch = 'N/A';
                        }
                        // $count = app('firebase.firestore')->database()->collection('user')->where('email','=',$email)  
                        // ->documents()->size();
                        // if($count==0)
                        // {
                        //     $firestore = app('firebase.firestore')
                        //     ->database()
                        //     ->collection('user')
                        //     ->newDocument();
                        // $firestore->set([
                        //     'client_id'=>'',
                        //     'email'    =>$email,
                        //     'mobile_no'=>$mobile,
                        //     'name'     =>$designation,
                        //     'uid'      =>$user_id
                        //  ]);
                        // }
                        Session::put('staff_id', $sid);
                        Session::put('username', $username);
                        Session::put('mobile', $mobile);
                        Session::put('name', $name);
                        Session::put('email', $email);
                        Session::put('address', $address);
                        Session::put('city', $city);
                        Session::put('qualification', $qualification);
                        Session::put('date_of_joining', $doj);
                        Session::put('dob', $dob);
                        Session::put('designation', $designation);
                        Session::put('image', $image);
                        Session::put('staff_company', $staff_company);
                        Session::put('role', $role);
                        Session::put('role_id', $role_id);
                        Session::put('user_id', $user_id);
                        Session::put('company', $company);
                        Session::put('company_id', $company_id[0]);
                        Session::put('default_company_id', $company_id[0]);
                        Session::put('short_code', $short_code);
                        Session::put('company_full', $company_full);
                        Session::put('company_name', $company_name);
                        Session::put('pan_no', $pan_no);
                        Session::put('gst_no', $gst_no);
                        Session::put('company_email', $company_email);
                        Session::put('company_contact', $company_contact);
                        Session::put('company_address', $company_address);
                        Session::put('head_office', $head_office);
                        Session::put('website_url', $website_url);
                        Session::put('facebook_url', $facebook_url);
                        Session::put('youtube_url', $youtube_url);
                        Session::put('company_logo', $company_logo);
                        Session::put('tds_applicable', $tds_applicable);
                        Session::put('tax_applicable', $tax_applicable);
                        Session::put('company_branch', $company_branch);
                        Session::put('company_full', $company_full);

                        Log::info($username . " Login Successfully") . '<br>';
                        if ($role_id == 9) {
                            return Redirect::to('presales-dashboard');
                        } elseif ($role_id == 8) {
                            return Redirect::to('sales-dashboard');
                        } else {
                            return Redirect::to('dashboard');
                        }
                    } catch (QueryException $e) {
                        Log::error("Database Query Error!");

                        return redirect()->back()->with('alert-danger', "Database Query Error!");
                    } catch (Exception $e) {
                        Log::error($e->getMessage());

                        return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
                    }
                } else {
                    return redirect()->back()->with('alert-danger', 'This username is inactive');
                }
            } else {
                Log::error('Invalid Username or Password') . '<br>';

                return redirect()->back()->with('alert-danger', 'Invalid Username or Password.');
            }
        }
    }
    public function change_company(Request $request)
    {
        $company = $request->company;
        $company_name = DB::table('company')->where('id', $company)->value('company_name');
        $short_code = DB::table('company')->where('id', $company)->value('short_code');
        $pan_no = DB::table('company')->where('id', $company)->value('pan_no');
        $gst_no = DB::table('company')->where('id', $company)->value('gst_no');
        $company_email = DB::table('company')->where('id', $company)->value('company_email');
        $company_contact = DB::table('company')->where('id', $company)->value('company_contact');
        $company_address = DB::table('company')->where('id', $company)->value('company_address');
        $head_office = DB::table('company')->where('id', $company)->value('head_office');
        $website_url = DB::table('company')->where('id', $company)->value('website_url');
        $facebook_url = DB::table('company')->where('id', $company)->value('facebook_url');
        $youtube_url = DB::table('company')->where('id', $company)->value('youtube_url');
        $company_logo = DB::table('company')->where('id', $company)->value('company_logo');
        $tds_applicable = DB::table('company')->where('id', $company)->value('tds_applicable');
        $tax_applicable = DB::table('company')->where('id', $company)->value('tax_applicable');
        $company_branch1 = DB::table('company')->where('id', $company)->value('company_branch');
          $header_footer = DB::table('company')->where('id', $company)->value('header_footer');
        if (!empty($company_branch1)) {
            $company_branch2 = json_decode($company_branch1);
            $branch = [];
            for ($i = 0; $i < sizeof($company_branch2); $i++) {
                $branch[] = $company_branch2[$i];
                $company_branch = implode(' | ', $branch);
            }
        } else {
            $company_branch = 'N/A';
        }

        Session::put('company_id', $company);
        Session::put('company_name', $company_name);
        Session::put('default_company_id', $company);
        Session::put('short_code', $short_code);
        Session::put('pan_no', $pan_no);
        Session::put('gst_no', $gst_no);
        Session::put('company_email', $company_email);
        Session::put('company_contact', $company_contact);
        Session::put('company_address', $company_address);
        Session::put('head_office', $head_office);
        Session::put('website_url', $website_url);
        Session::put('facebook_url', $facebook_url);
        Session::put('youtube_url', $youtube_url);
        Session::put('company_logo', $company_logo);
        Session::put('tds_applicable', $tds_applicable);
        Session::put('tax_applicable', $tax_applicable);
        Session::put('company_branch', $company_branch);
        Session::put('header_footer', $header_footer);
    }
    public function logout(Request $request)
    {
        $role = session('role_id');
        $username = session('email');
        Log::info($username . " Logout Successfully") . '<br>';

        \Cache::flush();
        Session::forget('username');
        Session::forget('email');
        Session::forget('password');
        Session::flush();

        return redirect('/')->withCookie(\Cookie::forget('laravel_token'))->with('alert-success', 'Successfully Logout');
    }
}
