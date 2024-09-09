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
            <a class="btn btn-outline-primary btn-rounded-20" href="{{ url('/users') }}">
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
                        <label for="pkgs" class="required">{{ __('Name')}}</label>
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
                   <div class="form-group" >
                        <label for="dimensions" class="required">{{ __('Country Code')}}</label>
                        <select class="form-control" name="country_code" >
                            @if(isset($country_code) && count($country_code) > 0)
                            @foreach($country_code as $country)
                                <option value="{{$country->code}}" {{ (isset($staff_detail) && $staff_detail->country_code == $country->code ? 'selected' :'') }}>{{"(".$country->code.") ".$country->country_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Phone Number')}}</label>
                        <input type="tel" class="form-control" id="phone" pattern="[0-9]{10}" placeholder="Contact Number" name="phone" maxlength="16" value="{{ isset($staff_detail) ? $staff_detail->phone :''}}" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))">
                    </div>
                </div>



                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="vol_wt" class="required">{{ __('User Type')}}</label>
                        <select class="form-control user_type" aria-label="Default select example" name="user_type">
                            <option value=""> Select </option>


                            <option value="1" @if(isset($staff_detail->registered_as) && $staff_detail->registered_as == '1') selected @endif >Individual</option>
                            <option value="0" @if(isset($staff_detail->registered_as) && $staff_detail->registered_as == '0') selected @endif> Business </option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-4 display_none" id="display_none_business">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Business Name ')}}</label>
                        <input type="text" class="form-control" id="business_name" placeholder="Business Name " name="business_name" value="{{ isset($staff_detail) ? $staff_detail->business_name :''}}" required>
                    </div>
                </div>

                <div class="col-sm-4 display_none" id="display_none">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('EIN No ')}}</label>
                        <input type="tel" class="form-control" id="ein_number" placeholder="Ein Number" name="ein_number" value="{{ isset($staff_detail) ? $staff_detail->ein_number :''}}" required>
                    </div>
                </div>


                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dimensions" class="required">{{ __('Bio')}}</label>
                        <textarea class="form-control" row="2" maxlength="200" id="bio" placeholder="Bio" name="bio">{{ isset($staff_detail) ? $staff_detail->bio :''}}</textarea>
                    </div>

                    <small style="position:relative;top:-9px;"> &nbsp; maximum 200 characters </small>

                </div>


                @if(!isset($staff_detail->id))

                <div class="col-sm-3">
                    <div class="form-group" style="margin-bottom:0px">
                        <label class="required">{{ __('Profile Image')}}</label>

                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                            </span>
                        </div>
                        <small style="position:relative;top:-9px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</small>
                        <input type="file" name="profile_image" class="file-upload-default" accept=".jpg,.jpeg,.png" id="profile_image" required>
                        <label id="profile_image_err" class="error"></label>
                    </div>

                    <div id="profile_image_display" style="display: {{ (isset($staff_detail->profile_image) && !empty($staff_detail->profile_image) ? 'block': 'none')}};">
                        <div class="form-group">
                            <div class="copy_class">
                                    <img id="profile_image_show" src="{{ isset($staff_detail->profile_image) && !empty($staff_detail->profile_image) ? url('storage/'.$staff_detail->profile_image) : '' }}" class="img-responsive imageZoomKp">
                               
                            </div>
                        </div>
                    </div>
                </div>
                @else

                <div class="col-sm-3">
                    <div class="form-group" style="margin-bottom:0px">
                        <label>{{ __('Profile Image')}}</label>

                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                            </span>
                        </div>
                        <small style="position:relative;top:-9px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</small>
                        <input type="file" name="profile_image" class="file-upload-default" accept=".jpg,.jpeg,.png" id="profile_image">
                        <label id="profile_image_err" class="error"></label>
                    </div>

                    <div id="profile_image_display" style="display: {{ (isset($staff_detail->profile_image) && !empty($staff_detail->profile_image) ? 'block': 'none')}};">
                        <div class="form-group">
                            <div class="copy_class">
                               
                                    <img id="profile_image_show" src="{{ isset($staff_detail->profile_image) && !empty($staff_detail->profile_image) ? url('storage/'.$staff_detail->profile_image) : '' }}" class="img-responsive imageZoomKp">
                               
                            </div>
                        </div>
                    </div>
                </div>

                @endif





                @if(!isset($staff_detail->id))

                <div class="col-sm-3">
                    <div class="form-group" style="margin-bottom:0px">
                        <label class="required">{{ __('Photo Id')}}</label>

                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                            </span>
                        </div>
                        <small style="position:relative;top:-9px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</small>
                        <input type="file" name="photo_id" class="file-upload-default" accept=".jpg,.jpeg,.png" id="photo_id" required>
                        <label id="photo_id_err" class="error"></label>
                    </div>

                    <div id="photo_id_display" style="display: {{ (isset($staff_detail->photo_id) && !empty($staff_detail->photo_id) ? 'block': 'none')}};">
                        <div class="form-group">
                            <div class="copy_class">
                                <img id="photo_id_show" src="{{ isset($staff_detail->photo_id) && !empty($staff_detail->photo_id) ? url('storage/'.$staff_detail->photo_id)  : '' }}" class="img-responsive imageZoomKp">
                            </div>
                        </div>
                    </div>
                </div>


                @else

                <div class="col-sm-3">
                    <div class="form-group" style="margin-bottom:0px">
                        <label>{{ __('Photo Id')}}</label>

                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                            </span>
                        </div>
                        <small style="position:relative;top:-9px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</small>
                        <input type="file" name="photo_id" class="file-upload-default" accept=".jpg,.jpeg,.png" id="photo_id">
                        <label id="photo_id_err" class="error"></label>
                    </div>

                    <div id="photo_id_display" style="display: {{ (isset($staff_detail->photo_id) && !empty($staff_detail->photo_id) ? 'block': 'none')}};">
                        <div class="form-group">
                            <div class="copy_class">
                                <img id="photo_id_show" src="{{ isset($staff_detail->photo_id) && !empty($staff_detail->photo_id) ? url('storage/'.$staff_detail->photo_id)  : '' }}" class="img-responsive imageZoomKp">
                            </div>
                        </div>
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

<!-- push external js -->
@push('script')
<script src="{{ asset('plugins/form-wizard/from_wizard.js') }}"></script>

<script>
    var document_array = [];
    $(document).ready(function() {

        //set date picker   
        //    $('#creation_date').datepicker({
        //        uiLibrary: 'bootstrap4',
        //        maxDate: new Date(),
        //        format: 'yyyy-mm-dd'
        //    });
        /* $('#dob').datetimepicker({

              format: 'YYYY-MM-DD',
              
          });*/
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
            bio: {
                required: true,
            },
            phone: {
                required: true,
                //minlength: 16,
                maxlength: 16,
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
                        window.location.href = "{{url('/users')}}";
                    } else {
                        $.notify(response.msg, "error");
                        $("#addEditForm").find('.submit_button').removeAttr("disabled");
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

    $('.user_type').on('change', function() {
        var value = $('.user_type').val();
        if (value == '0') {
            $('#display_none').removeClass('display_none');
            $('#display_none_business').removeClass('display_none');
        } else if (value == '1') {
            $('#display_none').addClass('display_none');
            $('#display_none_business').addClass('display_none');
        } else {
            $('#display_none').removeClass('display_none');
            $('#display_none_business').removeClass('display_none');
        }
    });
</script>



@if(isset($staff_detail->registered_as) && $staff_detail->registered_as == '0')

<script>
    $('#display_none').removeClass('display_none');
    $('#display_none_business').removeClass('display_none');
</script>



@endif


@endpush
@endsection