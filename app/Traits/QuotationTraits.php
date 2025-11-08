<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Traits\ClientTraits;


ini_set('max_execution_time', 2000);
date_default_timezone_set('Asia/Kolkata');

trait QuotationTraits
{
    use ClientTraits;
    public static function quotation_html_tbl()
    {
        $data= '<div class="action-dropdown-btn d-none">
        <div class="dropdown quotation-filter-action">
          <button class="btn border dropdown-toggle mr-1" type="button" id="quotation-filter-btn" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="selection1">Filter Quotation</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-filter-btn">
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="finalize">Finalize</a>
            <a class="dropdown-item active_btn" href="javascript:void(0);" data-value="unfinalize">Unfinalize</a>
          </div>
        </div>
        <div class="dropdown quotation-options">
          <button class="btn border dropdown-toggle mr-2" type="button" id="quotation-options-btn" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Options
          </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="quotation-options-btn">              
              <a class="dropdown-item" href="javascript:;">Delete</a>
              <a class="dropdown-item all_finalize"  href="javascript:;">finalize</a>
              <a class="dropdown-item all_unfinalize" href="javascript:;">Unfinalize</a>
              <a class="dropdown-item get_client_mail_info" href="javascript:;">Send Mail</a>
            </div>
        </div>
          
        <a href="quotation_add" class="btn btn-icon btn-outline-primary mr-1 add_button" type="button" aria-pressed="true">
          <i class="bx bx-plus"></i>Add Quotation</a>
    
      
      </div>
     
      <div class="table-responsive">
        <table class="table quotation-data-table dt-responsive wrap" style="width:100%">
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th><span class="align-middle">#</span></th>
              <th>Action</th>
              <th>Client Name</th>
              <th>Service</th>
              <th>Amount</th>
              <th>Send Date</th>
              <th>Finalized</th>
             </tr>
          </thead>
          <tbody>';
          return $data;
    }
    public static function quotation_data_html_tbl($quotation_list)
    {
        $i = 1;
        $j = 0;
       $out='';
        foreach ($quotation_list as $row) {
        $row->client_case_no = ClientTraits::get_client_case_no_by_id($row->client_id);
        $row->client_case_no = ClientTraits::get_client_case_no_by_id($row->client_id);
        $project_name_old=$row->case_no.' '.$row->task_name;
        $project_name=$row->case_no.'-'.$row->client_name.'-'.$row->task_name;
        $count_project=DB::table('projects')->where('project_name',$project_name_old)->orWhere('project_name',$project_name)->count();
        $out.= '<tr>
                      <td></td>
                      <td></td>
                      <td scope="row" style="font-size:19px; font-style:bold;"><input type="hidden" class="form-control quotation_details_id" value="' . $row->quotation_details_id . '"><a href="' . $row->file . '" target="_blank">Q' . str_pad($i++, 5, "0", STR_PAD_LEFT) . '</a></td>
                      <td><div class="quotation-action">';
        if ($row->finalize == 'YES') {
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-primary glow mr-1 mb-1 unfinalize" data-tooltip="Unfinalize"><i class="bx bx-list-check"></i></button>';
        } else {
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-success glow mr-1 mb-1 finalize" data-tooltip="Finalize"><i class="bx 
                                                                    bx-list-check"></i></button>';
        }
        $out .= '<button type="button" class="btn btn-icon rounded-circle btn-warning glow mr-1 mb-1 update_modal" data-toggle="modal" data-target="#updatequotation" data-send_date="' . date('d/m/Y', strtotime($row->send_date)) . '" data-service="' . $row->task_id . '" data-no_of_units="' . $row->no_of_units . '" data-per_unit_amount="' . $row->units_per_amount . '" data-amount="' . $row->amount . '" data-file="' . $row->file . '" data-id="' . $row->id . '" data-total_amt="' . $row->total_amt . '" data-quotation_detail_id="' . $row->quotation_details_id . '" data-client_name="' . $row->client_name . '" data-tooltip="Edit"><i class="bx bx-edit"></i></button>
                      <button type="button" class="btn btn-icon rounded-circle btn-danger glow mr-1 mb-1" data-quotation_details_id="' . $row->quotation_details_id . '" data-quotation_id="' . $row->id . '" data-qtotalamt="' . $row->total_amt . '"  data-qamount="' . $row->amount . '"  id="delete_quotation" data-tooltip="Delete"><i class="bx 
                      bx-trash"></i></button>';

        if (($row->finalize == 'yes' || $row->finalize == 'YES') && $count_project==0) {
          $out .= '<button type="button" class="btn btn-icon rounded-circle btn-secondary glow mr-1 mb-1 New_Project_modal" data-client_id="' . $row->client_id . '"  data-quotation_details_id="' . $row->quotation_details_id .'" data-case_no="' . $row->case_no . '" data-project_name="'.$project_name. '" data-tooltip="Create Project"><i class="bx bxs-credit-card"></i></button>';
        }     

         $out .= '</div></td>
                      <td><b>' . $row->client_case_no .'</b></td>
                      <td>' . $row->task_name . '</td>
                      <td class="text-right">' . number_format($row->amount, 2) . '</td>                             
                    
                      <td>' . date('d-m-Y', strtotime($row->send_date)) . '</td>
                      <td style="width:5px"> ';
        if ($row->finalize == 'no') {
          $out .= '<span class="badge badge-light-danger badge-pill">' . $row->finalize . '</span>';
        } else {
          $out .= '<span class="badge badge-light-success badge-pill">' . $row->finalize . '</span>';
        }
        $out .= '</td>';

        $out .= '</tr>';
    }
      return $out;
    }
}
