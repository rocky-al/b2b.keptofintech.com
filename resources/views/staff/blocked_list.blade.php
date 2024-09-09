@extends('layouts.main')
@section('title', 'Reported Users')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            
        </div>
        <div class="card">
            <div class="card-header justify-content-between">
                <h3><i class="ik ik-list"></i> {{ __('Reported Users')}}</h3>
                <div class="pull-right">
                </div>
         
            </div>
            <div class="card-body">
                
                    <div class="row">
                        @csrf
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="start-date" placeholder="Start Date" name="start_date">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="end-date" placeholder="End Date" name="end_date">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <button type="button" class="filter_btn btn btn-outline-primary mr-2"> Apply </button>
                                <button type="button" value="reset" class="reset_btn btn btn-outline-success" > Reset </button>
                            </div>
                        </div>

                    </div>
                
                <table id="data_table" class="table">
                    <thead>
                        <tr>
                            <th>{{ __('##')}}</th>
                            <th>{{ __('Reported By')}}</th>
                            <th>{{ __('Reported To')}}</th>
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
<script>
    
    $(document).ready(function() {
        //listing data table
        var table = $('#data_table').DataTable({
            responsive: true, 
            "bProcessing": true,
            "serverSide": true,
            "lengthMenu": [10,50,100,500],
            bFilter:false, //hide defalt search box
            ajax: {
                url: "{{ url('users/blocked') }}",
                data: function(d) {
                    d.start_date = $('#start-date').val();
                    d.end_date = $('#end-date').val();
                },
                error: function() {
                    alert("{{__('something_went_wrong')}}");
                }
            },
            "aoColumns": [
                        {mData: 'id',name: 'id'},
                        {mData: 'blocked_by',name: 'blocked_by'},
                        {mData: 'blocked_to',name: 'blocked_to'},
                        {mData: 'created_at',name: 'created_at'}
            ],
            "aoColumnDefs": [{
                "bSortable": false,
            }, ],
            order: [ [0, 'desc'] ]
        }); 
        table.draw();
        $('.reset_btn').on('click', function(event){
            $('#start-date').val('');
            $('#end-date').val('');
            table.draw();
        })
        $('.filter_btn').on('click', function(event){
            table.draw();
        })
        $('#start-date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'mm-dd-yyyy',
            showClear: true,
            clearBtn : true,
        });
        $('#end-date').datepicker({
            uiLibrary: 'bootstrap4',
            //maxDate: new Date(),
            format: 'mm-dd-yyyy',
            showClear: true,
            clearBtn : true,
        });
    });

</script>
@endpush
