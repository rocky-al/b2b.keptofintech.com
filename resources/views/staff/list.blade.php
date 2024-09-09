@extends('layouts.main')
@section('title', 'Users')
@section('content')
<div class="row">

@if(Auth::user()->user_type == 1)
<br>
<div class="col-md-4 text-center">
    <div id="day_amount" class="card">
        <h5>Rs/- <span id="day_amount_value"></span></h5>
        <h6><b>Day</b></h6>
    </div>
</div>
   <div class="col-md-4">
    <div id="week_amount" class="card text-center">
        <h5>Rs/- <span id="week_amount_value"></span></h5>
        <h6><b>Week</b></h6>
    </div>
</div>

<div class="col-md-4">
    <div id="month_amount" class="card text-center">
        <h5>Rs/- <span id="month_amount_value"></span></h5>
        <h6><b>Month</b></h6>
    </div>
</div>
@endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h3><i class="ik ik-list"></i> {{ __('Users')}}</h3>
                <div class="pull-right">
                    <div class="row">
                        @if(Auth::user()->user_type == 1)
                        <a class="btn btn-outline-secondary btn-rounded-20 mr-2" href="{{ url('/transactions') }}">
                            Transactions
                        </a>
                        <a class="btn btn-outline-secondary btn-rounded-20 mr-2" href="{{ url('/refund') }}">
                            Refund
                        </a>
                        @endif
                        <a class="btn btn-outline-primary btn-rounded-20 mr-2" href="{{ url('/users/create') }}">
                            <i class="ik ik-plus"></i>Add User
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="data_table" class="table">
                    <thead>
                        <tr>
                            <th>{{ __('##')}}</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Email')}}</th>
                            <th>{{ __('Phone No.')}}</th>
                            <th>{{ __('Created Date')}}</th>
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

@endsection
<!-- push external js -->
@push('script')
<!-- Select the state when select the country -->
<script>
    $(document).ready(function() {

    });
</script>
<!--server side table script start-->
<script>
    //listing data table
    $(document).ready(function() {
        var table = $('#data_table').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10, 50, 100, 500],
            ajax: {
                url: "{{ route('users') }}",
                data: function(d) {
                    d.status = $('#status').val();
                    d.user_type = $('#user_type').val();
                },
                // success: function(response) {
                //     $('#week_amount_value').text(response.states.week);
                //     $('#month_amount_value').text(response.states.month);
                //     $('#day_amount_value').text(response.states.day); 
                //     $('#data_table').DataTable().clear().rows.add(response.data).draw();
                // },

                error: function() {
                    alert("{{__('something_went_wrong')}}");
                },

            },

            "aoColumns": [{
                    mData: 'id'
                },
                {
                    mData: 'name'
                },

                {
                    mData: 'email'
                },

                {
                    mData: 'phone'
                },
                {
                    mData: 'created_at'
                },


                {
                    mData: 'actions'
                },

            ],
            "aoColumnDefs": [{
                "bSortable": false,
                'aTargets': [-1, -2, -3, -4, -5]
            }, ],
            order: [
                [0, 'desc']
            ]
        });

        $('.reset').on('click', function(event) {
            event.preventDefault();
            $('#user_type').val('');
            $('#status').val('');
            $('#data_table').DataTable().ajax.reload();
        })


        $('.filter').on('click', function(event) {
            event.preventDefault();
            $('#data_table').DataTable().ajax.reload();
        })
    });



$(document).ready(function() {
    $.ajax({
        url: "{{ route('dashboardStates') }}",
        type: "GET", // or "POST", depending on your server endpoint
        success: function(response) {
            console.log(response);
            $('#week_amount_value').text(response.week);
            $('#month_amount_value').text(response.month);
            $('#day_amount_value').text(response.day); 
        },
        error: function(xhr, textStatus, errorThrown) {
            alert("{{__('something_went_wrong')}}");
        }
    });
});



    //deleteItem
    function deleteItem(id) {
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
                        removedata(id = id);
                    },
                }
            }
        });
    }


    function removedata(id) {
        $.ajax({
            type: "POST",
            url: "{{route('delete.users')}}",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                console.log(data.success)
                $.notify(data.success, "success");
                $('#data_table').DataTable().ajax.reload();
            },
        });
    }




    $(document).ready(function() {
        $(document).on('change', '.status-checkbox', function() {
            var id = $(this).data("id");
            if (this.checked) {
                var value = '1';
            } else {
                var value = '0';
            }
            updateItemStatus(id = id, type = 'status', value = value);
        })
    });

    //update item
    function updateItemStatus(id, type, value) {
        $.ajax({
            type: "POST",
            url: "{{route('update.status')}}",
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
                    $('#data_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                }

            },
        });
    }


    // $(document).on('change', '#user_type,#status', function() {
    //     filter_listing();
    // });

    function filter_listing() {
        var form_data = $(document).find("#filter-subscription").serializeArray()
        get_cities(form_data);
    }
</script>
@endpush