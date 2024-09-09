@extends('layouts.main') 
@section('title', 'Employee')
@section('url_name', '/employees/employee')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush

    
    <div class="container-fluid">

        <div class="row">
            <!-- start message area-->
<!--            @include('include.message')-->
            <!-- end message area-->
           
                <div class="card ">
                    <div class="card-header justify-content-between">
                        <h3><i class="ik ik-list"></i> {{ ($employee_type == '1') ? 'Employees' : 'Staff Users' }}</h3>
                     @if($employee_type == '1')
                      @can('add_employee')
                     <a href="{{url('/employee/'.$type.'/create')}}"><button class="btn btn-outline-primary btn-rounded-20 pull-right">
                            <i class="ik ik-plus"></i>Add New {{ ($employee_type == '1') ? 'Employee' : 'Staff User' }}
                    </button></a>
                       @endcan
                       @else
                        @can('add_staff_user')
                         <a href="{{url('/employee/'.$type.'/create')}}"><button class="btn btn-outline-primary btn-rounded-20 pull-right">
                            <i class="ik ik-plus"></i>Add New {{ ($employee_type == '1') ? 'Employee' : 'Staff User' }}
                         </button></a>
                       @endcan
                       @endif
                    </div>
                     <div class="">
                            <!-- Tiles section start -->
                <div class="tiles_ui">
                    <ul>
                        <li class="light_green " >
                            <i class="ik ik-users"></i>
                            <h3>Total Employees &nbsp;<br><p id="employee_count">{{ $employee_count }}</p></h3>
                            <i class="fa fa-chevron-circle-right cursor-pointer" data-toggle="popover" data-placement="right"  data-content="" style="font-size: 20px;"></i>
                            
                        </li>
                        <li class="light_orange " >
                            <i class="ik ik-users"></i>
                            <h3>Total Singaporean  &nbsp;<br><p id="singaporeans_count">{{ $singaporeans_count }}</p></h3>
                            <i class="fa fa-chevron-circle-right cursor-pointer" data-toggle="popover" data-placement="right"  data-content="" style="font-size: 20px;"></i>
                        </li>
                        
                        <li class="light_dark ">
                             <i class="ik ik-users"></i>
                             <h3>Total Foreigners  &nbsp;<br><p id="foreigners_count">{{ $foreigners_count }}</p></h3>
                             <i class="fa fa-chevron-circle-right cursor-pointer" data-toggle="popover" data-placement="right"  data-content="" style="font-size: 20px;"></i>
                             
                        </li>
                    </ul>
                </div>                    
                         <div class="card-filter">
                             <div class="">
                                 <button class="btn btn-outline-primary btn-rounded-20 " onclick="filter_display();">
                            <i class="ik ik-search"></i> Search
                         </button></div>
                             <div class=" searching_filters pt-15 " id="filter_dis" style="display: none">
                                 <div class="row">
<!--                                     <div class="col-md-1">
                                         <h6 class="mt-10">Filters</h6>
                                     </div>-->
                                     <div class="col-md-2">
                                         <div class="form-group">
                                             <input type="text" class="form-control" name="first_name"  id="first_name" placeholder="Name" value="" onkeyup="filter_employe_list()" autocomplete="off">
                                         </div>
                                     </div>
<!--                                     <div class="col-md-2">
                                         <div class="form-group">
                                             <input type="text" class="form-control" name="last_name"  id="last_name" placeholder="Last Name" value="" onkeyup="filter_employe_list()" autocomplete="off">
                                         </div>
                                     </div>-->
<!--                                     <div class="col-md-2">
                                         <div class="form-group">
                                             <input type="text" class="form-control" name="email"  id="email" placeholder="Email" value="" onkeyup="filter_employe_list()" autocomplete="off">
                                         </div>
                                     </div>-->
                                      <div class="col-md-2">
                                         <div class="form-group">
                                             <input type="text" class="form-control" name="phone"  id="phone" placeholder="Phone Number" value="" onkeyup="filter_employe_list()" autocomplete="off">
                                         </div>
                                     </div>
                                      <div class="col-md-2">
                                         <div class="form-group">
                                             <input type="text" class="form-control" name="address"  id="address" placeholder="Address" value="" onkeyup="filter_employe_list()" autocomplete="off">
                                         </div>
                                     </div>
                                     
                                      <div class="col-md-2">
                                         <div class="form-group">
                                            <select id="status" name="status" class="form-control" onchange="filter_employe_list()" autocomplete="off">
                                                 <option value="">{{ __('All Status')}}</option>
                                                 <option value="0">{{ __('Inactive')}}</option>
                                                 <option value="1">{{ __('Active')}}</option>
                                                 <option value="2">{{ __('Terminated')}}</option>
                                                 <option value="3">{{ __('Resigned')}}</option>
                                             </select>
                                         </div>
                                     </div>
                                      <div class="col-md-2">
                                         <div class="form-group">
                                            <select id="type" name="type" class="form-control" onchange="filter_employe_list()" autocomplete="off">
                                                 <option value="">{{ __('All Type')}}</option>
                                                 <option value="0">{{ __('Singaporean Citizen')}}</option>
                                                 <option value="1">{{ __('Permanent Resident')}}</option>
                                                 <option value="2">{{ __('Foreigners')}}</option>
                                                 
                                             </select>
                                         </div>
                                     </div>
                                     <div class="col-md-2">
                                         <div class="form-group">
                                            <select id="employee_type" name="employee_type" class="form-control" onchange="filter_employe_list()" autocomplete="off">
                                                 <option value="">{{ __('All')}}</option>
                                                 <option value="1">{{ __('Non Managemenrt')}}</option>
                                                 <option value="0">{{ __('Management')}}</option>
                                             </select>
                                         </div>
                                     </div>
                                      <div class="col-md-2">
                                         <div class="form-group">
                                             {!! Form::select('position', $positions, null,[ 'class'=>'form-control','id'=>'position','placeholder' => 'Select Position','onchange' => "filter_employe_list()"]) !!}
                                            
                                         </div>
                                     </div>
                                     <div class="col-md-2">
                                         <div class="form-group">
                                             <div class="date" data-provide="datepicker">
                                                 <input type="text" class="form-control" name="hire_date"  id="hire_date" placeholder="Hire date" value=""  autocomplete="off">
                                                 <input type="hidden" name="start_date" id="start_date" value="">
                                                 <input type="hidden" name="end_date" id="end_date" value="">
                                             </div>
                                         </div>
                                     </div>
<!--                                     <div class="col-md-1">
                                         <div class="form-group">
                                             <button type="button" class="btn btn-outline-primary btn-rounded-20 pull-right" id="filter_employee_list_data">
                                                 Apply
                                             </button>
                                         </div>
                                     </div>-->

                                     <div class="col-md-1">
                                         <div class="form-group">
                                             <button type="button" class="btn btn-outline-primary btn-rounded-20 pull-right" id="reset_employee_list_data">
                                                <i class="ik ik-rotate-ccw "></i>Reset
                                             </button>
                                         </div>
                                     </div>

                                 </div>
                                 </div>
                         </div>
                     </div>
                    <div class="card-body">
                        
                        <table id="listing_table" class="table">
                            <thead>
                                <tr>
                                    <th width="5%">{{ __('##')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th width="12%">{{ __('Phone Number')}}</th>
                                    <th>{{ __('Address')}}</th>
                                    <th width="15%">{{ __('Designation')}}</th>
                                    <th width="10%">{{ __('Type')}}</th>
                                    <th width="10%">{{ __('Status')}}</th>
                                    <th width="10%">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
          
        </div>
    </div>
    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <!--server side users table script-->
    <script>
        
    $(document).ready(function() {
     initialize_popover_click();
    //call employee list function first time
     get_employee_list(); 
      // filter by date range
    $('#hire_date').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
             cancelLabel: 'Clear'

        },
    });
    $('input[name="hire_date"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        var start_date = picker.startDate.format('YYYY-MM-DD');
        var end_date = picker.endDate.format('YYYY-MM-DD');
        $("#start_date").val(picker.startDate.format('YYYY-MM-DD'));
        $("#end_date").val(picker.endDate.format('YYYY-MM-DD'));
        filter_employe_list();
    });
    $('input[name="hire_date"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $("#start_date").val('');
        $("#end_date").val('');
        filter_employe_list();
    });
  
    });
    
    $('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});
    //function for get employee list
     function get_employee_list(data_search_value = ''){
         
         var employee_type = "{{ $employee_type }}";
         var type = "{{ $type }}";

       

         //listing data table
        $('#listing_table').dataTable().fnDestroy();
        var table = $('#listing_table').DataTable({
            pageLength: 100,
            lengthMenu: [
                [100,200,500],
                [100,200,500]
            ],
            sDom: 'tr<"bottom" <"row" <"col-sm-4" l><"col-sm-3" i><"col-sm-5" p>>>',//'<"top" <"row" <"col-sm-9" l> <"col-sm-3" f> >>tr<"bottom" <"row" <"col-sm-6" i> <"col-sm-6" p>>>',
            processing: true,
            serverSide: true,
            ajax: {

                

                url: '/employee/'+type+'/list',
                type: "get",
                dataType : "json",
                data: {"data_search_value": data_search_value,"employee_type":employee_type},
                complete: function (data) {
                    //console.log(data['responseJSON'].employee_count);
                    $("#employee_count").text(data['responseJSON'].employee_count);
                    $("#employee_count").closest('li').find(".fa-chevron-circle-right").attr('data-content',data['responseJSON'].employee_count_popover);
                    $("#singaporeans_count").text(data['responseJSON'].singaporeans_count);
                    $("#singaporeans_count").closest('li').find(".fa-chevron-circle-right").attr('data-content',data['responseJSON'].singaporeans_count_popover);
                    $("#foreigners_count").text(data['responseJSON'].foreigners_count);
                    $("#foreigners_count").closest('li').find(".fa-chevron-circle-right").attr('data-content',data['responseJSON'].foreigners_count_popover);
                      $('[data-toggle="tooltip"]').tooltip();  
                    },
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
                    data: 'first_name',
                    name: 'first_name'

                },
                 {
                    targets: 2,
                    data: 'phone',
                    name: 'phone'
                },
                 {
                    targets: 3,
                    className: "lg-text-limit",
                    data: 'address',
                    name: 'address'
                },
                 {
                    targets: 4,
                    data: 'position',
                    name: 'position'
                },
                 {
                    targets: 5,
                    data: 'type',
                    name: 'type'
                },
                
                 {
                    targets: 6,
                    data: 'status',
                    name: 'status'
                },
                //only those have manage_user permission will get access
                {
                    targets: 7,
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function() {
//                initialize_popover();
//                initialize_tooltip();
            },
            oLanguage: {
                "sEmptyTable": "No records available"
            }
        });
     }
     
    
    
    // searching on change for employee list
    function filter_employe_list(){
        var first_name = $('#first_name').val();
//        var last_name = $('#last_name').val();
        var phone = $('#phone').val();
        //var email = $('#email').val();
        var address = $('#address').val();
//        var hire_date = $('#hire_date').val();
        var end_date = $('#end_date').val();
        var start_date = $('#start_date').val();
        var status = $('#status').val();
        var type = $('#type').val();
        var position = $('#position').val();
        var employee_type = $('#employee_type').val();
        
        
        var dataSearch = {"first_name": first_name,"phone":phone, "address": address,"start_date":start_date,"end_date":end_date,"status":status,"type":type,"position":position,"employee_type":employee_type};
        
        get_employee_list(dataSearch);
    }
    
     // reset on click 
    $(document).on("click", "#reset_employee_list_data", function (e) {
        
       $('#first_name').val('');
//       $('#last_name').val('');
       $('#phone').val('');
//       $('#email').val('');
       $('#address').val('');
       $('#hire_date').val('');
       $('#start_date').val('');
       $('#end_date').val('');
       $('#status').val('');
       $('#type').val('');
       $('#position').val('');
 
        get_employee_list();
    });
        </script>
    <!-- script for delete -->
        <script>
          //deleteItem
    function deleteItem(id) {
        //show confirmation popup
        $.confirm({
            title: 'Delete',
            content: 'Are you sure that you want to delete this profile?',
            buttons: {
                Cancel: function() {
                    //nothing to do
                },
                Sure: {
                    btnClass: 'btn-primary',
                    action: function () {
                        updateItemStatus(id = id, type = 'delete', value = '1');
                    },
                }
            }
        });

    }
    //update item
    function updateItemStatus(id, type, value) {
        var employee_type = "{{ $employee_type }}";
         var type = "{{ $type }}";
        $.ajax({
            type: "POST",
            url: "/employee/"+type+"/update_status",
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
                location.reload();
                
//                    var active_page = $(".pagination").find("li.active a").text();
//                    //reload datatable
//                    $('#listing_table').dataTable().fnPageChange((parseInt(active_page)-1));
               

            },
        });
    } 
    
    function filter_display(){
       if($('#filter_dis').css('display') == 'none')
        {
           document.getElementById("filter_dis").style.display ="block";
        }else{
            document.getElementById("filter_dis").style.display ="none";
        }
    }
        </script>
    @endpush
@endsection
