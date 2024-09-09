@extends('layouts.main') 
@section('title', $user->name)
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush


<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-user-plus bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Edit User')}}</h5>
                        <span>{{ __('Create new user, assign roles & permissions')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{url('/')}}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">{{ __('User')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <!-- clean unescaped data is to avoid potential XSS risk -->
                            {{ clean($user->name, 'titles')}}
                        </li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- start message area-->
        @include('include.message')
        <!-- end message area-->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link " id="home-tab" href="{{url('setting/payroll/payroll-generate')}}" role="tab">GENERAL INFO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" href="#profile" role="tab">CERTIFICATES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="agency-tab" href="{{url('setting/payroll/agency')}}">EMPLOYMENT CONTRACT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" href="#settings" role="tab">CORRESPONDENCES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="bonus-tab" href="javascript:void(0);" role="tab">BONUS/LOAN</a>
                        </li>
                    </ul>
                    <div class="tab-pane" id="bonus-tab" role="tabpanel" aria-labelledby="bonus-tab">
                        <ul class="nav nav-tabs sub-tabs" id="mysubTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link  active" id="bonus-added" href="javascript:void(0);" role="tab">Bonus Added</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" href="javascript:void(0);" role="tab">Loan Added</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" href="javascript:void(0);" role="tab">Advance Taken</a>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header justify-content-between">
                                        <h3>{{ __('Manage Bonus')}}</h3>
                                        <button class="btn btn-outline-primary btn-rounded-20 pull-right" href="#" onclick="addItem()">
                                            Add Bonus
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <table id="listing_table" class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Sr')}}</th>
                                                    <th>{{ __('Employee Name')}}</th>
                                                    <th>{{ __('Amount')}}</th>
                                                    <th>{{ __('Date Added')}}</th>
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
                        <label class="d-block">Select Employee Name</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter Title">
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
<!-- push external js -->
@push('script') 
<script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
<!--get role wise permissiom ajax script-->
<script src="{{ asset('js/get-role.js') }}"></script>
<!--server side table script start-->
<script>
    //listing data table
    $(document).ready(function() {

        var table = $('#listing_table').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            sDom: '<"top" <"row" <"col-sm-9" l> <"col-sm-3" f> >>tr<"bottom" <"row" <"col-sm-6" i> <"col-sm-6" p>>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/employee/bonus/list',
                type: "get",
                data:{"id":'<?php echo $user->id ?>'}
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
                    data: 'employee_id',
                    name: 'employee_id'

                },
                {
                    targets: 2,
                    data: 'amount',
                    name: 'amount'
                },
                //only those have manage_user permission will get access
                {
                    targets: 3,
                    data: 'created_at',
                    name: 'created_at'
                   
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
                    searchable: false
                }
            ]
        });
    });
    // add item
    function addItem() {
        //reset form
        $("#addEditForm")[0].reset();
        //open modal
        $("#addEditModal").modal("show");
        //change modal title
        $("#addEditModal").find(".modal-title").text('Add Bonus');
        //put id zero in case of add new item
        $("#addEditModal").find("input[name='id']").val(0);

    }
</script>
<!--server side table script end-->
@endpush
@endsection
