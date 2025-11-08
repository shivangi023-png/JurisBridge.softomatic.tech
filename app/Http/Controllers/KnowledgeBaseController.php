<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Traits\StaffTraits;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Exception;
use Aws\S3\S3Client;

class KnowledgeBaseController extends Controller
{
  use StaffTraits;
  public function upload_document_index()
  {
    try {
      if (session('username') == "") {
        return redirect('/')->with('status', "Please login First");
      }
      Log::Info('inside getstaff');

      $data = DB::table('upload_document')->whereDate('created_at', date('Y-m-d'))->where('uploaded_by', session('user_id'))->get();
      foreach ($data as $item) {
        $item->uploaded_by_name = DB::table('users')->join('staff', 'staff.sid', 'users.user_id')
          ->where('id', $item->uploaded_by)->value('staff.name');
      }
      return view('pages.upload_document', compact('data'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
    }
  }
  public function search_document_index()
  {
    try {
      if (session('username') == "") {
        return redirect('/')->with('status', "Please login First");
      }
      $data = DB::table('upload_document')->whereDate('created_at', date('Y-m-d'))->where('uploaded_by', session('user_id'))->get();
      foreach ($data as $item) {
        $item->uploaded_by_name = DB::table('users')->join('staff', 'staff.sid', 'users.user_id')
          ->where('id', $item->uploaded_by)->value('staff.name');
      }
      return view('pages.search_document', compact('data'));
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return redirect()->back()->with('alert-danger', 'something went wrong. try again later');
    }
  }
  public function upload_document(Request $request)
  {
    try {
      if (Session::get('username') != '') {
        $doc_title = $request->doc_title;
        $tag = $request->tag;
        $date = $request->date;
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d', strtotime($date));
        $type = $request->txt_type;
        $attach_file = $request->file;
        $description = $request->description;

        if ($request->has('file')) {
          Log::info('has file');
          // $upload_gr_table = $prefix . 'upload_gr';
          $target_dir = 'all_doc/upload_document/';
          $filename2 = strtolower($attach_file->getClientOriginalName());


          $extension = strtolower($attach_file->getClientOriginalExtension());
          $file_name = pathinfo($filename2, PATHINFO_FILENAME);
          $id = DB::table('upload_document')->max('id');

          $filename2 = $file_name . '-' . $id . '_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
          $foldername = 'upload_document';
          $path = $foldername . '/' . $filename2;
          Storage::disk('s3_quotations')->put($path, fopen($request->file('file'), 'r+'), 'public');
          $target_file = Storage::disk('s3_quotations')->url($path);
          if ($target_file) {

            $file_attach = $target_file;
          } else {

            return json_encode(array('status' => 'error', 'msg' => 'File Can`t be uploaded'));
          }
        } else {
          $file_attach = '';
        }

        $insert = DB::table('upload_document')->insert(['doc_title' => $doc_title, 'tag' => $tag, 'date' => $date, 'upload_gr_file_name' => $file_attach, 'type' => $type, 'description' => $description, 'uploaded_by' => session('user_id'), 'created_at' => now()]);

        if ($insert) {
          $tagArr = explode(",", $request->tag);
          for ($i = 0; $i < count($tagArr); $i++) {
            $checkExistTag = DB::table('tags')->where('tag', $tagArr[$i])->count();
            if ($checkExistTag == 0) {
              $insert_tag = DB::table('tags')->insert(['tag' => $tagArr[$i]]);
            }
          }
          $find = DB::table('upload_document')->whereDate('created_at', date('Y-m-d'))->where('uploaded_by', session('user_id'))->get();
          $out = '  <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doucument Title</th>
                                <th>Tag</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Uploaded by</th>
                                <th>File</th>
                            </tr>
              
                        </thead>';
          $i = 1;
          foreach ($find as $item) {

            $uploaded_by_name = DB::table('users')
              ->join('staff', 'staff.sid', 'users.user_id')
              ->where('id', $item->uploaded_by)->value('staff.name');
            if ($uploaded_by_name == '') {
              $uploaded_by_name = 'admin';
            }

            $out .= '<tr>
                                <td scope="row">' . $i++ . '</td>
                                <td>' . $item->doc_title . '</td>
                                <td>' . $item->tag . '</td>
                                <td>' . date("d-m-Y", strtotime($item->date)) . '</td>
                                <td>' . $item->type . '</td>
                                <td >' . $item->description . '</td>
                                <td>' . $uploaded_by_name . '</td >';
            if ($item->upload_gr_file_name != '') {
              $out .= '<td><a href="' . $item->upload_gr_file_name . '" target="_blank">View</a></td>';
            } else {
              $out .= '<td></td>';
            }
            $out .= '</tr>';
          }
          $out .= '</tbody></table>';
          return json_encode(array('status' => 'success', 'msg' => 'Document uploaded Successfully', 'out' => $out));
        } else {
          return json_encode(array('status' => 'error', 'msg' => 'Document can`t be uploaded'));
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

  public function autocomplete_tags(Request $request)
  {


    $tags = DB::table('tags')->get();
    $tags_array = array();
    foreach ($tags as $row) {
      array_push($tags_array, $row->tag);
    }
    return $tags_array = $tags_array;
  }
  public function autocomplete_title(Request $request)
  {


    $title = DB::table('upload_document')->get();
    $title_array = array();
    foreach ($title as $row) {
      array_push($title_array, $row->doc_title);
    }
    return $title_array = $title_array;
  }
  public function search_tags(Request $request)
  {

    $tags = $request->tags;
    $title = $request->title;
    $type = $request->type;
    $date = $request->date;

    $query = DB::table('upload_document');
    if ($tags != '') {

      $query = $query->where(function ($query) use ($tags) {
        $query->where('tag', 'Like', $tags . '%')
          ->orwhere('tag', 'Like', '%' . $tags)
          ->orwhere('tag', 'Like', '%' . $tags . '%');
      });
    }
    if ($title != '') {

      $query = $query->where(function ($query) use ($title) {
        $query->where('doc_title', 'Like', $title . '%')
          ->orwhere('doc_title', 'Like', '%' . $title)
          ->orwhere('doc_title', 'Like', '%' . $title . '%');
      });
      //$query->where('doc_title',trim($title));
    }
    if ($type != '') {
      $query->where('type', $type);
    }
    if ($date != '') {
      $date = str_replace('/', '-', $date);
      $date = date('Y-m-d', strtotime($date));
      $query->where('date', $date);
    }
    $find = $query->get();
    $out = '  <table class="table client-data-table wrap">
          <thead>
              <tr>
                  <th>Action</th>
                  <th>Document Title</th>
                  <th>Tag</th>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Uploaded by</th>
              </tr>

          </thead>';
    $i = 1;
    foreach ($find as $item) {

      $uploaded_by_name = DB::table('users')
        ->join('staff', 'staff.sid', 'users.user_id')
        ->where('id', $item->uploaded_by)->value('staff.name');
      if ($uploaded_by_name == '') {
        $uploaded_by_name = 'admin';
      }

      $out .= '<tr>
                  <td>
                   <div class="invoice-action">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <button type="button" data-id="' . $item->id . '" data-title="' . $item->doc_title . '" data-tag="' . $item->tag . '" data-type="' . $item->type . '" data-date="' . $item->date . '" data-description="' . $item->description . '" data-file="' . $item->upload_gr_file_name . '" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle btn-warning mr-2 mb-1 edit"><i class="bx bx-edit"></i></button>
                                                    </div>
                                                    <div class="col-3">
                                                        <button type="button" data-id="' . $item->id . '" class="btn btn-icon rounded-circle btn-danger mr-2 mb-1 delete"><i class="bx bx-trash-alt"></i></button>
                                                    </div>
                                                    <div class="col-3">
                                                        <a href="' . $item->upload_gr_file_name . '" target="_blank">
                                                            <button type="button" class="btn btn-icon rounded-circle btn-primary mr-1 mb-1"><i style="color:#fff;" class="bx bx-download"></i></button></a>
                                                    </div>
                                                </div>
                                            </div>
                                            </td>
                  <td>' . $item->doc_title . '</td>
                  <td style="color:#d9820f;">' . $item->tag . '</td>
                  <td>' . date("d-m-Y", strtotime($item->date)) . '</td>
                  <td>' . $item->type . '</td>
                  <td >' . $item->description . '</td>
                  <td>' . $uploaded_by_name . '</td >';
      $out .= '</tr>';
    }
    $out .= '</tbody></table>';
    return $out;
  }

  public function update_upload_document(Request $request)
  {

    try {
      $id = $request->id;
      $doc_title = $request->doc_title;
      $tag = $request->tag;
      $date = $request->date;
      $date = str_replace('/', '-', $date);
      $date = date('Y-m-d', strtotime($date));
      $type = $request->txt_type;
      $attach_file = $request->file;
      $description = $request->description;


      if ($attach_file != '') {


        $filename2 = strtolower($attach_file->getClientOriginalName());


        $extension = strtolower($attach_file->getClientOriginalExtension());
        $file_name = pathinfo($filename2, PATHINFO_FILENAME);


        $filename2 = $file_name . '-' . $id . '_' . strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
        $foldername = 'upload_document';
        $path = $foldername . '/' . $filename2;
        Storage::disk('s3_quotations')->put($path, fopen($request->file('file'), 'r+'), 'public');
        $target_file = Storage::disk('s3_quotations')->url($path);
        if ($target_file) {

          $file_attach = $target_file;
          $update = DB::table('upload_document')->where('id', $id)->update(['doc_title' => $doc_title, 'tag' => $tag, 'date' => $date, 'upload_gr_file_name' => $file_attach, 'type' => $type, 'description' => $description, 'uploaded_by' => session('user_id'), 'created_at' => now()]);
        } else {

          return json_encode(array('status' => 'error', 'msg' => 'File Can`t be uploaded'));
        }
      } else {
        $update = DB::table('upload_document')->where('id', $id)->update(['doc_title' => $doc_title, 'tag' => $tag, 'date' => $date, 'type' => $type, 'description' => $description, 'uploaded_by' => session('user_id'), 'created_at' => now()]);
      }



      if ($update) {
        $find = DB::table('upload_document')->where('date', date('Y-m-d'))->where('uploaded_by', session('user_id'))->get();
        $out = '  <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Doucument Title</th>
                                <th>Tag</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Uploaded by</th>
                                <th>File</th>
                            </tr>
              
                        </thead>';
        $i = 1;
        foreach ($find as $item) {

          $uploaded_by_name = DB::table('users')
            ->join('staff', 'staff.sid', 'users.user_id')
            ->where('id', $item->uploaded_by)->value('staff.name');
          if ($uploaded_by_name == '') {
            $uploaded_by_name = 'admin';
          }

          $out .= '<tr>
                               <td><div class="invoice-action">
                                <button type="button" data-id="' . $item->id . '" data-title="' . $item->doc_title . '" data-tag="' . $item->tag . '"  data-type="' . $item->type . '" data-date="' . $item->date . '" data-description="' . $item->description . '"  data-file="' . $item->upload_gr_file_name . '" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle btn-warning mr-1 mb-1 edit"><i class="bx bx-edit"></i></button>
                                <button type="button" data-id="' . $item->id . '" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete"><i class="bx bx-trash-alt"></i></button>
                                    </div></td>
                                <td>' . $item->doc_title . '</td>
                                <td>' . $item->tag . '</td>
                                <td>' . date("d-m-Y", strtotime($item->date)) . '</td>
                                <td>' . $item->type . '</td>
                                <td >' . $item->description . '</td>
                                <td>' . $uploaded_by_name . '</td >';
          if ($item->upload_gr_file_name != '') {
            $out .= '<td><a href="' . $item->upload_gr_file_name . '" target="_blank">View</a></td>';
          } else {
            $out .= '<td></td>';
          }
          $out .= '</tr>';
        }
        $out .= '</tbody></table>';
        return json_encode(array('status' => 'success', 'msg' => 'Document updated Successfully', 'out' => $out));
      } else {
        return json_encode(array('status' => 'error', 'msg' => 'Document can`t be updated'));
      }
    } catch (QueryException $e) {
      Log::error("Database error ! [" . $e->getMessage() . "]");
      return response()->json(array('error' => 'Database error'));
    } catch (Exception $e) {
      Log::error($e->getMessage());
      return response()->json(array('error' => 'Error'));
    }
  }
  public function delete_document(Request $request)
  {

    try {
      $id = $request->id;

      $delete = DB::table('upload_document')->where('id', $id)->delete();





      if ($delete) {
        $find = DB::table('upload_document')->where('date', date('Y-m-d'))->where('uploaded_by', session('user_id'))->get();
        $out = '  <table class="table client-data-table wrap">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Doucument Title</th>
                                <th>Tag</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Uploaded by</th>
                                <th>File</th>
                            </tr>
              
                        </thead>';
        $i = 1;
        foreach ($find as $item) {

          $uploaded_by_name = DB::table('users')
            ->join('staff', 'staff.sid', 'users.user_id')
            ->where('id', $item->uploaded_by)->value('staff.name');
          if ($uploaded_by_name == '') {
            $uploaded_by_name = 'admin';
          }

          $out .= '<tr>
                               <td><div class="invoice-action">
                                <button type="button" data-id="' . $item->id . '" data-title="' . $item->doc_title . '" data-tag="' . $item->tag . '"  data-type="' . $item->type . '" data-date="' . $item->date . '" data-description="' . $item->description . '"  data-file="' . $item->upload_gr_file_name . '" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle btn-warning mr-1 mb-1 edit"><i class="bx bx-edit"></i></button>
                                <button type="button" data-id="' . $item->id . '" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 delete"><i class="bx bx-trash-alt"></i></button>
                                    </div></td>
                                <td>' . $item->doc_title . '</td>
                                <td>' . $item->tag . '</td>
                                <td>' . date("d-m-Y", strtotime($item->date)) . '</td>
                                <td>' . $item->type . '</td>
                                <td >' . $item->description . '</td>
                                <td>' . $uploaded_by_name . '</td >';
          if ($item->upload_gr_file_name != '') {
            $out .= '<td><a href="' . $item->upload_gr_file_name . '" target="_blank">View</a></td>';
          } else {
            $out .= '<td></td>';
          }
          $out .= '</tr>';
        }
        $out .= '</tbody></table>';
        return json_encode(array('status' => 'success', 'msg' => 'Document updated Successfully', 'out' => $out));
      } else {
        return json_encode(array('status' => 'error', 'msg' => 'Document can`t be updated'));
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
