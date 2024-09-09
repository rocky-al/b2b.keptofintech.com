@extends('layouts.main')
@section('title', 'Transactions')
@section('content')
<div class="row">

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
<br>
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header justify-content-between">
                <h3><i class="ik ik-list"></i> {{ __('Transactions')}}</h3>
            </div>
            <div class="card-body">
                <table id="data_table" class="table">
                    <thead>
                        <tr>
                            <th>{{ __('##')}}</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Email')}}</th>
                            <th>{{ __('Phone No.')}}</th>
                            <th>{{ __('Client Txn ID')}}</th>
                            <th>{{ __('Txn ID')}}</th>
                            <th>{{ __('Amount')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Created Date')}}</th>
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
    $(document).ready(function() {
        var table = $('#data_table').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [50, 100, 500],
            ajax: {
                url: "{{ route('getTxnList') }}",
                data: function(d) {
                    d.status = $('#status').val();
                    d.user_id = "{{request('ref_')?request('ref_'):''}}";
                },

                error: function() {
                    alert("{{__('something_went_wrong')}}");
                }
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
                    mData: 'client_txn_id'
                },
                {
                    mData: 'txn_id'
                },
                {
                    mData: 'amount'
                },
                {
                    mData: 'status'
                },
                {
                    mData: 'created_at'
                }

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



    function filter_listing() {
        var form_data = $(document).find("#filter-subscription").serializeArray()
        get_cities(form_data);
    }
</script>
@endpush