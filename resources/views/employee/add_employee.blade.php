@extends('layouts.main')
@section('title', 'Add Employee')
@section('url_name', '/employees/employee')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/form-wizard/from_wizard.css') }}">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3>{{ __('Add')}} {{ ($employee_type == '1') ? ' Employee' : ' Staff User' }}</h3>
                <div class="pull-right">
                    @php
                    $url_type = ($employee_type == '1') ? 'employee' : 'staff';
                    @endphp
                    <a class="btn btn-outline-primary btn-rounded-20" href="{{ url('/employees/'.$url_type) }}">
                        <i class="ik ik-list"></i> List of {{ ($employee_type == '1') ? ' Employees' : ' Staff Users' }}
                    </a>
                </div>
            </div>
            <div class="card-body">

                <form class="forms-sample" id="addEditForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <ul id="progressbar">
                        <li class="active nav-item" id="general-info" style="text-align: center"><strong>GENERAL INFO</strong></li>
                        <li class=" nav-item" id="remuneration" style="text-align: center"><strong>REMUNERATION</strong></li> 
                    </ul>
                    <fieldset>
                        <div id="general-info-tab" role="tabpanel" aria-labelledby="general-info" class=" tab tab-pane fade show active">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-card">
                                        <h4>Personal Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="hidden" id="check_edit" name="check_edit" value="0">
                                                    <label for="type" class="required">{{ __('Type')}}</label>
                                                    <select id="type" name="type" class="form-control" onchange="display_permit_type_field(this.value)">
                                                        <option value="">{{ __('Select Type')}}</option>
                                                        <option value="0">{{ __('Singaporean Citizen')}}</option>
                                                        <option value="1">{{ __('Permanent Resident')}}</option>
                                                        <option value="2">{{ __('Foreigners')}}</option>

                                                    </select>


                                                </div>
                                            </div>

                                            <div class="col-sm-12" id="agency">
                                                <div class="form-group">
                                                    <label for="select_agency">{{ __('Agency')}}</label>

                                                    {!! Form::select('select_agency', $agencies, null,[ 'class'=>'form-control', 'placeholder' => 'Select Agency']) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-12 " id="nationality" style="display: none">
                                                <div class="form-group">
                                                    <label for="nationality">{{ __('Nationality')}}</label>
                                                    <input type="text" class="form-control "  placeholder="Nationality" name="nationality" value="{{ old('nationality')}}">

                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="permit_type_display" style="display: none">
                                                <div class="form-group">
                                                    <label for="permit_type" class="required">{{ __('Permit Type')}}</label>
                    <!--                               <select  name="permit_type" class="form-control">
                                                        <option value="">{{ __('Select Permit')}}</option>
                                                        <option value="0">{{ __('Work Permit')}}</option>
                                                        <option value="1">{{ __('S Pass')}}</option>
                                                        <option value="2">{{ __('Dependent Pass')}}</option>
                                                        <option value="3">{{ __('LTVP')}}</option>
                    
                                                    </select>-->
                                                    {!! Form::select('permit_type', $permittype, null,[ 'class'=>'form-control', 'placeholder' => 'Select Permit','id'=>"permit_type" ,'required'=>'required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="first_name" class="required">{{ __('First Name')}}</label>
                                                    <input type="text" class="form-control " id="first_name" placeholder="First Name" name="first_name" value="{{ old('first_name')}}">
                                                    <div class="help-block with-errors"></div>


                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_name" class="required">{{ __('Last Name')}}</label>
                                                    <input type="text" class="form-control " id="last_name" placeholder="Last Name" name="last_name" value="{{ old('last_name')}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12" style="display: none">
                                                <div class="form-group">
                                                    <label for="password">{{ __('Password')}}<span class="text-red">*</span></label>
                                                    <input type="password" class="form-control" id="password" placeholder="password" name="password" value="{{ old('password')}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" class="required">{{ __('Phone Number')}}</label>
                                                    <input type="text" class="form-control phone_pattern" id="phone" placeholder="XXXX XXXX" name="phone" maxlength="9" value="{{ old('phone')}}" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))">
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="email" class="required">{{ __('Email')}}</label>
                                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="dob" class="required">{{ __('DOB')}}</label>
                                                    <input type="text" class="form-control date_error" id="dob" placeholder="DD/MM/YYYY" name="dob" value="{{ old('dob')}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="gender">{{ __('Gender')}}</label>
                                                    <select id="gender" name="gender" class="form-control">
                                                        <option value="">{{ __('Select Gender')}}</option>
                                                        <option value="0">{{ __('Male')}}</option>
                                                        <option value="1">{{ __('Female')}}</option>
                                                        <!--                                    <option value="2">{{ __('Other')}}</option>-->

                                                    </select>
                                                </div>
                                            </div>


                                        </div> 


                                        <div class="form-group">
                                            <label>{{ __('Profile Image')}}<span style="font-size: 9px;"> &nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>
                                            <input type="file" name="profile_image" class="file-upload-default" accept=".jpg,.jpeg,.png" id="profile_image">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                </span>
                                            </div>
                                            <label id="profile_image_err" class="error"></label>

                                        </div>                       

                                        <div  id="profile_image_display" style="display: none;">
                                            <div class="form-group">
                                                <div class="copy_class">
                                                    <img id="profile_image_show" src="" class="img-responsive">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-card">
                                        <h4>Address Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="address">{{ __('Address')}}</label>
                                                    <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ old('address')}}">
                                                </div>
                                            </div>
                                            <!--                         <div class="col-sm-12">
                                                                     <div class="form-group">
                                                                            <label for="country">{{ __('Country')}}</label>
                                                                            {!! Form::select('country', $countries, null,[ 'class'=>'form-control', 'placeholder' => 'Select Country','onchange'=> 'get_state(this.value);']) !!}
                                                                        </div>
                                                                    </div>-->
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="state">{{ __('State')}}</label>
                    <!--                                <select id="state" name="state" class="form-control" onchange="get_city(this.value)">
                                                        <option value=""> Select State</option>
                                                    </select>-->
                                                    {!! Form::select('state', $state, null,[ 'class'=>'form-control', 'placeholder' => 'Select State','onchange'=>"get_city(this.value)"]) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="city">{{ __('City')}}</label>
                                                    <select id="city" name="city" class="form-control" onchange="">
                                                        <option value=""> Select City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="zipcode">{{ __('Zipcode')}}</label>
                                                    <input type="text" class="form-control" id="zipcode" placeholder="Zipcode" name="zipcode" value="{{ old('zipcode')}}" minlength="6" maxlength="6" onkeypress="return (event.charCode != 8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57))">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-card">
                                        <h4>Banking Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="bank_name">{{ __('Bank Name')}}</label>
                 <!--                                   <input type="text" class="form-control" id="bank_name" placeholder="Bank Name" name="bank_name" value="{{ old('bank_name')}}">-->
                                                    {!! Form::select('bank_name', $bank, null,[ 'class'=>'form-control', 'placeholder' => 'Select Bank','id'=>"bank_name"]) !!}   
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="bank_account_no">{{ __('Account #')}}</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control number" id="bank_account_no" placeholder="Account #" name="bank_account_no" onkeyup="display_bank_account_copy(this.value);"  onpaste="return false;" ondrop="return false;" value="{{ old('bank_account_no')}}"><span class="input-group-append" role="right-icon"><i class="ik ik-alert-triangle" id="account_flag" style="color:red; display:none" data-toggle="tooltip" title="Upload Bank Act Copy " data-placement="left"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="bank_account_copy_dis" style="display:none">
                                                <div class="form-group">
                                                    <label for="ba_copy">{{ __('Bank Account Copy')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png,pdf)</span></label>
                                                    <input type="file" name="ba_copy[]" class="file-upload-default" accept=".jpg,.jpeg,.png,.pdf" id="ba_copy" multiple>
                                                    <div class="input-group col-xs-12">
                                                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                        <span class="input-group-append">
                                                            <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                        </span>
                                                    </div>
                                                    <p id="ba_copy_err" class="error"></p>
                                                </div>
                                            </div>

                                            <div class="" id="list_ba" style="display: none;">
                                                <div class="form-group" id="show_ba">

                                                </div> 
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-card">
                                        <h4>Employment Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="employee_type" class="required">{{ __('Employee Type')}}</label>
                                                    <select id="employee_type" name="employee_type" class="form-control" onchange="display_user_field(this.value)">
                                                        <option value="">{{ __('Select Employee Type')}}</option>
                                                        <option value="1">{{ __('Non Management')}}</option>
                                                        <option value="0">{{ __('Management')}}</option>
                                                    </select>


                                                </div>
                                            </div>
                                            <div class="col-sm-12 d-none" id="position">
                                                <div class="form-group">
                                                    <label for="position" class="required">{{ __('Designation')}}</label>
                                                    {!! Form::select('position', $positions, null,[ 'class'=>'form-control','id'=>'position_val','placeholder' => 'Select']) !!}
                                                </div>
                                            </div>


                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="standard_time" class="required">{{ __('Standard Time')}}</label>
                                                    {!! Form::select('standard_time', $standardtimes, null,[ 'class'=>'form-control', 'placeholder' => 'Select Standard Time','id'=>'standard_time']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="hire_date" class="required">{{ __('Hire Date')}}</label>
                                                    <input type="text" class="form-control date_error" name="hire_date"  id="hire_date" placeholder="DD/MM/YYYY" value="" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 d-none" id="role">
                                                <div class="form-group">
                                                    <label for="role" class="required">{{ __('Role')}}</label>
                                                    {!! Form::select('role', $roles, '',[ 'class'=>'form-control', 'placeholder' => 'Select Role','id'=>'role_val']) !!}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="probation_period_type">{{ __('Probation Period Type')}}</label>
                                                    <select id="probation_period_type" name="probation_period_type" class="form-control" onchange="display_probation_field(this.value)">
                                                        <option value="">{{ __('Select Period Type')}}</option>
                                                        <option value="0">{{ __('In Days')}}</option>
                                                        <option value="1">{{ __('In Date Range')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div   id="date_range_cal" style="display: none;">
                                                    <div class="form-group">
                                                        <label class="d-block">Select Probation Date Range</label>
                                                        <div class="input-group input-daterange">
                                                            <input type="text" class="form-control" value="" name='date_range' readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div  id="period_pro" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="probation_period">{{ __('Probation Period ')}}</label>
                                                        <select id="probation_period" name="probation_period" class="form-control" >
                                                            <option value="">{{ __('Select Probation Period')}}</option>
                                                            <option value="15">{{ __('15')}}</option>
                                                            <option value="30">{{ __('30')}}</option>
                                                            <option value="45">{{ __('45')}}</option>
                                                            <option value="60">{{ __('60')}}</option>
                                                            <option value="75">{{ __('75')}}</option>
                                                            <option value="90">{{ __('90')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div> 
                                    <div class="form-card">
                                        <button class="btn btn-primary" type="button" onclick="add_document_field()"><i class="ik ik-plus"></i>Document Information</button>
                                        <div class="pt-15" id="document" >
                                        </div>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group text-right">
                                        <!--                         <a class="btn btn-primary" href="#remuneration-tab" id="next">Next</a>-->
                                    </div>
                                </div>
                            </div> 
                        </div> 
                        <div class="form-group text-right">
                            <input type="button" name="next" class="next btn btn-primary" value="Next" />
                        </div>
                    </fieldset>
                    <fieldset>
                        <!--                        <div id="remuneration-tab" role="tabpanel" aria-labelledby="remuneration" class="tab tab-pane fade">-->
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="salary" class="required">{{ __('Monthly Salary')}}</label>
                                                <div class="input-group"> <span class="input-group-append" role="right-icon">$ </span>
                                                    <input type="text" class="form-control decimal" id="salary" placeholder="Salary" name="salary" value="{{ old('salary')}}">  
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="leave_default">{{ __('Fixed Allowances')}}</label>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Accommodation')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fa_accommodation'></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Telecommunication')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fa_telecommunications'></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Food')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fa_food'></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Transport')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fa_transport'></td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                    </div> 
                                </div> 
                            </div>              
                                <div class="col-sm-4">
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col-sm-12">

                                                <div class="form-group">
                                                    <label for="leave_scheme">{{ __('Leave Scheme')}}</label>
                                                    {!! Form::select('leave_scheme', config('constants.leave_schema'), '',[ 'class'=>'form-control', 'placeholder' => 'Select']) !!}
                                                </div>
                                                <div class="form-group">
                                                    <label for="leave_default">{{ __('Default Leave Credits')}}</label>
                                                    <table width="100%">
                                                        @foreach($leaves as $leave)
                                                        <tr>
                                                            <td width="150px">
                                                                {{ $leave->title }} 
                                                            <td>
                                                            <td><input type="text" class="form-control decimal" value="{{$leave->leave}}" name='leave_default_{{$leave->id}}'></td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>

                                        </div> 
                                    </div> 
                                </div>
                               <div class="col-sm-4">
                                <div class="form-card">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="leave_default">{{ __('Fixed Deduction')}}</label>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Accommodation')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fd_accommodation'></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Amenities')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fd_amenities'></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="150px">
                                                            {{ __('Services')}}
                                                        <td>
                                                        <td><input type="text" class="form-control decimal" value="" name='fd_services'></td>
                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                           
                            </div>
                            <div class="form-group text-right">
                                <input type="button" name="previous" class="previous action-button-previous btn " value="Previous" /> <input type="submit" name="Save" class="btn btn-primary submit_button" value="Save" />
                            </div>
                            <!--                            </div>-->

                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/form-wizard/from_wizard.js') }}"></script>

    <script>

                                           var document_array = [];
                                           $(document).ready(function () {

                                               //set date picker   
                                               $('#dob').datepicker({
                                                   uiLibrary: 'bootstrap4',
                                                   maxDate: new Date(),
                                                   format: 'dd/mm/yyyy'
                                               }).on('change', function (selected) {
                                                   var min_Date = $('#dob').val();
                                                   $('#hire_date').datepicker('destroy');

                                                   $('#hire_date').datepicker({
                                                       uiLibrary: 'bootstrap4',
                                                       minDate: min_Date,
                                                       format: 'dd/mm/yyyy',
                                                   });
                                               });



                                               $('input[name="date_range"]').daterangepicker();

                                               window.addEventListener("hashchange", myFunction);

                                               function myFunction() {
                                                   var hash = window.location.hash;
                                                   if (hash != '') {

                                                       $('a[href="' + hash + '"]').trigger('click');
                                                   }
                                               }
                                               // Call when load window
                                               var hash = window.location.hash;
                                               if (hash != '') {

                                                   $('a[href="' + hash + '"]').trigger('click');
                                               } else {
                                                   $('.tabs-list li a').first().trigger('click');
                                                   $('.tabs-list li').first().addClass('active');

                                               }

                                           });



                                           //add update item
                                           $("#addEditForm").validate({
                                               rules: {
                                                   type: {
                                                       required: true,
                                                   },
                                                   employee_type: {
                                                       required: true,
                                                   },
                                                   first_name: {
                                                       required: true,
                                                   },
                                                   last_name: {
                                                       required: true,
                                                   },
                                                   dob: {
                                                       required: true,
                                                   },
                                                   hire_date: {
                                                       required: true,

                                                   },
                                                   phone: {
                                                       required: true,
                                                       //number: true,
                                                       minlength: 9,
                                                       maxlength: 9,
                                                   },
                                                   standard_time: {
                                                       required: true,
                                                   },
                                                   status: {
                                                       required: true,
                                                   },
                                                   salary: {
                                                       required: true,
                                                   },
                                                   role: {
                                                       required: {
                                                           function() {
                                                               var ele = document.getElementsByName('employee_type').value;
                                                               if (ele == '0') {
                                                                   return true;
                                                               } else {
                                                                   return false;
                                                               }

                                                           }
                                                       }

                                                   },
                                                   position: {
                                                       required: {
                                                           function() {
                                                               var ele = document.getElementsByName('employee_type').value;
                                                               if (ele == '1') {
                                                                   return true;
                                                               } else {
                                                                   return false;
                                                               }

                                                           }
                                                       }

                                                   },
                                                   email: {
                                                       required: true,
                                                       email:true,
                                      
                                                   },
                                                   'document[]': {
                                                       required: true
                                                   }

                                               },
                                               errorPlacement: function (error, element) {
                                                   error.insertAfter(element.parent());
                                               },
                                               messages: {},
                                               submitHandler: function (form) {

                                                   //form.submit();

                                                   var formData = new FormData($("#addEditForm")[0]);
                                                   var type = "{{$type}}";
                                                   var url_up = "{{url('/')}}" + "/employee/" + type + "/store";
                                                   $.ajax({
                                                       type: "POST",
                                                       enctype: 'multipart/form-data',
                                                       url: url_up,
                                                       data: formData,
                                                       mimeType: "multipart/form-data",
                                                       contentType: false,
                                                       cache: false,
                                                       processData: false,
                                                       beforeSend: function () {
                                                           $("#addEditForm").find('.submit_button').attr("disabled", true);
                                                           $('.loader').show();
                                                       },
                                                       success: function (data) {
                                                           $("#addEditForm").find('.submit_button').attr("disabled", false);
                                                           $('.loader').hide();
                                                           var response = JSON.parse(data);
                                                           //console.log(response);
                                                           if (response.code == 200) {
                                                               //show notification
                                                               //location.reload();
                                                               $.notify(response.msg, "success");
                                                               if (response.emp_type == "employee") {
                                                                   window.location.href = "{{url('employees/employee')}}";
                                                               } else {
                                                                   window.location.href = "{{url('employees/staff')}}";
                                                               }


                                                           } else {

                                                               $.notify(response.msg, "warning");
                                                           }


                                                       },
                                                   });
                                                   return false;

                                               }
                                           });


                                           $(document).on("click", ".file-upload-browse", function () {
                                               var file = $(this).parent().parent().parent().find('.file-upload-default');
                                               file.trigger('click');
                                           });
                                           $(document).on("change", ".file-upload-browse", function () {
                                               $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
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
                                           ;

                                           $('#profile_image').on('change', function () {

                                               imagesPreview(this, 'profile_image');

                                           });

                                           $(document).on('change', '#nric_copy', function () {

                                               imagesPreview(this, 'nric_copy');

                                           });

                                           //upload passport copy and display
                                           $(document).on('change', '#passport_copy', function () {

                                               var formData = new FormData();
                                               var error = 0;
                                               var ins = document.getElementById('passport_copy').files.length;
                                               for (var x = 0; x < ins; x++) {
                                                   formData.append("files[]", document.getElementById('passport_copy').files[x]);
                                                   if (!document.getElementById('passport_copy').files[x].name.match(/\.(jpg|jpeg|png)$/i)) {
                                                       error = 1;
                                                   }
                                               }
                                               if (error == 1)
                                               {
                                                   $.alert({
                                                       title: 'Error!',
                                                       content: 'Only formats are allowed : JPG,JPEG,PNG',
                                                   });
                                               } else
                                               {

                                                   var type = "{{$type}}";
                                                   var url_up = "{{url('/')}}" + "/employee/" + type + "/uploadImages";
                                                   $.ajax({
                                                       type: "POST",
                                                       enctype: 'multipart/form-data',
                                                       url: url_up,
                                                       headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                                       data: formData,
                                                       mimeType: "multipart/form-data",
                                                       contentType: false,
                                                       cache: false,
                                                       processData: false,
                                                       success: function (data) {
                                                           var response = JSON.parse(data);
                                                           if (response.code == 200) {
                                                               document.getElementById('list').style.display = "block";
                                                               document.getElementById('passport_flag').style.display = "none";
                                                               var length = response.images.length;

                                                               for (var i = 0; i < length; i++) {
                                                                   var url = response.images[i];
                                                                   $(document).find('#show').append('<div class="copy_class"><img src="' + url + '" class="img-responsive" /><span><button class="btn remove">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="passport_copy_name[]"></div>');
                                                               }

                                                               $(document).find("#passport_copy").val('');
                                                           } else {

                                                               $.notify(response.msg, "warning");
                                                           }
                                                       },
                                                   });
                                               }

                                           });

                                           //upload ba copy and display 
                                           $('#ba_copy').on('change', function () {

                                               var formData = new FormData();
                                               var error = 0;
                                               var ins = document.getElementById('ba_copy').files.length;
                                               for (var x = 0; x < ins; x++) {
                                                   formData.append("files[]", document.getElementById('ba_copy').files[x]);
                                                   if (!document.getElementById('ba_copy').files[x].name.match(/\.(jpg|jpeg|png|pdf)$/i)) {
                                                       error = 1;
                                                   }
                                               }
                                               if (error == 1)
                                               {
                                                   $.alert({
                                                       title: 'Error!',
                                                       content: 'Only formats are allowed : JPG,JPEG,PNG,PDF',
                                                   });
                                               } else
                                               {
                                                   var type = "{{$type}}";
                                                   var url_up = "{{url('/')}}" + "/employee/" + type + "/uploadImages";
                                                   $.ajax({
                                                       type: "POST",
                                                       enctype: 'multipart/form-data',
                                                       url: url_up,
                                                       headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                                       data: formData,
                                                       mimeType: "multipart/form-data",
                                                       contentType: false,
                                                       cache: false,
                                                       processData: false,
                                                       success: function (data) {

                                                           var response = JSON.parse(data);
                                                           if (response.code == 200) {
                                                               $('#account_flag').hide();
                                                               document.getElementById('list_ba').style.display = "block";
                                                               var length = response.images.length;
                                                               //loop for files
                                                               for (var i = 0; i < length; i++) {
                                                                   var ext = response.image_name[i].split('.').pop();
                                                                   //check for file is pdf or image
                                                                   if (ext == 'pdf') {
                                                                       var url = response.images[i];
                                                                       $('#show_ba').append('<div class="copy_class"><a href="' + url + '" target="_blank" />' + response.image_name[i].substring(1, 4) + '.' + ext + '</a><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="ba_copy_name[]"></div>');
                                                                   } else {
                                                                       var url = response.images[i];
                                                                       $('#show_ba').append('<div class="copy_class"><img src="' + url + '" class="img-responsive" /><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="ba_copy_name[]"></div>');
                                                                   }

                                                               }

                                                               $("#ba_copy").val('');
                                                           } else {

                                                               $.notify(response.msg, "warning");
                                                           }
                                                       },
                                                   });
                                               }
                                           });

                                           $(document).on('click', '.remove', function () {
                                               //alert("hello");
                                               $(this).closest('.copy_class').remove();
                                               //check for passport value and show flag icon according to that
                                               var passport = $('#passport_no').val();
                                               if (passport != '') {
                                                   var file_flag = $('input[name="passport_copy_name[]"').val();

                                                   if (file_flag == '' || file_flag == undefined) {
                                                       $('#passport_flag').show();
                                                   }
                                               }

                                               var file_flag_ba = $('input[name="ba_copy_name[]"').val();

                                               if (file_flag_ba == '' || file_flag_ba == undefined) {
                                                   $('#account_flag').show();
                                               }



                                           });


                                           //function for display bank  copy
                                           function bankCopyPreview(input) {

                                               if (input.files) {
                                                   var filesAmount = input.files.length;

                                                   for (i = 0; i < filesAmount; i++) {
                                                       var reader = new FileReader();
                                                       if (!input.files[i].name.match(/\.(jpg|jpeg|png|gif|pdf)$/i)) {
                                                           var fileExtension = ['jpeg', 'jpg', 'png', 'pdf'];
                                                           $('#bank_account_copy').val('');
                                                           document.getElementById('bank_account_copy_err').innerHTML = 'Only formats are allowed : ' + fileExtension.join(', ');
                                                           document.getElementById('account_flag').style.display = "block";
                                                           break;
                                                       } else {
                                                           document.getElementById('bank_account_copy_err').innerHTML = '';
                                                           document.getElementById('list_bank').style.display = "block";
                                                           document.getElementById('account_flag').style.display = "none";
                                                           if (input.files[i].name.match(/\.(pdf)$/i)) {
                                                               $('#show_bank').append('<a href="' + window.URL.createObjectURL(input.files[i]) + '" >' + input.files[i]['name'] + '</a>');
                                                           } else {
                                                               $('#show_bank').append('<div class="copy_class"><img src="' + window.URL.createObjectURL(input.files[i]) + '" class="img-responsive"/></div>');
                                                           }
                                                       }



                                                       reader.readAsDataURL(input.files[i]);
                                                   }

                                               }
                                           }

                                           $('#bank_account_copy').on('change', function () {
                                               $('#show_bank').html("");
                                               bankCopyPreview(this);

                                           });




    </script>
    <script>

        //display probation field accoring to select
        function display_probation_field(value) {
            if (value == '0') {
                $('#date_range_cal').hide();
                $('#period_pro').show();
            }
            if (value == '1') {
                $('#date_range_cal').show();
                $('#period_pro').hide();
            }
            if (value == '') {
                $('#date_range_cal').hide();
                $('#period_pro').hide();
            }
        }

        //display nric copy
        function display_nric_copy(value) {

            if (value != '') {
                $('#nric_copy_dis').show();
                var file_flag = $('#nric_copy').val();

                if (file_flag == '') {
                    $('#nric_copy_flag').show();
                }

            } else {
                $('#nric_copy_dis').hide();
                $('#nric_copy_flag').hide();
                $('#nric_copy_display').hide();
                $('#nric_copy').val('');

            }

        }

        //display passport copy
        function display_passport_copy(value) {

            if (value != '') {
                $('#passport_copy_dis').show();
                var file_flag = $('input[name="passport_copy_name[]"').val();

                if (file_flag == '' || file_flag == undefined) {
                    $('#passport_flag').show();
                }

            } else {
                $('#passport_copy_dis').hide();
                $('#passport_flag').hide();
                $('#list').hide();
                $('#show').html('');

            }

        }

        //display bank account copy
        function display_bank_account_copy(value) {

            if (value != '') {
                $('#bank_account_copy_dis').show();
                //var file_flag = $('#bank_account_copy').val();
                var file_flag = $('input[name="ba_copy_name[]"').val();
                if (file_flag == '' || file_flag == undefined) {

                    $('#account_flag').show();
                }
            } else {
                $('#bank_account_copy_dis').hide();
                $('#account_flag').hide();
                //           $('#show_bank').hide();
                //           $('#bank_account_copy').val('');
                $('#list_ba').hide();
                $('#show_ba').html('');
            }

        }

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

        //get state option
        function get_state(value) {
            var type = "{{$type}}";
            var url_up = "{{url('/')}}" + "/employee/" + type + "/get_state";
            $.ajax({
                type: "POST",
                url: url_up,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    country_id: value,

                },
                success: function (data) {

                    $('#state').html(data);
                },
            });
        }

        //get city option
        function get_city(value) {
            var type = "{{$type}}";
            var url_up = "{{url('/')}}" + "/employee/" + type + "/get_city";
            $.ajax({
                type: "POST",
                url: url_up,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    state_id: value,

                },
                success: function (data) {

                    $('#city').html(data);
                },
            });
        }

        $(document).on('focus click tap', 'input', function () {
            $(this).attr("autocomplete", 'new-password');
        });


        function display_user_field(value) {

            if (value == '0') {
                $('#position').addClass('d-none');
                $('#role').removeClass('d-none');

            }
            if (value == '1') {
                $('#position').removeClass('d-none');
                $('#role').addClass('d-none');

            }
        }

        //    $(document).on("click", ".tabs-list li", function() {
        //        
        //        if ($(this).find('a').attr('href') == '#general-info-tab') {
        //            history.pushState({}, null, '#general-info-tab');
        //           
        //        } else if ($(this).find('a').attr('href') == '#remuneration-tab') {
        //            history.pushState({}, null, '#remuneration-tab');         
        //        } 
        //    });



        //function for document add more functionality  
        function add_document_field() {
            var count = $(".document").length;
            if (count == 2) {

            } else {
                var html = '<div class="document"><div class="form-group"><select name=document[] class="form-control d_value" data-d_val=""><option value="">Select</option><option value="0">Identification card </option><option value="1">Passport</option></select></div><div class="document_attach"></div></div>';
                $('#document').append(html);
            }

        }

        $(document).on("change", "select[name='document[]']", function () {
            var document_value = $(this).val();
            var html = '';

            if (document_value == " ") {
                var remove_val = $(this).attr('data-d_val');
                const index = document_array.indexOf(remove_val);
                if (index > -1) {
                    document_array.splice(index, 1);
                }
                $(this).attr('data-d_val', '');
            }
            if (jQuery.inArray(document_value, document_array) == -1) {
                document_array.push(document_value);
                if (document_value == '1') {
                    var remove_val = $(this).attr('data-d_val');
                    const index = document_array.indexOf(remove_val);
                    if (index > -1) {
                        document_array.splice(index, 1);
                    }
                    var html = '<div class="form-group"><label for="passport_no">Passport Number</label><div class="input-group"><input type="text" class="form-control alphanum" id="passport_no" placeholder="Passport No" name="passport_no" onpaste="return false;" ondrop="return false;" value="" required>' +
                            '<span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="passport_flag" style="color:red; display:none" data-toggle="tooltip" title="Upload Passport Copy" data-placement="left"></i></span></div></div><div class="form-group">' +
                            '<label for="expiry_date">Expiration Date</label><input type="text" class="form-control expiry_date " id="expiry_date" placeholder="DD/MM/YYYY" name="expiry_date" required readonly></div><div class="form-group">' +
                            '<label for="passport_copy">Password Copy<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label><input type="file" name="passport_copy[]" class="file-upload-default" accept=".jpg,.jpeg,.png" id="passport_copy" multiple>' +
                            '<div class="input-group col-xs-12"><input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image"><span class="input-group-append"><button class="file-upload-browse btn btn-primary" type="button">Upload</button></span></div>' +
                            '<p id="passport_copy_err" class="error"></p></div><div class="" id="list" style="display: none;"><div class="form-group" id="show"></div></div>';
                    $(this).attr('data-d_val', document_value);


                }

                if (document_value == '0') {
                    var remove_val = $(this).attr('data-d_val');
                    const index = document_array.indexOf(remove_val);
                    if (index > -1) {
                        document_array.splice(index, 1);
                    }
                    var html = '<div class="form-group"><label for="nric_no">NRIC No</label><div class="input-group"><input type="text" class="form-control" id="nric_no" placeholder="NRIC No" name="nric_no" maxlength="9" required>' +
                            '<span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="nric_copy_flag" style="color:red; display:none" data-toggle="tooltip" title="Upload NRIC ID Card" data-placement="left"></i></span></div></div>' +
                            '<div class="form-group"><label for="nric_copy">NRIC Copy<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>' +
                            '<input type="file" name="nric_copy" class="file-upload-default" accept=".jpg,.jpeg,.png" id="nric_copy"><div class="input-group col-xs-12"><input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">' +
                            '<span class="input-group-append"><button class="file-upload-browse btn btn-primary" type="button">Upload</button></span></div><label id="nric_copy_err" class="error"></label></div><div id="nric_copy_display" style="display: none">' +
                            '<div class="form-group"><div class="copy_class"><img id="nric_copy_show" src="" class="img-responsive"></div></div></div>';
                    $(this).attr('data-d_val', document_value);
                }
            } else {
                var remove_val = $(this).attr('data-d_val');
                const index = document_array.indexOf(remove_val);
                if (index > -1) {
                    document_array.splice(index, 1);
                }
                $(this).attr('data-d_val', '');
                var html = "<p style='color:red'>this type is already selected</p>"
            }

            $(this).closest('.document').find(".document_attach").html('');
            $(this).closest('.document').find(".document_attach").html(html);
            $(document).find('#expiry_date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd/mm/yyyy',
            });
        });



</script>


    @endpush
    @endsection