@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Search Document')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/taginput/tagsinput.css')}}">
<style>
    .valid_err {

        font-size: 12px;
    }
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->

<section class="client-list-wrapper">
    <div class="row match-height">
        <div class="col-12">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <div class="alert bg-rgba-{{ $msg }} alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="d-flex align-items-center">
                    @if(Session::has('alert-success'))
                    <i class="bx bx-like"></i>
                    @else
                    <i class="bx bx-error"></i>
                    @endif
                    <span>
                        {{ Session::get('alert-' . $msg) }}
                    </span>
                </div>
            </div>
            @endif
            @endforeach
            <div id="alert">


            </div>

            <div class="data_div">


                <div class="card">
                    <div class="card-body" style="padding: 0.7rem;">
                        @include('layouts.tabs')
                        <!-- datatable start -->
                        <div class="row" style="padding-left:22px">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-3">

                                        <div class="form-label-group">
                                            <input type="text" autocomplete="off" class="form-control title" placeholder="Title">
                                            <label for="doc_title">Title</label>
                                            <span class="doc_title_err valid_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">

                                        <div class="form-label-group">
                                            <input type="text" autocomplete="off" class="form-control search_tag" placeholder="Tags">
                                            <label for="doc_title">Tags</label>
                                            <span class="doc_title_err valid_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <fieldset class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="inputGroupSelect01">Category</label>
                                                </div>
                                                <select name="search_cat" id="search_cat" class="form-control search_cat">
                                                    <option value="" selected>Choose...</option>

                                                    <option value="GR">GR</option>
                                                    <option value="RERA">RERA</option>
                                                    <option value="DDR">DDR</option>
                                                    <option value="DJR">DJR</option>
                                                    <option value="High Court">High Court</option>
                                                    <option value="Supreme Court">Supreme Court</option>
                                                    <option value="Consumer Court">Consumer Court</option>
                                                    <option value="Quotation">Quotation</option>
                                                    <option value="Builder Notice/Reply">Builder Notice/Reply</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <span class="txt_type_err valid_err"></span>
                                        </fieldset>
                                    </div>
                                    <div class="col-3">

                                        <div class="form-label-group">
                                            <input type="text" autocomplete="off" class="form-control datepicker search_date" id="search_date" placeholder="Date">
                                            <label for="doc_title">Date</label>
                                            <span class="doc_title_err valid_err"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-icon btn-primary mr-1 search_btn mb-1" id="search_btn"><i class="bx bx-search"></i></button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table client-data-table wrap">
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

                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach($data as $row)
                                    <tr>
                                        <td>
                                            <div class="invoice-action">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <button type="button" data-id="{{$row->id}}" data-title="{{$row->doc_title}}" data-tag="{{$row->tag}}" data-type="{{$row->type}}" data-date="{{$row->date}}" data-description="{{$row->description}}" data-file="{{$row->upload_gr_file_name}}" data-toggle="modal" data-target="#updateModal" class="btn btn-icon rounded-circle btn-warning mr-2 mb-1 edit"><i class="bx bx-edit"></i></button>
                                                    </div>
                                                    <div class="col-3">
                                                        <button type="button" data-id="{{$row->id}}" class="btn btn-icon rounded-circle btn-danger mr-2 mb-1 delete"><i class="bx bx-trash-alt"></i></button>
                                                    </div>
                                                    <div class="col-3">
                                                        <a href="{{$row->upload_gr_file_name}}" target="_blank">
                                                            <button type="button" class="btn btn-icon rounded-circle btn-primary mr-1 mb-1"><i style="color:#fff;" class="bx bx-download"></i></button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$row->doc_title}}</td>
                                        <td style="color:#d9820f;">{{$row->tag}}</td>
                                        <td>{{date("d-m-Y",strtotime($row->date))}}</td>
                                        <td>{{$row->type}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>{{$row->uploaded_by_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- datatable ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Update document</h4>
            </div>
            <form class="form" id="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">

                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" class="form-control id" id="id" name="id">
                            <div class="col-12">
                                <input type="hidden" class="client_id">
                                <div class="form-label-group">
                                    <input type="text" class="form-control doc_title" id="doc_title" name="doc_title" placeholder="Document Title">
                                    <label for="doc_title">Document Title</label>
                                    <span class="doc_title_err valid_err"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-label-group">
                                    <input type="text" class="form-control datepicker" id="date" name="date" placeholder="Date" autocomplete="off">
                                    <label for="date">Date</label>
                                    <span class="date_err valid_err"></span>
                                </div>
                            </div>


                            <div class="col-12">
                                <fieldset class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Category</label>
                                        </div>
                                        <select name="txt_type" id="txt_type" class="form-control txt_type">
                                            <option value="" selected>Choose...</option>

                                            <option value="GR">GR</option>
                                            <option value="RERA">RERA</option>
                                            <option value="DDR">DDR</option>
                                            <option value="DJR">DJR</option>
                                            <option value="High Court">High Court</option>
                                            <option value="Supreme Court">Supreme Court</option>
                                            <option value="Consumer Court">Consumer Court</option>
                                            <option value="Quotation">Quotation</option>
                                            <option value="Builder Notice/Reply">Builder Notice/Reply</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <span class="txt_type_err valid_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <div class="form-label-group">
                                    <input type="text" class="form-control tag" autocomplete="off" data-role="tagsinput" id="tag" name="tag" placeholder="Tag">
                                    <label for="tag">Tag</label>
                                    <span class="tag_err valid_err"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input file_name" name="file_name" id="file_name">

                                        <span class="custom-file-label">Upload File</span>
                                    </div>
                                    <span class="file_name_err valid_err"></span>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset class="form-group">
                                    <div style="padding-bottom:8px" class="view_file"></div>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <div class="form-label-group">
                                    <textarea class="form-control description" id="description" name="description" autocomplete="off" placeholder="Description"></textarea>
                                    <label for="description">Description</label>
                                    <span class="description_err valid_err"></span>
                                </div>
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary ml-1" id="update">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Update</span>
                            </button>
                            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>

                        </div>
            </form>
        </div>
    </div>
</div>

<!-- Basic multiple Column Form section end -->
@endsection
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/jszip.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{asset('js/scripts/pickers/datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/scripts/pages/document.js')}}"></script>

<script src="{{asset('js/scripts/taginput/tagsinput.js')}}"></script>

<script>
    $(document).ready(function() {
        $(".datepicker").datepicker().on("changeDate", function(ev) {
            $(".datepicker.dropdown-menu").hide();
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.edit', function() {

            $('#id').val($(this).data('id'));
            $('.doc_title').val($(this).data('title'));
            $('.tag').tagsinput('add', $(this).data('tag'));
            // $('#txt_tag').tagsinput($(this).data('tag'));
            $('#date').val($(this).data('date'));
            $('.txt_type').val($(this).data('type'));
            $('.description').val($(this).data('description'));
            $('.view_file').html('<a href="' + $(this).data('file') + '" target="_blank">View Uploaded FIle</a>');
            $('#updateModal').modal('show');
        });


        $(document).on('click', '#update', function() {

            $('.valid_err').html('');
            var arr = [];
            var id = $('.id').val();
            var doc_title = $('.doc_title').val();
            var tag = $('.tag').val();
            var date = $('#date').val();
            var txt_type = $('.txt_type').val();
            var description = $('.description').val();
            if ($('#file_name').val() != '') {
                var file_data1 = $('#file_name').prop('files')[0];
                $('#file_name').each(function() {
                    var fp = $(this);
                    var lg = fp[0].files.length; // get length 

                    var items = fp[0].files;



                    if (lg != 0) {



                        var ext = items[0].name.substr(-3);
                        var file_msg = validFile(ext);
                        console.log(ext);
                        if (file_msg) {
                            arr.push('file_name_err');
                            arr.push(file_msg);
                            //$(this).closest('fieldset').find('span.file_name_err').html(file_msg).css('color', 'red');
                            valid = false;
                        }
                        if (items[0].size > 2000000) {

                            arr.push('file_name_err');
                            arr.push('File size must be less than or equal to 1 MB');
                            valid = false;
                        }


                    }

                });
            } else {

                var file_data1 = '';
            }


            var form_data = new FormData();
            form_data.append('id', id);
            form_data.append('doc_title', doc_title);
            form_data.append('tag', tag);
            form_data.append('date', date);
            form_data.append('txt_type', txt_type);
            form_data.append('description', description);
            form_data.append('file', file_data1);




            if (doc_title == '') {
                arr.push('doc_title_err');
                arr.push('Title required');
            }



            if (tag == '') {
                arr.push('tag_err');
                arr.push('Tags required');
            }

            if (date == '') {
                arr.push('date_err');
                arr.push('Date required');
            }
            if (txt_type == '') {
                arr.push('txt_type_err');
                arr.push('Type required');
            }









            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $.ajax({
                    type: 'POST',
                    url: "update_document",
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(data) {
                        $('#updateModal').modal('toggle')
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $('#file_name').val("");
                            $('.txt_type').val("");
                            $('.txt_type').trigger('change');


                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            $('.table-responsive').empty().html(res.out);
                            if ($(".client-data-table").length) {
                                var dataListView = $(".client-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control",
                                        },
                                        {
                                            orderable: true,
                                            targets: 0,
                                            // checkboxes: { selectRow: true }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false,
                                        },
                                    ],

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Appointment",
                                    },

                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row",
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0,
                                        },
                                    },
                                });
                            }
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');

                        }

                    },
                    error: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            }
        });
    });
    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this document?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn btn-warning",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: "delete_document",
                    data: {
                        id: id
                    },
                    success: function(data) {

                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');

                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            $('.table-responsive').empty().html(res.out);
                            if ($(".client-data-table").length) {
                                var dataListView = $(".client-data-table").DataTable({
                                    columnDefs: [{
                                            targets: 0,
                                            className: "control",
                                        },
                                        {
                                            orderable: true,
                                            targets: 0,
                                            // checkboxes: { selectRow: true }
                                        },
                                        {
                                            targets: [0, 1],
                                            orderable: false,
                                        },
                                    ],

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"f><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

                                    language: {
                                        search: "",
                                        searchPlaceholder: "Search Appointment",
                                    },

                                    select: {
                                        style: "multi",
                                        selector: "td:first-child",
                                        items: "row",
                                    },
                                    responsive: {
                                        details: {
                                            type: "column",
                                            target: 0,
                                        },
                                    },
                                });
                            }
                        } else {
                            $('#alert').html(
                                '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                                res.msg + '</span></div></div>');

                        }

                    },
                    error: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something wend wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
            } //end result value
            else {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your document file is safe :)',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-success',
                })
            }
        });
    });

    function validFile(ext) {

        var extension = ext;
        var msg = "";
        switch (extension) {
            case 'PDF':
            case 'jpg':
            case 'pdf':
            case 'peg':
            case 'png':
            case 'doc':
            case 'ocx':

                msg = ""; // There's was a typo in the example where
                return msg;
                break;
            default:
                msg = "File type must be pdf,doc or docx,jpg,png";
                return msg;
        }
    }
</script>

@endsection