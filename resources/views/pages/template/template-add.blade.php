@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','New Template')
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/editors/quill/quill.bubble.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-staff.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/tooltip-style.css')}}">
<link rel="stylesheet" href="{{asset('css/plugins/forms/form-quill-editor.css')}}">
<style>
    .valid_err {

        font-size: 12px;
    }

    #full-container {
        height: 80px;
    }

    #full-container1 {
        height: 300px;
    }
</style>
@endsection
@section('content')
<!-- Basic multiple Column Form section start -->

<section id="multiple-column-form">
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
            <div class="card">

                <div class="card-body">
                    @include('layouts.tabs')
                    <form class="form" id="form">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-label-group">
                                        <input type="text" class="form-control" id="template_name" name="template_name" placeholder="Template Name">
                                        <label for="template_name">Template Name</label>
                                        <span class="template_name_err valid_err"></span>
                                    </div>
                                </div>
                                <!-- <div class="col-12 mb-5">
                                    <h5>Subject</h5>
                                    <div class="full-editor">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="full-wrapper">
                                                    <div id="full-container">
                                                        <div class="subject_editor">


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="subject_editor_err valid_err"></span>
                                </div> -->
                                <div class="col-12">
                                    <h5>Subject</h5>
                                    <div class="form-label-group">
                                        <textarea class="form-control subject_editor" name="subject" autocomplete="off"></textarea>
                                    </div>
                                    <span class="subject_editor_err valid_err"></span>
                                </div>
                                <div class="col-12 mb-3">
                                    <h5>Body</h5>
                                    <div class="full-editor">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="full-wrapper1">
                                                    <div id="full-container1">
                                                        <div class="body_editor">


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="body_editor_err valid_err ml-1"></span>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end mt-2">
                            <button type="button" id="submit" name="submit" class="btn btn-primary mr-3 px-5">Submit</button>
                            <button type="reset" class="btn btn-light-secondary px-5">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="staff-list-wrapper">
                <div class="card">
                    <div class="card-body">
                        <!-- datatable start -->
                        <div class="data_div">
                            <div class="table-responsive">
                                <table class="table staff-data-table wrap">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>id</th>
                                            <th>Template Name</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($template as $val)
                                        <tr>
                                            <td><a href="template_edit-{{$val->id}}" class="btn btn-icon rounded-circle glow btn-warning mr-1 mb-1" data-tooltip="Edit"><i class="bx bx-edit"></i></a>
                                            </td>
                                            <td>{{$i++}}</td>
                                            <td>{{$val->template_name}}</td>
                                            <td>{{$val->subject}}</td>
                                            <td>{{$val->message}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- datatable ends -->
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
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/js/editors/quill/quill.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/pages/staff.js')}}"></script>
<!-- <script src="{{asset('js/scripts/editors/editor-quill.js')}}"></script> -->

<script>
    $(document).ready(function() {

    var body_editor = new Quill("#full-container1 .body_editor", {
        bounds: "#full-container1 .body_editor",
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


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function isQuillEmpty(quill) {
        if (JSON.stringify(quill.getContents()) == "\{\"ops\":[\{\"insert\":\"\\n\"\}]\}") {
            return true;
        } else {
            return false;
        }
    }

    $(document).on('click', '#submit', function() {
        $('.valid_err').html('');
        var arr = [];
        var template_name = $('#template_name').val();
        var subject = $('.subject_editor').val();
        var body = body_editor.root.innerHTML.trim();

        if (template_name == '') {
            arr.push('template_name_err');
            arr.push('Template Name required');
        }

        if (subject == '') {
            arr.push('subject_editor_err');
            arr.push('Subject required');
        }


        if (isQuillEmpty(body_editor)) {
            arr.push('body_editor_err');
            arr.push('Message required');
        }

        if (arr != '') {
            for (var i = 0; i < arr.length; i++) {
                var j = i + 1;
                $('.' + arr[i]).html(arr[j]).css('color', 'red');
                i = j;
            }
        } else {
            $.ajax({
                type: 'post',
                url: 'template_add',
                data: {
                    template_name: template_name,
                    subject: subject,
                    body: body
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
                        $("#template_name").val('');
                        $(".subject_editor").val('');
                        body_editor.setText('');
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-success alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-like"></i><span>' +
                            res.msg + '</span></div></div>');

                        $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert").slideUp(500);
                        });
                        get_template_update();
                    } else {
                        $("#alert").animate({
                                scrollTop: $(window).scrollTop(0)
                            },
                            "slow"
                        );
                        $('#alert').html(
                            '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                            res.msg + '</span></div></div>');

                    }
                    $(".alert").fadeTo(2000, 500).slideUp(500, function() {
                        $(".alert").slideUp(500);
                    });
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
    }); <<
    << << < Updated upstream
    }); ===
    === =
    }); <<
    <<
    <<
    <
    HEAD: resources / views / pages / template - add.blade.php ===
        ===
        = >>>
        >>> > Stashed changes

    function get_template_update() {
        $.ajax({
            type: "get",
            url: "get_template_update",
            datatype: "text",

            <<
            << << < Updated upstream
            success: function(data) {
                console.log(data);
                $(".data_div").html(data);
            },
            error: function(data) {
                $("#alert")
                    .html(
                        '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                        res.msg +
                        "</span></div></div>"
                    )
                    .focus();
            },
        });
    } ===
    === =
    success: function(data) {
            console.log(data);
            $(".data_div").html(data);
        },
        error: function(data) {
            $("#alert")
                .html(
                    '<div class="alert bg-rgba-danger alert-dismissible mb-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="d-flex align-items-center"><i class="bx bx-x"></i><span>' +
                    res.msg +
                    "</span></div></div>"
                )
                .focus();
        },
    });
    } >>>
    >>>
    >
    upstream / main: resources / views / pages / template / template - add.blade.php >>>
        >>> > Stashed changes
</script>

@endsection