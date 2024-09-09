@extends('layouts.main')
@section('title', 'Change Password')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3>{{ __('Change Password ')}}</h3>
               
            </div>
            <div class="card-body">
                <form id="addEditForm" >
                    @csrf
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
                    
                    <div class="row change_password">
                        <div class="col-md-4">
                         <div class="form-group">
                             <label for="old_password" class="required">{{ __('Old Password')}}</label>
                                <div class="position-relative" id="show_hide_password">
                                    <input type="password" class="form-control" id="old_password" placeholder=" Old Password" name="old_password" >
                                    <a href="javascript:void(0)" class="hiden"><i class='ik ik-eye-off'></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                         <div class="form-group">
                                <label for="new_password" class="required">{{ __('New Password')}}</label>
                                <div class="position-relative" id="show_hide_password2">
                                    <input type="password" class="form-control" id="new_password" placeholder=" New Password" name="new_password" >
                                    <a href="javascript:void(0)" class="hiden"><i class='ik ik-eye-off'></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                         <div class="form-group">
                             <label for="confirm_password" class="required">{{ __('Confirm Password')}}</label>
                                <div class="position-relative" id="show_hide_password3">
                                    <input type="password" class="form-control" id="confirm_password" placeholder=" Confirm Password" name="confirm_password" >
                                    <a href="javascript:void(0)" class="hiden"><i class='ik ik-eye-off'></i></a>
                                </div>
                            </div>
                        </div>
                        
                          
                    <div class="col-sm-12">
                     <div class="form-group text-right">
                        <input class="btn btn-primary" type="submit" name="Save" value="Save">
                    </div>
                    </div>
                    </div>
                            
                </form>
            </div>
        </div>
    </div>
    </div>


<!-- push external js -->
@push('script')

<script>
    //add update item
    $("#addEditForm").validate({
        rules: {
            new_password:{
                required: true,
                minlength:8,
            },
            old_password:{
                required: true,
                minlength:8,
            },
            confirm_password:{
                required: true,
                minlength:8,
            },
                        
        },
        messages: {},
        submitHandler: function(form) {
            //serialize form data
            
            var formData = $("#addEditForm").serialize();

            $.ajax({
                type: "POST",
                url: "{{url('change/password/update')}}",
                data: formData,
                success: function(data) {
                    var response = JSON.parse(data);
                    console.log(response);
                    if (response.code == 200) {    
                      //show notification
                        $.notify(response.msg, "success");
                        location.reload();
                    } else {
                        
                        $.notify(response.msg, "error");
                    }
                },
            });
            return false;
        }
    });


</script>

<script>
    $(document).ready(function() {
       
    

        $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {

            event.preventDefault();

            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
               
                $('#show_hide_password .hiden i').removeClass('ik').removeClass('ik-eye');
                $('#show_hide_password .hiden i').addClass('ik').addClass('ik-eye-off');
               
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password .hiden i').removeClass('ik').removeClass('ik-eye-off');
                $('#show_hide_password .hiden i').addClass('ik').addClass('ik-eye');
            }

        });

        $("#show_hide_password2 a").on('click', function(event) {

event.preventDefault();

if ($('#show_hide_password2 input').attr("type") == "text") {
    $('#show_hide_password2 input').attr('type', 'password');
   
    $('#show_hide_password2 .hiden i').removeClass('ik').removeClass('ik-eye');
    $('#show_hide_password2 .hiden i').addClass('ik').addClass('ik-eye-off');
   
} else if ($('#show_hide_password2 input').attr("type") == "password") {
    $('#show_hide_password2 input').attr('type', 'text');
    $('#show_hide_password2 .hiden i').removeClass('ik').removeClass('ik-eye-off');
    $('#show_hide_password2 .hiden i').addClass('ik').addClass('ik-eye');
}

});


$("#show_hide_password3 a").on('click', function(event) {

event.preventDefault();

if ($('#show_hide_password3 input').attr("type") == "text") {
    $('#show_hide_password3 input').attr('type', 'password');
   
    $('#show_hide_password3 .hiden i').removeClass('ik').removeClass('ik-eye');
    $('#show_hide_password3 .hiden i').addClass('ik').addClass('ik-eye-off');
   
} else if ($('#show_hide_password3 input').attr("type") == "password") {
    $('#show_hide_password3 input').attr('type', 'text');
    $('#show_hide_password3 .hiden i').removeClass('ik').removeClass('ik-eye-off');
    $('#show_hide_password3 .hiden i').addClass('ik').addClass('ik-eye');
}

});


    });


    });
    </script>

@endpush
@endsection