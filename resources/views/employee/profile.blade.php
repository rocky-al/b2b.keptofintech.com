@extends('layouts.main')
@section('title', 'Change Password')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <input type="file" name="profile_image" class="file-upload-default" accept=".jpg,.jpeg,.png" id="profile_image" style="display : none">
                @if(isset($user->profile_image) && !empty($user->profile_image))
                <div class="magnific-img profile_class">
                    <a class="image-popup-vertical-fit" href="{{ url('storage/'.$user->profile_image) }}" title="profile_image">
                        <div class="copy_class">
                            <img src="{{ url('storage/'.$user->profile_image) }}" class="img-responsive avatar">
                        </div> 

                    </a>
                    <i class="ik ik-edit-2 file-upload-browse" data-toggle="tooltip" title="Change Profile Image" data-placement="bottom"></i>&nbsp;
                    <h3>
                        {{(isset($user->first_name) && !empty($user->first_name) ? $user->first_name :'')}} {{(isset($user->last_name) && !empty($user->last_name) ? $user->last_name :'')}}-ID#{{(isset($user->id) && !empty($user->id) ? $user->id :'')}} 
                       
                    </h3>

                </div>

                @else
                <div class="magnific-img profile_class">
                    <a class="image-popup-vertical-fit" href="{{ asset('img/default_user.png') }}" title="default_user">

                        <div class="copy_class">
                            <img src="{{ asset('img/default_user.png') }}" class="img-responsive avatar">
                        </div>
                    </a>
                    <i class="ik ik-edit-2 file-upload-browse" data-toggle="tooltip" title="Change Profile Image" data-placement="bottom"></i>&nbsp;
                    <h3>
                        {{(isset($user->first_name) && !empty($user->first_name) ? $user->first_name :'')}} {{(isset($user->last_name) && !empty($user->last_name) ? $user->last_name :'')}}-ID#{{(isset($user->id) && !empty($user->id) ? $user->id :'')}}  
                    </h3>

                </div>


                @endif

            </div>
            <div class="card-body">
                <form id="addEditForm" >
                    @csrf
                    <input type="hidden" name="id" value="{{ isset($user->id) ? $user->id : '' }}">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="first_name" class="required">{{ __('First Name')}}</label>
                                <input type="text" class="form-control " id="first_name" placeholder="First Name" name="first_name" value="{{ (isset($user->first_name) && !empty($user->first_name) ? $user->first_name : '')}}">
                                <div class="help-block with-errors"></div>


                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="last_name" class="required">{{ __('Last Name')}}</label>
                                <input type="text" class="form-control " id="last_name" placeholder="Last Name" name="last_name" value="{{ (isset($user->last_name) && !empty($user->last_name) ? $user->last_name : '') }}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone" class="required">{{ __('Phone Number')}}</label>
                                <input type="text" class="form-control phone_pattern" id="phone" placeholder="Phone Number" name="phone" maxlength="9" value="{{ (isset($user->phone) && !empty($user->phone) ? preg_replace('/^(\d{4})(\d{4})$/', '$1 $2', $user->phone) : '') }}" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="required">{{ __('Email')}}</label>
                                <input type="email" class="form-control {{($user->employee_type == '0') ? 'email_edit' : '' }}" id="email" placeholder="Email" name="email" autocomplete="off" value="{{ (isset($user->email) && !empty($user->email) ? $user->email : '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <input class="btn btn-primary" type="submit" name="Save" value="Save">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<!-- push external js -->
@push('script')

<script>
    $(document).ready(function () {
        $('#dob').datepicker({
            uiLibrary: 'bootstrap4',
            maxDate: new Date(),
            format: 'dd/mm/yyyy'
        });

    });

    $('.file-upload-browse').on('click', function () {
        var file = $(this).parent().parent().parent().find('.file-upload-default');
        file.trigger('click');
    });
    $('.file-upload-default').on('change', function () {
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });
    //add update item
    $("#addEditForm").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            phone: {
                required: true,
            },
            email: {
                required: true,
            },
            
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
       },
        messages: {},
        submitHandler: function (form) {
            //serialize form data

            //var formData = $("#addEditForm").serialize();

            var formData = new FormData($("#addEditForm")[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{url('employee/profile/update')}}",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    var response = JSON.parse(data);
                    console.log(response);
                    if (response.code == 200) {
                        //show notification
                        $.notify('Profile Update Successfully', "success");
                        location.reload();
                    } else {

                        $.notify('Something went wrong.', "warning");
                    }
                },
            });
            return false;
        }
    });



    // display image or pdf file after choosen
    function  imagesPreview(input, image_name) {

        if (input.files) {
            var filesAmount = input.files.length;



            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                if (!input.files[i].name.match(/\.(jpg|jpeg|png)$/i)) {
                    var fileExtension = ['jpeg', 'jpg', 'png'];
                    document.getElementById(image_name + '_err').innerHTML = 'Only formats are allowed : ' + fileExtension.join(', ');
                    document.getElementById(image_name + '_display').style.display = 'none';
                    $('#' + image_name).val('');

                    continue;
                } else {
                    document.getElementById(image_name + '_err').innerHTML = '';
                    document.getElementById(image_name + '_display').style.display = 'block';


                    document.getElementById(image_name + '_show').src = window.URL.createObjectURL(input.files[i]);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

    }
    ;

    $('#profile_image').on('change', function () {

        //imagesPreview(this,'profile_image');
        var formData = new FormData();
        formData.append("files", document.getElementById('profile_image').files[0]);
        formData.append("id", "{{ $user->id }}");

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{url('employee/profile/profile_image_update')}}",
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                var response = JSON.parse(data);
                console.log(response);
                if (response.code == 200) {
                    //show notification
                    $.notify(response.msg, "success");
                    location.reload();
                } else {

                    $.notify(response.msg, "warning");
                }
            },
        });

    });
    
    //display permit type field
        function display_permit_type_field(value) {

            if (value == '2') {
                $('#permit_type_display').show();
                $('#nationality').show();
                $('#agency').hide();
            } else {
                $('#permit_type_display').hide();
                $('#nationality').hide();
                $('#agency').show();
            }

        }



</script>
@endpush
@endsection