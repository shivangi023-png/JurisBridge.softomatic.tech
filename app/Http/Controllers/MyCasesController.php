<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Firestore\FieldValue;

class MyCasesController extends Controller
{
  public function chat()
  {

    $email = session('email');
    $count = app('firebase.firestore')->database()->collection('user')->where('email', '=', $email)
      ->documents()->size();
    if ($count == 0) {

      $mobile = session('mobile');
      $designation = session('designation');
      $user_id = session('user_id');
      $firestore = app('firebase.firestore')
        ->database()
        ->collection('user')
        ->newDocument();
      $firestore->set([
        'client_id' => '',
        'email'    => $email,
        'mobile_no' => $mobile,
        'name'     => $designation,
        'uid'      => $user_id
      ]);
    }
    return view('pages.chat.chat_communication');
  }
  public function getMyCases(Request $request)
  {
    try {

      $clients_ids =  DB::table('mycases')->distinct()->get('client_id');
      $ids = array_column(json_decode($clients_ids, true), 'client_id');
      $clients_list =  DB::table('clients')->select('id', 'case_no', 'client_name')->whereIn('id', $ids)->get();

      foreach ($clients_list as $list) {
        $list->mycases_list =  DB::table('mycases')->select('id', 'case_no', 'client_id', 'description')->where('client_id', $list->id)->where('status', 'finalize')->get();
      }
      return view('pages.chat.mycases_list', compact('clients_list'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    }
  }
  
  public function chat_list(Request $request)
  {
    try {
      $case_id = $request->case_id;
      $mycases =  DB::table('mycases')->where('case_no', $request->case_no)->where('status', 'finalize')->first();
      $out = '';


      $chats1 = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)->orderBy('createdAt')->documents();

      $datearr = array();
      foreach ($chats1 as $row) {

        $datearr[] = $row->data()['date'];
      }

      $doc = '';

      $chats = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)->orderBy('createdAt')->documents();
      $i = 0;
      foreach ($chats as $row) {
        $from = $row->data()['from'];

        $users = app('firebase.firestore')->database()->collection('user')->where('uid', '=', $from)->documents();
        foreach ($users as $user) {

          $name = $user->data()['name'];
        }

        if (date('Y-m-d') == $datearr[$i]) {
          $date = 'Today';
        } else if (date('Y-m-d', strtotime("-1 days")) == $datearr[$i]) {
          $date = 'Yesterday';
        } else {
          $date = date('d-m-Y', strtotime($datearr[$i]));
        }
        if ($i == 0) {

          $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
        } else {
          if ($datearr[$i] != $datearr[$i - 1]) {
            $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
          }
        }


        if ($from != session('user_id')) {
          $out .= '<div class="row no-gutters">
              <div class="col-md-3">
                <div class="chat-bubble chat-bubble--left">
                <div class="user_name">
                    ' . $name . '
                  </div>
                  <div class="chat_msg">
                    ' . $row->data()['msg'] . '
                  </div>
                  
                </div>
              </div>
            </div>';
        } else {
          $out .= '<div class="row no-gutters">
            <div class="col-md-3 offset-md-9">
              <div class="chat-bubble chat-bubble--right">
                <div class="user_name">
                ' . $name . '
                </div>
                <div class="chat_msg">
                  ' . $row->data()['msg'] . '
                </div>
              </div>
            </div>
          </div>';
        }
        $i++;
      }
      $size = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)
        ->documents()->size();

      if ($size > 0) {
        $out .= '<input type="hidden" value="' . $date . '" class="date"><div class="doc_div"><input type="hidden" value="' . $size . '" class="doc_size"></div>';
      }





      return view('pages.chat.chat_list', compact('mycases', 'out'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    }
  }

  public function getContacts(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'client_id' => 'required|numeric',
      ]);

      if ($v->fails()) {
        return $v->errors();
      }
      $client_id = $request->client_id;
      $assign_cases = DB::table('assign_cases')->join('client_contacts', 'client_contacts.contact', 'assign_cases.phone_no')->select('assign_cases.*', 'client_contacts.name')->where('assign_cases.client_id', $client_id)->get();
      $assign_no = array();
      foreach ($assign_cases as $ac) {
        $assign_no[] = $ac->phone_no;
      }
      $contacts = DB::table('client_contacts')->whereNotIn('contact', $assign_no)->where('client_id', $client_id)->get();
      return response()->json(array('status' => 'success', 'contacts' => $contacts, 'assign_cases' => $assign_cases));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    }
  }
  public function add_participate(Request $request)
  {
    try {

      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'phone_no' => 'required',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $phone_no_arr = $request->phone_no;
      $client_id = $request->client_id;
      $case_id = $request->case_id;
      $type = $request->type;

      $msg = '';
      $status = '';
      foreach ($phone_no_arr as $phone_no) {
        $checkassign = DB::table('assign_cases')->where('case_id', $case_id)->where('phone_no', $phone_no)->count();
        if ($checkassign == 0) {
          $insert = DB::table('assign_cases')->insert(['client_id' => $client_id, 'case_id' => $case_id, 'phone_no' => $phone_no, 'type' => $type]);
          if ($insert) {
            $status = 'success';
          } else {
            $status = 'error';
            $msg = 'Contact can`t be assigned';
          }
        } else {
          $status = 'error';
          $msg = 'Already Assigned';
        }
      }

      if ($status == 'success') {
        $assign_cases = DB::table('assign_cases')->where('client_id', $client_id)->get();
        return response()->json(array('status' => 'success', 'msg' => 'Contact assigned successfully', 'assign_cases' => $assign_cases));
      } else if ($status == 'error') {
        return response()->json(array('status' => 'error', 'msg' => $msg));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    }
  }

  public function remove_participate(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'assign_id' => 'required'
      ]);

      if ($v->fails()) {
        return $v->errors();
      }
      $assign_ids = $request->assign_id;
      $client_id = $request->client_id;
      $case_id = $request->case_id;
      $status = '';
      foreach ($assign_ids as $assign_id) {
        $delete = DB::table('assign_cases')->where('id', $assign_id)->delete();
        if ($delete) {
          $status = 'success';
        } else {
          $status = 'error';
        }
      }
      if ($status == 'success') {
        $assign_cases = DB::table('assign_cases')->where('client_id', $client_id)->get();
        $assign_no = array();
        foreach ($assign_cases as $ac) {
          $assign_no[] = $ac->phone_no;
        }
        $contacts = DB::table('client_contacts')->whereNotIn('contact', $assign_no)->where('client_id', $client_id)->get();
        return response()->json(array('status' => 'success', 'msg' => 'Contact deleted successfully', 'contacts' => $contacts, 'assign_cases' => $assign_cases));
      } else if ($status == 'error') {
        return response()->json(array('status' => 'error', 'msg' => 'Contact can`t be deleted'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong, please contact to support team'));
    }
  }

  public function getStaffContacts(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'client_id' => 'required|numeric',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $client_id = $request->client_id;
      $type = $request->type;

      $assign_cases = DB::table('assign_cases')->leftJoin('staff', 'staff.mobile', 'assign_cases.phone_no')->join('clients', 'assign_cases.client_id', 'clients.id')->select('assign_cases.*', 'staff.name', 'staff.mobile')->where('type', $type)->where('assign_cases.client_id', $client_id)->get();
      $assign_no = array();
      foreach ($assign_cases as $ac) {
        $assign_no[] = $ac->phone_no;
      }
      $contacts = DB::table('staff')->whereNotIn('mobile', $assign_no)->get();

      return response()->json(array('status' => 'success', 'staff_contact' => $contacts, 'staff_assign_cases' => $assign_cases));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    }
  }

  public function add_staff(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'phone_no' => 'required',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $phone_no_arr = $request->phone_no;
      $client_id = $request->client_id;
      $case_id = $request->case_id;
      $type = $request->type;
      $msg = '';
      $status = '';
      foreach ($phone_no_arr as $phone_no) {
        $checkassign = DB::table('assign_cases')->where('case_id', $case_id)->where('type', $type)->where('phone_no', $phone_no)->count();
        if ($checkassign == 0) {
          $insert = DB::table('assign_cases')->insert(['client_id' => $client_id, 'case_id' => $case_id, 'phone_no' => $phone_no, 'type' => $type]);
          if ($insert) {
            $status = 'success';
          } else {
            $status = 'error';
            $msg = 'Contact can`t be assigned';
          }
        } else {
          $status = 'error';
          $msg = 'Already Assigned';
        }
      }

      if ($status == 'success') {
        $assign_cases = DB::table('assign_cases')->where('client_id', $client_id)->get();
        return response()->json(array('status' => 'success', 'msg' => 'Contact assigned successfully', 'assign_cases' => $assign_cases));
      } else if ($status == 'error') {
        return response()->json(array('status' => 'error', 'msg' => $msg));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Something went wrong, please contact to support team'));
    }
  }

  public function remove_staff(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
        'client_id' => 'required|numeric',
        'assign_id' => 'required'
      ]);

      if ($v->fails()) {
        return $v->errors();
      }
      $assign_ids = $request->assign_id;
      $client_id = $request->client_id;
      $case_id = $request->case_id;
      $status = '';
      foreach ($assign_ids as $assign_id) {
        $delete = DB::table('assign_cases')->where('id', $assign_id)->delete();
        if ($delete) {
          $status = 'success';
        } else {
          $status = 'error';
        }
      }
      if ($status == 'success') {
        $assign_cases = DB::table('assign_cases')->where('client_id', $client_id)->get();
        $assign_no = array();
        foreach ($assign_cases as $ac) {
          $assign_no[] = $ac->phone_no;
        }
        $contacts = DB::table('client_contacts')->whereNotIn('contact', $assign_no)->where('client_id', $client_id)->get();
        return response()->json(array('status' => 'success', 'msg' => 'Contact deleted successfully', 'contacts' => $contacts, 'assign_cases' => $assign_cases));
      } else if ($status == 'error') {
        return response()->json(array('status' => 'error', 'msg' => 'Contact can`t be deleted'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong, please contact to support team'));
    }
  }

  public function upload_mycases_doc(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric'
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $date = date('Y-m-d');
      $uid = session('user_id');
      $file = $request->fileName;
      $case_id = $request->case_id;
      $description = $request->description;
      $case_doc_id = DB::table('case_document')->max('id') + 1;

      $filename = strtolower($file->getClientOriginalName());
      $extension = strtolower($file->getClientOriginalExtension());
      $file_name = pathinfo($filename, PATHINFO_FILENAME);
      $case_doc_id = DB::table('case_document')->max('id') + 1;
      $doc_name = $file_name . '-' . $case_doc_id . '_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;

      Storage::disk('s3_mycase_document')->put($doc_name, $file, 'public');
      $target_file = Storage::disk('s3_mycase_document')->url($doc_name);

      log::info('---------s3 url path-------------');
      log::info($file);
      log::info($doc_name);
      log::info($target_file);

      if ($target_file) {
        Log::info("target file" . $target_file);
        $insert = DB::table('case_document')->insert(['case_id' => $case_id, 'document_link' => $target_file, 'date' => $date, 'description' => $description, 'uploaded_by' => $uid]);
        if ($insert) {
          return response()->json(array('status' => 'success', 'msg' => 'File uploaded successfully'));
        } else {
          return json_encode(array('status' => 'error', 'msg' => 'File Can`t be uploaded'));
        }
      } else {
        Log::error("File Can't be uploaded");
        return response()->json(array('status' => 'error', 'msg' => 'File Can`t be uploaded'));
      }
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Something went wrong, please contact to support team'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function send_chats(Request $request)
  {
    try {
      $case_id = $request->case_id;
      $db = app('firebase.firestore')->database();
      $chat = $request->chat;
      $client_id = $request->client_id;

      $addedDocRef = $db->collection('messages/chat/case_' . $case_id)->add([
        'case_id' => $case_id,
        'client_id' => $client_id,
        'createdAt' => FieldValue::serverTimestamp(),
        'date' => date('Y-m-d'),
        'from' => session('user_id'),
        'msg' => $chat,

      ]);
      $out = '';

      $mycases =  DB::table('mycases')->where('id', $case_id)->where('status', 'finalize')->first();

      $chats1 = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)->orderBy('createdAt')->documents();

      $datearr = array();
      foreach ($chats1 as $row) {

        $datearr[] = $row->data()['date'];
      }



      $chats = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)->orderBy('createdAt')->documents();
      $i = 0;
      foreach ($chats as $row) {
        $from = $row->data()['from'];

        $users = app('firebase.firestore')->database()->collection('user')->where('uid', '=', $from)->documents();
        foreach ($users as $user) {

          $name = $user->data()['name'];
        }

        if (date('Y-m-d') == $datearr[$i]) {
          $date = 'Today';
        } else if (date('Y-m-d', strtotime("-1 days")) == $datearr[$i]) {
          $date = 'Yesterday';
        } else {
          $date = date('d-m-Y', strtotime($datearr[$i]));
        }
        if ($i == 0) {

          $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
        } else {
          if ($datearr[$i] != $datearr[$i - 1]) {
            $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
          }
        }


        if ($from != session('user_id')) {
          $out .= '<div class="row no-gutters">
                  <div class="col-md-3">
                    <div class="chat-bubble chat-bubble--left">
                    <div class="user_name">
                        ' . $name . '
                      </div>
                      <div class="chat_msg">
                        ' . $row->data()['msg'] . '
                      </div>
                    </div>
                  </div>
                </div>';
        } else {
          $out .= '<div class="row no-gutters">
                <div class="col-md-3 offset-md-9">
                  <div class="chat-bubble chat-bubble--right">
                    <div class="user_name">
                    ' . $name . '
                    </div>
                    <div class="chat_msg">
                      ' . $row->data()['msg'] . '
                    </div>
                  </div>
                </div>
              </div>';
        }
        $i++;
      }
      $size = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)
        ->documents()->size();
      $out .= '<input type="hidden" value="' . $date . '" class="date"><div class="doc_div"><input type="hidden" value="' . $size . '" class="doc_size"></div>';





      return view('pages.chat.chat_list', compact('mycases', 'out'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    }
  }
  public function get_next_msg(Request $request)
  {
    try {
      $doc_size = $request->doc_size;
      $case_id = $request->case_id;
      $today = $request->date;
      $out = '';

      $newsize = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)
        ->documents()->size();
      $limit = $newsize - $doc_size;
      if ($limit > 0) {
        $mycases =  DB::table('mycases')->where('id', $case_id)->where('status', 'finalize')->first();

        $chats1 = app('firebase.firestore')->database()->collection('messages/chat/case_1')->orderBy('createdAt', 'Desc')->limit($limit)->documents();

        $datearr = array();
        foreach ($chats1 as $row) {

          $datearr[] = $row->data()['date'];
        }


        $doc_id = '';
        $chats = app('firebase.firestore')->database()->collection('messages/chat/case_1')->orderBy('createdAt', 'Desc')->limit($limit)->documents();
        $i = 0;
        foreach ($chats as $row) {
          $from = $row->data()['from'];

          $users = app('firebase.firestore')->database()->collection('user')->where('uid', '=', $from)->documents();
          foreach ($users as $user) {

            $name = $user->data()['name'];
          }
          if ($today != 'Today') {
            if (date('Y-m-d') == $datearr[$i]) {
              $date = 'Today';
            } else if (date('Y-m-d', strtotime("-1 days")) == $datearr[$i]) {
              $date = 'Yesterday';
            } else {
              $date = date('d-m-Y', strtotime($datearr[$i]));
            }
            if ($i == 0) {

              $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
            } else {
              if ($datearr[$i] != $datearr[$i - 1]) {
                $out .= '<center><div class="badge badge-pill badge-light-secondary mr-1 mb-1">' . $date . '</div></center>';
              }
            }
          } else {
            $date = 'Today';
          }
          if ($from != session('user_id')) {
            $out .= '<div class="row no-gutters">
                    <div class="col-md-3">
                      <div class="chat-bubble chat-bubble--left">
                      <div class="user_name">
                          ' . $name . '
                        </div>
                        <div class="chat_msg">
                          ' . $row->data()['msg'] . '
                        </div>
                      </div>
                    </div>
                  </div>';
          } else {
            $out .= '<div class="row no-gutters">
                  <div class="col-md-3 offset-md-9">
                    <div class="chat-bubble chat-bubble--right">
                      <div class="user_name">
                      ' . $name . '
                      </div>
                      <div class="chat_msg">
                        ' . $row->data()['msg'] . '
                      </div>
                    </div>
                  </div>
                </div>';
          }
          $i++;
        }
      }
      $size = app('firebase.firestore')->database()->collection('messages/chat/case_' . $case_id)
        ->documents()->size();
      if ($limit > 0) {

        $out .= '<input type="hidden" value="' . $date . '" class="date"><div class="doc_div"><input type="hidden" value="' . $size . '" class="doc_size"></div>';
      }






      return $out;
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return json_encode(["status" => "error", "msg" => 'Something went wrong, please contact to support team']);
    }
  }

  public function case_invoice(Request $request)
  {

    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
      ]);

      if ($v->fails()) {
        return $v->errors();
      }
      $case_id = $request->case_id;
      $quotation_id = DB::table('mycases')->where('id', $case_id)->value('quotation_id');
      $case_invoice = DB::table('bill')->where('quotation', 'like', '[%' . $quotation_id . '%]')->get();
      $service_name = DB::table('quotation_details')->join('services', 'quotation_details.task_id', 'services.id')->where('quotation_details.id', $quotation_id)->value('services.name');
      foreach ($case_invoice as $row) {
        $short_code = DB::table('company')->where('id', $row->company)->value('short_code');
        $row->document_link = 'https://karyarat2.0.dearsociety.in/generate_invoice-' . $row->id . '-tax';
        $row->invoice_no = $short_code . '-' . str_pad($row->invoice_no, 4, '0', STR_PAD_LEFT) . '/' . date('Y', strtotime($row->bill_date));
        if ($row->description == "") {
          $row->description = $service_name;
        }
      }
      return view('pages.clients.get_case_invoice', compact('case_invoice'));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Error'));
    }
  }
  public function case_document(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'case_id' => 'required|numeric',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $case_id = $request->case_id;
      $case_documents = DB::table('case_document')->where('case_id', $case_id)->get();
      foreach ($case_documents as $cd) {
        $user_id = DB::table('users')->where('id', $cd->uploaded_by)->value('user_id');
        $cd->name = DB::table('staff')->where('sid', $user_id)->value('name');
      }
      return view('pages.clients.get_case_document', compact('case_documents'));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Error'));
    }
  }
  
  
  
  //////////////////////////////new codes/////////////////////////////////////////////////////////////
   public function get_case_clients(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'mobile_no' => 'required|numeric',
        'staff_id' => 'required|numeric',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $mobile_no = $request->mobile_no;
      $staff_id = $request->staff_id;
      $case_id=DB::table('assign_cases')->where('phone_no',$mobile_no)->pluck('case_id');
      $data = DB::table('mycases')->join('clients','mycases.client_id','clients.id')->select('mycases.*','clients.client_name','clients.case_no')->whereIn('mycases.id',$case_id)->where('mycases.status','finalize')->groupBy('mycases.client_id')->get();
     
      return response()->json(array('status' => 'success', 'data' => $data));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Error'));
    }
  }
    public function mycases(Request $request)
  {
    try {
      $v = Validator::make($request->all(), [
        'mobile_no' => 'required|numeric',
        'client_id' => 'required|numeric',
      ]);
      if ($v->fails()) {
        return $v->errors();
      }
      $mobile_no = $request->mobile_no;
      $client_id = $request->client_id;
      $case_id=DB::table('assign_cases')->where('phone_no',$mobile_no)->where('client_id',$client_id)->pluck('case_id');
      $data = DB::table('mycases')->join('clients','mycases.client_id','clients.id')->join('quotation_details','quotation_details.id','mycases.quotation_id')->select('mycases.*','clients.client_name','clients.case_no','quotation_details.task_id')->whereIn('mycases.id',$case_id)->where('mycases.status','finalize')->get();
     foreach($data as $row)
        {
            $row->service_name=DB::table('services')->where('id',$row->task_id)->value('name');
            $row->service_desc=DB::table('services')->where('id',$row->task_id)->value('description');
            $row->finalize_at=date('d-m-Y',strtotime($row->created_at));
            $row->update_at=date('d-m-Y',strtotime($row->comment_at));
        }
      return response()->json(array('status' => 'success', 'data' => $data));
    } catch (\Throwable $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('status' => 'error', 'msg' => 'Database Error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('status' => 'error', 'msg' => 'Error'));
    }
  }
}
