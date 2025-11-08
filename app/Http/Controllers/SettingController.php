<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;
use App\Traits\StaffTraits;
use App\Traits\ClientTraits;

class SettingController extends Controller
{
    use ClientTraits;
    use StaffTraits;

    public function staff_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside getstaff');

            $cities = DB::table('city')->get();
            $company = DB::table('company')->get();
            $role = DB::table('role')->get();
            $staff = $this->get_all_staff_list();

            return view('pages.staff.staff-add', compact('cities', 'company', 'role', 'staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function staff_add(Request $request)
    {
        try {
            Log::Info($request);
            $city = $request->city;
            $company = $request->company;
            $staff_name = $request->staff_name;
            $emailid = $request->emailid;
            $mobile = $request->mobile;
            $date_of_joining = $request->date_of_joining;
            $joining = str_replace('/', '-', $date_of_joining);
            $doj = date('Y-m-d', strtotime($joining));
            $password = bcrypt($request->password);
            $designation = $request->designation;
            $qualification = $request->qualification;
            $address = $request->address;
            $role_id = $request->role_id;
            $gender = $request->gender;
            $date_of_birth = $request->dob;
            $birth = str_replace('/', '-', $date_of_birth);
            $dob = date('Y-m-d', strtotime($birth));
            $image = $request->image;
            $signature = $request->signature;
            if ($request->hasFile('image')) {
                Log::Info($image);
                $target_dir1 = 'all_docs/staff/';
                Log::Info($target_dir1);
                $fileofname1 = strtolower($image->getClientOriginalName());
                Log::Info($fileofname1);
                $file_name1 =  explode('.', $fileofname1)[0];
                log::info($file_name1);
                $extension1 = strtolower($image->getClientOriginalExtension());
                Log::Info($extension1);
                $filename1 = $file_name1 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension1;
                $target_file1 = $target_dir1 . $filename1;
                Log::Info($target_file1);
                if ($image->move(base_path() . '/all_docs/staff/', $filename1)) {
                    log::info('File with name ' . $filename1 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }
            } else {
                $target_file1 = '';
            }

            if ($request->hasFile('signature')) {
                Log::Info($signature);
                $target_dir2 = 'all_docs/staff/';
                Log::Info($target_dir2);
                $fileofname2 = strtolower($signature->getClientOriginalName());
                Log::Info($fileofname2);
                $file_name2 =  explode('.', $fileofname2)[0];
                log::info($file_name2);
                $extension2 = strtolower($signature->getClientOriginalExtension());
                Log::Info($extension2);
                $filename2 = $file_name2 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension2;
                $target_file2 = $target_dir2 . $filename2;
                Log::Info($target_file2);
                if ($signature->move(base_path() . '/all_docs/staff/', $filename2)) {
                    log::info('File with name ' . $filename2 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }
            } else {
                $target_file2 = '';
            }

            $insert_id = DB::table('staff')->insertGetId(['name' => $staff_name, 'emailid' => $emailid, 'gender' => $gender, 'dob' => $dob, 'mobile' => $mobile, 'date_of_joning' => $doj, 'qualification' => $qualification, 'designation' => $designation, 'address' => $address, 'city' => $city, 'company' => $company, 'image' => $target_file1, 'signature' => $target_file2, 'created_by' => session('user_id'), 'created_at' => now(), 'updated_at' => now()]);
            Log::Info($insert_id);
            $insert1 = DB::table('users')->insert(['user_id' => $insert_id, 'email' => $emailid, 'password' => $password, 'role_id' => $role_id, 'created_at' => now(), 'created_by' => session('user_id'), 'updated_at' => now()]);
            Log::Info($insert1);
            if ($insert_id) {
                if ($insert1) {
                    Log::info("Staff Add Successfully");
                    if ($request->wantsJson()) {
                        return response()->json(array('status' => 'success', 'insert' => $insert_id, 'insert1' => $insert1));
                    } else {
                        return json_encode(array('status' => 'success', 'msg' => 'Staff Detail Inserted'));
                    }
                } else {
                    Log::error("Staff can Not be added");
                    if ($request->wantsJson()) {
                        return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                    } else {
                        return json_encode(array('status' => 'error', 'msg' => 'staff detail can`t be inserted'));
                    }
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function staff_edit($id, Request $request)
    {
        try {
            $cities = DB::table('city')->get();
            $company = DB::table('company')->get();
            $role = DB::table('role')->get();
            $staff = DB::table('staff')
                ->join('users', 'users.user_id', 'staff.sid')
                ->join('role', 'role.id', 'users.role_id')
                ->join('city', 'city.id', '=', 'staff.city', 'left')
                ->select('staff.*', 'users.role_id', 'role.role', 'city.id as city_id', 'city.city_name')
                ->where('staff.sid', $id)
                ->get();

            foreach ($staff as $val) {
                $val->company_id = json_decode($val->company);
                $val->dob = date('d/m/Y', strtotime($val->dob));
                $val->date_of_joining = date('d/m/Y', strtotime($val->date_of_joning));
            }

            return view('pages.staff.staff-edit', compact('id', 'cities', 'company', 'role', 'staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function staff_update(Request $request)
    {

        try {
            Log::Info("Inside staff update");
            $sid = $request->sid;
            $city = $request->city;
            $company = $request->company;
            $staff_name = $request->staff_name;
            $emailid = $request->emailid;
            $mobile = $request->mobile;
            $date_of_joining = $request->date_of_joining;
            $joining = str_replace('/', '-', $date_of_joining);
            $doj = date('Y-m-d', strtotime($joining));
            $designation = $request->designation;
            $qualification = $request->qualification;
            $address = $request->address;
            $role_id = $request->role_id;
            $gender = $request->gender;
            $date_of_birth = $request->dob;
            $birth = str_replace('/', '-', $date_of_birth);
            $dob = date('Y-m-d', strtotime($birth));
            $image = $request->image;
            $signature = $request->signature;

            if ($request->hasFile('image') && $request->hasFile('signature')) {
                Log::Info($image);
                $target_dir1 = 'all_docs/staff/';
                Log::Info($target_dir1);
                $fileofname1 = strtolower($image->getClientOriginalName());
                Log::Info($fileofname1);
                $file_name1 =  explode('.', $fileofname1)[0];
                log::info($file_name1);
                $extension1 = strtolower($image->getClientOriginalExtension());
                Log::Info($extension1);
                $filename1 = $file_name1 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension1;
                $target_file1 = $target_dir1 . $filename1;
                Log::Info($target_file1);
                if ($image->move(base_path() . '/all_docs/staff/', $filename1)) {
                    log::info('File with name ' . $filename1 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }

                Log::Info($signature);
                $target_dir2 = 'all_docs/staff/';
                Log::Info($target_dir2);
                $fileofname2 = strtolower($signature->getClientOriginalName());
                Log::Info($fileofname2);
                $file_name2 =  explode('.', $fileofname2)[0];
                log::info($file_name2);
                $extension2 = strtolower($signature->getClientOriginalExtension());
                Log::Info($extension2);
                $filename2 = $file_name2 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension2;
                $target_file2 = $target_dir2 . $filename2;
                Log::Info($target_file2);
                if ($signature->move(base_path() . '/all_docs/staff/', $filename2)) {
                    log::info('File with name ' . $filename2 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }

                $update_id = DB::table('staff')->where('sid', $sid)->update(['image' => $target_file1, 'signature' => $target_file2, 'updated_by' => session('user_id'), 'updated_at' => now()]);
            } else if ($request->hasFile('image')) {
                Log::Info($image);
                $target_dir1 = 'all_docs/staff/';
                Log::Info($target_dir1);
                $fileofname1 = strtolower($image->getClientOriginalName());
                Log::Info($fileofname1);
                $file_name1 =  explode('.', $fileofname1)[0];
                log::info($file_name1);
                $extension1 = strtolower($image->getClientOriginalExtension());
                Log::Info($extension1);
                $filename1 = $file_name1 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension1;
                $target_file1 = $target_dir1 . $filename1;
                Log::Info($target_file1);
                if ($image->move(base_path() . '/all_docs/staff/', $filename1)) {
                    log::info('File with name ' . $filename1 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }
                $update_id = DB::table('staff')->where('sid', $sid)->update(['image' => $target_file1, 'updated_by' => session('user_id'), 'updated_at' => now()]);
            } else if ($request->hasFile('signature')) {
                Log::Info($signature);
                $target_dir2 = 'all_docs/staff/';
                Log::Info($target_dir2);
                $fileofname2 = strtolower($signature->getClientOriginalName());
                Log::Info($fileofname2);
                $file_name2 =  explode('.', $fileofname2)[0];
                log::info($file_name2);
                $extension2 = strtolower($signature->getClientOriginalExtension());
                Log::Info($extension2);
                $filename2 = $file_name2 . '_' . strtotime(date('Y-m-d H:i:s')) . '_' . $role_id . '.' . $extension2;
                $target_file2 = $target_dir2 . $filename2;
                Log::Info($target_file2);
                if ($signature->move(base_path() . '/all_docs/staff/', $filename2)) {
                    log::info('File with name ' . $filename2 . ' uploaded to server in location ' . base_path() . '/all_docs/staff/');
                    Log::info('Upload file process Successful');
                }
                $update_id = DB::table('staff')->where('sid', $sid)->update(['signature' => $target_file2, 'updated_by' => session('user_id'), 'updated_at' => now()]);
            } else if ($staff_name != '' || $city != '' || $company != '' || $emailid != '' || $gender != '' || $mobile != '' || $address != '' || $doj != '' || $dob != '' || $qualification != '' || $designation != '') {
                $update_id = DB::table('staff')->where('sid', $sid)->update(['name' => $staff_name, 'city' => $city, 'company' => $company, 'emailid' => $emailid, 'gender' => $gender, 'mobile' => $mobile, 'address' => $address, 'date_of_joning' => $doj, 'dob' => $dob, 'qualification' => $qualification, 'designation' => $designation, 'updated_by' => session('user_id'), 'updated_at' => now()]);
                Log::Info($update_id);
                log::info($sid);
            }

            if ($update_id) {
                $update_user = DB::table('users')->where('user_id', $sid)->update([
                    'email' => $emailid,
                    'role_id' => $role_id,
                    'updated_by' => session('user_id'),
                    'updated_at' => now()
                ]);
                Log::info("Staff Updated Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success'));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Staff Detail Updated!'));
                }
            } else {
                Log::error("Staff can Not be Update");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Staff Detail can`t be updated'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {

            return json_encode(array('status' => 'error', 'msg' => 'error'));
        }
    }

    public function staff_status_change(Request $request)
    {

        try {
            $id = $request->id;
            $status = $request->status;

            $change_status = DB::table('users')->where('user_id', $id)->update([
                'status' => $status
            ]);

            if ($change_status) {
                return json_encode(array('status' => 'success', 'msg' => 'Status has been changed!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Status can not be changed'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('error' => 'Error'));
        }
    }

    public function get_staff_status_change()
    {
        try {
            $staff = $this->get_all_staff_list();
            return view('pages.staff.staff_status_change', compact('staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function company_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get company');
            $company = DB::table('company')->orderBy('id', 'desc')->get();
            return view('pages.company.company-add', compact('company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function company_add(Request $request)
    {
        try {
            Log::Info($request);
            $company_name = $request->company_name;
            $company_email = $request->company_email;
            $company_contact = $request->company_contact;
            $company_address = $request->company_address;
            $company_branch = $request->company_branch;
            $head_office = $request->head_office;
            $website_url = $request->website_url;
            $facebook_url = $request->facebook_url;
            $youtube_url = $request->youtube_url;
            $company_logo = $request->company_logo;
            $short_code = $request->short_code;
            $pan_no = $request->pan_no;
            $gst_no = $request->gst_no;
            $tax_applicable = $request->tax_applicable;
            $tds_applicable = $request->tds_applicable;

            if ($request->hasFile('company_logo')) {
                Log::Info($company_logo);
                $target_dir = 'all_docs/company/';
                Log::Info($target_dir);
                $fileofname = strtolower($company_logo->getClientOriginalName());
                Log::Info($fileofname);
                $file_name =  explode('.', $fileofname)[0];
                log::info($file_name);
                $extension = strtolower($company_logo->getClientOriginalExtension());
                Log::Info($extension);
                $filename = $file_name . '_' . strtotime(date('Y-m-d H:i:s'))  . '.' . $extension;
                $target_file = $target_dir . $filename;
                Log::Info($target_file);
                if ($company_logo->move(base_path() . '/all_docs/company/', $filename)) {
                    log::info('File with name ' . $filename . ' uploaded to server in location ' . base_path() . '/all_docs/company/');
                    Log::info('Upload file process Successful');
                }
            } else {
                $target_file = '';
            }

            $insert = DB::table('company')
                ->insert([
                    'company_name' => $company_name,
                    'short_code' => $short_code,
                    'company_email' => $company_email,
                    'company_contact' => $company_contact,
                    'company_address' => $company_address,
                    'head_office' => $head_office,
                    'website_url' => $website_url,
                    'facebook_url' => $facebook_url,
                    'youtube_url' => $youtube_url,
                    'company_branch' => json_encode($company_branch),
                    'company_logo' => $target_file,
                    'pan_no' => $pan_no,
                    'gst_no' => $gst_no,
                    'tax_applicable' => $tax_applicable,
                    'tds_applicable' => $tds_applicable,
                    'created_at' => now()
                ]);

            Log::Info($insert);

            if ($insert) {
                Log::info("Company Add Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success', 'insert' => $insert));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Company Detail Inserted'));
                }
            } else {
                Log::error("Company can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Company details can not be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function company_edit($id, Request $request)
    {
        try {
            $company = DB::table('company')->where('id', $id)->get();

            foreach ($company as $val) {
                $val->company_branch = json_decode($val->company_branch);
            }

            return view('pages.company.company-edit', compact('id', 'company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function company_update(Request $request)
    {
        try {
            Log::Info($request);
            $id = $request->id;
            $company_name = $request->company_name;
            $company_email = $request->company_email;
            $company_contact = $request->company_contact;
            $company_address = $request->company_address;
            $company_branch = $request->company_branch;
            $head_office = $request->head_office;
            $website_url = $request->website_url;
            $facebook_url = $request->facebook_url;
            $youtube_url = $request->youtube_url;
            $company_logo = $request->company_logo;
            $short_code = $request->short_code;
            $pan_no = $request->pan_no;
            $gst_no = $request->gst_no;
            $tax_applicable = $request->tax_applicable;
            $tds_applicable = $request->tds_applicable;

            if ($request->hasFile('company_logo')) {
                Log::Info($company_logo);
                $target_dir = 'all_docs/company/';
                Log::Info($target_dir);
                $fileofname = strtolower($company_logo->getClientOriginalName());
                Log::Info($fileofname);
                $file_name =  explode('.', $fileofname)[0];
                log::info($file_name);
                $extension = strtolower($company_logo->getClientOriginalExtension());
                Log::Info($extension);
                $filename = $file_name . '_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
                $target_file = $target_dir . $filename;
                Log::Info($target_file);
                if ($company_logo->move(base_path() . '/all_docs/company/', $filename)) {
                    log::info('File with name ' . $filename . ' uploaded to server in location ' . base_path() . '/all_docs/company/');
                    Log::info('Upload file process Successful');
                }

                $update = DB::table('company')
                    ->where('id', $id)
                    ->update([
                        'company_name' => $company_name,
                        'short_code' => $short_code,
                        'company_email' => $company_email,
                        'company_contact' => $company_contact,
                        'company_address' => $company_address,
                        'head_office' => $head_office,
                        'website_url' => $website_url,
                        'facebook_url' => $facebook_url,
                        'youtube_url' => $youtube_url,
                        'company_branch' => json_encode($company_branch),
                        'company_logo' => $target_file,
                        'pan_no' => $pan_no,
                        'gst_no' => $gst_no,
                        'tax_applicable' => $tax_applicable,
                        'tds_applicable' => $tds_applicable,
                        'updated_at' => now()
                    ]);
            } else {
                $update = DB::table('company')
                    ->where('id', $id)
                    ->update([
                        'company_name' => $company_name,
                        'short_code' => $short_code,
                        'company_email' => $company_email,
                        'company_contact' => $company_contact,
                        'company_address' => $company_address,
                        'head_office' => $head_office,
                        'website_url' => $website_url,
                        'facebook_url' => $facebook_url,
                        'youtube_url' => $youtube_url,
                        'company_branch' => json_encode($company_branch),
                        'pan_no' => $pan_no,
                        'gst_no' => $gst_no,
                        'tax_applicable' => $tax_applicable,
                        'tds_applicable' => $tds_applicable,
                        'updated_at' => now()
                    ]);
            }

            Log::Info($update);

            if ($update) {
                Log::info("Company Update Successfully");

                return json_encode(array('status' => 'success',  'msg' => 'Company details updated!'));
            } else {
                Log::error("Company can Not be updated");
                return json_encode(array('status' => 'error', 'msg' => 'Company details can not be updated'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_company_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get company');
            $company = DB::table('company')->orderBy('id', 'desc')->get();
            return view('pages.company.get_company_update', compact('company'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }


    public function bank_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get bank');
            $company = DB::table('company')->get();
            $bank_details = DB::table('bank_detailes')->orderBy('id', 'desc')->get();
            return view('pages.bank.bank-add', compact('company', 'bank_details'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function bank_add(Request $request)
    {
        try {
            Log::Info($request);
            $bankname = $request->bankname;
            $branchname = $request->branchname;
            $accnumber = $request->accnumber;
            $ifsccode = $request->ifsccode;
            $bankaddress = $request->bankaddress;
            $company = $request->company;
            $default_bank = $request->default_bank_account;
            if ($default_bank) {
                $default_bank_account = 'yes';
            } else {
                $default_bank_account = 'no';
            }

            $insert = DB::table('bank_detailes')->insertGetId(['bankname' => $bankname, 'branchname' => $branchname, 'accnumber' => $accnumber, 'ifsccode' => $ifsccode, 'bankaddress' => $bankaddress, 'company' => $company, 'default_bank_account' => $default_bank_account, 'created_at' => now()]);

            Log::Info($insert);

            if ($insert) {
                Log::info("Bank Add Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success', 'insert' => $insert_id));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Bank Detail Inserted'));
                }
            } else {
                Log::error("Bank can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Bank detail can`t be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function bank_update(Request $request)
    {
        try {
            Log::Info($request);
            $id = $request->id;
            $bankname = $request->bankname;
            $branchname = $request->branchname;
            $accnumber = $request->accnumber;
            $ifsccode = $request->ifsccode;
            $bankaddress = $request->bankaddress;
            $company = $request->company;
            $default_bank = $request->default_bank_account;
            if ($default_bank) {
                $default_bank_account = 'yes';
            } else {
                $default_bank_account = 'no';
            }

            $update = DB::table('bank_detailes')->where('id', $id)->update(['bankname' => $bankname, 'branchname' => $branchname, 'accnumber' => $accnumber, 'ifsccode' => $ifsccode, 'bankaddress' => $bankaddress, 'company' => $company, 'default_bank_account' => $default_bank_account, 'updated_at' => now()]);

            Log::Info($update);

            if ($update) {
                Log::info("Bank Update Successfully");

                return json_encode(array('status' => 'success', 'msg' => 'Bank updated successfully !!'));
            } else {
                Log::error("Bank can Not be updated");
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_bank_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get bank');

            $bank_details = DB::table('bank_detailes')->orderBy('id', 'desc')->get();
            return view('pages.bank.bank_update', compact('bank_details'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function template_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside template');
            $template = DB::table('email_template')->orderBy('id', 'desc')->get();
            foreach ($template as $val) {
                $val->subject = json_decode($val->subject);
                $val->message = json_decode($val->message);
            }

            return view('pages.template.template-add', compact('template'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function template_add(Request $request)
    {
        try {
            Log::Info($request);
            $template_name = $request->template_name;
            $subject = json_encode($request->subject);
            $body = json_encode($request->body);

            $insert = DB::table('email_template')->insert(['template_name' => $template_name, 'subject' => $subject, 'message' => $body]);

            Log::Info($insert);

            if ($insert) {
                Log::info("Template Added Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success'));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Template Detail Inserted'));
                }
            } else {
                Log::error("Bank can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Template detail can`t be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function template_edit($id, Request $request)
    {
        try {
            $template = DB::table('email_template')->where('id', $id)->get();
            foreach ($template as $val) {
                $val->subject = json_decode($val->subject);
                $val->message = json_decode($val->message);
            }

            return view('pages.template.template-edit', compact('template'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function template_update(Request $request)
    {
        try {
            Log::Info($request);
            $id = $request->id;
            $template_name = $request->template_name;
            $subject = json_encode($request->subject);
            $body = json_encode($request->body);

            $update = DB::table('email_template')->where('id', $id)->update(['template_name' => $template_name, 'subject' => $subject, 'message' => $body]);

            Log::Info($update);

            if ($update) {
                Log::info("Template Update Successfully");

                return json_encode(array('status' => 'success', 'msg' => 'Template updated successfully!'));
            } else {
                Log::error("Template can Not be updated");
                return json_encode(array('status' => 'error', 'msg' => 'Template can`t be updated!'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_template_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside template');
            $template = DB::table('email_template')->orderBy('id', 'desc')->get();
            foreach ($template as $val) {
                $val->subject = json_decode($val->subject);
                $val->message = json_decode($val->message);
            }

            return view('pages.template.get_template_update', compact('template'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function assign_leads_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside template');
            $staff = $this->get_staff_list_userid();

            return view('pages.clients.assign_leads', compact('staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function get_assign_leads(Request $request)
    {
        try {
            $staff = $request->staff;
            $company = session('company_id');
            $assign_leads = $this->get_clients_leads_list($company, 'leads', 'active', $staff, '', '', '', '', '', '', '', '', '');

            if ($assign_leads != '') {
                return view('pages.clients.get_assign_leads', compact('assign_leads'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'Can not get assign leads!!'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
        }
    }

    public function assign_leads(Request $request)
    {
        try {
            $clientid = $request->clientid;
            $client_id = explode(',', $clientid);
            $staff = $request->staff;
            $selectedstaff = $request->selectedstaff;
            $total = sizeof($client_id);
            for ($i = 0; $i < $total; $i++) {
                $update = DB::table('clients')
                    ->where('id', $client_id[$i])
                    ->update(['assign_to' => $staff]);
            }


            if ($update) {
                return json_encode(array('status' => 'success', 'msg' => 'Lead assigned successfully!!'));
            } else {
                return json_encode(array('status' => 'fail', 'msg' => 'Lead can`t be assigned !!'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. please try again');
        }
    }

    public function service_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get bank');
            $services = DB::table('services')->orderBy('id', 'desc')->get();
            return view('pages.services.service-add', compact('services'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function service_add(Request $request)
    {
        try {
            Log::Info($request);
            $service_name = $request->service_name;
            $description = $request->description;

            $insert_id = DB::table('services')->insertGetId(['name' => $service_name, 'description' => $description, 'created_at' => now()]);

            Log::Info($insert_id);

            if ($insert_id) {
                Log::info("Service Add Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success', 'insert' => $insert_id));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Service Detail Inserted'));
                }
            } else {
                Log::error("Bank can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Service detail can`t be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function service_update(Request $request)
    {
        try {
            Log::Info($request);
            $id = $request->id;
            $service_name = $request->service_name;
            $description = $request->description;

            $update = DB::table('services')->where('id', $id)->update(['name' => $service_name, 'description' => $description, 'updated_at' => now()]);

            Log::Info($update);

            if ($update) {
                Log::info("Service Update Successfully");

                return json_encode(array('status' => 'success', 'msg' => 'Service updated successfully !!'));
            } else {
                Log::error("Service can Not be updated");
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_service_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get bank');
            $services = DB::table('services')->orderBy('id', 'desc')->get();
            return view('pages.services.service_update', compact('services'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function lead_type_add_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get lead type');
            $lead_type = DB::table('lead_type')->orderBy('id', 'desc')->get();
            return view('pages.lead_type.lead_type_add', compact('lead_type'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function lead_type_add(Request $request)
    {
        try {
            Log::Info($request);
            $type = $request->lead_type;

            $insert_id = DB::table('lead_type')->insertGetId(['type' => $type, 'created_at' => now()]);

            Log::Info($insert_id);

            if ($insert_id) {
                Log::info("Lead Type Add Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success', 'insert' => $insert_id));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Lead Type Detail Inserted'));
                }
            } else {
                Log::error("Bank can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Lead Type detail can`t be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function lead_type_update(Request $request)
    {
        try {
            Log::Info($request);
            $id = $request->id;
            $type = $request->lead_type;

            $update = DB::table('lead_type')->where('id', $id)->update(['type' => $type, 'updated_at' => now()]);

            Log::Info($update);

            if ($update) {
                Log::info("Lead Type Update Successfully");

                return json_encode(array('status' => 'success', 'msg' => 'Lead Type updated successfully !!'));
            } else {
                Log::error("Lead Type can Not be updated");
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_lead_type_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get lead type');
            $lead_type = DB::table('lead_type')->orderBy('id', 'desc')->get();
            return view('pages.lead_type.lead_type_update', compact('lead_type'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }
    public function lead_data_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $filename = DB::table('lead_data')->where('file_name', '!=', '0')->distinct()->get(['file_name']);
            return view('pages.upload_data_list', compact('filename'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }
    public function upload_lead_data_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $leads = DB::table('leads')->whereDate('created_at', date('Y-m-d'))->orderBy('id', 'desc')->get();
            return view('pages.upload_data', compact('leads'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }
    public function upload_data(Request $request)
    {
        if (Session::get('username') == '') {
            return redirect('/')->with('status', "Please login First");
        }
        try {
            $file = $request->file('file');
            // File Details 
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            $location = '/data_csv/';
            // Upload file
            $file->move(base_path() . $location, $filename);
            $filepath = base_path() . $location . $filename;
            // Reading file
            $file = fopen($filepath, "r");
            $importData_arr = array();
            $i = 0;
            $header = NULL;
            $importData_arr = array();
            if (($handle = fopen($filepath, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (!$header)
                        $header = $row;
                    else
                        $importData_arr[] = array_combine($header, $row);
                }
                fclose($handle);
            }
            // Insert to database
            $status = '';
            foreach ($importData_arr as $row) {
                $fb_id = trim($row['fb_id']);
                $ad_id = trim($row['ad_id']);
                $ad_name = trim($row['ad_name']);
                $adset_id = trim($row['adset_id']);
                $adset_name = trim($row['adset_name']);
                $campaign_id = trim($row['campaign_id']);
                $campaign_name = trim($row['campaign_name']);
                $form_id = trim($row['form_id']);
                $form_name = trim($row['form_name']);
                $dob = trim($row['dob']);
                $are_you_member = trim($row['are_you_committee_member']);
                $platform = trim($row['platform']);
                $full_name = trim($row['full_name']);
                $phone = trim($row['phone']);
                $email = trim($row['email']);
                $street_address = trim($row['street_address']);
                $society_name_address  = trim($row['society_name_address']);
                $units = trim($row['no_of_flats_shops_society']);
                $is_organic = trim($row['is_organic']);
                $created_time = trim($row['created_time']);

                $phone = str_replace(['p:+91', 'p:'], '', $phone);

                if (!empty($dob)) {
                    $dob = date('Y-m-d', strtotime($dob));
                }
                $are_you_member = DB::table('client_contacts')->where('contact', $phone)->where('email', $email)->value('committee_member');
                $data = [
                    'name' => $full_name, 'email' => $email, 'mobile_no' => $phone, 'society_name' => $society_name_address, 'units' => $units, 'address' => $street_address, 'from' => $platform, 'fb_id' => $fb_id, 'ad_id' => $ad_id, 'ad_name' => $ad_name, 'adset_id' => $adset_id, 'adset_name' => $adset_name, 'campaign_id' => $campaign_id, 'campaign_name' => $campaign_name, 'form_id' => $form_id, 'form_name' => $form_name, 'dob' => $dob,
                    'check_commitee_member' => $are_you_member,'lead_type'=>1
                ];

                $insert = DB::table('leads')->insert($data);
                if ($insert) {
                    $status = 'success';
                } else {
                    $status = 'error';
                }
            }
            if ($status == 'success') {
                return response()->json(array('status' => 'success', 'msg' => 'CSV File has been successfully Imported.'));
            } else {
                return response()->json(array('status' => 'error', 'msg' => 'CSV File could not Imported.'));
            }
        } catch (\QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (\Exception $e) {
            Log::error('Exception3=' . $e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => $e->getMessage()));
        } catch (\Throwable $e) {
            report($e);
            Log::error('ErrorException4=' . $e->getMessage());
        }
    }
    public function search_lead_data(Request $request)
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $consumer_name = $request->consumer_name;
            $address = $request->address;
            $mobile_no = $request->mobile_no;
            $file_name = $request->file_name;
            $query = DB::table('lead_data');

            if ($file_name != '') {
                $query = $query->where('file_name', $file_name);
            }
            if ($consumer_name != '') {
                $query = $query->where(function ($query) use ($consumer_name) {
                    $query->where('consumer_name', 'like', $consumer_name . '%');
                    $query->orWhere('consumer_name', 'like', '%' . $consumer_name);
                    $query->orWhere('consumer_name', 'like', '%' . $consumer_name . '%');
                });
            }
            if ($address != '') {
                $query = $query->where(function ($query) use ($address) {
                    $query->where('address', 'like', $address . '%');
                    $query->orWhere('address', 'like', '%' . $address);
                    $query->orWhere('address', 'like', '%' . $address . '%');
                });
            }
            if ($mobile_no != '') {
                $query = $query->where('mobile_no', $mobile_no);
            }
            $out = '<div class="card">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table client-data-table wrap">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>CONSUMER NAME</th>
                                  <th>MOBILE NO</th>
                                  <th>ADDRESS</th>
                              </tr>
                          </thead>
                          <tbody>';
            $i = 1;
            $query->orderBy('id')->chunk(10000, function ($inspectors) use (&$out, $i) {

                foreach ($inspectors as $row) {

                    $out .= '<tr>
                   <td>' . $i++ . '</td>
                       <td>' . $row->consumer_name . '</td>
                       <td>' . $row->mobile_no . '</td>
                       <td>' . $row->address . '</td>
                   </tr>';
                }
            });

            $out .= '</tbody>
            </table>
        </div>
    </div>
</div>';
            return $out;
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");


            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());


            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }


    public function add_office_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get office master');
            $office_master = DB::table('office_master')->orderBy('id', 'desc')->get();
            return view('pages.office_master.add_office', compact('office_master'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }

    public function add_office(Request $request)
    {
        try {
            $insert = DB::table('office_master')->insert(['name' =>  $request->name, 'created_at' => now()]);
            if ($insert) {
                Log::info("Office Add Successfully");
                return json_encode(array('status' => 'success', 'msg' => 'Office Inserted'));
            } else {
                Log::error("office can Not be inserted");
                return json_encode(array('status' => 'error', 'msg' => 'Office can`t be inserted'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }

    public function get_office_update()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            Log::Info('inside get office');
            $office_master = DB::table('office_master')->orderBy('id', 'desc')->get();
            return view('pages.office_master.update_office', compact('office_master'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }

    public function office_update(Request $request)
    {
        try {
            $update = DB::table('office_master')->where('id', $request->id)->update(['name' => $request->name, 'updated_at' => now()]);
            if ($update) {
                Log::info("Office Update Successfully");
                return json_encode(array('status' => 'success', 'msg' => 'Office updated successfully !!'));
            } else {
                Log::error("Office Master can Not be updated");
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }

    public function staff_shift_index()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $staff = DB::table('staff')->get();
            return view('pages.staff.staff_shift', compact('staff'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
            }
        }
    }

    public function staff_shift(Request $request)
    {
        try {
            $from_time = $request->from_time;
            $from_time1 =  date("H:i:s", strtotime(str_replace(" ", "", $from_time)));
            $to_time = $request->to_time;
            $to_time1 =  date("H:i:s", strtotime(str_replace(" ", "", $to_time)));
            $from_time2 = explode(":", $from_time1);
            $to_time2 = explode(":", $to_time1);
            $staff_time_in = ($from_time2[0] * 3600) + ($from_time2[1] * 60) + $from_time2[2];
            $staff_time_out = ($to_time2[0] * 3600) + ($to_time2[1] * 60) + $to_time2[2];
            $total_working_hours = $staff_time_out - $staff_time_in;
            $total_working_hours = gmdate('H:i', abs($total_working_hours));
            $total_working_hours = ltrim(str_replace(':', '.', $total_working_hours), 0);
            $existStaff = DB::table('staff_shift')->where('staff_id', $request->staff_id)->count();
            if ($existStaff > 0) {
                return json_encode(array('status' => 'error', 'msg' => 'Staff Shift already exist,Please edit!!'));
            }
            $insert = DB::table('staff_shift')->insertGetId(['staff_id' => $request->staff_id, 'from_time' =>  str_replace(" ", "", $from_time), 'to_time' =>  str_replace(" ", "", $to_time), 'total_working_hours' => $total_working_hours, 'created_at' => now()]);
            if ($insert) {
                Log::info("staff Add Successfully");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'success', 'insert' => $insert));
                } else {
                    return json_encode(array('status' => 'success', 'msg' => 'Staff details Inserted'));
                }
            } else {
                Log::error("staff can Not be inserted");
                if ($request->wantsJson()) {
                    return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Staff details can`t be inserted'));
                }
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function get_staff_shift()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $staff_shift =
                DB::table('staff_shift')->select('staff_shift.*', 'staff.name')->join('staff', 'staff.sid', 'staff_shift.staff_id')->orderBy('staff_shift.id', 'desc')->get();
            return view('pages.staff.staff_shift_update', compact('staff_shift'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }


    public function update_staff_shift(Request $request)
    {
        try {
            $from_time = $request->from_time;
            $from_time1 =  date("H:i:s", strtotime(str_replace(" ", "", $from_time)));
            $to_time = $request->to_time;
            $to_time1 =  date("H:i:s", strtotime(str_replace(" ", "", $to_time)));
            $from_time2 = explode(":", $from_time1);
            $to_time2 = explode(":", $to_time1);
            $staff_time_in = ($from_time2[0] * 3600) + ($from_time2[1] * 60) + $from_time2[2];
            $staff_time_out = ($to_time2[0] * 3600) + ($to_time2[1] * 60) + $to_time2[2];
            $total_working_hours = $staff_time_out - $staff_time_in;
            $total_working_hours = gmdate('H:i', abs($total_working_hours));
            $total_working_hours = ltrim(str_replace(':', '.', $total_working_hours), 0);
            $update = DB::table('staff_shift')->where('id', $request->id)->update(['from_time' => str_replace(" ", "", $from_time), 'to_time' => str_replace(" ", "", $to_time), 'total_working_hours' => $total_working_hours, 'updated_at' => now()]);
            if ($update) {
                Log::info("staff Shift Update Successfully");
                return json_encode(array('status' => 'success', 'msg' => 'staff shift updated successfully !!'));
            } else {
                Log::error("Staff Shift can not be updated");
                return json_encode(array('status' => 'error'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }
    public function leave_analytics()
    {
        try {
            if (session('username') == "") {
                return redirect('/')->with('status', "Please login First");
            }
            $staff = $this->get_all_staff_list();
            $leave_type = DB::table('leave_type')->get();

            return view('pages.leave.leave_analytics', compact('staff', 'leave_type'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        }
    }

    public function get_staff_leave()
    {
        try {
            $leaves = DB::table('staff_leave')->join('staff', 'staff.sid', 'staff_leave.staff_id')->join('leave_type', 'leave_type.id', 'staff_leave.leave_type')->select('staff.name', 'staff_leave.*', 'leave_type.type')->get();
            return view('pages.leave.leave_analytics_table', compact('leaves'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        }
    }

    public function add_leave_analytics(Request $request)
    {
        try {
            $staff_id = $request->staff_id;
            $leave_type = $request->leave_type;
            $total_leaves = $request->total_leaves;
            $available_leaves = $request->available_leaves;
            $month = $request->month;
            $year = $request->year;
            $j = 0;
            for ($i = 0; $i < sizeof($total_leaves); $i++) {
                if ($total_leaves[$i] == 0 && $available_leaves[$i] == 0) {
                    continue;
                }

                $exist_leave = DB::table('staff_leave')->where('staff_id', $staff_id)->where('leave_type', $leave_type[$i])->where('month', $month)->where('session', $year)->first();
                if (!is_null($exist_leave)) {
                    $insert_id = DB::table('staff_leave')->where('id', $exist_leave->id)->update(['total_leaves' => $total_leaves[$i], 'available_leaves' => $available_leaves[$i]]);
                    if ($insert_id) {
                        $j++;
                    }
                } else {
                    if ($total_leaves[$i] > 0) {
                        $insert_id = DB::table('staff_leave')->insertGetId(['staff_id' => $staff_id, 'leave_type' => $leave_type[$i], 'total_leaves' => $total_leaves[$i], 'available_leaves' => $available_leaves[$i], 'month' => $month, 'session' => $year]);
                        if ($insert_id) {
                            $j++;
                        }
                    }
                }
            }
            log::info('value of i ' . $i);
            log::info('value of j ' . $j);
            if ($i == $j || $i > $j) {
                return json_encode(array('status' => 'success', 'msg' => 'Staff Leave inserted'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'Staff Leave can`t be inserted'));
            }
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                Log::error($e->getMessage());

                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
            } else {
                return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
            }
        }
    }

    public function update_leave_analytics(Request $request)
    {
        try {
            $id = $request->leave_id;
            $staff_id = $request->staff_id;
            $leave_type = $request->leave_type;
            $total_leaves = $request->total_leaves;
            $available_leaves = $request->available_leaves;
            $month = $request->month;
            $year = $request->year;

            $exist_leave = DB::table('staff_leave')->where('id', '!=', $id)->where('staff_id', $staff_id)->where('leave_type', $leave_type)->where('month', $month)->where('session', $year)->count();
            if ($exist_leave > 0) {
                return json_encode(array('status' => 'error', 'msg' => 'Already exist Staff Leave for this leave type , please edit'));
            } else {
                $update = DB::table('staff_leave')->where('id', $id)->update(['leave_type' => $leave_type, 'total_leaves' => $total_leaves, 'available_leaves' => $available_leaves, 'month' => $month, 'session' => $year]);
                if ($update) {
                    return json_encode(array('status' => 'success', 'msg' => 'Staff Leave updated'));
                } else {
                    return json_encode(array('status' => 'error', 'msg' => 'Staff Leave can`t be updated'));
                }
            }
        } catch (QueryException $e) {
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
        } catch (Exception $e) {
            return json_encode(array('status' => 'error', 'msg' => 'something went wrong. try again later'));
        }
    }
     public function office_address()
    {
        try {
            return view('pages.office_address.office_address');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        }
    }

    public function office_address_list()
    {
        try {
            $office_address = DB::table('dept_address')->orderBy('id','desc')->get();
            foreach($office_address as $val){
                $geolocation = json_decode($val->geolocation, true);
                if ($geolocation && $geolocation[0] !== '""' && $geolocation[1] !== '""' && $geolocation[0] !== null && $geolocation[1] !== null) {
                    $val->latitude=$geolocation[0];
                    $val->longitude=$geolocation[1];
                    $val->geolocation = implode(', ', $geolocation);
                } else {
                    $val->latitude='';
                    $val->longitude='';
                    $val->geolocation = ''; 
                }
            }
            return view('pages.office_address.office_address_list', compact('office_address'));
        } catch (QueryException $e) {
            Log::error("Database error ! [" . $e->getMessage() . "]");
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(array('status' => 'error', 'msg' => 'Something went wrong , Please contact support team!'));
        }
    }
     public function export_lead()
    {
        try {
            return view('pages.export_lead');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
        }
    }
}
