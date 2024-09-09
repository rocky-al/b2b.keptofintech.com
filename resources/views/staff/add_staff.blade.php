@extends('layouts.main')
@section('title', 'Add Users')
@section('url_name', '/users')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/form-wizard/from_wizard.css') }}">

<style>
    .display_none {
        display: none;
    }
</style>

<div class="card">
    <div class="card-header justify-content-between d-flex">
        @if(isset($staff_detail->id ))
        <h3>{{ __('Update User')}}</h3>
        @else
        <h3>{{ __('Add User')}}</h3>
        @endif
        <div class="pull-right">
            <a class="btn btn-outline-primary btn-rounded-20" href="{{ url('/home') }}">
                <i class="ik ik-list"></i> List of Users
            </a>
        </div>
    </div>
    <div class="card-body">

        <form class="forms-sample" id="addEditForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="id" placeholder="" name='id' value="{{ isset($staff_detail) ? $staff_detail->id :'0'}}">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pkgs" class="required">{{ __('First Name')}}</label>
                        <input type="text" class="form-control" id="first_name" placeholder="First Name" name='first_name' value="{{ isset($staff_detail) ? $staff_detail->first_name :''}}">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="vol_wt" class="required">{{ __('Email')}}</label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name='email' value="{{ isset($staff_detail) ? $staff_detail->email :''}}">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Phone Number')}}</label>
                        <input type="tel" class="form-control" id="phone" pattern="[0-9]{10}" placeholder="Contact Number" name="phone" maxlength="16" value="{{ isset($staff_detail) ? $staff_detail->phone :''}}" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))">
                    </div>
                </div>
                
                @if(!isset($staff_detail->id ))
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Password')}}</label>
                        <input type="text" class="form-control" id="password"  placeholder="Password" name="password" maxlength="16" value="" >
                    </div>
                </div>
                @endif

                <div class="col-sm-12 ">
                    <div class="form-group">
                        <input class="btn btn-primary pull-right submit_button" type="submit" name="Save" value="Save">
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@if(isset($staff_detail->id ))
<div class="card">
    <div class="card-header justify-content-between d-flex">
        
        <h3>{{ __('Chnage Password')}}</h3>
       
        
    </div>
    <div class="card-body">

        <form class="forms-sample" id="addEditFormPassword" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="pid" placeholder="" name='id' value="{{ isset($staff_detail) ? $staff_detail->id :'0'}}">
            <div class="row">
                
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Password')}}</label>
                        <input type="text" class="form-control" id="password"  placeholder="Password" name="password" maxlength="16" value="" >
                    </div>
                </div>
                <div class="col-sm-12 ">
                    <div class="form-group">
                        <input class="btn btn-primary pull-right submit_button" type="submit" name="Save" value="Save">
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endif

<!-- push external js -->
@push('script')
<script src="{{ asset('plugins/form-wizard/from_wizard.js') }}"></script>

<script>
    var document_array = [];
    $(document).ready(function() {

        $('#dob').datepicker({
            uiLibrary: 'bootstrap4',
            maxDate: new Date(),
            format: 'dd/mm/yyyy'
        }).on('change', function(selected) {
            var min_Date = $('#dob').val();
            $('#hire_date').datepicker('destroy');

            $('#hire_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: min_Date,
                format: 'dd/mm/yyyy',
            });
        });
    });



    //add update item
    $("#addEditForm").validate({
        rules: {
            first_name: {
                required: true,
            },
           
            email: {
                required: true
            },
            
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength : 16

            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        },
        messages: {},
        submitHandler: function(form) {
            //form.submit();
            var formData = new FormData($("#addEditForm")[0]);
            var url_up = "{{url('/users/store')}}";
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#addEditForm").find('.submit_button').attr("disabled", true);
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    var response = JSON.parse(data);
                    //console.log(response);
                    if (response.code == 200) {
                        //show notification
                        //location.reload();
                        $.notify(response.msg, "success");
                        window.location.href = "{{route('users')}}";
                    } else {
                        $.notify(response.msg, "error");
                        $("#addEditForm").find('.submit_button').removeAttr("disabled");
                    }
                },
            });
            return false;
        }
    });
    $("#addEditForm").validate({
        rules: {
            first_name: {
                required: true,
            },
           
            email: {
                required: true
            },
            
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength : 16

            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        },
        messages: {},
        submitHandler: function(form) {
            //form.submit();
            var formData = new FormData($("#addEditForm")[0]);
            var url_up = "{{url('/users/store')}}";
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#addEditForm").find('.submit_button').attr("disabled", true);
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    var response = JSON.parse(data);
                    //console.log(response);
                    if (response.code == 200) {
                        //show notification
                        //location.reload();
                        $.notify(response.msg, "success");
                        window.location.href = "{{route('users')}}";
                    } else {
                        $.notify(response.msg, "error");
                        $("#addEditForm").find('.submit_button').removeAttr("disabled");
                    }
                },
            });
            return false;
        }
    });
    $("#addEditFormPassword").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength : 16

            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        },
        messages: {},
        submitHandler: function(form) {
            //form.submit();
            var formData = new FormData($("#addEditFormPassword")[0]);
            var url_up = "{{url('/users/passwordUpdate')}}";
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#addEditFormPassword").find('.submit_button').attr("disabled", true);
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    var response = JSON.parse(data);
                    //console.log(response);
                    if (response.code == 200) {
                        //show notification
                        //location.reload();
                        $.notify(response.msg, "success");
                        window.location.href = "{{route('users')}}";
                    } else {
                        $.notify(response.msg, "error");
                        $("#addEditFormPassword").find('.submit_button').removeAttr("disabled");
                    }
                },
            });
            return false;
        }
    });
    $(document).on("click", ".file-upload-browse", function() {
        var file = $(this).parent().parent().parent().find('.file-upload-default');
        file.trigger('click');
    });
    $(document).on("change", ".file-upload-browse", function() {
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });



    // display image or pdf file after choosen
    function imagesPreview(input, image_name) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                if (!input.files[i].name.match(/\.(jpg|jpeg|png)$/i)) {
                    var fileExtension = ['jpeg', 'jpg', 'png'];
                    document.getElementById(image_name + '_err').innerHTML = 'Only formats are allowed : ' + fileExtension.join(', ');
                    document.getElementById(image_name + '_display').style.display = 'none';
                    $('#' + image_name).val('');
                    if (image_name == "nric_copy") {
                        document.getElementById(image_name + '_flag').style.display = 'block';
                    }
                    continue;
                } else {
                    document.getElementById(image_name + '_err').innerHTML = '';
                    document.getElementById(image_name + '_display').style.display = 'block';
                    if (image_name == "nric_copy") {
                        document.getElementById(image_name + '_flag').style.display = 'none';
                    }

                    document.getElementById(image_name + '_show').src = window.URL.createObjectURL(input.files[i]);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

    }


    $('#profile_image').on('change', function() {
        imagesPreview(this, 'profile_image');
    });

    $('#photo_id').on('change', function() {

        imagesPreview(this, 'photo_id');

    });
</script>


@endpush
@endsection