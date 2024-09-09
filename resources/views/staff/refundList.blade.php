@extends('layouts.main')
@section('title', 'Transactions')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header justify-content-between">
                <h3><i class="ik ik-list"></i>Refund</h3>
                <div class="pull-right">
                    <div class="row">
                       

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
                            <th>{{ __('Client Txn ID')}}</th>
                            <th>{{ __('Txn ID')}}</th>
                            <th>{{ __('Amount')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Refund')}}</th>
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

    //listing data table
    $(document).ready(function() {

        var table = $('#data_table').DataTable({
            responsive: true,
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [50, 100, 500],
            ajax: {
                url: "{{ route('getRefundList') }}",
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
                    mData: 'refund'
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



function refunduser(id) {
    $.ajax({
        type: "POST",
        url: "{{ route('refunduser') }}",
        data: {
            id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.message) {
                alert(response.message);
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            }  else {
                $.notify('Unknown response', "warning");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            $.notify('Error occurred while processing the request', "error");
        }
    });
}

</script>
@endpush