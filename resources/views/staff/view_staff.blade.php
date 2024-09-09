@extends('layouts.main')
@section('title', 'User Info')
@section('url_name', '/users')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/form-wizard/from_wizard.css') }}">

<div class="card">
    <div class="card-header justify-content-between d-flex">

        <h3>{{ __('User Info')}}</h3>

        <div class="pull-right">
            <a class="btn btn-outline-primary btn-rounded-20" href="{{ url('/users') }}">
                <i class="ik ik-list"></i> List of Users
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pkgs">{{ __('Name')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ isset($user_detail) ? $user_detail->first_name :''}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pkgs">{{ __('Email')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ isset($user_detail) ? $user_detail->email :''}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pkgs">{{ __('Registered As')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ isset($user_detail) && $user_detail->registered_as == '0' ? 'Business' : 'Individual' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pkgs">{{ __('Status')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ isset($user_detail) && $user_detail->status == '1' ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vol_wt">{{ __('Phone Number')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            {{ isset($user_detail) ? (!empty($user_detail->country_code) ? $user_detail->country_code.'-' : '').$user_detail->phone : '' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">

                    @if($user_detail->registered_as == '0')
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vol_wt">{{ __('EIN Number')}}</label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ isset($user_detail) ? $user_detail->ein_number :''}}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vol_wt">{{ __('Bio')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            {{ isset($user_detail) ? $user_detail->bio :''}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vol_wt">{{ __('Business Name')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group">
                            {{ isset($user_detail) ? $user_detail->business_name :''}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="dimensions">{{ __('Profile Image')}}</label>
                    </div>
                    <div class="col-sm-3">
                        <div id="profile_image_display" style="display: {{ (isset($user_detail->profile_image) && !empty($user_detail->profile_image) ? 'block': 'none')}};">
                            <div class="form-group">

                                <?php
                                echo '<a href="javascript:" onclick=imageZoom(\'storage\',\'' . $user_detail->profile_image . '\')>';
                                ?>
                                <div class="copy_class">
                                    <img id="profile_image_show" src="{{ isset($user_detail->profile_image) && !empty($user_detail->profile_image) ? url('storage/'.$user_detail->profile_image) : '' }}" class="img-responsive">
                                </div>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- <div class="card">
    <div class="card-header justify-content-between d-flex">
        <h3>{{ __('Blocked User Report')}}</h3>
    </div>
    <div class="card-body">
        <div class="    ">
            <table id="data_table" class="table">
                <thead>
                    <tr>
                        <th>{{ __('##')}}</th>
                        <th>{{ __('Blocked To')}}</th>
                        <th>{{ __('Created Date')}}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div> -->


<!-- push external js -->
@push('script')
<script src="{{ asset('plugins/form-wizard/from_wizard.js') }}"></script>

<script>
    //listing data table
    // $(document).ready(function() {
            //     var table = $('#data_table').DataTable({
            //         responsive: true,
            //         "bProcessing": true,
            //         "serverSide": true,
            //         "lengthMenu": [10, 50, 100, 500],
            //         ajax: {
            //             url: "{{ url('blocked_users') }}",
            //             data: function(d) {
            //                 d.id = {{$user_detail->id}};
            //             },

            //             error: function() {
            //                 alert("{{__('something_went_wrong')}}");
            //             }
            //         },

            //         "aoColumns": [{
            //                 mData: 'id'
            //             },
            //             {
            //                 mData: 'name'
            //             },                        
            //             {
            //                 mData: 'created_at'
            //             },

            //         ],
            //         "aoColumnDefs": [{
            //             "bSortable": false,
            //             'aTargets': []
            //         }, ],
            //         order: [
            //             [0, 'desc']
            //         ]
            //     }); 
            // });
</script>


@endpush
@endsection