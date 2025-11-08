@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Upload Document')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.bubble.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/datepicker/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/common.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/taginput/tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('css/plugins/forms/form-quill-editor.css')}}">

<style>
    .valid_err {
        font-size: 12px;
    }

    .UDF-Btn {
        position: absolute;
        right: 0;
        top: 12px;
        padding-right: 10px;
    }

    #full-body-container {
        height: 100px;
    }

    .card_ht {
        height: 380px;
    }
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->

<section class="upload-list-wrapper">
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
            <center>
                <div class="spinner-grow text-primary loader" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="loader" style="display:none">Please wait...</h5>
            </center>
            <div class="card card_ht">
                <div class="card-body" style="padding: 0.7rem;">
                    @include('layouts.tabs')
                    <form class="form" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="UDF-Btn d-flex justify-content-end">
                                <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Upload</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Cancel</button>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" class="client_id">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control doc_title" id="doc_title" name="doc_title" placeholder="Document Title">
                                        <label for="doc_title">Document Title</label>
                                        <span class="doc_title_err valid_err"></span>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control datepicker" id="date" name="date" placeholder="Date" autocomplete="off">
                                        <label for="date">Date</label>
                                        <span class="date_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-3">
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
                                <div class="col-5">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control tag" autocomplete="off" data-role="tagsinput" id="tag" name="tag" placeholder="Tag">
                                        <label for="tag">Tag</label>
                                        <span class="tag_err valid_err"></span>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file_name" id="file_name">
                                        <label class="custom-file-label">Upload File</label>
                                    </div>
                                    <span class="file_name_err valid_err"></span>

                                </div>
                                <div class="col-12">
                                    <!-- <div class="form-label-group">
                                        <textarea class="form-control description" id="description" name="description" autocomplete="off" placeholder="Description"></textarea>
                                        <label for="description">Description</label>
                                        <span class="description_err valid_err"></span>
                                    </div> -->
                                    <label for="description">Description</label>
                                    <fieldset class="full-editor description">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="full-body-wrapper">
                                                    <div id="full-body-container">
                                                        <div class="body_editor">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <span class="description_err valid_err"></span>
                                </div>
                            </div>
                            <!-- <div class="col-12 d-flex justify-content-end">
                                <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Upload</button>
                                <button type="reset" class="btn btn-light-secondary px-5">Cancel</button>
                            </div> -->
                        </div>
                    </form>
                </div>
            </div>

            <div class="data_div">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table upload-data-table wrap">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Document Title</th>
                                        <th>Tag</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Uploaded by</th>
                                        <th>File</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach($data as $row)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$row->doc_title}}</td>
                                        <td>{{$row->tag}}</td>
                                        <td>{{date("d-m-Y",strtotime($row->date))}}</td>
                                        <td>{{$row->type}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>{{$row->uploaded_by_name}}</td>
                                        @if($row->upload_gr_file_name!="")
                                        <td><a href="{{$row->upload_gr_file_name}}" target="_blank">View</a></td>
                                        @else
                                        <td></td>
                                        @endif
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
</section>

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
<script src="{{asset('vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>
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

        var body_editor = new Quill("#full-body-container .body_editor", {
            bounds: "#full-body-container .body_editor",
            modules: {
                formula: true,
                syntax: true,
                toolbar: [
                    [{
                            font: [],
                        },
                        {
                            size: [],
                        },
                    ],
                    ["bold", "italic", "underline", "strike"],
                    [{
                            color: [],
                        },
                        {
                            background: [],
                        },
                    ],
                    [{
                            script: "super",
                        },
                        {
                            script: "sub",
                        },
                    ],
                    [{
                            header: "1",
                        },
                        {
                            header: "2",
                        },
                        "blockquote",
                        "code-block",
                    ],
                    [{
                            list: "ordered",
                        },
                        {
                            list: "bullet",
                        },
                        {
                            indent: "-1",
                        },
                        {
                            indent: "+1",
                        },
                    ],
                    [
                        "direction",
                        {
                            align: [],
                        },
                    ],
                    ["link", "image", "video", "formula"],
                    ["clean"],
                ],
            },
            theme: "snow",
        });

        $(document).on('click', '#submit', function() {
            $('.valid_err').html('');
            var arr = [];
            var doc_title = $('.doc_title').val();
            var tag = $('.tag').val();
            var date = $('#date').val();
            var txt_type = $('.txt_type').val();
            // var description = $('.description').val();
            var description = $('#full-body-container .body_editor .ql-editor').text();
            var file_data1 = $('#file_name').prop('files')[0];

            var form_data = new FormData();
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

            $('#file_name').each(function() {
                var fp = $(this);
                var lg = fp[0].files.length; // get length 

                var items = fp[0].files;

                if (lg == 0) {
                    console.log('ok');
                    $(this).closest('fieldset').find('span.file_name_err').html('Upload File').css('color', 'red');
                    valid = false;
                } else {

                    var ext = items[0].name.substr(-3);
                    var file_msg = validFile(ext);
                    console.log(ext);
                    if (file_msg) {

                        $(this).closest('fieldset').find('span.file_name_err').html(file_msg).css('color', 'red');
                        valid = false;
                    }
                    if (items[0].size > 10000000) {
                        $(this).closest('fieldset').find('span.file_name_err').html(
                            'File size must be less than or equal to 10 MB').css('color', 'red');
                        valid = false;
                    }
                }

            });

            if (arr != '') {
                for (var i = 0; i < arr.length; i++) {
                    var j = i + 1;
                    $('.' + arr[i]).html(arr[j]).css('color', 'red');
                    i = j;
                }
            } else {
                $(".loader").css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: "upload_document",
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(data) {
                        console.log(data);
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        var res = JSON.parse(data);
                        if (res.status == 'success') {
                            $(".loader").css("display", "none");
                            $('#alert').html(
                                '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                                res.msg + '</span></div></div>');
                            $('#file_name').val('');
                            $('.custom-file-label').text('Upload File');
                            $(".tag").tagsinput('removeAll');
                            $('.txt_type').val("");
                            $('.txt_type').trigger('change');
                            body_editor.setText('');
                            $("#form").trigger('reset');
                            $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                                $(".alert").slideUp(500);
                            });
                            $('.table-responsive').empty().html(res.out);
                            if ($(".upload-data-table").length) {
                                var dataListView = $(".upload-data-table").DataTable({
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

                                    dom: '<"top d-flex flex-wrap"<"action-filters flex-grow-1"><"actions action-btns d-flex align-items-center">><"clear">rt<"bottom"ip>',

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
                            $.ajax({
                                type: "get",
                                url: "autocomplete_tags",

                                success: function(data) {
                                    console.log(data);
                                    $(".bootstrap-tagsinput input").autocomplete({
                                        source: data,
                                    });
                                },
                            });
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
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>Something went wrong!</span></div></div>'
                        );
                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                    }
                });
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