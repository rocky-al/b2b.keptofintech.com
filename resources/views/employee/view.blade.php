@extends('layouts.main')
@section('title', $user->name)
@section('url_name', '/employees/employee')
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/form-wizard/from_wizard.css') }}">
@endpush


<div class="">
    <div class="row">
        <!-- start message area-->
        @include('include.message')
        <!-- end message area-->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    @if(isset($user->profile_image) && !empty($user->profile_image))
                    <div class="magnific-img profile_class">
                        <a class="image-popup-vertical-fit" href="{{ get_file_url($user->profile_image) }}" title="{{ $user->profile_image }}">
                            <div class="copy_class">
                                <img src="{{ get_file_url($user->profile_image) }}" class="img-responsive avatar">
                            </div>
                            <h3>
                                {{(isset($user->first_name) && !empty($user->first_name) ? $user->first_name :'')}} {{(isset($user->last_name) && !empty($user->last_name) ? $user->last_name :'')}} - ID # {{(isset($user->id) && !empty($user->id) ? $user->id :'')}}
                            </h3>
                        </a>
                    </div>
                    @else
                    <div class="magnific-img profile_class">
                        <a class="image-popup-vertical-fit" href="{{ asset('img/default_user.png') }}" title="default_user">
                            <div class="copy_class">
                                <img src="{{ asset('img/default_user.png') }}" class="img-responsive avatar">
                            </div>
                            <h3>
                                {{(isset($user->first_name) && !empty($user->first_name) ? $user->first_name :'')}} {{(isset($user->last_name) && !empty($user->last_name) ? $user->last_name :'')}} - ID # {{(isset($user->id) && !empty($user->id) ? $user->id :'')}}
                            </h3>
                        </a>
                    </div>

                    @endif
                    <!-- check for profile type  -->
                    @if($pro_type !='profile')
                    <div class="form-group text-right">
                        <!--check for employee type and check edit permission according to that-->

                        @can('edit_employee')
                        <button type="button" class="btn btn-outline-primary btn-rounded-20 " onclick="showedit();" id="edit_button" style="display: none"><i class="ik ik-edit-2"></i>Edit</button>@endcan&nbsp; <a class="btn btn-outline-primary btn-rounded-20" href="{{ url('/employees/employee') }}">
                            <i class="ik ik-list"></i> List of Employees
                        </a>

                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs tabs-list" id="myTab" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" id="general-info" data-toggle="tab" href="#general-info-tab" role="tab" aria-controls="general-info-tab" aria-selected="true">GENERAL INFO</a>
                        </li>
                        @if($pro_type != 'profile')
                        @can('manage_certificate')
                        <li class="nav-item">
                            <a class="nav-link" href="#certificate-tab" data-toggle="tab" id="certificate" role="tab" aria-controls="certificate-tab" aria-selected="false">CERTIFICATES</a>
                        </li>
                        @endcan
                        @if(Gate::check('manage_offer_letter') || Gate::check('manage_employment_letter'))
                        <li class="nav-item">
                            <a class="nav-link" href="#employee-contract-tab" data-toggle="tab" id="employee-contract" role="tab" aria-controls="employee-contract-tab" aria-selected="false">EMPLOYMENT CONTRACT</a>
                        </li>
                        @endif

                        @if($user->type == '2')
                        @can('manage_employee_permit')
                        <li class="nav-item">
                            <a class="nav-link" href="#employee-permit-tab" data-toggle="tab" id="employee-permit" role="tab" aria-controls="employee-permit-tab" aria-selected="false">EMPLOYMENT PERMIT</a>
                        </li>
                        @endcan
                        @endif
                        @if(Gate::check('manage_increment_letter') || Gate::check('manage_warning_letter') || Gate::check('manage_other_doc'))
                        <li class="nav-item">
                            <a class="nav-link" href="#correspondance-tab" data-toggle="tab" id="correspondance" role="tab" aria-controls="correspondance-tab" aria-selected="false">CORRESPONDENCES</a>
                        </li>
                        @endif
                        @if(Gate::check('manage_employee_bonus_added') || Gate::check('manage_employee_loan_added') || Gate::check('manage_employee_advance_taken'))
                        <li class="nav-item">
                            <a class="nav-link" href="#bonus-tab" data-toggle="tab" id="bonus" role="tab" aria-controls="bonus-tab" aria-selected="false">BONUS/LOAN</a>
                        </li>
                        @endif
                        @endif
                    </ul>
                    <div class="tab-content">
                        <!-- general info section starts here -->
                        <div id="general-info-tab" role="tabpanel" aria-labelledby="general-info" class="tab tab-pane fade show active">


                            <div class="row" id="employee_details">
                                <div class="col-sm-12 pt-20">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-card">
                                        <h4>Personal Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">

                                                <div class="form-group d-flex">
                                                    <label for="type" class="w-30">{{ __('Type')}}</label>

                                                    @if($user->type == '0')
                                                    {{ __('Singaporean Citizen') }}
                                                    @elseif($user->type == '1')
                                                    {{ __('Permanent Resident') }}
                                                    @else
                                                    {{ __('Foreigners')}}
                                                    @endif
                                                </div>
                                            </div>
                                            @if($user->type == '2')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="permit_type" class="w-30">{{ __('Permit Type')}}</label>
                                                            {{ $user->permit_type_name }}
                                                    <!--                                                   
                                                    @if($user->permit_type == '0')
                                                    {{ __('Work Permit') }}
                                                    @elseif($user->permit_type == '1')
                                                    {{ __('S Pass') }}
                                                    @elseif($user->permit_type == '2')
                                                    {{ __('Dependent Pass')}}
                                                    @elseif($user->permit_type == '3')
                                                    {{ __('LTVP')}}
                                                    @else

                                                    @endif-->

                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-sm-12" style="display: {{ ($user->type == '2') ? 'none' : 'block'}}">
                                                <div class="form-group d-flex">
                                                    <label for="agency" class="w-30">{{ __('Agency')}}</label>
                                                    {{ (isset($user->agency_name) && !empty($user->agency_name)) ? $user->agency_name : '' }}
                                                </div>
                                            </div>
                                             <div class="col-sm-12 " style="display: {{ ($user->type == '2') ? 'block' : 'none'}}">
                                            <div class="form-group">
                                                 <label for="nationality">{{ __('Nationality')}}</label>
                                                 {{ (isset($user->nationality) && !empty($user->nationality)) ? $user->nationality : '' }}
                                             </div>
                                         </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="first_name" class="w-30">{{ __('Name')}}</label>
                                                    {{ $user->first_name . ' '.$user->last_name }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="phone" class="w-30">{{ __('Phone Number')}}</label>
                                                    {{ substr($user->phone, 0, 4).' '.substr($user->phone, 4, 4) }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="dob" class="w-30">{{ __('Date of Birth')}}</label>
                                                    {{ (isset($user->dob) && !empty($user->dob)) ? $user->dob : '' }}
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="email" class="w-30">{{ __('Email')}}</label>
                                                    {{ (isset($user->email) && !empty($user->email)) ? $user->email : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="gender" class="w-30">{{ __('Gender')}}</label>

                                                    @if($user->gender == '0')
                                                    {{ __('Male') }}
                                                    @elseif($user->gender == '1')
                                                    {{ __('Female') }}
                                                    @else

                                                    @endif
                                                </div>
                                            </div>
                                            @if($pro_type =='profile')
                                            @if($user->employee_type == '0')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Role')}}</label>
                                                    {{ (isset($user_role->name) && !empty($user_role->name)) ? $user_role->name : '' }}
                                                </div>
                                            </div>
                                            @endif
                                            @endif

                                        </div>
                                    </div>

                                    @if($pro_type !='profile')
                                    <div class="form-card">
                                        <h4>Employment Information</h4>
                                        <div class="row">
                                            @if($user->employee_type == '1')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="position" class="w-30">{{ __('Designation')}}</label>
                                                    {{ (isset($user->employee_position_name) && !empty($user->employee_position_name)) ? $user->employee_position_name : '' }}
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="standard_time" class="w-30">{{ __('Standard Time')}}</label>
                                                    {{ (isset($user->standard_name) && !empty($user->standard_name)) ? $user->standard_name : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="hire_date" class="w-30">{{ __('Hire Date')}}</label>
                                                    {{ (isset($user->hire_date) && !empty($user->hire_date)) ? $user->hire_date : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Status')}}</label>

                                                    @if($user->status == '0')
                                                    {{ __('Inactive') }}
                                                    @elseif($user->status == '1')
                                                    {{ __('Active') }}
                                                    @elseif($user->status == '2')
                                                    {{ __('Terminated') }}
                                                    @elseif($user->status == '3')
                                                    {{ __('Resigned') }}
                                                    @endif
                                                </div>
                                            </div>
                                             @if($user->status == '2' || $user->status == '3')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    @if($user->status == '2')
                                                    <label for="" class="w-30">{{ __('Termination Date')}}</label>
                                                    {{ (isset($user->termination_resign_date) && !empty($user->termination_resign_date) ? $user->termination_resign_date : '' )}}
                                                    @endif

                                                    @if($user->status == '3')
                                                    <label for="" class="w-30">{{ __('Resigned Date')}}</label>
                                                    {{ (isset($user->termination_resign_date) && !empty($user->termination_resign_date) ? $user->termination_resign_date : '' )}}
                                                    @endif
                                                </div>
                                            </div>
                                             @endif
                                             @if($user->status == '2' || $user->status == '3')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    @if($user->status == '2')
                                                    <label for="" class="w-40">{{ __('Termination Notice Period')}}</label>
                                                    {{ (isset($user->termination_resign_letter) && !empty($user->termination_resign_letter) ? $user->termination_resign_letter : '' )}}
                                                    @endif

                                                    @if($user->status == '3')
                                                    <label for="" class="w-30">{{ __('Resigned Letter')}}</label>
                                                    @if(isset($user->termination_resign_letter) && !empty($user->termination_resign_letter))
                                                    <a href="javascript:void(0)" class="preview_certificate_doc" data-src="{{ get_file_url($user->termination_resign_letter) }}">{{ substr($user->termination_resign_letter,16,7).'.pdf'}}</a>
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                             @endif

                                            @if($user->employee_type == '0')
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Role')}}</label>
                                                    {{ (isset($user_role->name) && !empty($user_role->name)) ? $user_role->name : '' }}
                                                </div>
                                            </div>
                                            @endif
                                             <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="probation_period_type" class="w-30">{{ __('Probation Period Type')}}</label>

                                                    @if($user->probation_period_type == '0')
                                                    {{ __('In days') }}
                                                    @elseif($user->probation_period_type == '1')
                                                    {{ __('In date range') }}
                                                    @else

                                                    @endif
                                                </div>


                                                @if($user->probation_period_type != '' && !is_null($user->probation_period_type))

                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Probation Period')}}</label>
                                                    @if($user->probation_period_type == '0')
                                                    {{ !empty($user->probation_period) ? $user->probation_period : ''}}
                                                    @endif

                                                    @if($user->probation_period_type == '1')
                                                    {{ (!empty($user->probation_start_date) && !empty($user->probation_end_date)) ? ($user->probation_start_date.'-'.$user->probation_end_date) : ''}}
                                                    @endif
                                                </div>

                                                @endif
                                             </div>


                                        </div>
                                    </div>
                                    
                                           
                                
                                    @endif
                                </div>
                                @if($pro_type !='profile')
                                <div class="col-sm-6">
                                    <div class="form-card">
                                        <h4>Address Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="address" class="w-30">{{ __('Address')}}</label>
                                                    {{ (isset($user->address) && !empty($user->address)) ? $user->address : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="country" class="w-30">{{ __('Country')}}</label>
                                                    {{ (isset($user->country_name) && !empty($user->country_name)) ? $user->country_name : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="state" class="w-30">{{ __('State')}}</label>
                                                    {{ (isset($user->state_name) && !empty($user->state_name)) ? $user->state_name : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="city" class="w-30">{{ __('City')}}</label>
                                                    {{ (isset($user->city_name) && !empty($user->city_name)) ? $user->city_name : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="pin" class="w-30">{{ __('Zipcode')}}</label>
                                                    {{ (isset($user->pin) && !empty($user->pin)) ? $user->pin : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-card">
                                        <h4>Banking Information</h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="bank_name" class="w-30">{{ __('Bank Name')}}</label>
                                                    {{ (isset($user->bank) && !empty($user->bank)) ? $user->bank : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Account #')}}</label>
                                                    {{ (isset($user->bank_account_no) && !empty($user->bank_account_no)) ? $user->bank_account_no : '' }}
                                                </div>
                                            </div>
                                            <!-- check for bank account number is set or not and then display copy of bank account -->
                                            @if(isset($user->bank_account_no) && !empty($user->bank_account_no))

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="" class="">{{ __('Bank Account Copy')}}</label>
                                                    @if(!empty($baCopies))
                                                    @foreach($baCopies as $baCopy)
                                                    @php
                                                    $extension = pathinfo($baCopy->file, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if($extension == 'pdf')
                                                    <a href="javascript:void(0)" class="preview_certificate_doc anchor-link" data-src="{{ get_file_url($baCopy->file) }}">{{ substr($baCopy->file,16,12).'.pdf' }}</a>
                                                    @else
                                                    <div class="magnific-img">
                                                        <a class="image-popup-vertical-fit" href="{{ get_file_url($baCopy->file) }}" title="{{ $baCopy->file }}">
                                                            <div class="copy_class">
                                                                <img src="{{ get_file_url($baCopy->file) }}" class="img-responsive"> &nbsp;
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <!--                                                <div class="col-sm-12"> <div class="form-group ">
                                                    <label for="" class="">{{ __('Bank Account Copy')}}</label>
                                                    @if(isset($user->bank_account_copy) && !empty($user->bank_account_copy))
                                                    @php
                                                    $extension = pathinfo($user->bank_account_copy, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if($extension == 'pdf')
                                                    <a href="javascript:void(0)" class="preview_certificate_doc anchor-link" data-src="{{ asset('/employee_images').'/'.$user->bank_account_copy }}">{{ $user->bank_account_copy }}</a>
                                                    @else
                                                    <div class="magnific-img">
                                                        <a class="image-popup-vertical-fit" href="{{ asset('/employee_images').'/'.$user->bank_account_copy }}" title="{{ $user->bank_account_copy }}">
                                                            <div class="copy_class">
                                                                <img src="{{ asset('/employee_images').'/'.$user->bank_account_copy }}" class="img-responsive">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endif
                                                    @endif
                                                </div> </div>-->

                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-card">
                                        <h4>Document Information</h4>
                                        <div class="row">
                                            @if(isset($user->passport_no) && !empty($user->passport_no))
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Passport Number')}}</label>
                                                    {{ (isset($user->passport_no) && !empty($user->passport_no)) ? $user->passport_no : '' }}
                                                </div>
                                            </div>
                                             <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-30">{{ __('Passport Expiry Date')}}</label>
                                                    {{ (isset($user->passport_expiry_date) && !empty($user->passport_expiry_date)) ? date('d/m/Y',strtotime($user->passport_expiry_date)) : '' }}
                                                </div>
                                            </div>

                                            
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">{{ __('Passport Copy')}}</label>
                                                    @if(isset($user->passport_copy) && !empty($user->passport_copy))
                                                    @php
                                                    $passport_copies = json_decode($user->passport_copy);
                                                    @endphp
                                                    @foreach($passport_copies as $passport_copy)
                                                    <div class="magnific-img">
                                                        <a class="image-popup-vertical-fit" href="{{ get_file_url($passport_copy) }}" title="{{ $passport_copy }}">
                                                            <div class="copy_class">
                                                                <img src="{{ get_file_url($passport_copy) }}" class="img-responsive"> &nbsp;
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                             @if(isset($user->nric_no) && !empty($user->nric_no))
                                            <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="nric_no" class="w-30">{{ __('NRIC No')}}</label>
                                                    {{ (isset($user->nric_no) && !empty($user->nric_no)) ? $user->nric_no : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label for="" class="">{{ __('NRIC Copy')}}</label>
                                                    @if(isset($user->nric_copy) && !empty($user->nric_copy))
                                                    <div class="magnific-img">
                                                        <a class="image-popup-vertical-fit" href="{{ get_file_url($user->nric_copy) }}" title="{{ $user->nric_copy }}">
                                                            <div class="copy_class">
                                                                <img src="{{ get_file_url($user->nric_copy) }}" class="img-responsive">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif


                                        </div>

                                    </div>

                                </div>
                                  <div class="col-sm-6">
                                    <div class="form-card">
                                        <div class="row">
                                             <div class="col-sm-12">
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-40">{{ __('Monthly Salary')}}</label>
                                                    {{ (isset($employee_salary_info->basic_salay) && !empty($employee_salary_info->basic_salay)) ? '$ '.$employee_salary_info->basic_salay : '' }}
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">{{ __('Fixed Allowances')}}</label>
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Accommodation')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fa_accommodation) && !empty($employee_salary_info->fa_accommodation) ? $employee_salary_info->fa_accommodation : '')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Telecommunication')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fa_telecommunications) && !empty($employee_salary_info->fa_telecommunications) ? $employee_salary_info->fa_telecommunications :'')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Food')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fa_food) && !empty($employee_salary_info->fa_food) ? $employee_salary_info->fa_food :'')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Transport')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fa_transport) && !empty($employee_salary_info->fa_transport) ? $employee_salary_info->fa_transport :'')}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="form-group">
                                                    <label for="leave_default">{{ __('Fixed Deduction')}}</label>
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Accommodation')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fd_accommodation) && !empty($employee_salary_info->fd_accommodation) ? $employee_salary_info->fd_accommodation : '')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Amenities')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fd_amenities) && !empty($employee_salary_info->fd_amenities) ? $employee_salary_info->fd_amenities : '')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="150px">
                                                                {{ __('Services')}}
                                                            <td>
                                                            <td>{{(isset($employee_salary_info->fd_services) && !empty($employee_salary_info->fd_services) ? $employee_salary_info->fd_services :'')}}</td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               
                                </div>
                                  <div class="col-sm-6">
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col-sm-12">

                                            
                                                <div class="form-group d-flex">
                                                    <label for="" class="w-40">{{ __('Leave Scheme')}}</label>
                                                    {{ (isset($leave_scheme_name) && !empty($leave_scheme_name) ? $leave_scheme_name : '')}}

                                                </div>
                                            </div>


                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">{{ __('Default Leave Credits')}}</label>
                                                    @if(!empty($leaves_detail))

                                                    <table width="100%">
                                                        @foreach($leaves_detail as $leave)
                                                        <tr>
                                                            <td>
                                                                {{ $leave->title }}
                                                            <td>
                                                            <td>{{$leave->no_of_leave}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </table>

                                                    @endif
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                           
                                <div class="col-sm-12">
                                    <div class="form-card">
                                        <h4>Log History</h4>
                                        <div class="row">
                                            <table id='history_log' class="table ">
                                                <thead>
                                                    <tr>
                                                        <th>Changed Date</th>
                                                        <th>Changed By</th>
                                                        <th>Event</th>
                                                        <th>Changed Fields</th>
                                                        <th>Value</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="">
                                                    @if(isset($history) && !empty($history))
                                                    @php
                                                    $flag = 0;
                                                    @endphp
                                                    @foreach($history as $value)
                                                    @php
                                                    $data_val = json_decode($value->data);

                                                    $count = 0;
                                                    $flag = $flag + 1;

                                                    @endphp
                                                    @if(!empty($data_val))
                                                    @php
                                                    $data_count = count((array)$data_val);
                                                    @endphp
                                                    @foreach($data_val as $key => $val)
                                                    @if($count == 0)
                                                    @if($key != 'updated_at')
                                                    <tr data-toggle="collapse" data-target="#accordion_{{$flag}}" class="clickable">
                                                        <td>{{ $value->created_at }}</td>
                                                        <td>{{ $value->first_name }}</td>
                                                        <td>{{ $value->action }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)); }}</td>
                                                        <td>{{ $val->old .' To '. $val->new }}</td>
                                                        <td>@if($data_count > 1)<a type='' class="btn on_click" value="Expand" data-val='0'><i class="ik ik-chevron-down" style="color:black;"></i></a>@endif</td>

                                                    </tr>
                                                    @php
                                                    $count = 1;
                                                    @endphp
                                                    @endif
                                                    @else
                                                    @if($key != 'updated_at')
                                                    <tr id="accordion_{{$flag}}" class="collapse">
                                                        <td></td>
                                                        <td>{{ $value->first_name }}</td>
                                                        <td>{{ $value->action }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)); }}</td>
                                                        <td>{{ $val->old .' To '. $val->new }}</td>
                                                        <td></td>

                                                    </tr>
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                    @endforeach

                                                    @else
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>No Data Available</td>
                                                        <td></td>
                                                        <td></td>

                                                    </tr>
                                                    @endif
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="" id="employee_general_edit" style="display:none">
                                <div class="col-sm-12 pt-20">
                                </div>
                                <form class="forms-sample" id="employeeGeneralEditForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                    <ul id="progressbar">
                                        <li class="active nav-item" id="general-info" style="text-align: center"><strong>GENERAL INFO</strong></li>
                                                <li class=" nav-item" id="remuneration" style="text-align: center"><strong>REMUNERATION</strong></li> 
                                     </ul>
                                     <fieldset>
                                    <div class="row">

                                        <div class="col-sm-4">
                                            <div class="form-card">
                                                <h4>Personal Information</h4>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                             <input type="hidden" id="check_edit" name="check_edit" value="1">
                                                            <label for="type">{{ __('Type')}}<span class="text-red">*</span></label>
                                                            <select id="type" name="type" class="form-control" onchange="display_permit_type_field(this.value)">
                                                                <option value="">{{ __('Select Type')}}</option>
                                                                <option value="0" {{ (isset($user->type) && ($user->type == '0')) ? 'selected' : '' }}>{{ __('Singaporean Citizen')}}</option>
                                                                <option value="1" {{ (isset($user->type) && ($user->type == '1')) ? 'selected' : '' }}>{{ __('Permanent Resident')}}</option>
                                                                <option value="2" {{ (isset($user->type) && ($user->type == '2')) ? 'selected' : '' }}>{{ __('Foreigners')}}</option>

                                                            </select>


                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 " id="agency" style="display: {{($user->type == '2') ? 'none': 'block'}}">
                                                        <div class="form-group">
                                                            <label for="select_agency">{{ __('Agency')}}</label>

                                                            {!! Form::select('select_agency', $agencies, (isset($user->agency) && ($user->agency) ? $user->agency : '' ),[ 'class'=>'form-control', 'placeholder' => 'Select Agency']) !!}
                                                        </div>
                                                    </div>
                                                     <div class="col-sm-12 " id="nationality" style="display: {{($user->type == '2') ? 'block': 'none'}}">
                                                    <div class="form-group">
                                                         <label for="nationality">{{ __('Nationality')}}</label>
                                                         <input type="text" class="form-control "  placeholder="Nationality" name="nationality" value="{{ (isset($user->nationality) && ($user->nationality) ? $user->nationality : '' )}}">

                                                     </div>
                                                 </div>
                                                    <div class="col-sm-12" id="permit_type_display" style="display: {{ (($user->type == '2') ? 'block': 'none') }}">
                                                        <div class="form-group">
                                                            <label for="permit_type" class="required">{{ __('Permit Type')}}</label>
                      
                                                            {!! Form::select('permit_type', $permitType, (isset($user->permit_type) && !empty($user->permit_type) ? $user->permit_type : ''),[ 'class'=>'form-control', 'placeholder' => 'Select Permit','id'=>"permit_type",'required'=>'required']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="first_name" class="required">{{ __('First Name')}}</label>
                                                            <input type="text" class="form-control " id="first_name" placeholder="First Name" name="first_name" value="{{ (isset($user->first_name) && !empty($user->first_name) ? $user->first_name : '')}}">
                                                            <div class="help-block with-errors"></div>


                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="last_name" class="required">{{ __('Last Name')}}</label>
                                                            <input type="text" class="form-control " id="last_name" placeholder="Last Name" name="last_name" value="{{ (isset($user->last_name) && !empty($user->last_name) ? $user->last_name : '') }}">
                                                        </div>
                                                    </div>
                                                    @if($user->employee_type == '0')
                                                    <div class="col-sm-12 d-none">
                                                        <div class="form-group">
                                                            <label for="password">{{ __('Password')}}</label>
                                                            <input type="password" class="form-control" id="password" placeholder="password" name="password" value="{{ old('password')}}">
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="phone" class="required">{{ __('Phone Number')}}</label>
                                                            <input type="text" class="form-control phone_pattern" id="phone" placeholder="XXXX XXXX" name="phone" maxlength="9" value="{{ (isset($user->phone) && !empty($user->phone) ? substr($user->phone, 0, 4).' '.substr($user->phone, 4, 4)  : '') }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="email" class="required">{{ __('Email')}}</label>
                                                            <input type="email" class="form-control {{($user->employee_type == '0') ? 'email_edit' : '' }}" id="email" placeholder="Email" name="email" autocomplete="off" value="{{ (isset($user->email) && !empty($user->email) ? $user->email : '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="dob" class="required">{{ __('DOB')}}</label>
                                                            <input type="text" class="form-control date_error" id="dob" placeholder="DD/MM/YYYY" name="dob" value="{{ (isset($user->dob) && !empty($user->dob) ? $user->dob : '') }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="gender">{{ __('Gender')}}</label>
                                                            <select id="gender" name="gender" class="form-control">
                                                                <option value="">{{ __('Select Gender')}}</option>
                                                                <option value="0" {{ (isset($user->gender) && ($user->gender == '0')) ? 'selected' : '' }}>{{ __('Male')}}</option>
                                                                <option value="1" {{ (isset($user->gender) && ($user->gender == '1')) ? 'selected' : '' }}>{{ __('Female')}}</option>
                                                                <!--                                    <option value="2">{{ __('Other')}}</option>-->

                                                            </select>
                                                        </div>
                                                    </div>


                                                </div>


                                                <div class="form-group">
                                                    <label>{{ __('Profile Image')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>
                                                    <input type="file" name="profile_image" class="file-upload-default" accept=".jpg,.jpeg,.png" id="profile_image">
                                                    <div class="input-group col-xs-12">
                                                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                        <span class="input-group-append">
                                                            <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                        </span>
                                                    </div>
                                                    <label id="profile_image_err" class="error"></label>

                                                </div>

                                                <div id="profile_image_display" style="display: {{ (isset($user->profile_image) && !empty($user->profile_image) ? 'block': 'none')}};">
                                                    <div class="form-group">
                                                        <div class="copy_class">
                                                            <img id="profile_image_show" src="{{ isset($user->profile_image) && !empty($user->profile_image) ? get_file_url($user->profile_image) : '' }}" class="img-responsive">
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
                                                            <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ (isset($user->address) && !empty($user->address) ? $user->address : '') }}">
                                                        </div>
                                                    </div>
                                                    <!--                                                    <div class="col-sm-12">
                                                                                                            <div class="form-group">
                                                                                                                <label for="country">{{ __('Country')}}</label>
                                                                                                                {!! Form::select('country', $countries, (isset($user->country) && ($user->country) ? $user->country : '' ),[ 'class'=>'form-control', 'placeholder' => 'Select Country','onchange'=> 'get_state(this.value);']) !!}
                                                                                                            </div>
                                                                                                        </div>-->
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="state">{{ __('State')}}</label>

                                                            {!! Form::select('state', $state, (isset($user->state) && ($user->state) ? $user->state : '' ),[ 'class'=>'form-control', 'placeholder' => 'Select State','onchange'=>"get_city(this.value)"]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="city">{{ __('City')}}</label>
                                                            <select id="city" name="city" class="form-control" onchange="">
                                                                <option value=""> Select City</option>
                                                                @if(isset($city) && !empty($city))
                                                                @foreach($city as $city_detail)
                                                                @if($city_detail->id == $user->city)
                                                                @php
                                                                $is_selected = 'selected';
                                                                @endphp
                                                                @else
                                                                @php
                                                                $is_selected = '';
                                                                @endphp
                                                                @endif
                                                                <option value="{{$city_detail->id}}" {{ $is_selected }}> {{$city_detail->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="zipcode">{{ __('Zipcode')}}</label>
                                                            <input type="text" class="form-control" id="zipcode" placeholder="Zipcode" minlength="6" maxlength="6" name="zipcode" value="{{ (isset($user->pin) && !empty($user->pin) ? $user->pin : '') }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
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
                                                            {!! Form::select('bank_name', $bank, (isset($user->bank_name) && !empty($user->bank_name) ? $user->bank_name : ''),[ 'class'=>'form-control', 'placeholder' => 'Select Bank','id'=>"bank_name"]) !!}   
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="bank_account_no">{{ __('Account #')}}</label>

                                                            @if((!empty($user->bank_account_no) && !empty($user->bank_account_copy)) || (empty($user->bank_account_no)))
                                                            @php
                                                            $display_val = "none";
                                                            @endphp
                                                            @else
                                                            @php
                                                            $display_val = "block";
                                                            @endphp
                                                            @endif
                                                            <div class="input-group">
                                                                <input type="text" class="form-control number" id="bank_account_no" placeholder="Account #" name="bank_account_no" onkeyup="display_bank_account_copy(this.value);"  value="{{ (isset($user->bank_account_no) && !empty($user->bank_account_no) ? $user->bank_account_no : '')}}"><span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="account_flag" style="color:red; display:{{$display_val}}" data-toggle="tooltip" title="Upload Bank Act Copy " data-placement="left"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" id="bank_account_copy_dis" style="display:{{ (isset($user->bank_account_no) && !empty($user->bank_account_no) ? 'block': 'none')}}">
                                                        <!--                                                        <div class="form-group">
                                                            <label for="bank_account_copy">{{ __('Bank Account Copy')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png,pdf)</span></label>
                                                            <input type="file" name="bank_account_copy" class="file-upload-default" accept=".jpg,.jpeg,.png,.pdf" id="bank_account_copy">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <span class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                                </span>
                                                            </div>
                                                            <p id="bank_account_copy_err" class="error"></p>

                                                        </div>-->

                                                        <div class="form-group">
                                                            <label for="ba_copy">{{ __('Bank Account Copy')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>
                                                            <input type="file" name="ba_copy[]" class="file-upload-default" accept=".jpg,.jpeg,.png,pdf" id="ba_copy" multiple>
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <span class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                                </span>
                                                            </div>
                                                            <p id="ba_copy_err" class="error"></p>

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12" id="list_ba" style="display: {{ ((isset($user->bank_account_no) && !empty($user->bank_account_no) && isset($baCopies) && !empty($baCopies)) ? 'block': 'none')}};">

                                                        @if(isset($baCopies) && !empty($baCopies))

                                                        @foreach($baCopies as $baCopy)
                                                        @php
                                                        $extension = pathinfo($baCopy->file, PATHINFO_EXTENSION);
                                                        @endphp
                                                        @if($extension == 'pdf')
                                                        <div class="copy_class">
                                                            <a href="{{ get_file_url($baCopy->file) }}" target="_blank">{{ substr($baCopy->file,16,6).'.'.$extension }}</a><span><button class="btn remove" data-id="{{ $baCopy->id }}" data-name="" type="button">x</button></span>
                                                        </div>
                                                        @else
                                                        <div class="copy_class">
                                                            <img src="{{ get_file_url($baCopy->file) }}" class="img-responsive"><span><button class="btn remove" data-id="{{ $baCopy->id }}" data-name="" type="button">x</button></span>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                        <div class="form-group" id="show_ba">
                                                        </div>
                                                    </div>
                                                    <!--                                                        <div class="form-group" id="show_bank">
                                                            @if(isset($user->bank_account_copy) && !empty($user->bank_account_copy))
                                                            @php
                                                            $extension = pathinfo($user->bank_account_copy, PATHINFO_EXTENSION);
                                                            @endphp
                                                            @if($extension == 'pdf')
                                                            <a href="{{ asset('/employee_images').'/'.$user->bank_account_copy }}">{{ $user->bank_account_copy }}</a>
                                                            @else
                                                            <div class="copy_class">
                                                                <img src="{{ asset('/employee_images').'/'.$user->bank_account_copy }}" class="img-responsive" />
                                                            </div>
                                                            @endif
                                                            @endif
                                                        </div>-->

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-card">
                                                <h4>Employment Information</h4>
                                                <div class="row">
                                                    <input type='hidden' id='employee_type' name="employee_type" value='{{ $user->employee_type  }}'>
                                                    @if($user->employee_type == '1')
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="position" class="required">{{ __('Designation')}}</label>
                                                            {!! Form::select('position', $positions, $user->position,[ 'class'=>'form-control','id'=>'position_val','placeholder' => 'Select']) !!}
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="standard_time" class="required">{{ __('Standard Time')}}</label>
                                                            {!! Form::select('standard_time', $standardtimes, (isset($user->standard_time) && ($user->standard_time) ? $user->standard_time : '' ),[ 'class'=>'form-control', 'placeholder' => 'Select Standard Time','id'=>'standard_time']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="hire_date" class="required">{{ __('Hire Date')}}</label>
                                                            <input type="text" class="form-control date_error" name="hire_date" id="hire_date" placeholder="DD/MM/YYYY" value="{{ (isset($user->hire_date) && !empty($user->hire_date) ? $user->hire_date : '')}}" readonly>

                                                        </div>
                                                    </div>


                                                    @if($user->employee_type == '0')
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="role" class="required">{{ __('Role')}}</label>
                                                            {!! Form::select('role', $roles, ($user_role->id),[ 'class'=>'form-control', 'placeholder' => 'Select Role','id'=>'role_val']) !!}
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="status" class="required">{{ __('Status')}}</label>

                                                            <select id="status" name="status" class="form-control" onchange="getTerminateOrResigned_field(this.value)">
                                                                <option value="">{{ __('Select Status')}}</option>
                                                                <option value="0" {{ (isset($user->status) && ($user->status == '0')) ? 'selected' : '' }}>{{ __('Inactive')}}</option>
                                                                <option value="1" {{ (isset($user->status) && ($user->status == '1')) ? 'selected' : '' }}>{{ __('Active')}}</option>
                                                                <option value="2" {{ (isset($user->status) && ($user->status == '2')) ? 'selected' : '' }}>{{ __('Terminated')}}</option>
                                                                <option value="3" {{ (isset($user->status) && ($user->status == '3')) ? 'selected' : '' }}>{{ __('Resigned')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="termination_resign" style="display: {{ (isset($user->status) && (($user->status == '3') || ($user->status == '2')) ? 'block': 'none')}}">
                                                        <div class="form-group">
                                                            <label for="termination_resign_date" id="text_date">{{ ($user->status == '2') ? 'Date' : 'Termination Date'}}</label>
                                                            <div class="date" data-provide="datepicker">
                                                                <input type="text" class="form-control" name="termination_resign_date" id="termination_resign_date" placeholder="Date" value="{{ (isset($user->termination_resign_date) && !empty($user->termination_resign_date) ? $user->termination_resign_date : '')}}" readonly>
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="period" style="display: {{ (isset($user->status) && ($user->status == '2') ? 'block': 'none')}}">
                                                        <div class="form-group">
                                                            <label for="termination_notice_period">{{ __('Termination Notice Preiod')}}</label>
                                                            <input type="text" class="form-control only_number" id="termination_notice_period" placeholder="Notice Period" name="termination_notice_period" value="{{ (isset($user->termination_resign_letter) && !empty($user->termination_resign_letter) ? $user->termination_resign_letter : '')}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" id="resign_letter" style="display:{{ ((isset($user->status) && ($user->status == '3')) ? 'block': 'none')}}">
                                                        <div class="form-group">
                                                            <label for="termination_resign_letter">{{ __('Resignation Letter')}}<span style="font-size: 9px;">(Only formats are allowed: pdf)</span></label>
                                                            <input type="file" name="termination_resign_letter" class="file-upload-default" accept=".pdf" id="termination_resign_letter">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                                <span class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                                </span>
                                                            </div>
                                                            <p id="resign_letter_err" class="error"></p>

                                                        </div>
                                                        <div id="resign_letter_display" style="display: {{ ((isset($user->status) && ($user->status == '3') && isset($user->termination_resign_letter) && !empty($user->termination_resign_letter)) ? 'block': 'none')}}">
                                                            <div class="form-group">
                                                                <div id="show_letter">
                                                                    <a id="resign_letter_image" href="{{ (isset($user->termination_resign_letter) && !empty($user->termination_resign_letter)) ? get_file_url($user->termination_resign_letter) : '' }}" class="img-responsive">{{ (isset($user->termination_resign_letter) && !empty($user->termination_resign_letter)) ? substr($user->termination_resign_letter,16,7).'.pdf' : ''}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                       <div class="form-group">
                                                            <label for="probation_period_type">{{ __('Probation Period Type')}}</label>
                                                            <select id="probation_period_type" name="probation_period_type" class="form-control" onchange="display_probation_field(this.value)">
                                                                <option value="">{{ __('Select Period Type')}}</option>
                                                                <option value="0" {{ (isset($user->probation_period_type) && ($user->probation_period_type == '0')) ? 'selected' : '' }}>{{ __('In Days')}}</option>
                                                                <option value="1" {{ (isset($user->probation_period_type) && ($user->probation_period_type == '1')) ? 'selected' : '' }}>{{ __('In Date Range')}}</option>
                                                            </select>
                                                        </div>

                                                        <div id="date_range_cal" style="display: {{ (isset($user->probation_period_type) && ($user->probation_period_type == '1') ? 'block' : 'none')}};">
                                                            <div class="form-group">
                                                                <label class="d-block">Select Probation Date Range</label>
                                                                <div class="input-group input-daterange">
                                                                    <input type="text" class="form-control" value="" name='date_range' id="date_range" readonly>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div id="period_pro" style="display: {{ (isset($user->probation_period_type) && ($user->probation_period_type == '0') ? 'block' : 'none')}};">
                                                            <div class="form-group">
                                                                <label for="probation_period">{{ __('Probation Period ')}}</label>
                                                                <select id="probation_period" name="probation_period" class="form-control">
                                                                    <option value="">{{ __('Select Probation Period')}}</option>
                                                                    <option value="15" {{ (isset($user->probation_period) && ($user->probation_period == '15')) ? 'selected' : '' }}>{{ __('15')}}</option>
                                                                    <option value="30" {{ (isset($user->probation_period) && ($user->probation_period == '30')) ? 'selected' : '' }}>{{ __('30')}}</option>
                                                                    <option value="45" {{ (isset($user->probation_period) && ($user->probation_period == '45')) ? 'selected' : '' }}>{{ __('45')}}</option>
                                                                    <option value="60" {{ (isset($user->probation_period) && ($user->probation_period == '60')) ? 'selected' : '' }}>{{ __('60')}}</option>
                                                                    <option value="75" {{ (isset($user->probation_period) && ($user->probation_period == '75')) ? 'selected' : '' }}>{{ __('75')}}</option>
                                                                    <option value="90" {{ (isset($user->probation_period) && ($user->probation_period == '90')) ? 'selected' : '' }}>{{ __('90')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        </div>

                                                </div>
                                            </div>
                                                    <div class="form-card">
                                                @php 
                                                $flag_pass = '';
                                                $flag_nric = '';
                                                @endphp
                                                    <button class="btn btn-primary" type="button" onclick="add_document_field()"><i class="ik ik-plus"></i>Document Information</button>
                                                    <div class="pt-15" id="document" >
                                                        @if(isset($user->passport_no) && !empty($user->passport_no))
                                                         <div class="document">
                                                        <div class="form-group">
                                                            <select name="document[]" class="form-control d_value valid" data-d_val="1" aria-invalid="false">
                                                                <option value="">Select</option>
                                                                <option value="0">Identification card </option>
                                                                <option value="1" selected>Passport</option></select>
                                                        </div>
                                                        <div class="document_attach">
                                                            @php 
                                                                $flag_pass = 1;
                                                            @endphp
                                                            <div class="form-group">
                                                                <label for="passport_no">{{ __('Passport Number')}}</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control alphanum" id="passport_no" placeholder="Passport No" name="passport_no"  value="{{ (isset($user->passport_no) && !empty($user->passport_no) ? $user->passport_no : '')}}" required><span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="passport_flag" style="color:red; display:{{ ((!empty($user->passport_no) && !empty($user->passport_copy)) || (empty($user->passport_no)) ? 'none': 'block')}}" data-toggle="tooltip" title="Upload Passport Copy" data-placement="left"></i></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                       <label for="expiry_date">Expiration Date</label><input type="text" class="form-control expiry_date " id="passport_expiry_date" placeholder="DD/MM/YYYY" name="expiry_date" required readonly value="{{(isset($user->passport_expiry_date) && !empty($user->passport_expiry_date) ? date('d/m/Y',strtotime($user->passport_expiry_date )): '')}}"></div>     
                                                   <div class="" id="passport_copy_dis" style="display:{{ (isset($user->passport_no) && !empty($user->passport_no) ? 'block': 'none')}}">
                                                    <div class="form-group">
                                                        <label for="passport_copy">{{ __('Password Copy')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>
                                                        <input type="file" name="passport_copy[]" class="file-upload-default" accept=".jpg,.jpeg,.png" id="passport_copy" multiple>
                                                        <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                            <span class="input-group-append">
                                                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                            </span>
                                                        </div>
                                                        <p id="passport_copy_err" class="error"></p>

                                                    </div>
                                                </div>
                                                            
                                               <div class="" id="list" style="display: {{ ((isset($user->passport_no) && !empty($user->passport_no) && isset($user->passport_copy) && !empty($user->passport_copy)) ? 'block': 'none')}};">

                                                    @if(!empty($user->passport_copy))
                                                    <input type="hidden" value="{{ $user->passport_copy }}" name="passport_file_val" id="passport_file_val"></input>
                                                    @php
                                                    $passport_files = json_decode($user->passport_copy);
                                                    @endphp
                                                    @if(count($passport_files) > 0)
                                                    @foreach($passport_files as $passport_file)
                                                    <div class="copy_class">
                                                        <img src="{{ get_file_url($passport_file) }}" class="img-responsive" /><span><button class="btn remove" data-id="0" data-name="{{ $passport_file }}" type="button">x</button><input type="hidden" value="{{ $passport_file }}" name="passport_copy_name[]"></input></span>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                    <div class="form-group" id="show">
                                                    </div>
                                                </div>
                                               </div>
                                                    </div>
                                                        @endif
                                                 @if(isset($user->nric_no) && !empty($user->nric_no))
                                                     <div class="document">
                                                        <div class="form-group">
                                                            <select name="document[]" class="form-control d_value valid" data-d_val="0" aria-invalid="false">
                                                                <option value="">Select</option>
                                                                <option value="0" selected>Identification card </option>
                                                                <option value="1">Passport</option></select>
                                                        </div>
                                                         <div class="document_attach">
                                                             @php  
                                                            $flag_nric = 0;
                                                            @endphp
                                                <div class="form-group">
                                                    <label for="nric_no">{{ __('NRIC No')}}</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="nric_no" placeholder="NRIC No" name="nric_no"  maxlength="9" value="{{ (isset($user->nric_no) && !empty($user->nric_no) ? $user->nric_no : '')}}" required><span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="nric_copy_flag" style="color:red; display:{{ ((!empty($user->nric_no) &&  !empty($user->nric_copy)) || (empty($user->nric_no)) ? 'none': 'block')}}" data-toggle="tooltip" title="Upload NRIC ID Card" data-placement="left"></i></span>
                                                    </div>
                                                </div>


                                                <div id="nric_copy_dis" style="display:{{ (isset($user->nric_no) && !empty($user->nric_no) ? 'block': 'none')}}">
                                                    <div class="form-group">
                                                        <label for="nric_copy">{{ __('NRIC Copy')}}<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>
                                                        <input type="file" name="nric_copy" class="file-upload-default" accept=".jpg,.jpeg,.png" id="nric_copy">
                                                        <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                            <span class="input-group-append">
                                                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                            </span>
                                                        </div>
                                                        <label id="nric_copy_err" class="error"></label>

                                                    </div>
                                                </div>

                                                <div id="nric_copy_display" style="display: {{ ((isset($user->nric_no) && !empty($user->nric_no) && isset($user->nric_copy) && !empty($user->nric_copy)) ? 'block': 'none')}}">
                                                    <div class="form-group">
                                                        <div class="copy_class">
                                                            <img id="nric_copy_show" src="{{ isset($user->nric_copy) && !empty($user->nric_copy) ? get_file_url($user->nric_copy) : '' }}" class="img-responsive">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                                 @endif
                                            </div>
                                        </div>
                                        </div>
                                       
                                        </div>
                                           <div class="form-group text-right">
                                            <input class="btn btn-success" type="submit" name="Save" value="Save"> <input type="button" name="next" class="next btn btn-primary" value="Next" />
                                             </div>
                                     </fieldset>
                                      <fieldset>
                                          <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="salary" class="required">{{ __('Monthly Salary')}}</label>
                                                            <div class="input-group"> <span class="input-group-append" role="right-icon">$ </span>
                                                                <input type="text" class="form-control decimal" id="salary" placeholder="Salary" name="salary" value="{{ (isset($employee_salary_info->basic_salay) && !empty($employee_salary_info->basic_salay) ? $employee_salary_info->basic_salay : '')}}">
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
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fa_accommodation) && !empty($employee_salary_info->fa_accommodation) ? $employee_salary_info->fa_accommodation : '')}}" name='fa_accommodation'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ __('Telecommunication')}}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fa_telecommunications) && !empty($employee_salary_info->fa_telecommunications) ? $employee_salary_info->fa_telecommunications :'')}}" name='fa_telecommunications'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ __('Food')}}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fa_food) && !empty($employee_salary_info->fa_food) ? $employee_salary_info->fa_food :'')}}" name='fa_food'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ __('Transport')}}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fa_transport) && !empty($employee_salary_info->fa_transport) ? $employee_salary_info->fa_transport :'')}}" name='fa_transport'></td>
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
                                                            {!! Form::select('leave_scheme', config('constants.leave_schema'), (isset($user->leave_scheme) && ($user->leave_scheme) ? $user->leave_scheme : '' ),[ 'class'=>'form-control', 'placeholder' => 'Select']) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="leave_default">{{ __('Default Leave Credits')}}</label>
                                                            <table width="100%">
                                                                @php
                                                                $arr_leave_id = array();
                                                                @endphp
                                                                @if(isset($leaves_detail) && !empty($leaves_detail))
                                                                @foreach($leaves_detail as $leave)
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ $leave->title }}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{$leave->no_of_leave}}" name='leave_default_{{$leave->leave_id}}'></td>
                                                                </tr>
                                                                @php
                                                                array_push($arr_leave_id,$leave->leave_id);
                                                                @endphp
                                                                @endforeach
                                                                @endif
                                                                @foreach($leaves as $leave_master)
                                                                @if(!in_array($leave_master->id,$arr_leave_id))
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ $leave_master->title }}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{$leave_master->no_of_leave}}" name='leave_default_{{$leave_master->id}}'></td>
                                                                </tr>
                                                                @endif
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
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fd_accommodation) && !empty($employee_salary_info->fd_accommodation) ? $employee_salary_info->fd_accommodation : '')}}" name='fd_accommodation'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ __('Amenities')}}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fd_amenities) && !empty($employee_salary_info->fd_amenities) ? $employee_salary_info->fd_amenities : '')}}" name='fd_amenities'></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="150px">
                                                                        {{ __('Services')}}
                                                                    <td>
                                                                    <td><input type="text" class="form-control decimal" value="{{(isset($employee_salary_info->fd_services) && !empty($employee_salary_info->fd_services) ? $employee_salary_info->fd_services :'')}}" name='fd_services'></td>
                                                                </tr>

                                                            </table>
                                                        </div>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                          </div>
                                            <div class="form-group text-right">
                                               <input type="button" name="previous" class="previous action-button-previous btn " value="Previous" />  <input class="btn btn-primary" type="submit" name="Save" value="Update">
                                            </div>
                                         </fieldset>
                                </form>

                            </div>
                        </div>
                        
                        <!-- general info section end here -->
                        <!-- check for hit from profile or not -->
                        @if($pro_type !='profile')
                        <!-- vs Employment contract section starts here -->
                        <div id="employee-contract-tab" role="tabpanel" aria-labelledby="employee-contract" class="tab tab-pane fade">
                            <ul class="nav nav-tabs sub-tabs tabs-list contract_class" id="mysubTab" role="tablist">
                                @can('manage_offer_letter')
                                <li class="nav-item ">
                                    <a class="nav-link active " href="#letter-of-offer-tab" data-toggle="tab" id="offer-letter" role="tab" aria-controls="letter-of-offer" aria-selected="true">Letter of Offer</a>
                                </li>
                                @endcan
                                @can('manage_employment_letter')
                                <li class="nav-item">
                                    <a class="nav-link" href="#letter-of-employment-tab" data-toggle="tab" id="employment-letter" role="tab" aria-controls="letter-of-employment" aria-selected="false">Letter of Employment</a>
                                </li>
                                @endcan
                            </ul>
                            <div class="tab-content">
                                <div id="letter-of-offer-tab" role="tabpanel" aria-labelledby="offer-letter" class="tab tab-pane fade show active">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="card">
                                                <div class="append-offer-letter-html">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="letter-of-employment-tab" role="tabpanel" aria-labelledby="employment-letter" class="tab tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="card">                                                
                                                <div class=" append-employment-letter-html"></div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- vs Employment contract section end here -->

                        <!-- certificate section start here-->
                        <div id="certificate-tab" role="tabpanel" aria-labelledby="certificate" class="tab tab-pane fade">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">

                                        <div class="card-filter">

                                            <div class="row searching_filters">
                                                <div class="col-md-1">
                                                    <h6 class="mt-10">Filters</h6>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('course', $courses, null,[ 'class'=>'form-control', 'placeholder' => 'All Course', 'onchange'=>'filter_certificate_list()', 'id' => 'course']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('course_type', $course_types, null,[ 'class'=>'form-control', 'placeholder' => 'All Course Type','onchange'=>'filter_certificate_list()', 'id' => 'course_type']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-outline-primary btn-rounded-20 pull-right" id="reset_employee_certificate_data">
                                                            <i class="ik ik-rotate-ccw "></i> Reset
                                                        </button>
                                                    </div>
                                                </div>
                                                @can('add_certificate')
                                                <div class="col-md-2 justify-content-between">
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-outline-primary btn-rounded-20 pull-right" href="javascript:void(0)" onclick="addCertificate()">
                                                            <i class="ik ik-plus "></i>Add New
                                                        </button>
                                                    </div>
                                                </div>
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="card">

                                            <div class="">
                                                <table id="certificate_listing_table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('##')}}</th>
                                                            <th>{{ __('Course Title')}}</th>
                                                            <th>{{ __('Type')}}</th>
                                                            <th>{{ __('Upload Files')}}</th>
                                                            <th>{{ __('Action')}}</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- certificate section end here-->
                        <!-- Employee permit Start here -->
                        <div id="employee-permit-tab" role="tabpanel" aria-labelledby="employee-permit" class="tab tab-pane fade">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">

                                        <div class="card-filter">

                                            <div class="row searching_filters">
                                                <div class="col-md-1">
                                                    <h6 class="mt-10">Filters</h6>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="input-group input-daterange">
                                                            <input type="text" class="form-control" value="" name='permit_date_range' id="permit_date_range" placeholder="Expiry Date">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-outline-primary btn-rounded-20 pull-right" id="reset_employee_permit_data">
                                                            <i class="ik ik-rotate-ccw "></i> Reset
                                                        </button>
                                                    </div>
                                                </div>
                                                @can('add_employee_permit')
                                                @if($employee_permit_tab < 3)
                                                @php 
                                                  $add_class = '';
                                                  @endphp
                                                  @else
                                                  @php 
                                                  $add_class = 'd-none';
                                                  @endphp
                                                 @endif
                                                <div class="col-md-7 justify-content-between {{$add_class}}" id="permit_add">
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-outline-primary btn-rounded-20 pull-right" href="javascript:void(0)" onclick="addPermit('0')">
                                                            <i class="ik ik-plus "></i>Add New
                                                        </button>
                                                    </div>
                                            </div>
                                           
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header justify-content-between use-only-border">
                                        </div>
                                        <div class="">
                                            <table id="permit_listing_table" class="table">
                                                <thead>
                                                    <tr>
                                                        <th >{{ __('##')}}</th>
                                                        <th>{{ __('Type')}}</th>
                                                        <th>{{ __('Expiry Date')}}</th>
                                                        <th>{{ __('Type')}}</th>
                                                        <th>{{ __('Action')}}</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end -->
                    <!-- bonus info section start here -->
                    <div id="bonus-tab" class="tab tab-pane fade" role="tabpanel" aria-labelledby="bonus">
                        <ul class="nav nav-tabs sub-tabs tabs-list bonus_class" id="mysubTab" role="tablist">
                            @can('manage_employee_bonus_added')
                            <li class="nav-item ">
                                <a class="nav-link active " href="#bonus-added" data-toggle="tab" id="bonus1" role="tab" aria-controls="bonus-added" aria-selected="true">Bonus Added</a>
                            </li>
                            @endcan
                            @can('manage_employee_loan_added')
                            <li class="nav-item">
                                <a class="nav-link" href="#loan-added" data-toggle="tab" id="loan" role="tab" aria-controls="loan-added" aria-selected="false">Loan Added</a>
                            </li>
                            @endcan
                            @can('manage_employee_advance_taken')
                            <li class="nav-item">
                                <a class="nav-link" href="#advance-taken" data-toggle="tab" id="advance" role="tab" aria-controls="advance-taken" aria-selected="false">Advance Taken</a>
                            </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                            <!-- Bonus listing info section starts here -->
                            <div id="bonus-added" role="tabpanel" aria-labelledby="bonus1" class="tab tab-pane fade show active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">

                                            <div class="card-filter">
                                                <form class="forms-sample" action="{{url('bonus/export')}}">
                                                    <input type="hidden" name='id' value='{{$user->id}}'>
                                                    <div class="row searching_filters">
                                                        <div class="col-md-1">
                                                            <h6 class="mt-10">Filters</h6>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group input-daterange">
                                                                {!! Form::text('bonus_created_date', '',[ 'class'=>'form-control', 'placeholder' => 'Search by created date','id'=> 'bonus_created_date','autocomplete'=>'off']) !!}
                                                                <input type="hidden" name="bonus_start_date" id="bonus_start_date" value="">
                                                                <input type="hidden" name="bonus_end_date" id="bonus_end_date" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-outline-primary btn-rounded-20 pull-right" id=""><i class="ik ik-download"></i>
                                                                    Download Report
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <table id="bonus_listing_table" class="table">
                                            <thead>
                                                <tr>
                                                    <th >{{ __('##')}}</th>
                                                    <th>{{ __('Amount')}}</th>
                                                    <th>{{ __('Date Added')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- bonus listing section end here -->
                            <!-- loan listing info section starts here -->
                            <div id="loan-added" class="tab tab-pane fade" role="tabpanel" aria-labelledby="loan">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-filter">
                                                <form class="forms-sample" action="{{url('loan/export')}}">
                                                    <input type="hidden" name='id' value='{{$user->id}}'>
                                                    <div class="row searching_filters">
                                                        <div class="col-md-1">
                                                            <h6 class="mt-10">Filters</h6>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group input-daterange">
                                                                {!! Form::text('loan_created_date', '',[ 'class'=>'form-control', 'placeholder' => 'Search by created date','id'=> 'loan_created_date','autocomplete'=>'off']) !!}
                                                                <input type="hidden" name="loan_start_date" id="loan_start_date" value="">
                                                                <input type="hidden" name="loan_end_date" id="loan_end_date" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-outline-primary btn-rounded-20 pull-right" id=""><i class="ik ik-download"></i>
                                                                    Download Report
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <table id="loan_listing_table" class="table">
                                            <thead>
                                                <tr>
                                                    <th >{{ __('##')}}</th>
                                                    <th>{{ __('Loan Amount')}}</th>
                                                    <th>{{ __('Terms')}}</th>
                                                    <th>{{ __('Deducation each month')}}</th>
                                                    <th>{{ __('Progress')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                            <!-- loan listing section end here -->
                            <!-- advance taken section start here -->
                            <div id="advance-taken" class="tab tab-pane fade" role="tabpanel" aria-labelledby="advance">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card">
                                            <div class="card-filter">
                                                <form class="forms-sample" action="{{url('advance/export')}}">
                                                    <input type="hidden" name='id' value='{{$user->id}}'>
                                                    <div class="row searching_filters">
                                                        <div class="col-md-1">
                                                            <h6 class="mt-10">Filters</h6>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="input-group input-daterange">
                                                                {!! Form::text('advance_created_date', '',[ 'class'=>'form-control', 'placeholder' => 'Search by created date','id'=> 'advance_created_date','autocomplete'=>'off']) !!}
                                                                <input type="hidden" name="advance_start_date" id="advance_start_date" value="">
                                                                <input type="hidden" name="advance_end_date" id="advance_end_date" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-outline-primary btn-rounded-20 pull-right" id=""><i class="ik ik-download"></i>
                                                                    Download Report
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>

                                            <table id="advance_listing_table" class="table">
                                                <thead>
                                                    <tr>
                                                        <th >{{ __('##')}}</th>
                                                        <th>{{ __('Amount')}}</th>
                                                        <th>{{ __('Date Added')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- advance section end here -->
                        </div>
                    </div>
                    <!-- bonus listing info section end here -->

                    <!-- correspondance section -->
                    <div id="correspondance-tab" class="tab tab-pane fade" role="tabpanel" aria-labelledby="correspondance">
                        <ul class="nav nav-tabs sub-tabs tabs-list corres_class" id="mysubTab" role="tablist">
                            @can('manage_warning_letter')
                            <li class="nav-item ">
                                <a class="nav-link " href="#warning-letter-tab" data-toggle="tab" id="warning-letter" role="tab" aria-controls="warning-letter" aria-selected="true">Warning Letter</a>
                            </li>
                            @endcan
                            @can('manage_increment_letter')
                            <li class="nav-item">
                                <a class="nav-link" href="#increment-letter-tab" data-toggle="tab" id="increment-letter" role="tab" aria-controls="increment-letter" aria-selected="false">Increment Letter</a>
                            </li>
                            @endcan
                            @can('manage_other_doc')
                            <li class="nav-item">
                                <a class="nav-link" href="#other-doc" data-toggle="tab" id="advance" role="tab" aria-controls="other-doc" aria-selected="false">Other Doc</a>
                            </li>
                            @endcan
                        </ul>
                        <div class="tab-content">
                                @can('manage_warning_letter')
                            <div id="warning-letter-tab" role="tabpanel" aria-labelledby="warning-doc" class="tab tab-pane fade show active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header justify-content-between no-border pr-0">
                                                <h3></h3>
                                                @can('add_warning_letter')
                                                <a class="btn btn-outline-primary btn-rounded-20 pull-right generate-warning-letter" href="javascript:void(0);">
                                                    Generate Warning Letter
                                                </a>
                                                @endcan
                                            </div>
                                            <div class="">
                                                <table id="warning_listing_table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th >{{ __('##')}}</th>
                                                            <th>{{ __('Warning Letter')}}</th>
                                                            <th>{{ __('Generated Date')}}</th>
                                                            <th>{{ __('Upload Endorsed Doc')}}</th>
                                                            <th>{{ __('Status')}}</th>
                                                            <th>{{ __('Action')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endcan
                            <div id="increment-letter-tab" role="tabpanel" aria-labelledby="increment-letter" class="tab tab-pane fade show ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header justify-content-between no-border pr-0">
                                                <h3></h3>
                                                @can('add_increment_letter')
                                                <a class="btn btn-outline-primary btn-rounded-20 pull-right generate-increment-letter" href="javascript:void(0);">
                                                    Generate Increment Letter
                                                </a>
                                                @endcan
                                            </div>
                                            <div class="">
                                                <table id="increment_listing_table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th >{{ __('##')}}</th>
                                                            <th>{{ __('Increment Letter')}}</th>
                                                            <th>{{ __('Generated Date')}}</th>
                                                            <th>{{ __('Upload Endorsed Doc')}}</th>
                                                            <th>{{ __('Status')}}</th>
                                                            <th>{{ __('Action')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Bonus listing info section starts here -->
                            <div id="other-doc" role="tabpanel" aria-labelledby="other-doc" class="tab tab-pane fade show ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">

                                            <div class="card-filter">
                                                <div class="row searching_filters">
                                                    <div class="col-md-1">
                                                        <h6 class="mt-10">Filters</h6>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="input-group input-daterange">
                                                                <input type="text" class="form-control" value="" name='doc_date_range' id="doc_date_range" placeholder="Generated Date">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-outline-primary btn-rounded-20 pull-right" onclick="resetOtherDocFilter();">
                                                                <i class="ik ik-rotate-ccw "></i> Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @can('add_other_doc')
                                                    <div class="col-md-7 justify-content-between">
                                                        <div class="form-group text-right">
                                                            <button class="btn btn-outline-primary btn-rounded-20 pull-right" href="javascript:void(0)" onclick="addDoc()">
                                                                <i class="ik ik-plus "></i>Add New
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <table id="doc_listing_table" class="table">
                                                <thead>
                                                    <tr>
                                                        <th >{{ __('##')}}</th>
                                                        <th>{{ __('Document Name')}}</th>
                                                        <th>{{ __('Generated Date')}}</th>
                                                        <th>{{ __('Action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- docs listing section end here -->

                        </div>
                    </div>
                    <!--corresponding section end here -->

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- bonus modal start-->
<div class="modal fade edit-layout-modal pr-0 " id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-300" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalLabel">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addEditForm">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label class="d-block">Employee Type</label>
                        <select class="form-control" name="employee_type" id="employee_type">
                            <option value="">Select Type</option>
                            <option value="0">Singaporean Citizen</option>
                            <option value="1">Permanent Resident</option>
                            <option value="2">Foreigners</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Select Employees</label>
                        <select class="form-control" name="employee_id" id="employee-id" multiple>
                            <option value="">Select Employees</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Enter Bonus Amount</label>
                        <input type="text" name="amount" class="form-control" placeholder="Enter Amount">
                    </div>
                    <div class="form-group">
                        <label class="d-block">Add Remarks</label>
                        <textarea class="form-control" placeholder="Enter Title" name="remark"></textarea>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="Save" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- bonus modal end-->

<!-- certificate modal start-->
<div class="modal fade edit-layout-modal pr-0 " id="addEditCertificateModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditCertificateModalLabel">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addEditCertificateForm">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="employee_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label class="d-block required">Course</label>
                        {!! Form::select('certificate_title', $courses, null,[ 'class'=>'form-control', 'placeholder' => 'Select Course','onchange' =>'set_course_type(this.value)']) !!}
                    </div>

                    <div class="form-group">
                        <label class="d-block">Course Type</label>
                        <input type="text" name="certificate_type" class="form-control" placeholder="Course Type" readonly>
                        <input type="hidden" name="certificate_type_id" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="certificate_file">{{ __('Upload File')}}<span style="font-size: 9px;">(Only formats are allowed : jpeg,pdf)</span></label>
                        <input type="file" name="certificate_file" class="file-upload-default" accept=".jpeg,.pdf" id="certificate_file">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                            </span>
                        </div>
                        <p id="certificate_file_err" class="error"></p>

                    </div>

                    <div id="list_cer" style="display: none;">
                        <div class="form-group" id="show_cer">

                        </div>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary submit_button" type="submit" name="Save" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- certificate modal end-->

<!-- certificate pdf display modal -->
<div id="certificate-doc-preview-modal" class="modal fade black-full-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header no-border">

                <button type="button" class="close" data-dismiss="modal">×</button>

            </div>
            <div class="modal-body">
            </div>

        </div>

    </div>
</div>
<!-- end -->
<!-- Permit Modal -->
<div class="modal fade edit-layout-modal pr-0 " id="addEditPermitModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-70" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditPermitModalLabel">Add</h5>&nbsp;
                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary d-none" id="renew" onclick="showrenewPermit()">Renew permit</button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <form id="addEditPermitForm">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="employee_id" value="{{ $user->id }}">
                    <input type="hidden" name="is_renew">
                    <div class="form-group" "id"="employee_permit_type">
                        <label class=" required">Type</label>
                        {!! Form::select('type', config('constants.employee_permit_type'), null,[ 'class'=>'form-control', 'placeholder' => 'Select Type']) !!}
                    </div>

                    <div class="form-group">
                        <label class="d-block required">Expiry Date</label>
                        <input type="text" name="expiry_date" class="form-control date_error" placeholder="Expiry Date" id="expiry_date" readonly>

                    </div>

                    <div id="new_permit">
                        <div class="form-group ">
                             <div class="d-flex">
                            <label for="ipa_employee" class="d-block w-30 required">IPA Employee <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-ipa_employee-logo upload-permit" data-name="ipa_employee" style="cursor:pointer"></i>
                             <i class="ik ik-edit text-green upload-permit change-ipa_employee-logo d-none" data-name="ipa_employee" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                             </div>
                            <div class="">
                                {!! Form::hidden('logo', '', [ 'class'=>'d-none'])!!}
                                {!! Form::file('ipa_employee', [ 'class'=>'form-control d-none date_error', 'accept' => '.pdf','id'=>'ipa_employee']) !!}
                               
                                <div class="ipa_employee-pdf-preview"> 
                                    <iframe id="ipa_employee-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>  
                                </div>
                            </div>
                            <div class="err_ipa_employee pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="d-flex">
                            <label for="ipa_employer" class="d-block w-30 required">IPA Employer <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-employer-logo upload-permit" data-name="ipa_employer" style="cursor:pointer"></i>
                             <i class="ik ik-edit text-green upload-permit change-employer-logo d-none" data-name="ipa_employer" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                            </div>
                           
                            <div class="">
                                {!! Form::hidden('logo', '', [ 'class'=>'d-none'])!!}
                                {!! Form::file('ipa_employer', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'ipa_employer']) !!}
                                <div class="employer-pdf-preview">
                                     
                                    <iframe id="employer-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>      
                                </div>
                            </div>
                            <div class="err_ipa_employer pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="d-flex">
                            <label for="permit_application" class="d-block w-30 required">Permit Application Form <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-permit_application-logo upload-permit" data-name="permit_application" style="cursor:pointer"></i>
                             <i class="ik ik-edit text-green upload-permit change-permit_application-logo d-none" data-name="permit_application" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                            </div>
                            
                            <div class="">
                                {!! Form::file('permit_application', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'permit_application']) !!}
                                <div class="permit_application-pdf-preview">
                                    <iframe id="permit_application-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
                                </div>
                            </div>
                            <div class="err_permit_application pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>

                    </div>
                    <div class="form-group ">
                        <div class="d-flex">
                        <label for="salary_declaration" class="d-block w-30 required">Salary Declaration <small><i>(Accepts: pdf)</small></i></label>
                        <i class="ik ik-upload-cloud f-16 text-green upload-salary_declaration-logo upload-permit" data-name="salary_declaration" style="cursor:pointer"></i>
                        <i class="ik ik-edit text-green upload-permit change-salary_declaration-logo d-none" data-name="salary_declaration" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                        </div>
                         
                        <div class="">
                            {!! Form::file('salary_declaration', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'salary_declaration']) !!}
                            <div class="salary_declaration-pdf-preview">
                                <iframe id="salary_declaration-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
    
                            </div>
                        </div>
                        <div class="err_salary_declaration pl-2" style="display: none;">
                            <p style='color:red'>Only formats allowed is Pdf</p>
                        </div>
                    </div>
                    <div class="form-group ">
                         <div class="d-flex">
                        <label for="issuance_letter" class="d-block w-30 required">Issuance Letter <small><i>(Accepts: pdf)</small></i></label>
                        <i class="ik ik-upload-cloud f-16 text-green upload-issuance_letter-logo upload-permit" data-name="issuance_letter" style="cursor:pointer"></i>
                         <i class="ik ik-edit text-green upload-permit change-issuance_letter-logo d-none" data-name="issuance_letter" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                         </div>
                        
                        <div class="">
                            {!! Form::file('issuance_letter', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'issuance_letter']) !!}
                            <div class="issuance_letter-pdf-preview">
                                <iframe id="issuance_letter-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
                                <i class="ik ik-trash text-red remove-permit-pdf remove-issuance_letter-logo d-none" data-toggle="tooltip" title="Remove logo" style="cursor:pointer" data-name="issuance_letter" data-val="issuance_letter"></i>
                            </div>
                        </div>
                        <div class="err_issuance_letter pl-2" style="display: none;">
                            <p style='color:red'>Only formats allowed is Pdf</p>
                        </div>
                    </div>
                    <div id="renew_permit" class="d-none">

                        <div class="form-group ">
                            <div class="d-flex">
                            <label for="renewal_notice" class="d-block w-30 required">Renewal Notice <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-renewal_notice-logo upload-permit" data-name="renewal_notice" style="cursor:pointer"></i>
                        
                            <i class="ik ik-edit text-green upload-permit change-renewal_notice-logo d-none" data-name="renewal_notice" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                           </div>
                            <div class="">
                                {!! Form::file('renewal_notice', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'renewal_notice']) !!}
                                <div class="renewal_notice-pdf-preview">
                                    
                                    <iframe id="renewal_notice-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
                                    <i class="ik ik-trash text-red remove-permit-pdf remove-renewal_notice-logo d-none" data-toggle="tooltip" title="Remove" style="cursor:pointer" data-name="renewal_notice" data-val="renewal_notice"></i>
                                      
                                </div>
                            </div>
                            <div class="err_renewal_notice pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="d-flex"> 
                            <label for="work_permit_application" class="d-block w-30 required">Work Permit Application <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-work_permit_application-logo upload-permit" data-name="work_permit_application" style="cursor:pointer"></i>
                            
                            <i class="ik ik-edit text-green upload-permit change-work_permit_application-logo d-none" data-name="work_permit_application" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                            </div>
                            <div class="">
                                {!! Form::file('work_permit_application', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'work_permit_application']) !!}
                                <div class="work_permit_application-pdf-preview">
                                     
                                    <iframe id="work_permit_application-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
                                    <i class="ik ik-trash text-red remove-permit-pdf remove-work_permit_application-logo d-none" data-toggle="tooltip" title="Remove" style="cursor:pointer" data-name="work_permit_application" data-val="work_permit_application"></i>
          
                                </div>
                            </div>
                            <div class="err_work_permit_application pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>
                        <div class="form-group ">
                             <div class="d-flex">
                            <label for="renewed_permit_docs" class="d-block w-30 required">Other Docs <small><i>(Accepts: pdf)</small></i></label>
                            <i class="ik ik-upload-cloud f-16 text-green upload-renewed_permit_docs-logo upload-permit" data-name="renewed_permit_docs" style="cursor:pointer"></i>
                          
                             <i class="ik ik-edit text-green upload-permit change-renewed_permit_docs-logo d-none" data-name="renewed_permit_docs" data-toggle="tooltip" title="Change File" style="cursor:pointer"></i>
                           </div>
                             <div class="">
                                {!! Form::file('renewed_permit_docs', [ 'class'=>'form-control d-none ipa', 'accept' => '.pdf','id'=>'renewed_permit_docs']) !!}
                                <div class="renewed_permit_docs-pdf-preview">    
                                    <iframe id="renewed_permit_docs-pdf-preview" src="" frameborder="0" width="95%" style="height:300px" class="d-none"></iframe>
                                    <i class="ik ik-trash text-red remove-permit-pdf remove-renewed_permit_docs-logo d-none" data-toggle="tooltip" title="Remove" style="cursor:pointer" data-name="renewed_permit_docs" data-val="renewed_permit_docs"></i>
                                </div>
                            </div>
                            <div class="err_renewed_permit_docs pl-2" style="display: none;">
                                <p style='color:red'>Only formats allowed is Pdf</p>
                            </div>
                        </div>
                    </div>
                    <div class=" d-none" id="permit_history_log">
                        <div class="card">
                            <div class="card-header">
                                <b><a class="card-link" data-toggle="collapse" href="#collapseOne">
                                        History Log
                                    </a></b>
                            </div>
                            <div id="collapseOne" class="collapse">

                            </div>
                        </div>
                    </div>

                    </body>

                    </html>


                    <div class="form-group">
                        <input class="btn btn-primary submit_button" type="submit" name="Save" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end -->
<!-- Other Doc Modal -->
<div class="modal fade edit-layout-modal pr-0 " id="addEditOtherDocModal" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-40" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditOtherDocModalLabel">Add</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <form id="addEditOtherDocForm">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="employee_id" value="{{ $user->id }}">


                    <div class="form-group d-flex">
                        <label for="doc_file" class="d-block w-30 required">File<small><i>&nbsp;(Accepts: pdf, doc, docx)</small></i></label>
                        <div class="d-flex">
                            <input type="hidden" name="other_doc_file_name">
                            {!! Form::file('doc_file', [ 'class'=>'form-control d-none date_error', 'accept' => '.pdf,.doc,.docx']) !!}
                            <div class="doc_file-pdf-preview">
                                <a id="doc_file-pdf-preview" href=""></a>
                                <i class="ik ik-trash text-red remove-doc_file-logo d-none remove-permit-pdf" data-toggle="tooltip" title="Remove" style="cursor:pointer" data-name="doc_file" data-val="doc_file"></i>
                                <i class="ik ik-edit text-green upload-permit change-doc_file-logo d-none" data-name="doc_file" data-toggle="tooltip" title="Change Document" style="cursor:pointer"></i>
                                <!--<i class="ik ik-upload-cloud text-green upload-logo upload-permit upload-doc_file-logo  " data-toggle="tooltip" title="Upload" style="cursor:pointer" data-name="doc_file"></i> -->
                                <div class="input-group col-xs-12 upload-doc_file-logo">
                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Choose File">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse upload-permit btn " type="button" data-name="doc_file">{{ __('Choose File')}}</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary submit_button" type="submit" name="Save" value="Upload">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end HT -->
<!-- vs for offer letter modal -->
<div id="generate-offer-letter-modal" class="modal fade pr-0 letter-full-view" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-100" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">
                <div class="append-offer-letter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- vs For endorsed document preview modal -->
<div id="endorsed-doc-preview-modal" class="modal fade black-full-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header no-border">

                <button type="button" class="close" data-dismiss="modal">×</button>

            </div>
            <div class="modal-body">
            </div>

        </div>

    </div>
</div>
<!-- end -->

<!-- vs For offer letter preview modal -->
<div id="offer-letter-preview-modal" class="modal fade black-full-modal letter-view-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">

            </div>

        </div>

    </div>
</div>
<!-- end -->
<!-- vs for employment letter modal -->
<div id="generate-employment-letter-modal" class="modal fade pr-0 letter-full-view" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-100" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">
                <div class="append-employment-letter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end -->
<!-- vs For employment letter preview modal -->
<div id="employment-letter-preview-modal" class="modal fade black-full-modal letter-view-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>

            </div> -->
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">

            </div>

        </div>

    </div>
</div>
<!-- end -->

<!-- vs for generate warning letter modal -->
<div id="generate-warning-letter-modal" class="modal fade pr-0 letter-full-view" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-100" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">
                <div class="append-warning-letter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- vs For warning letter preview modal -->
<div id="warning-letter-preview-modal" class="modal fade black-full-modal letter-view-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">

            </div>
        </div>

    </div>
</div>
<!-- end -->

<!-- vs for generate increment letter modal -->
<div id="generate-increment-letter-modal" class="modal fade pr-0 letter-full-view" tabindex="-1" role="dialog" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog w-100" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">
                <div class="append-increment-letter"></div>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<!-- vs For increment letter preview modal -->
<div id="increment-letter-preview-modal" class="modal fade black-full-modal letter-view-modal" role="dialog" data-backdrop="static" data-keyboard="false" style="z-index:9999">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <div class="modal-body">

            </div>
        </div>

    </div>
</div>
<!-- end -->

<!-- push external js -->
@push('script')
<script>
    //for probation date range..code  by harshita
    var probation_start_date = '{{$user->probation_start_date}}';
    var probation_end_date = '{{$user->probation_end_date}}';
    //end
    // filter by date range for bonus
    $('#bonus_created_date').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'

        },
    });
    $('input[name="bonus_created_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var bonus_start_date = picker.startDate.format('YYYY-MM-DD');
        var bonus_end_date = picker.endDate.format('YYYY-MM-DD');
        $("#bonus_start_date").val(picker.startDate.format('YYYY-MM-DD'));
        $("#bonus_end_date").val(picker.endDate.format('YYYY-MM-DD'));
        var dataSearch = {
            "bonus_start_date": bonus_start_date,
            "bonus_end_date": bonus_end_date
        };
        get_bonus_list(dataSearch);
    });
    $('input[name="bonus_created_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $("#bonus_start_date").val('');
        $("#bonus_end_date").val('');
        get_bonus_list();
    });
    // filter by date range for loan
    $('#loan_created_date').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'

        },
    });
    $('input[name="loan_created_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var loan_start_date = picker.startDate.format('YYYY-MM-DD');
        var loan_end_date = picker.endDate.format('YYYY-MM-DD');
        $("#loan_start_date").val(picker.startDate.format('YYYY-MM-DD'));
        $("#loan_end_date").val(picker.endDate.format('YYYY-MM-DD'));
        var dataSearch = {
            "loan_start_date": loan_start_date,
            "loan_end_date": loan_end_date
        };
        get_loan_list(dataSearch);
    });
    $('input[name="loan_created_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $("#loan_start_date").val('');
        $("#loan_end_date").val('');
        get_loan_list();
    });
    // filter by date range for advance
    $('#advance_created_date').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'

        },
    });
    $('input[name="advance_created_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var advance_start_date = picker.startDate.format('YYYY-MM-DD');
        var advance_end_date = picker.endDate.format('YYYY-MM-DD');
        $("#advance_start_date").val(picker.startDate.format('YYYY-MM-DD'));
        $("#advance_end_date").val(picker.endDate.format('YYYY-MM-DD'));
        var dataSearch = {
            "advance_start_date": advance_start_date,
            "advance_end_date": advance_end_date
        };
        get_advance_list(dataSearch);
    });
    $('input[name="advance_created_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
         $("#advance_start_date").val('');
        $("#advance_end_date").val('');
        get_advance_list();
    });
</script>
<!--server side table script start-->
<script>
     var document_array = [];
    //listing data table
    $(document).ready(function() {
       document_array.push("{{$flag_pass}}");
       document_array.push("{{$flag_nric}}");
       console.log(document_array);
        //set date picker   
        $('#hire_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy'
        });
        //set date picker   
        $('#dob').datepicker({
            uiLibrary: 'bootstrap4',
            maxDate: new Date(),
            format: 'dd/mm/yyyy'
        });
       
        $('#termination_resign_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy', 
        });
      
        if (probation_start_date != '' && probation_end_date != '') {
            $('#date_range').daterangepicker({
                startDate: probation_start_date, // after open picker you'll see this dates as picked
                endDate: probation_end_date,
                locale: {
                    format: 'DD/MM/YYYY',
                }
            }, function(start, end, label) {

            }).val(probation_start_date + " - " + probation_end_date);
        } else {
            $('#date_range').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                }
            })
        }
        //set date picker   
        $('#expiry_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy'
        });
        
        $(document).find('#passport_expiry_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy', 
        }); 


        $('#doc_date_range').daterangepicker({
            "autoUpdateInput": false,
        });

        $('input[name="doc_date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            var startDate = picker.startDate.format('DD/MM/YYYY');
            var endDate = picker.endDate.format('DD/MM/YYYY');

            var dataSearch = {
                "startDate": startDate,
                "endDate": endDate,
            };
            get_otherdoc_list(dataSearch);
        });

        $('input[name="doc_date_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            get_otherdoc_list();
        });

        $('#permit_date_range').daterangepicker({
            "autoUpdateInput": false,
        });

        $('input[name="permit_date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            var startDate = picker.startDate.format('DD/MM/YYYY');
            var endDate = picker.endDate.format('DD/MM/YYYY');

            var dataSearch = {
                "startDate": startDate,
                "endDate": endDate,
            };
            get_permit_list(dataSearch);
        });

        $('input[name="permit_date_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            get_permit_list();
        });
//        get_bonus_list();
//        get_loan_list();
//        get_advance_list();



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
            $("#edit_button").show();
        }
    });
    // listing of the employee bonus 
    function get_bonus_list(data_search_value = '') {
        $('#bonus_listing_table').dataTable().fnDestroy();
        var table = $('#bonus_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-4" i> <"col-sm-4" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/bonus/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'amount',
                    name: 'amount'
                },
                //only those have manage_user permission will get access
                {
                    targets: 2,
                    data: 'created_at',
                    name: 'created_at'

                }
            ]
        });
    }
    // listing of the employee advance 
    function get_advance_list(data_search_value = '') {
        $('#advance_listing_table').dataTable().fnDestroy();
        var table = $('#advance_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-4" i> <"col-sm-4" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/advances/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'amount',
                    name: 'amount'
                },
                //only those have manage_user permission will get access
                {
                    targets: 2,
                    data: 'created_at',
                    name: 'created_at'

                }
            ]
        });
    }
    // listing of the employee loans 
    function get_loan_list(data_search_value = '') {
        $('#loan_listing_table').dataTable().fnDestroy();
        var table = $('#loan_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-4" i> <"col-sm-4" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/loans/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'amount',
                    name: 'amount'
                },
                {
                    targets: 2,
                    data: 'term',
                    name: 'term'
                },
                {
                    targets: 3,
                    data: 'monthly_deduction',
                    name: 'monthly_deduction'
                },
                {
                    targets: 4,
                    data: 'progress_bar',
                    name: 'progress_bar'
                }
            ]
        });
    }
    //server side table script end

    $(document).on("click", ".tabs-list li", function() {
        if ($(this).find('a').attr('href') == '#general-info-tab') {
            history.pushState({}, null, '#general-info-tab');
            $("#edit_button").show();
            $("#employee_general_edit").hide();
            $("#employee_details").show();
        } else if ($(this).find('a').attr('href') == '#certificate-tab') {
            history.pushState({}, null, '#certificate-tab');
            @can('manage_certificate')
            get_certificate_list();
            @endcan
            $("#edit_button").hide();
        } else if ($(this).find('a').attr('href') == '#employee-contract-tab') {
            history.pushState({}, null, '#employee-contract-tab');             
            get_offer_letter_detail();
             $('.contract_class li a').first().trigger('click');
            $('.contract_class li').first().addClass('active');
            
            $("#edit_button").hide();
        } else if ($(this).find('a').attr('href') == '#correspondance-tab') {
            history.pushState({}, null, '#correspondance-tab');
            @can('manage_warning_letter')
            getWarningLetterList(); // get the warning letter listing
            @endcan
            $('.corres_class li a').first().trigger('click');
            $('.corres_class li').first().addClass('active');
            $("#edit_button").hide();
        } else if ($(this).find('a').attr('href') == '#bonus-tab') {
            history.pushState({}, null, '#bonus-tab');
            $('.bonus_class li a').first().trigger('click');
            $('.bonus_class li').first().addClass('active');
            
            $("#edit_button").hide();
        } else if ($(this).find('a').attr('href') == '#employee-permit-tab') {
            history.pushState({}, null, '#employee-permit-tab');

            @can('manage_employee_permit')
            get_permit_list();
            @endcan
            $("#edit_button").hide();
        }
    });

    $(document).on("click", "#bonus-tab .nav-tabs li", function() {
        $("#edit_button").hide();
        if ($(this).find('a').attr('href') == '#bonus-added') {
            get_bonus_list();

        } else if ($(this).find('a').attr('href') == '#loan-added') {
            get_loan_list();

        } else if ($(this).find('a').attr('href') == '#advance-taken') {
            get_advance_list();

        }
    });
    
</script>

<script>
    // code by harshita
    //get employee certificate list

    function get_certificate_list(data_search_value = '') {
        $('#certificate_listing_table').dataTable().fnDestroy();
        var u_id = '{{ $user->id }}';
        var table = $('#certificate_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-3" i><"col-sm-5" p>>>',
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/employee/certificate/list',
                type: "get",
                data: {
                    "id": u_id,
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'certificate_title',
                    name: 'certificate_title'
                },
                {
                    targets: 2,
                    data: 'certificate_type',
                    name: 'certificate_type'

                },
                {
                    targets: 3,
                    data: 'certificate_file',
                    name: 'certificate_file'

                },
                {
                    targets: 4,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false

                }
            ],
            drawCallback: function() {
                $('.image-popup-vertical-fit').magnificPopup({
                    type: 'image',
                    mainClass: 'mfp-with-zoom',
                    gallery: {
                        enabled: true
                    },

                    zoom: {
                        enabled: true,

                        duration: 300, // duration of the effect, in milliseconds
                        easing: 'ease-in-out', // CSS transition easing function

                        opener: function(openerElement) {

                            return openerElement.is('img') ? openerElement : openerElement.find('img');
                        }
                    }

                });
            }
        });

    }

    //add certificate
    function addCertificate() {
        //reset form
        $("#addEditCertificateForm")[0].reset();
        //open modal
        $("#addEditCertificateModal").modal("show");
        //change modal title
        $("#addEditCertificateModal").find(".modal-title").text('Add New Certificate');
        //put id zero in case of add new item
        $("#addEditCertificateModal").find("input[name='id']").val(0);
        $('#show_cer').html('');
    }

    $('#reset_employee_certificate_data').on('click', function() {

        $('#course').val('');
        $('#course_type').val('');
        get_certificate_list();
    });
     $(document).on("click", ".file-upload-browse", function() {
        var file = $(this).parent().parent().parent().find('.file-upload-default');
        file.trigger('click');
    });
    $(document).on("change", ".file-upload-browse", function() { 
        var ext = $(this).val().replace(/C:\\fakepath\\/i, '').split('.').pop();
        var file_name = $(this).val().replace(/C:\\fakepath\\/i, '').substring(0, 3);
        //$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        $(this).parent().find('.form-control').val(file_name + '.' + ext);
    });
    //function for display Certificate file preview 
    function certificateFilePreview(input) {

        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                if (!input.files[i].name.match(/\.(jpg|jpeg|png|gif|pdf)$/i)) {
                    var fileExtension = ['jpeg', 'pdf'];
                    document.getElementById('certificate_file_err').innerHTML = 'Only formats are allowed : ' + fileExtension.join(', ');
                    break;
                } else {
                    document.getElementById('certificate_file_err').innerHTML = '';
                    document.getElementById('list_cer').style.display = "block";
                    if (input.files[i].name.match(/\.(pdf)$/i)) {
                        $('#show_cer').append('<a href="' + window.URL.createObjectURL(input.files[i]) + '" >' + input.files[i]['name'] + '</a>');
                    } else {
                        $('#show_cer').append('<div class="copy_class"><img src="' + window.URL.createObjectURL(input.files[i]) + '" class="img-responsive"/></div>');
                    }
                }



                reader.readAsDataURL(input.files[i]);
            }

        }
    }

    $('#certificate_file').on('change', function() {
        $('#show_cer').html("");
        certificateFilePreview(this);
    });

    function set_course_type(value) {

        $.ajax({
            type: "POST",
            url: "{{url('employee/certificate/get_course_type')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                course_id: value,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $("#addEditCertificateModal").find("input[name='certificate_type_id']").val(response.type);
                    $("#addEditCertificateModal").find("input[name='certificate_type']").val(response.type_name);
                }

            },
        });
    }

    //add update item
    $("#addEditCertificateForm").validate({
        rules: {
            certificate_title: {
                required: true,
            },
        },
        messages: {},
        submitHandler: function(form) {
            //serialize form data

            var formData = new FormData($("#addEditCertificateForm")[0]);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{url('employee/certificate/store')}}",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $("#addEditCertificateForm").find('.submit_button').attr("disabled", true);
                     $('.loader').show();
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                         $("#addEditCertificateForm").find('.submit_button').attr("disabled", false);
                     $('.loader').hide();
                        //reload datatable
                        //get datatable active page
                        var itemId = $("#addEditCertificateForm").find("input[name='id']").val();
                        if (itemId != '' && itemId > 0) {
                            //in case of edit load active page data
                            var active_page = $(".pagination").find("li.active a").text();
                        } else {
                            //in case of add new item get first page data
                            var active_page = 1;
                        }
                        //load datatable
                        $('#certificate_listing_table').dataTable().fnPageChange(parseInt(active_page) - 1);
                        //show notification
                        $.notify(response.msg, "success");
                        $("#addEditCertificateModal").modal("hide");
                        //reset form
                        $("#addEditCertificateForm")[0].reset();
                    } else {
                         $("#addEditCertificateForm").find('.submit_button').attr("disabled", false);
                     $('.loader').hide();
                        $.notify(response.msg, "warning");
                    }
                },
            });
            return false;
        }
    });
    //update item
    function updateCertificate(id) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/certificate/get_by_id')}}",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    var item = response.data;
                    //put  item details in all input fields
                    //reset form
                    $("#addEditCertificateForm")[0].reset();
                    $("#addEditCertificateModal").find(".modal-title").text('Update Certificate');
                    $("#addEditCertificateModal").find("select[name='certificate_title']").val(item.certificate_title);
                    $("#addEditCertificateModal").find("input[name='id']").val(item.id);
                    $("#addEditCertificateModal").find("input[name='certificate_type_id']").val(item.certificate_type);
                    $("#addEditCertificateModal").find("input[name='certificate_type']").val(item.type_name);
                    if (item.certificate_file != '' && item.certificate_file != null) {

                        document.getElementById('list_cer').style.display = "block";
                        $('#show_cer').html('');

                        var extension = item.certificate_file.split('.').pop();
                        var url = response.certificate_file;
                        if (extension == 'pdf') {
                            $('#show_cer').append('<a href="' + url + '" >' + item.certificate_file.substring(16, 23) + '.pdf</a>');
                        } else {

                            $('#show_cer').append('<div class="copy_class"><img src="' + url + '" class="img-responsive"/></div>');
                        }

                    }
                    $("#addEditCertificateModal").modal("show");
                } else {
                    $.notify(response.msg, "warning");
                }
            },
        });
    }


    //deleteItem
    function deleteCertificate(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateItemCertificate(id = id, type = 'delete', value = '1');
                    },
                }
            }
        });
    }
    //update item
    function updateItemCertificate(id, type, value) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/certificate/delete')}}",
            data: {
                id: id,
                type: type,
                value: value,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");
                } else {
                    $.notify(response.msg, "warning");
                }
                //reload data table in case of delete item
                if (type == 'delete') {
                    var active_page = $(".pagination").find("li.active a").text();
                    //reload datatable
                    $('#certificate_listing_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                }

            },
        });
    }

    // searching on change for employee list
    function filter_certificate_list() {
        var certificate_title = $('#course').val();
        var certificate_type = $('#course_type').val();
        var dataSearch = {
            "certificate_title": certificate_title,
            "certificate_type": certificate_type,
        };
        get_certificate_list(dataSearch);
    }

    //code for employee general info

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

    };
    //on change of profile image display
    $('#profile_image').on('change', function() {

        imagesPreview(this, 'profile_image');
    });
    //on change functionality of nric copy
    $(document).on('change','#nric_copy', function(){

        imagesPreview(this, 'nric_copy');
    });
    //display and upload passport copy
   $(document).on('change','#passport_copy', function() {
        //        $('#show').html('');
        //       
        var formData = new FormData();
        var ins = document.getElementById('passport_copy').files.length;
        var error = 0;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('passport_copy').files[x]);
            if (!document.getElementById('passport_copy').files[x].name.match(/\.(jpg|jpeg|png)$/i)) {
                error = 1;
            }
        }
        if (error == 1) {
            $.alert({
                title: 'Error!',
                content: 'Only formats are allowed : JPG,JPEG,PNG',
            });
        } else {
            var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
            var url_up = "{{url('/')}}" + "/employee/" + type + "/uploadImages";
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                        document.getElementById('list').style.display = "block";
                        document.getElementById('passport_flag').style.display = "none";
                        var length = response.images.length;
                        for (var i = 0; i < length; i++) {
                            var url = response.images[i];
                            $('#show').append('<div class="copy_class"><img src="' + url + '" class="img-responsive" /><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="passport_copy_name[]"></div>');
                        }

                        $("#passport_copy").val('');
                    } else {

                        $.notify(response.msg, "warning");
                    }
                },
            });
        }
    });
    $('#ba_copy').on('change', function() {
        var formData = new FormData();
        var error = 0;
        var ins = document.getElementById('ba_copy').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('ba_copy').files[x]);
            if (!document.getElementById('ba_copy').files[x].name.match(/\.(jpg|jpeg|png|pdf)$/i)) {
                error = 1;
            }
        }
        if (error == 1) {
            $.alert({
                title: 'Error!',
                content: 'Only formats are allowed : JPG,JPEG,PNG,PDF',
            });
        } else {
            var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
            var url_up = "{{url('/')}}" + "/employee/" + type + "/uploadImages";
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                        document.getElementById('list_ba').style.display = "block";
                        $('#account_flag').hide();
                        var length = response.images.length;
                        for (var i = 0; i < length; i++) {
                            //                            var url = "{{ asset('/employee_images').'/'}}" + response.images[i];
                            //                            $('#show_ba').append('<div class="copy_class"><img src="' + url + '" class="img-responsive" /><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.images[i] + '" name="ba_copy_name[]"></div>');
                            var ext = response.image_name[i].split('.').pop();
                            var url = response.images[i];
                            //check for file extension
                            if (ext == 'pdf') {
                                $('#show_ba').append('<div class="copy_class"><a href="' + url + '" target="_blank" />' + response.image_name[i].substring(1, 4) + '.' + ext + '</a><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="ba_copy_name[]"></div>');
                            } else {
                                $('#show_ba').append('<div class="copy_class"><img src="' + url + '" class="img-responsive" /><span><button class="btn remove" data-id="0" data-name="">x</button></span><input type="hidden" value="' + response.image_name[i] + '" name="ba_copy_name[]"></div>');
                            }
                        }

                        $("#ba_copy").val('');
                    } else {
                        var file_flag_ba = $('input[name="ba_copy_name[]"').val();

                        if (file_flag_ba == '' || file_flag_ba == undefined) {
                            $('#account_flag').show();
                        }
                        $.notify(response.msg, "warning");
                    }
                },
            });
        }
    });

    $(document).on('click', '.remove', function() {

        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        $(this).closest('.copy_class').remove();

        var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
        var url_up = "{{url('/')}}" + "/employee/" + type + "/baCopyImageRemove";
        if (id != '0') {
            $.ajax({
                type: "POST",
                url: url_up,
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (response.code == 200) {
                        //alert("hello");
                        $(this).closest('.copy_class').remove();

                        var file_flag_ba = $('input[name="ba_copy_name[]"').val();

                        if (file_flag_ba == '' || file_flag_ba == undefined) {
                            $('#account_flag').show();
                        }
                        //$.notify(response.msg, "success");
                    } else {
                        $.notify(response.msg, "warning");
                    }
                },
            });
        } else {
            $(this).closest('.copy_class').remove();
            var passport = $('#passport_no').val();
            if (passport != '') {
                var file_flag = $('input[name="passport_copy_name[]"').val();

                if (file_flag == '' || file_flag == undefined) {
                    $('#passport_flag').show();
                }
            }
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

    $('#bank_account_copy').on('change', function() {
        $('#show_bank').html("");
        bankCopyPreview(this);
    });
    //function for display bank  copy
    function resignPreview(input) {

        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                if (!input.files[i].name.match(/\.(pdf)$/i)) {
                    var fileExtension = ['pdf'];
                    $('#termination_resign_letter').val('');
                    document.getElementById('resign_letter_err').innerHTML = 'Only formats are allowed : ' + fileExtension.join(', ');
                    document.getElementById('resign_letter_display').style.display = 'none';
                    break;
                } else {
                    document.getElementById('resign_letter_err').innerHTML = '';
                    document.getElementById('resign_letter_display').style.display = 'block';
                    if (input.files[i].name.match(/\.(pdf)$/i)) {
                        $('#show_letter').append('<a href="' + window.URL.createObjectURL(input.files[i]) + '" >' + input.files[i]['name'] + '</a>');
                    }
                }



                reader.readAsDataURL(input.files[i]);
            }

        }
    }

    $('#termination_resign_letter').on('change', function() {
        $('#show_letter').html("");
        resignPreview(this);
    });
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
            //            $('#show_bank').hide();
            //            $('#bank_account_copy').val('');
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
        var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
        var url_up = "{{url('/')}}" + "/employee/" + type + "/get_state";
        $.ajax({
            type: "POST",
            url: url_up,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                country_id: value,
            },
            success: function(data) {

                $('#state').html(data);
            },
        });
    }

    //get city option
    function get_city(value) {
        var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
        var url_up = "{{url('/')}}" + "/employee/" + type + "/get_city";
        $.ajax({
            type: "POST",
            url: url_up,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                state_id: value,
            },
            success: function(data) {

                $('#city').html(data);
            },
        });
    }


    //add update item
    $("#employeeGeneralEditForm").validate({
        rules: {
            type: {
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
               // number: true,
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
                        var ele = '{{ $user->employee_type }}';
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
                        var ele = '{{ $user->employee_type }}';
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
                email:true
            }

        },
        errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
        },
        submitHandler: function(form) {

            //form.submit();

            var formData = new FormData($("#employeeGeneralEditForm")[0]);
            var type = "{{($user->employee_type=='1') ? 'employee' : 'staff'}}";
            var url_up = "{{url('/')}}" + "/employee/" + type + "/update";

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
                   
                     $('.loader').show();
                },
                success: function(data) {
                      $('.loader').hide();
                    var response = JSON.parse(data);
                    //console.log(response);
                    if (response.code == 200) {
                        //show notification

                        $.notify(response.msg, "success");
                        $("#edit_button").show();
                        location.reload();
                    } else {

                        $.notify(response.msg, "warning");
                    }


                },
            });
            return false;
        }
    });

    function getTerminateOrResigned_field(value) {

        if (value == '2') {
            $('#text_date').text('Termination Date');
            $('#termination_resign').show();
            $('#period').show();
            $('#resign_letter').hide();
        } else if (value == '3') {
            $('#text_date').text('Date');
            $('#termination_resign').show();
            $('#period').hide();
            $('#resign_letter').show();
        } else {
            $('#termination_resign').hide();
            $('#period').hide();
            $('#resign_letter').hide();
        }

    }
    // show edit form at the click on the edit button 
    function showedit() {
        $("#employee_general_edit").show();
        $("#employee_details").hide();
        $("#edit_button").hide();
    }

    $(document).on("click", ".preview_certificate_doc", function() {
        $('#view-iframe-feb').remove();
        var width = $(window).width();
        var height = $(window).height();
        var link = $(this).attr('data-src');
        //alert(link);
        var html = '<iframe id="view-iframe-feb" src="' + link + '" frameborder="0" width="100%" style="height:85vh"><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
        $("#certificate-doc-preview-modal").modal("toggle");
        //    $("#certificate-doc-preview-modal .modal-header h4").text('Preview');
        $("#certificate-doc-preview-modal .modal-body").html(html);
        $("#certificate-doc-preview-modal .modal-dialog").addClass("modal-lg");
    });

    //fetch permit list
    function get_permit_list(data_search_value = '') {
        $('#permit_listing_table').dataTable().fnDestroy();
        var table = $('#permit_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-3" i><"col-sm-5" p>>>',
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/employee/permit/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'type',
                    name: 'type'
                },

                {
                    targets: 2,
                    data: 'expiry_date',
                    name: 'expiry_date'

                },
                {
                    targets: 3,
                    data: 'is_renew',
                    name: 'is_renew'

                },
                {
                    targets: 4,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false

                }
            ],
            "initComplete":function( settings, json){
		var table_length = $('#permit_listing_table').dataTable().fnGetData().length;
                           if(table_length < 3){ 
                               $('#permit_add').removeClass('d-none');
                            }else{  
                               $('#permit_add').addClass('d-none'); 
                            }
                            
                },  
        });
        
        
    }

    $('#reset_employee_permit_data').on('click', function() {
        $('#permit_date_range').val('');
        get_permit_list();
    });

    //add certificate
    function addPermit(value, type = '') {
        //reset form
        $("#addEditPermitForm")[0].reset();
        //open modal
        $("#addEditPermitModal").modal("show");
        //change modal title
        $("#addEditPermitModal").find(".modal-title").text('Add New Permit');
        $("#addEditPermitModal").find("input[name='is_renew']").val(value);
        if (value == 0) {
            $("#renew_permit").addClass("d-none");
            $("#new_permit").removeClass("d-none");
            $("#addEditPermitModal").find("select[name='type']").removeAttr('disabled');

            $('#employer-pdf-preview').addClass('d-none');
            $(".change-employer-logo").addClass('d-none');
            $(".upload-employer-logo").removeClass('d-none');
            $('#ipa_employee-pdf-preview').addClass('d-none');
            $(".change-ipa_employee-logo").addClass('d-none');
            $(".upload-ipa_employee-logo").removeClass('d-none');
            $('#permit_application-pdf-preview').addClass('d-none');
            $(".change-permit_application-logo").addClass('d-none');
            $(".upload-permit_application-logo").removeClass('d-none');
        } else {
            $("#renew_permit").removeClass("d-none");
            $("#addEditPermitModal").find("select[name='type']").val(type);
            $("#addEditPermitModal").find("select[name='type']").attr('disabled', 'disabled');
            $("#new_permit").addClass("d-none");

            $('#work_permit_application-pdf-preview').addClass('d-none');
            $(".change-work_permit_application-logo").addClass('d-none');
            $(".upload-work_permit_application-logo").removeClass('d-none');

            $('#renewed_permit_docs-pdf-preview').addClass('d-none');
            $(".change-renewed_permit_docs-logo").addClass('d-none');
            $(".upload-renewed_permit_docs-logo").removeClass('d-none');
            $('#renewal_notice-pdf-preview').addClass('d-none');
            $(".change-renewal_notice-logo").addClass('d-none');
            $(".upload-renewal_notice-logo").removeClass('d-none');
        }
        $('#issuance_letter-pdf-preview').addClass('d-none');
        $(".change-issuance_letter-logo").addClass('d-none');
        $(".upload-issuance_letter-logo").removeClass('d-none');

        $('#salary_declaration-pdf-preview').addClass('d-none');
        $(".change-salary_declaration-logo").addClass('d-none');
        $(".upload-salary_declaration-logo").removeClass('d-none');
        $('#permit_history_log').addClass('d-none');
        //put id zero in case of add new item
        $("#addEditPermitModal").find("input[name='id']").val(0);

    }

    //upload logo trigger logo input 
    $(document).on('click', '.upload-permit', function() {
        var name = $(this).attr('data-name');
        $(document).find('input[name="' + name + '"]').trigger('click');

    });

    $(document).on('click', '.remove-permit-pdf', function() {
        var name = $(this).attr('data-name');
        var val = $(this).attr('data-val');
        $('input[name="' + name + '"]').val('');
        $('input[name="other_doc_file_name"]').val('');
        //remove preview
        $('#' + val + '-pdf-preview').attr('href', '');
        $('#' + val + '-pdf-preview').text('');
        $(".change-" + val + "-logo").addClass('d-none');
        $(this).addClass('d-none');
        //$(".remove-project-logo").addClass('d-none');
        $(".upload-" + val + "-logo").removeClass('d-none');
    });
    //function to upload pdf file
    function upload_pdf_permit_display($this,callback)
    {
        var formData = new FormData();
        var url_name = '';
        var ins = $this.files.length;
        var url_up = "/employee/permit/upload_pdf";
        formData.append("files",$this.files[0]);
        $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url_up,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data:formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                
                success: function(data) {      
                 var response = JSON.parse(data);
                    if (response.code == 200) { 
                       url_name = response.file_url;
                       callback(url_name);
                       }else{ 
                        $.notify(response.msg, "warning");
                    }
                },
            });
            
    }
    $(document).on('change', 'input[name="ipa_employee"]',  function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#ipa_employee').val('');
            $('.err_ipa_employee').show().delay(5000).fadeOut();
        } else {
           upload_pdf_permit_display($this_val,function(file_url){
               if(file_url !=''){  
            //show preview
                $('#ipa_employee-pdf-preview').attr('src', file_url);
                $('#ipa_employee-pdf-preview').removeClass('d-none');
                //hide upload icon show remove and change icon
                $(".change-ipa_employee-logo").removeClass('d-none');
                $(".upload-ipa_employee-logo").addClass('d-none');
            }
           });  
           
//            reader.onload = (e) => {
//                //show preview
//                $('#ipa_employee-pdf-preview').attr('src', e.target.result);
//                $('#ipa_employee-pdf-preview').removeClass('d-none');
//                //hide upload icon show remove and change icon
//                $(".change-ipa_employee-logo").removeClass('d-none');
//                //$(".remove-project-logo").removeClass('d-none');
//                $(".upload-ipa_employee-logo").addClass('d-none');
//            }
//            reader.readAsDataURL(this.files[0]);
        }

    });

    
    
   
    $(document).on('change', 'input[name="ipa_employer"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#ipa_employer').val('');
            $('.err_ipa_employer').show().delay(5000).fadeOut();
        } else {
            
             upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){  
                    //show preview
                $('#employer-pdf-preview').attr('src', file_url);
                $('#employer-pdf-preview').removeClass('d-none');
                //            $('#employer-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-employer-logo").removeClass('d-none');
                //$(".remove-employer-logo").removeClass('d-none');
                $(".upload-employer-logo").addClass('d-none');

            }
        });
    }

    });

    $(document).on('change', 'input[name="permit_application"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
          var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#permit_application').val('');
            $('.err_permit_application').show().delay(5000).fadeOut();
        } else {
             upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
                   
                    //show preview
                $('#permit_application-pdf-preview').attr('src', file_url);
                $('#permit_application-pdf-preview').removeClass('d-none');
                //$('#permit_application-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-permit_application-logo").removeClass('d-none');
                //$(".remove-permit_application-logo").removeClass('d-none');
                $(".upload-permit_application-logo").addClass('d-none'); 
            }
        });
           
        }

    });

    $(document).on('change', 'input[name="salary_declaration"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#salary_declaration').val('');
            $('.err_salary_declaration').show().delay(5000).fadeOut();
        } else {
             upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
                //show preview
                $('#salary_declaration-pdf-preview').attr('src', file_url);
                $('#salary_declaration-pdf-preview').removeClass('d-none');
                //$('#salary_declaration-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-salary_declaration-logo").removeClass('d-none');
                //$(".remove-salary_declaration-logo").removeClass('d-none');
                $(".upload-salary_declaration-logo").addClass('d-none');
            }
        });
        }

    });

    $(document).on('change', 'input[name="issuance_letter"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#issuance_letter').val('');
            $('.err_issuance_letter').show().delay(5000).fadeOut();
        } else {
            upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
                //show preview
                $('#issuance_letter-pdf-preview').attr('src', file_url);
                $('#issuance_letter-pdf-preview').removeClass('d-none');
                $(".change-issuance_letter-logo").removeClass('d-none');
                $(".upload-issuance_letter-logo").addClass('d-none');
            }
        });
        }

    });


    $(document).on('change', 'input[name="renewal_notice"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#renewal_notice').val('');
            $('.err_renewal_notice').show().delay(5000).fadeOut();
        } else {
             upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
          
                //show preview
                $('#renewal_notice-pdf-preview').attr('src', file_url);
                $('#renewal_notice-pdf-preview').removeClass('d-none');
                //$('#renewal_notice-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-renewal_notice-logo").removeClass('d-none');
                $(".upload-renewal_notice-logo").addClass('d-none');
            }
        });
        }

    });

    $(document).on('change', 'input[name="renewed_permit_docs"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#renewed_permit_docs').val('');
            $('.err_renewed_permit_docs').show().delay(5000).fadeOut();
        } else {
             upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
                //show preview
                $('#renewed_permit_docs-pdf-preview').attr('src', file_url);
                $('#renewed_permit_docs-pdf-preview').removeClass('d-none');
                //$('#renewed_permit_docs-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-renewed_permit_docs-logo").removeClass('d-none');
                $(".upload-renewed_permit_docs-logo").addClass('d-none');
            }
        });
        }

    });

    $(document).on('change', 'input[name="work_permit_application"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;
        var $this_val = this;
        if (!this.files[0].name.match(/\.(pdf)$/i)) {

            $('#work_permit_application').val('');
            $('.err_work_permit_application').show().delay(5000).fadeOut();
        } else {
              upload_pdf_permit_display($this_val,function(file_url){
                 
               if(file_url !=''){ 
                //show preview
                $('#work_permit_application-pdf-preview').attr('src', file_url);
                $('#work_permit_application-pdf-preview').removeClass('d-none');
                //$('#work_permit_application-pdf-preview').text(this.files[0].name);
                //hide upload icon show remove and change icon
                $(".change-work_permit_application-logo").removeClass('d-none');
                $(".upload-work_permit_application-logo").addClass('d-none');
            }
        });
        }

    });

    //add update item
    $("#addEditPermitForm").validate({
        rules: {
            type: {
                required: true,
            },
            expiry_date: {
                required: true,
            },
            ipa_employee: {
                required: true,
            },
            ipa_employer: {
                required: true,
            },
            salary_declaration: {
                required: true,
            },
            issuance_letter: {
                required: true,
            },
            permit_application: {
                required: true,
            },

        },
        errorPlacement: function(error, element) {
            if (element.hasClass("date_error")) {

                error.insertAfter(element.parent());
            } else { // This is the default behavior of the script for all fields
                error.insertAfter(element);
            }
        },
        messages: {},
        submitHandler: function(form) {
            //serialize form data
            $("#addEditPermitModal").find("select[name='type']").removeAttr('disabled');
            var formData = new FormData($("#addEditPermitForm")[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{url('employee/permit/store')}}",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $("#addEditPermitForm").find('.submit_button').attr("disabled", true);
                     $('.loader').show();
                },
                success: function(data) {
                    $("#addEditPermitForm").find('.submit_button').attr("disabled", false);
                     $('.loader').hide();
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                        //reload datatable
                        
                        //get datatable active page
                        var itemId = $("#addEditPermitForm").find("input[name='id']").val();
                        if (itemId != '' && itemId > 0) {
                            //in case of edit load active page data
                            var active_page = $(".pagination").find("li.active a").text();
                        } else {
                            //in case of add new item get first page data
                            var active_page = 1;
                        }
                        //load datatable
                        $('#permit_listing_table').dataTable().fnPageChange(parseInt(active_page) - 1);
                        var table_length = $('#permit_listing_table').dataTable().fnGetData().length;
                        var table_length = table_length + 1;
                           if(table_length < 3){ 
                               console.log(table_length)
                               $('#permit_add').removeClass('d-none');
                            }else{  
                               $('#permit_add').addClass('d-none'); 
                            }
                        //show notification
                        $.notify(response.msg, "success");
                        $("#addEditPermitModal").modal("hide");
                        //reset form
                        $("#addEditPermitForm")[0].reset();
                    } else {
                        var is_renew = $("#addEditPermitModal").find("input[name='is_renew']").val();
                        if(is_renew == '1'){
                        $("#addEditPermitModal").find("select[name='type']").attr('disabled', 'disabled');
                    }
                     $("#addEditPermitForm").find('.submit_button').attr("disabled", false);
                        $.notify(response.msg, "warning");
                    }
                },
            });
            return false;
        }
    });


    //delete permit
    function deletePermit(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want to delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateItemPermit(id = id, type = 'delete', value = '1');
                    },
                }
            }
        });

    }
    //update delete column
    function updateItemPermit(id, type, value) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/permit/delete')}}",
            data: {
                id: id,
                type: type,
                value: value,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    console.log(response.msg);
                    $.notify(response.msg, "success");
                    //reload data table in case of delete item
                    if (type == 'delete') {
                        var active_page = $(".pagination").find("li.active a").text();
                        //reload datatable
                        $('#permit_listing_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                        var table_length = $('#permit_listing_table').dataTable().fnGetData().length;
                        console.log(table_length);
                           if(table_length < 4 ){
                               $('#permit_add').removeClass('d-none');
                               
                            }else{
                               $('#permit_add').addClass('d-none'); 
                            }
                    }
                } else {
                    $.notify(response.msg, "warning");
                }
            },
        });
    }


    //update item
    function updatePermit(id) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/permit/get_by_id')}}",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    var item = response.data;
                    //put  item details in all input fields
                    //reset form
                    $("#addEditPermitForm")[0].reset();

                    $("#addEditPermitModal").find(".modal-title").text('Update Permit');
                    $("#addEditPermitModal").find("select[name='type']").val(item.type);
                    //$("#addEditPermitModal").find("select[name='type']").addClass('dis');
                    $("#addEditPermitModal").find("select[name='type']").attr('disabled', 'disabled');
                    $("#addEditPermitModal").find("input[name='expiry_date']").val(item.expiry_date);
                    $("#addEditPermitModal").find("input[name='id']").val(item.id);
                    $("#addEditPermitModal").find("select[name='employee_id']").val(item.employee_id);
                    $("#addEditPermitModal").find("select[name='is_renew']").val(item.is_renew);
                    $('#permit_history_log').removeClass('d-none');
                    $("#addEditPermitModal").find("#collapseOne").html(response.html);

                    if (item.is_renew == '0') {

                        $("#new_permit").removeClass('d-none');
                        $("#renew_permit").addClass('d-none');

                        $('#ipa_employee-pdf-preview').attr('src', item.ipa_employee);
                        $('#ipa_employee-pdf-preview').removeClass('d-none');
                        $(".change-ipa_employee-logo").removeClass('d-none');
                        $(".upload-ipa_employee-logo").addClass('d-none');

                        $('#employer-pdf-preview').attr('src', item.ipa_employer);
                        $('#employer-pdf-preview').removeClass('d-none');
                        $(".change-employer-logo").removeClass('d-none');
                        $(".upload-employer-logo").addClass('d-none');

                        $('#permit_application-pdf-preview').attr('src', item.permit_application);
                        $('#permit_application-pdf-preview').removeClass('d-none');
                        $(".change-permit_application-logo").removeClass('d-none');
                        $(".upload-permit_application-logo").addClass('d-none');
                    } else {
                        $("#renew_permit").removeClass('d-none');
                        $("#new_permit").addClass('d-none');

                        //show preview
                        $('#work_permit_application-pdf-preview').attr('src', item.work_permit_application);
                        $('#work_permit_application-pdf-preview').removeClass('d-none');
                        //hide upload icon show remove and change icon
                        $(".change-work_permit_application-logo").removeClass('d-none');
                        $(".upload-work_permit_application-logo").addClass('d-none');

                        $('#renewed_permit_docs-pdf-preview').attr('src', item.other_doc);
                        $('#renewed_permit_docs-pdf-preview').removeClass('d-none');
                        $(".change-renewed_permit_docs-logo").removeClass('d-none');
                        $(".upload-renewed_permit_docs-logo").addClass('d-none');

                        //show preview
                        $('#renewal_notice-pdf-preview').attr('src', item.renewal_notice);
                        $('#renewal_notice-pdf-preview').removeClass('d-none');
                        //hide upload icon show remove and change icon
                        $(".change-renewal_notice-logo").removeClass('d-none');
                        $(".upload-renewal_notice-logo").addClass('d-none');
                    }
                    //show preview
                    $('#salary_declaration-pdf-preview').attr('src', item.salary_declaration);
                    $('#salary_declaration-pdf-preview').removeClass('d-none');
                    //hide upload icon show remove and change icon
                    $(".change-salary_declaration-logo").removeClass('d-none');
                    $(".upload-salary_declaration-logo").addClass('d-none');

                    //show preview
                    $('#issuance_letter-pdf-preview').attr('src', item.issuance_letter);
                    $('#issuance_letter-pdf-preview').removeClass('d-none');
                    //hide upload icon show remove and change icon
                    $(".change-issuance_letter-logo").removeClass('d-none');
                    $(".upload-issuance_letter-logo").addClass('d-none');

                    $("#addEditPermitModal").modal("show");
                } else {
                    $.notify(response.msg, "warning");
                }
            },
        });
    }

    // show renew permit
    function showrenewPermit() {
        $("#addEditPermitModal").find(".modal-title").text('Add Renew Permit');
        $("#new_permit").hide();
        $("#renew").hide();
        $('#issuance_letter-pdf-preview').attr('href', '');
        $('#issuance_letter-pdf-preview').text('');
        //hide upload icon show remove and change icon
        $(".change-issuance_letter-logo").addClass('d-none');

        $(".upload-issuance_letter-logo").removeClass('d-none');

        $("#renew_permit").removeClass('d-none');


    }
    //other doc list 
    function get_otherdoc_list(data_search_value = '') {
        $('#doc_listing_table').dataTable().fnDestroy();
        var table = $('#doc_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-3" i><"col-sm-5" p>>>',
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/employee/otherdocs/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'file',
                    name: 'file'
                },

                {
                    targets: 2,
                    data: 'created_at',
                    name: 'created_at'

                },
                {
                    targets: 3,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false

                }
            ]
        });
    }
    //add Other doc
    function addDoc() {
        //reset form
        $("#addEditOtherDocForm")[0].reset();
        //open modal
        $("#addEditOtherDocModal").modal("show");
        //change modal title
        $("#addEditOtherDocModal").find(".modal-title").text('Upload Doc');
        //put id zero in case of add new item
        $("#addEditOtherDocModal").find("input[name='id']").val(0);
        $('input[name="doc_file"]').val('');
        $('#doc_file-pdf-preview').attr('href', '');
        $('#doc_file-pdf-preview').text('');
        //hide upload icon show remove and change icon
        $(".change-doc_file-logo").addClass('d-none');
        $(".remove-doc_file-logo").addClass('d-none');
        $(".upload-doc_file-logo").removeClass('d-none');

    }

    $(document).on('change', 'input[name="doc_file"]', function() {
        let reader = new FileReader();
        var file_size = this.files[0].size / 1000 / 1024;

        reader.onload = (e) => {
            //show preview
            $('#doc_file-pdf-preview').attr('href', e.target.result);
            $('#doc_file-pdf-preview').text(this.files[0].name);
            //hide upload icon show remove and change icon
            $(".change-doc_file-logo").removeClass('d-none');
            $(".remove-doc_file-logo").removeClass('d-none');
            $(".upload-doc_file-logo").addClass('d-none');
        }
        reader.readAsDataURL(this.files[0]);

    });

    //add update Other Doc
    $("#addEditOtherDocForm").validate({
        rules: {
            doc_file: {
                required: true,
            },

        },
        messages: {},
        submitHandler: function(form) {
            //serialize form data

            var formData = new FormData($("#addEditOtherDocForm")[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "{{url('employee/otherdoc/store')}}",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                        //reload datatable
                        //get datatable active page
                        var itemId = $("#addEditOtherDocForm").find("input[name='id']").val();
                        if (itemId != '' && itemId > 0) {
                            //in case of edit load active page data
                            var active_page = $(".pagination").find("li.active a").text();
                        } else {
                            //in case of add new item get first page data
                            var active_page = 1;
                        }
                        //load datatable
                        $('#doc_listing_table').dataTable().fnPageChange(parseInt(active_page) - 1);
                        //show notification
                        $.notify(response.msg, "success");
                        $("#addEditOtherDocModal").modal("hide");
                        //reset form
                        $("#addEditOtherDocForm")[0].reset();
                    } else {
                        $.notify(response.msg, "warning");
                    }
                },
            });
            return false;
        }
    });

    //update document form
    function updateOtherDoc(id) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/otherdoc/get_by_id')}}",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    var item = response.data;
                    //put  item details in all input fields
                    //reset form
                    $("#addEditOtherDocForm")[0].reset();

                    $("#addEditOtherDocModal").find(".modal-title").text('Update File');

                    $("#addEditOtherDocModal").find("input[name='id']").val(item.id);
                    $("#addEditOtherDocModal").find("input[name='other_doc_file_name']").val(item.file);
                    var url = response.doc_file;
                    $('#doc_file-pdf-preview').attr('href', url);
                    $('#doc_file-pdf-preview').text(item.file.substring(16));
                    //hide upload icon show remove and change icon
                    $(".change-doc_file-logo").removeClass('d-none');
                    $(".remove-doc_file-logo").removeClass('d-none');
                    $(".upload-doc_file-logo").addClass('d-none');

                    $("#addEditOtherDocModal").modal("show");
                } else {
                    $.notify(response.msg, "warning");
                }
            },
        });
    }


    //delete other Doc
    function deleteOtherDoc(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        deleteItemDoc(id = id, type = 'delete', value = '1');
                    },
                }
            }
        });

    }
    //update item
    function deleteItemDoc(id, type, value) {
        $.ajax({
            type: "POST",
            url: "{{url('employee/otherdoc/delete')}}",
            data: {
                id: id,
                type: type,
                value: value,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");

                } else {
                    $.notify(response.msg, "warning");
                }
                var active_page = $(".pagination").find("li.active a").text();
                //reload datatable
                $('#doc_listing_table').dataTable().fnPageChange((parseInt(active_page) - 1));

            },
        });
    }

    function resetOtherDocFilter() {
        $('#doc_date_range').val('');
        get_otherdoc_list();
    }
</script>
<!-- HT end script here -->

<!-- ------vs start script here ---------->

<!-- Offer letter script start -->
<script>
    //vs get the active tab href when click on EMPLOYMENT CONTRACT tab
    $(document).on("click", "#employee-contract-tab .nav-tabs li", function() {

        if ($(this).find('a').attr('href') == '#letter-of-offer-tab') {          
          get_offer_letter_detail();
        } else if ($(this).find('a').attr('href') == '#letter-of-employment-tab') {
            get_employment_letter_detail();
        } else if ($(this).find('a').attr('href') == '#warning-letter-tab') {
            getWarningLetterList();
        }
    });

    // get offer letter detail
    function get_offer_letter_detail(){
        var employee_id = '<?php echo $user->id ?>';
        $.get("/offerletter/detail/" + employee_id, function(data) {
            $('#letter-of-offer-tab').find('.append-offer-letter-html').html(data);
            initialize_tooltip();
        });
    }
    // vs For file icon script
    $(document).on("click", ".file-choose", function() {
        var recordId = $(this).data('id');
        //call upload document function
        upload_endorsed_doc(recordId);
    });
    // vs upload document function
    function upload_endorsed_doc(data_id) {
        //trigger on input file
        var file_class = 'endorsed_doc' + data_id;
        $("." + file_class).trigger('click');
        //ff

        form = new FormData(),
            xhr = new XMLHttpRequest();
        $(document).on("change", "." + file_class, function(e) {
            //var doc_file = $(this).val();
            var doc_file = e.target.files[0].name;
            // $(this).siblings('span').text(doc_file);

            //call ajax after select the document 
            var formid = '#endorsed_docFrm' + data_id;
            var formData = new FormData($(formid)[0]);
            $.ajax({
                type: 'post',
                dataType: 'JSON',
                url: '/offerletter/upload_endorsed_doc',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    console.log(data.code);
                    if (data.code == 200) {
                        $.notify(data.msg, "success");
                        //for refresh table
                        get_offer_letter_detail();
                    } else {
                        $.notify(data.msg, "warning");
                    }

                }
            });
        });
    }
    //vs for preview of endorsed document
    $(document).on("click", ".preview_endorsed_doc", function() {
        $('#view-iframe-feb').remove();
        var width = $(window).width();
        var height = $(window).height();
        var link = $(this).data('src');
        var html = '<iframe id="view-iframe-feb" src="' + link + '" frameborder="0" width="100%" style="height:85vh"><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></iframe>';
        $("#endorsed-doc-preview-modal").modal("toggle");
        //$("#endorsed-doc-preview-modal .modal-header h4").text('Preview');
        $("#endorsed-doc-preview-modal .modal-body").html(html);
        $("#endorsed-doc-preview-modal .modal-dialog").addClass("modal-lg");
    });
    // for load the generate offer letter html
    $(document).on("click", ".generate-offer-letter", function() {
        var employee_id = '{{$user->id}}';
        $.get("/offerletter/generate-offer-letter/" + employee_id, function(data) {
            $('#generate-offer-letter-modal').find('.append-offer-letter').html(data);
            $('#generate-offer-letter-modal').modal('show');
            generate_offer_letter(); // form validation and save offer letter function
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#letter_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            //calculate offer letter salary information 
            $("input[name=salary]").keyup(function(event) {
                var basic_salary = $(this).val();
                calculate_offer_letter_salary(basic_salary);
            });
            //Scroll letter on particular id
            scrollToLetter();
            eSignature('#generate-offer-letter-modal'); //Load e-signature script    
            //eSignature('#director-signature','#director-signature-img','#e_sign','.clear-signature','.edit-signature');   
        });
    });
    //for edit the offer letter
    $(document).on("click", ".edit-offer-letter", function() {
        var employee_offer_letter_id = $(this).data('id');
        console.log(employee_offer_letter_id);
        $.get("/offerletter/edit-offer-letter/" + employee_offer_letter_id, function(data) {
            $('#generate-offer-letter-modal').find('.append-offer-letter').html(data);
            $('#generate-offer-letter-modal').modal('show');
            generate_offer_letter(); // form validation and save offer letter function
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#letter_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            //calculate offer letter salary information 
            $("input[name=salary]").keyup(function(event) {
                var basic_salary = $(this).val();
                calculate_offer_letter_salary(basic_salary);
            });
            //Scroll letter on particular id
            scrollToLetter();
            //Load e-signature script
            //eSignature('#director-signature','#director-signature-img','#e_sign','.clear-signature','.edit-signature');
            eSignature('#generate-offer-letter-modal'); //Load e-signature script 
        });
    });
    //For calculate the offer letter salary summary
    function calculate_offer_letter_salary(basic_salary) {
        //add basic salary
        $('#offerLetterFrm').find('input[name=basic_salay]').val(basic_salary);
        //get yearly salay
        var yearly_salary = basic_salary * 12;
        //get yearly total hours (total week* 44 Hours per week)
        var yearly_total_hours = '2288';
        //total hourly rate 
        var hourly_rate = (yearly_salary / yearly_total_hours).toFixed(2);
        $('#offerLetterFrm').find('input[name=hourly_rate]').val(hourly_rate);
        //OT Rate - Additional Hours (1.5 x hourly_rate)
        var additional_ot_rate = (1.5 * hourly_rate).toFixed(2);

        $('#offerLetterFrm').find('input[name=ot_rate_additional_hours]').val(additional_ot_rate);
        //OT Rate - PH, Rest Day (2 x hourly_rate)
        var rest_day_ot_rate = (2 * hourly_rate).toFixed(2);
        $('#offerLetterFrm').find('input[name=ot_rate_ph]').val(rest_day_ot_rate);
        //For Fixed Allowances (added in salary)
        var fa_accommodation = $('#offerLetterFrm').find('input[name=fa_accommodation]').val();
        var fa_telecommunications = $('#offerLetterFrm').find('input[name=fa_telecommunications]').val();
        var fa_food = $('#offerLetterFrm').find('input[name=fa_food]').val();
        var fa_transport = $('#offerLetterFrm').find('input[name=fa_transport]').val();
        var total_fixed_allowances = parseFloat(fa_accommodation) + parseFloat(fa_telecommunications) + parseFloat(fa_food) + parseFloat(fa_transport);
        basic_salary = parseFloat(basic_salary) + parseFloat(total_fixed_allowances);

        //end
        //Fixed Deductions (substract from salary)
        var fd_accommodation = $('#offerLetterFrm').find('input[name=fd_accommodation]').val();
        var fd_amenities = $('#offerLetterFrm').find('input[name=fd_amenities]').val();
        var fd_services = $('#offerLetterFrm').find('input[name=fd_services]').val();
        var total_fixed_deduction = parseFloat(fd_accommodation) + parseFloat(fd_amenities) + parseFloat(fd_services);
        basic_salary = parseFloat(basic_salary) - parseFloat(total_fixed_deduction);
        //end
        $('#offerLetterFrm').find('input[name=total_basic_salary]').val(basic_salary);
        //Basic Salary + Fixed OT($600 + $201.60* [($6.30 x 8 hrs) x 4])
        var fixed_ot = (rest_day_ot_rate * 8) * 4; //[($6.30 x 8 hrs) x 4]
        fixed_ot = fixed_ot.toFixed(2);
        var basic_salary_with_fixed_ot = parseFloat(basic_salary) + parseFloat(fixed_ot);
        //Basic Salary + Fixed OT
        var basic_salary_with_fixed_ot = (basic_salary_with_fixed_ot).toFixed(2);
        //check NAN value
        if (isNaN(parseFloat(basic_salary_with_fixed_ot))) {
            basic_salary_with_fixed_ot = '0.00';
        }
        //check check NAN value
        if (isNaN(parseFloat(basic_salary))) {
            basic_salary = '0.00';
        }
        $('#offerLetterFrm').find('.append-basic-salary').html(basic_salary);
        $('#offerLetterFrm').find('.append-basic-salary-with-fixed-ot').html(basic_salary_with_fixed_ot);
        $('#offerLetterFrm').find('input[name=basic_salary_fixed_ot]').val(basic_salary_with_fixed_ot);
    }
    // form validation and submit form of offer letter
    function generate_offer_letter() {
        $("#offerLetterFrm").validate({
            rules: {

                letter_date: {
                    required: true,
                },
                basic_salay: {
                    required: true,
                    number: true
                },
                salary: {
                    required: true,
                    number: true
                }



            },
            messages: {},
            errorPlacement: function(error, element) {
                if (element.hasClass("date_error")) {

                    error.insertAfter(element.parent());
                } else { // This is the default behavior of the script for all fields
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData = new FormData($("#offerLetterFrm")[0]);
                //formData.append('template', selectedUsr);
                $.ajax({
                    type: "POST",
                    url: "{{url('offerletter/store')}}",
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
                        $("#offerLetterFrm").find('.submit_button').attr("disabled", false);
                        $('.loader').hide();
                        $('#generate-offer-letter-modal').modal('hide');
                        var response = JSON.parse(data);
                        if (response.code == 200) {
                            //show notification
                            $.notify(response.msg, "success");
                            get_offer_letter_detail(); // form validation and save offer letter function
                        } else {
                            $.notify(response.msg, "warning");
                        }
                    },
                });
                return false;
            }

        });
    }
    //preview offer letter script
    $(document).on("click", ".offer-letter-preview", function() {
        var id = $(this).data('id'); // offer letter primary id
        $.get("/offerletter/offer-letter-preview/" + id, function(data) {
            $('#offer-letter-preview-modal').find('.modal-body').html(data);
            $('#offer-letter-preview-modal').modal('show');
        });
    });
    //deleteItem
    function deleteOfferLetter(id) {
        
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateOfferLetterStatus(id = id, type = 'delete', value = '1');
                    }
                },
            }
        });
    }
    //Approve offer letter
    function approveOfferLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Approve',
            content: 'Are you sure you want approve this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateOfferLetterStatus(id = id, type = 'approve', value = '2');
                    }
                },
            }
        });
    }

    function updateOfferLetterStatus(id, type, value) {
        
        $.ajax({
            type: "POST",
            url: "{{url('offerletter/update-status')}}",
            data: {
                id: id,
                value: value,
                type: type,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $(document).find(".tooltip").tooltip("hide");//hide the tooltip
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");
                } else {
                    $.notify(response.msg, "warning");
                }
                //reload data table in case of delete item
                if (type == 'delete') {
                    var active_page = $(".pagination").find("li.active a").text();
                    //reload datatable
                    get_offer_letter_detail();
                } else { // refresh after approved offer letter 
                    get_offer_letter_detail();
                }


            },
        });
    }

    //For scroll on particular section using id of offer letter
    function scrollToLetter() {
        $(document).on("click", ".sidebar", function() {
            var scroll_to_id = $(this).find('a').attr('scroll_to_id');
            var letterType = $(this).find('a').attr('type');
            if (letterType == 'offer_letter') {
                var $container = $('#generate-offer-letter-modal');
            } else if (letterType == 'employment_letter') {
                var $container = $('#generate-employment-letter-modal');
            } else if (letterType == 'warning_letter') {
                console.log('ddddddd');
                var $container = $('#generate-warning-letter-modal');
            } else if (letterType == 'increment_letter') {
                var $container = $('#generate-increment-letter-modal');
            }

            var $scrollTo = $(scroll_to_id);
            $container.animate({
                scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
            });

        });
    }

    //For e-signature of director
    function eSignature(modal_id) {
         
        //E-signature
        var signload = 1;
        if (signload == 1) {
            signload = 0;
            setTimeout(function() {
                //initialize signature pad
                initialize_signature_pad(modal_id);

                $(modal_id).find('.clear-signature').click(function() {
                    $(document).find(modal_id+' #director-signature').jSignature('reset');
                    $(document).find(modal_id+' #e_sign').val('');
                    $(document).find(modal_id+' #director-signature-img').attr('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D');
                }); 
                 
                $(modal_id).find('#director-signature').bind('change', function(e) {                   
                    var datapair = $(modal_id).find('#director-signature').jSignature("getData", "image");                    
                    if (datapair[1].length > 1908) {
                        var img = "data:" + datapair[0] + "," + datapair[1];
                        //show signature view
                        $(document).find(modal_id+' .signature-view').removeClass('d-none');
                        //hide signature pad
                        $(document).find(modal_id+' .signature-box').addClass('d-none');
                        $(document).find(modal_id+' #e_sign').val(img);
                        $(document).find(modal_id+' #director-signature-img').attr('src', img);
                    }
                });  

                $(modal_id).on('click', '.edit-signature', function() {           
                     
                    //hide signature view
                    $(document).find(modal_id+' .signature-view').addClass('d-none');
                    //show signature pad
                    $(document).find(modal_id+' .signature-box').removeClass('d-none');
                    //return false;
                    //empty signature pad
                    $(document).find(modal_id+' #director-signature').html('');
                    //empty signature value
                    $(document).find(modal_id+' #e_sign').val('');
                    $(document).find(modal_id+' #director-signature-img').attr('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D');
                    
                    //initialize signature pad
                     initialize_signature_pad(modal_id);
                     
                });
                

            }, 2000);
        }
    }

     //initialize signature pad
     function initialize_signature_pad(modal_id) {          
        var $sign = $(modal_id).find('#director-signature').jSignature({
            color: "#00f",
            lineWidth: 2,
            'background-color': 'transparent',
            'decor-color': 'transparent',
        });
    }
   
</script>
<!-- Offer letter script end -->

<!-- Employment letter script start -->
<script>
     // get employment letter detail
     function get_employment_letter_detail(){
        var employee_id = '<?php echo $user->id ?>';
        $.get("/employment/letter-detail/" + employee_id, function(data) {
            $('#letter-of-employment-tab').find('.append-employment-letter-html').html(data);
            initialize_tooltip();
        });
    }
    // for load the generate offer letter html
    $(document).on("click", ".generate-employment-letter", function() {
        var employee_id = '{{$user->id}}';
        var employee_type = '{{$user->employee_type}}';
        $.get("/employment/generate-employment-letter/" + employee_id+'/'+employee_type, function(data) {
            $('#generate-employment-letter-modal').find('.append-employment-letter').html(data);
            $('#generate-employment-letter-modal').modal('show');
            addEditEmploymentLetter(); // form validation and save employment letter function
            // for date picker
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#letter_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            $('#commencement_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            //Scroll letter on particular id
            scrollToLetter();
           //Load e-signature script
            eSignature('#generate-employment-letter-modal'); //Load e-signature script 
        });
    });
    // form validation and submit form of offer letter
    function addEditEmploymentLetter() {
        $("#employmentLetterFrm").validate({
            rules: {

                letter_date: {
                    required: true,
                },
                commencement_date: {
                    required: true,
                }


            },
            messages: {},
            errorPlacement: function(error, element) {
                // if (element.attr("name") == "commencement_date") {
                //     error.insertAfter(element);
                // }
                if (element.hasClass("date_error")) {

                    error.insertAfter(element.parent());
                } else { // This is the default behavior of the script for all fields
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData = new FormData($("#employmentLetterFrm")[0]);
                //formData.append('template', selectedUsr);
                $.ajax({
                    type: "POST",
                    url: "{{url('employment/store')}}",
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#employmentLetterFrm").find('.submit_button').attr("disabled", true);
                        $('.loader').show();
                    },
                    success: function(data) {
                        $("#employmentLetterFrm").find('.submit_button').attr("disabled", false);
                        $('.loader').hide();
                        $('#generate-employment-letter-modal').modal('hide');
                        var response = JSON.parse(data);
                        if (response.code == 200) {
                            //show notification
                            $.notify(response.msg, "success");
                            get_employment_letter_detail(); // form validation and save offer letter function
                        } else {
                            $.notify(response.msg, "warning");
                        }
                    },
                });
                return false;
            }

        });
    }

    //for edit the offer letter
    $(document).on("click", ".edit-employment-letter", function() {
        var employment_letter_id = $(this).data('id');
        var employee_type = '{{$user->employee_type}}';
        $.get("/employment/edit-employment-letter/" + employment_letter_id+'/'+employee_type, function(data) {
            $('#generate-employment-letter-modal').find('.append-employment-letter').html(data);
            $('#generate-employment-letter-modal').modal('show');
            addEditEmploymentLetter(); // form validation and save offer letter function
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#letter_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            $('#commencement_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            //Scroll letter on particular id
            scrollToLetter();
            //Load e-signature script
            eSignature('#generate-employment-letter-modal'); //Load e-signature script
        });
    });
    //deleteItem
    function deleteEmploymentLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateEmploymentLetterStatus(id = id, type = 'delete', value = '1');
                    }
                },
            }
        });
    }

    function updateEmploymentLetterStatus(id, type, value) {
        $("[data-toggle='tootip']").tooltip("hide");//hide the tooltip
        $.ajax({
            type: "POST",
            url: "{{url('employment/update-status')}}",
            data: {
                id: id,
                value: value,
                type: type,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $(document).find(".tooltip").tooltip("hide");//hide the tooltip
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");
                } else {
                    $.notify(response.msg, "warning");
                }
                //reload data table in case of delete item
                if (type == 'delete') {
                    var active_page = $(".pagination").find("li.active a").text();
                    //reload datatable
                    get_employment_letter_detail();
                } else { // refresh list after approved letter 
                    get_employment_letter_detail();
                }


            },
        });
    }
    //Approve employment letter
    function approveEmploymentLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Approve',
            content: 'Are you sure you want approve this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateEmploymentLetterStatus(id = id, type = 'approve', value = '2');
                    }
                },
            }
        });
    }

    //preview offer letter script
    $(document).on("click", ".employment-letter-preview", function() {
        var id = $(this).data('id'); // employment letter primary id
        $.get("/employment/view/" + id, function(data) {
            $('#employment-letter-preview-modal').find('.modal-body').html(data);
            $('#employment-letter-preview-modal').modal('show');
        });
    });
    // For employment file icon script
    $(document).on("click", ".employment-file-choose", function() {
        var recordId = $(this).data('id');
        //call upload document function
        employment_upload_endorsed_doc(recordId)
    });
    // upload employment document function
    function employment_upload_endorsed_doc(data_id) {
        //trigger on input file
        var file_class = 'employment_endorsed_doc' + data_id;
        $("." + file_class).trigger('click');
        form = new FormData();
        // call ajax after select the document
        $(document).on("change", "." + file_class, function(e) {
            //var doc_file = $(this).val();
            var doc_file = e.target.files[0].name; //get file name
            // $(this).siblings('span').text(doc_file);

            //call ajax after select the document 
            var formid = '#employment_endorsed_docFrm' + data_id;
            var formData = new FormData($(formid)[0]);
            $.ajax({
                type: 'post',
                dataType: 'JSON',
                url: '/employment/upload-endorsed-doc',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    console.log(data.code);
                    if (data.code == 200) {
                        $.notify(data.msg, "success");
                        //for refresh table
                        get_employment_letter_detail();
                    } else {
                        $.notify(data.msg, "warning");
                    }

                }
            });
        });
    }
</script>
<!-- Employment letter script end -->

<!-- Warning letter script start -->
<script>
    // get the active tab href when click on EMPLOYMENT CONTRACT tab
    $(document).on("click", "#correspondance-tab .nav-tabs li", function() {
        if ($(this).find('a').attr('href') == '#warning-letter-tab') {
            @can('manage_warning_letter')
            getWarningLetterList();
            @endcan
            $("#edit_button").hide();
        } else if ($(this).find('a').attr('href') == '#increment-letter-tab') {
            getIncrementLetterList();
            $("#edit_button").hide();
        }
        else  if ($(this).find('a').attr('href') == '#other-doc') {
            @can('manage_other_doc')
            get_otherdoc_list();
            @endcan
            $("#edit_button").hide();
        }
    });
    //list of warning letter
    function getWarningLetterList(data_search_value = '') {
        $('#warning_listing_table').dataTable().fnDestroy();
        var table = $('#warning_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: '<"top" <"row" <"col-sm-9" l> <"col-sm-3" f> >>tr<"bottom" <"row" <"col-sm-6" i> <"col-sm-6" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/warning/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'lettername',
                    name: 'lettername'
                },
                //only those have manage_user permission will get access
                {
                    targets: 2,
                    data: 'generated_date',
                    name: 'generated_date'

                },
                {
                    targets: 3,
                    data: 'endorsed_doc',
                    name: 'endorsed_doc'

                },
                {
                    targets: 4,
                    data: 'status',
                    name: 'status'

                },
                {
                    targets: 5,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '110px'

                }
            ],
            drawCallback: function() {
                // initialize_popover();
                initialize_tooltip();
            },
            oLanguage: {
                "sEmptyTable": "No records available"
            }

        });
    }
    $(document).on("click", ".generate-warning-letter", function() {
        var employee_id = '{{$user->id}}';
        $.get("/warning/generate-letter/" + employee_id, function(data) {
            $('#generate-warning-letter-modal').find('.append-warning-letter').html(data);
            $('#generate-warning-letter-modal').modal('show');
            addEditWarningLetter(); // form validation and save warning letter function 
            scrollToLetter(); //scroll modal by id
        });
    });
    //add and edit form with validation
    function addEditWarningLetter() {
        $("#warningLetterFrm").validate({
            rules: {
                'nature_infraction_id[]': {
                    required: true,
                    minlength: 1
                },
                date_time_location_of_infraction: {
                    required: true,
                },
                other: {
                    required: function() {
                        return $('input[name=nature_infraction_id]').is(':checked');
                    }
                },

            },
            messages: {},
            errorPlacement: function(error, element) {
                if (element.attr("name") == "nature_infraction_id[]") {
                    error.insertAfter(".nature_infraction");
                } else { // This is the default behavior of the script for all fields                        
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData = new FormData($("#warningLetterFrm")[0]);
                //formData.append('template', selectedUsr);
                $.ajax({
                    type: "POST",
                    url: "{{url('warning/store')}}",
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#warningLetterFrm").find('.submit_button').attr("disabled", true);
                        $('.loader').show();
                    },
                    success: function(data) {
                        $("#warningLetterFrm").find('.submit_button').attr("disabled", false);
                        $('.loader').hide();
                        $('#generate-warning-letter-modal').modal('hide');
                        var response = JSON.parse(data);
                        if (response.code == 200) {
                            //show notification
                            $.notify(response.msg, "success");
                            getWarningLetterList(); // form validation and save warning letter function
                        } else {
                            $.notify(response.msg, "warning");
                        }
                    },
                });
                return false;
            }

        });
    }
    //for edit the warning letter
    $(document).on("click", ".edit-warning-letter", function() {
        var warning_letter_id = $(this).data('id');
        $.get("/warning/edit-letter/" + warning_letter_id, function(data) {
            $('#generate-warning-letter-modal').find('.append-warning-letter').html(data);
            $('#generate-warning-letter-modal').modal('show');
            addEditWarningLetter(); // form validation and save warning letter function
            //Scroll letter on particular id
            scrollToLetter();
        });
    });
    // display the other nature infraction box when check on other
    $(document).on("click", "input[name='nature_infraction_id[]']", function() {
        $('.other_infraction').hide();
        $('input[name="nature_infraction_id[]"]:checked').each(function() {
            if (this.value == '12') {
                $('.other_infraction').show();
            }
        });

    });
    //deleteItem
    function deleteWarningLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateWarningLetterStatus(id = id, type = 'delete', value = '1');
                    }
                },
            }
        });
    }

    function updateWarningLetterStatus(id, type, value) {
        $("[data-toggle='tootip']").tooltip("hide");//hide the tooltip
        $.ajax({
            type: "POST",
            url: "{{url('warning/update-status')}}",
            data: {
                id: id,
                value: value,
                type: type,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $(document).find(".tooltip").tooltip("hide");//hide the tooltip
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");
                } else {
                    $.notify(response.msg, "warning");
                }
                //reload data table in case of delete item
                if (type == 'delete') {
                    var active_page = $(".pagination").find("li.active a").text();
                    //reload datatable
                    $('#warning_listing_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                } else { // refresh list after approved letter 
                    getWarningLetterList();
                }


            },
        });
    }
    //Approve employment letter
    function approveWarningLetter(id) {

        //show confirmation popup
        $.confirm({
            title: 'Approve',
            content: 'Are you sure you want approve this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateWarningLetterStatus(id = id, type = 'approve', value = '2');
                    }
                },
            }
        });
    }

    //preview warning letter script
    $(document).on("click", ".warning-letter-preview", function() {
        var id = $(this).data('id'); // employment letter primary id
        $.get("/warning/view/" + id, function(data) {
            $('#warning-letter-preview-modal').find('.modal-body').html(data);
            $('#warning-letter-preview-modal').modal('show');
        });
    });

    // For warning letter file icon script
    $(document).on("click", ".warning-file-choose", function() {
        var recordId = $(this).data('id');
        //call upload document function
        warning_upload_endorsed_doc(recordId)
    });
    // upload employment document function
    function warning_upload_endorsed_doc(data_id) {
        //trigger on input file
        var file_class = 'warning_endorsed_doc' + data_id;
        $("." + file_class).trigger('click');
        form = new FormData();
        // call ajax after select the document
        $(document).on("change", "." + file_class, function(e) {
            //var doc_file = $(this).val();
            var doc_file = e.target.files[0].name; //get file name
            // $(this).siblings('span').text(doc_file);

            //call ajax after select the document 
            var formid = '#warning_endorsed_docFrm' + data_id;
            var formData = new FormData($(formid)[0]);
            $.ajax({
                type: 'post',
                dataType: 'JSON',
                url: '/warning/upload-endorsed-doc',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    console.log(data.code);
                    if (data.code == 200) {
                        $.notify(data.msg, "success");
                        //for refresh table
                        getWarningLetterList();
                    } else {
                        $.notify(data.msg, "warning");
                    }

                }
            });
        });
    }
</script>
<!-- Warning letter script end -->

<!-- Increment letter script start -->
<script>
    //list of warning letter
    function getIncrementLetterList(data_search_value = '') {
        $('#increment_listing_table').dataTable().fnDestroy();
        var table = $('#increment_listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: '<"top" <"row" <"col-sm-9" l> <"col-sm-3" f> >>tr<"bottom" <"row" <"col-sm-6" i> <"col-sm-6" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/increment/list',
                type: "get",
                data: {
                    "id": '<?php echo $user->id ?>',
                    "data_search_value": data_search_value
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'

                },
                {
                    targets: 1,
                    data: 'lettername',
                    name: 'lettername'
                },
                //only those have manage_user permission will get access
                {
                    targets: 2,
                    data: 'generated_date',
                    name: 'generated_date'

                },
                {
                    targets: 3,
                    data: 'endorsed_doc',
                    name: 'endorsed_doc'

                },
                {
                    targets: 4,
                    data: 'status',
                    name: 'status'

                },
                {
                    targets: 5,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '110px'

                }
            ],
            drawCallback: function() {
                // initialize_popover();
                initialize_tooltip();
            },
            oLanguage: {
                "sEmptyTable": "No records available"
            }

        });
    }
    $(document).on("click", ".generate-increment-letter", function() {
        var employee_id = '{{$user->id}}';
        $.get("/increment/generate-letter/" + employee_id, function(data) {
            $('#generate-increment-letter-modal').find('.append-increment-letter').html(data);
            $('#generate-increment-letter-modal').modal('show');
            addEditIncrementLetter(); // form validation and save warning letter function 
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#increment_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });

        });
    });
    //add and edit form with validation
    function addEditIncrementLetter() {
        $("#incrementLetterFrm").validate({
            rules: {

                increment_date: {
                    required: true,
                },
                increment_amount: {
                    required: true,
                },

            },
            messages: {},
            errorPlacement: function(error, element) {
                if (element.hasClass("date_error")) {
                    error.insertAfter(element.parent());
                } else { // This is the default behavior of the script for all fields                        
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData = new FormData($("#incrementLetterFrm")[0]);
                //formData.append('template', selectedUsr);
                $.ajax({
                    type: "POST",
                    url: "{{url('increment/store')}}",
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $("#incrementLetterFrm").find('.submit_button').attr("disabled", true);
                        $('.loader').show();
                    },
                    success: function(data) {
                        $("#incrementLetterFrm").find('.submit_button').attr("disabled", false);
                        $('.loader').hide();
                        $('#generate-increment-letter-modal').modal('hide');
                        var response = JSON.parse(data);
                        if (response.code == 200) {
                            //show notification
                            $.notify(response.msg, "success");
                            getIncrementLetterList(); // form validation and save warning letter function
                        } else {
                            $.notify(response.msg, "warning");
                        }
                    },
                });
                return false;
            }

        });
    }
    //for edit the increment letter
    $(document).on("click", ".edit-increment-letter", function() {
        var increment_letter_id = $(this).data('id');
        $.get("/increment/edit-letter/" + increment_letter_id, function(data) {
            $('#generate-increment-letter-modal').find('.append-increment-letter').html(data);
            $('#generate-increment-letter-modal').modal('show');
            addEditIncrementLetter(); // form validation and save increment letter function                 
            var date = new Date();
            date.setDate(date.getDate() - 1);
            $('#increment_date').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: date,
                format: 'dd/mm/yyyy',
            });
            //Scroll letter on particular id
            scrollToLetter();
        });
    });
    //deleteItem
    function deleteIncrementLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure you want delete this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateIncrementLetterStatus(id = id, type = 'delete', value = '1');
                    }
                },
            }
        });
    }

    function updateIncrementLetterStatus(id, type, value) {
        $("[data-toggle='tootip']").tooltip("hide");//hide the tooltip
        $.ajax({
            type: "POST",
            url: "{{url('increment/update-status')}}",
            data: {
                id: id,
                value: value,
                type: type,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $(document).find(".tooltip").tooltip("hide");//hide the tooltip
                var response = JSON.parse(data);
                if (response.code == 200) {
                    $.notify(response.msg, "success");
                } else {
                    $.notify(response.msg, "warning");
                }
                //reload data table in case of delete item
                if (type == 'delete') {
                    var active_page = $(".pagination").find("li.active a").text();
                    //reload datatable
                    $('#increment_listing_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                } else { // refresh list after approved letter 
                    getIncrementLetterList();
                }


            },
        });
    }
    //Approve increment letter
    function approveIncrementLetter(id) {
        //show confirmation popup
        $.confirm({
            title: 'Approve',
            content: 'Are you sure you want approve this?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function() {
                        updateIncrementLetterStatus(id = id, type = 'approve', value = '2');
                    }
                },
            }
        });
    }

    //preview increment letter script
    $(document).on("click", ".increment-letter-preview", function() {
        var id = $(this).data('id'); // employment letter primary id
        $.get("/increment/view/" + id, function(data) {
            $('#increment-letter-preview-modal').find('.modal-body').html(data);
            $('#increment-letter-preview-modal').modal('show');
        });
    });

    // For increment letter file icon script
    $(document).on("click", ".increment-file-choose", function() {
        var recordId = $(this).data('id');
        //call upload document function
        increment_upload_endorsed_doc(recordId)
    });
    // upload increment document function
    function increment_upload_endorsed_doc(data_id) {
        //trigger on input file
        var file_class = 'increment_endorsed_doc' + data_id;
        $("." + file_class).trigger('click');
        form = new FormData();
        // call ajax after select the document
        $(document).on("change", "." + file_class, function(e) {
            //var doc_file = $(this).val();
            var doc_file = e.target.files[0].name; //get file name
            // $(this).siblings('span').text(doc_file);

            //call ajax after select the document 
            var formid = '#increment_endorsed_docFrm' + data_id;
            var formData = new FormData($(formid)[0]);
            $.ajax({
                type: 'post',
                dataType: 'JSON',
                url: '/increment/upload-endorsed-doc',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(data) {
                    $('.loader').hide();
                    console.log(data.code);
                    if (data.code == 200) {
                        $.notify(data.msg, "success");
                        //for refresh table
                        getIncrementLetterList();
                    } else {
                        $.notify(data.msg, "warning");
                    }

                }
            });
        });
    }
    //Calculate the incremented salary from increment amount
    $(document).on("keyup", "input[name=increment_amount]", function() {
        var increment_amount = $(this).val();
        var basic_salary = $('#incrementLetterFrm').find("input[name=basic_salary]").val();
        var incremented_salary = parseFloat(increment_amount) + parseFloat(basic_salary);
        incremented_salary = incremented_salary.toFixed(2);
        if (isNaN(parseFloat(incremented_salary))) {
            incremented_salary = '0.00';
        }
        //Display incremented salary
        $('#incrementLetterFrm').find("input[name=incremented_salary]").val(incremented_salary);
    }).on("click", "input[name=increment_amount]", function() {
        var increment_amount = $(this).val();
        var basic_salary = $('#incrementLetterFrm').find("input[name=basic_salary]").val();
        var incremented_salary = parseFloat(increment_amount) + parseFloat(basic_salary);
        incremented_salary = incremented_salary.toFixed(2);
        if (isNaN(parseFloat(incremented_salary))) {
            incremented_salary = '0.00';
        }
        //Display incremented salary
        $('#incrementLetterFrm').find("input[name=incremented_salary]").val(incremented_salary);
    });
</script>
<!-- Increment letter script end -->

<!-- vs end script here -->
<script>
    
    
    $(document).on("click", ".on_click", function() {
        var data_val = $(this).attr('data-val');
        if (data_val == '1') {
            $(this).attr('data-val', '0');
            $(this).val('Expand');
            $(this).html('');
            $(this).html('<i class="ik ik-chevron-down" style="color:black;"></i>');
        } else {
            $(this).attr('data-val', '1'); //setter
            $(this).val('Collapse');
            $(this).html('');
            $(this).html('<i class="ik ik-chevron-up" style="color:black;"></i>');
        }
    });
    
    //function for document add more functionality  
   function add_document_field(){
       var count = $(".document").length;
       if(count == 2){
           
       }else{
        var html = '<div class="document"><div class="form-group"><select name=document[] class="form-control d_value" data-d_val=""><option value="">Select</option><option value="0">Identification card </option><option value="1">Passport</option></select></div><div class="document_attach"></div></div>';
        $('#document').append(html); 
       }
        
   }
   
   $(document).on("change", "select[name='document[]']", function() {
      var document_value = $(this).val();
      var html = '';
      
      if(document_value == " "){
        var remove_val =  $(this).attr('data-d_val') ;
        const index = document_array.indexOf(remove_val);
        if (index > -1) {
          document_array.splice(index, 1);
        }
        $(this).attr('data-d_val','');
      }
      if(jQuery.inArray(document_value, document_array) == -1){   
            document_array.push(document_value);
       if(document_value == '1'){
          var remove_val =  $(this).attr('data-d_val') ;
        const index = document_array.indexOf(remove_val);
        if (index > -1) {
          document_array.splice(index, 1);
        }
         var html = '<div class="form-group"><label for="passport_no">Passport Number</label><div class="input-group"><input type="text" class="form-control alphanum" id="passport_no" placeholder="Passport No" name="passport_no" onpaste="return false;" ondrop="return false;" value="" required>'+
                 '<span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="passport_flag" style="color:red; display:none" data-toggle="tooltip" title="Upload Passport Copy" data-placement="left"></i></span></div></div><div class="form-group">'+
                 '<label for="expiry_date">Expiration Date</label><input type="text" class="form-control expiry_date " id="passport_expiry_date" placeholder="DD/MM/YYYY" name="expiry_date" required readonly></div><div class="form-group">'+
                 '<label for="passport_copy">Password Copy<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label><input type="file" name="passport_copy[]" class="file-upload-default" accept=".jpg,.jpeg,.png" id="passport_copy" multiple>'+
                 '<div class="input-group col-xs-12"><input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image"><span class="input-group-append"><button class="file-upload-browse btn btn-primary" type="button">Upload</button></span></div>'+
                 '<p id="passport_copy_err" class="error"></p></div><div class="" id="list" style="display: none;"><div class="form-group" id="show"></div></div>';
        $(this).attr('data-d_val',document_value) ;
        
       
     }
     
      if(document_value == '0'){
          var remove_val =  $(this).attr('data-d_val') ;
        const index = document_array.indexOf(remove_val);
        if (index > -1) {
          document_array.splice(index, 1);
        }
         var html ='<div class="form-group"><label for="nric_no">NRIC No</label><div class="input-group"><input type="text" class="form-control" id="nric_no" placeholder="NRIC No" name="nric_no" maxlength="9" required>'+
                    '<span class="input-group-append" role="left-icon"><i class="ik ik-alert-triangle" id="nric_copy_flag" style="color:red; display:none" data-toggle="tooltip" title="Upload NRIC ID Card" data-placement="left"></i></span></div></div>'+
                    '<div class="form-group"><label for="nric_copy">NRIC Copy<span style="font-size: 9px;">&nbsp;(Only formats are allowed: jpeg, jpg, png)</span></label>'+
                    '<input type="file" name="nric_copy" class="file-upload-default" accept=".jpg,.jpeg,.png" id="nric_copy"><div class="input-group col-xs-12"><input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">'+
                    '<span class="input-group-append"><button class="file-upload-browse btn btn-primary" type="button">Upload</button></span></div><label id="nric_copy_err" class="error"></label></div><div id="nric_copy_display" style="display: none">'+
                    '<div class="form-group"><div class="copy_class"><img id="nric_copy_show" src="" class="img-responsive"></div></div></div>';
        $(this).attr('data-d_val',document_value);
        }
       }else{
         var remove_val =  $(this).attr('data-d_val') ;
        const index = document_array.indexOf(remove_val);
        if (index > -1) {
          document_array.splice(index, 1);
        }
        $(this).attr('data-d_val','');
        var html = "<p style='color:red'>this type is already selected</p>"
       }
     
        $(this).closest('.document').find(".document_attach").html('');
        $(this).closest('.document').find(".document_attach").html(html);
        
        $(document).find('#passport_expiry_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy', 
        }); 
    });
</script>


<script src="{{ asset('plugins/jSignature/jSignature.js') }}"></script>
<script src="{{ asset('plugins/form-wizard/from_wizard.js') }}"></script>
@endpush
@endsection