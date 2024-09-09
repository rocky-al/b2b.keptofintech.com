@extends('layouts.main') 
@section('title','Notifications')
@section('content')
 @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush
    
<div class="row">
    <div class="col-md-12">
        
        <div class="card latest-update-card">
            <div class="card-header justify-content-between">
                <h3><i class="ik ik-list"></i> {{ __(' Notifications')}}</h3>
                
            </div>
            <div class="card-filter">

                <div class="row">
                    <div class="col-md-1">
                        <h6 class="mt-10">Filters</h6>
                    </div>
                    <input type="hidden" id="endIndex">
                     <div class="col-md-3">
                         <div class="form-group">
                             <input type="text" class="form-control input-daterange" name="date_range"  id="date_range" placeholder="Date Range" readonly>
                         </div>
                     </div>                                  
                     <div class="col-md-1">
                         <div class="form-group">
                             <button type="button" class="btn btn-outline-primary btn-rounded-20" id="reset_list_data">
                                <i class="ik ik-rotate-ccw "></i>Reset
                             </button>
                         </div>
                     </div>
                     <div class="col-md-1 ml-1">
                         <div class="form-group ">
                             <button type="button" class="btn btn-outline-primary btn-rounded-20 send-action">
                                Mark as Read
                             </button>
                         </div>
                     </div>
                </div>
            </div>
            <div class="card-body">
                 <div class="card-block">
                        <div class="">
                             <label class="sc-cheakbox "><input type="checkbox" class="notify_select_all" ><span class="checkmark"></span> Select All</label>
                            <div class="latest-update-box">
<!--                                @if(isset($notification_info) && !empty($notification_info))
                                @foreach($notification_info as $info)
                                <div class="row pt-20 pb-30">
                                    <div class="col-auto update-meta pr-0">
                                        <i class="b-primary update-icon ring"></i>
                                    </div>
                                    <div class="col pl-5  {{($info->is_read == '0' ? 'unread':'read')}}">
                                        <a href="{{url('/').'/'.$info->redirect_path}}"><h6>{{$info->title}}</h6></a>
                                        <p class="text-muted mb-0">{{ $info->description }}</p>
                                    </div>
                                </div>
                                @endforeach
                                @endif-->
                                <!-- for display notification body -->
                                <ul class="timeline notification_list_all add-line" style="border-bottom:none;">

                                </ul>   
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<!-- push external js -->
@push('script')
<script>
    //set variable for date
    var startDate = '';
    var endDate = '';
   //set empty array for check notification 
    selectSubModuleArray=new Array();
    uncheck_list=new Array();
    
      $(document).ready(function() { 
        datesArray=new Array();
        $('#date_range').daterangepicker({
            "autoUpdateInput": false,
        });
        $('input[name="date_range"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            startDate = picker.startDate.format('DD/MM/YYYY');
            endDate = picker.endDate.format('DD/MM/YYYY');
            
            $(document).find('.notification_list_all').html('');
            call_notification(startDate,endDate,'');
        });
        $('input[name="date_range"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');  
        });
    //call function of notification records display    
    call_notification();
});
      
 //load on scroll     
 $(window).scroll(function(){   
  if ($(window).scrollTop() == $(document).height() - $(window).height() && (parseInt($("#endIndex").val())%20)==0){
 
    var startD = startDate;
    var endD = endDate;
  call_notification(startD,endD,$("#endIndex").val());
}
});
//update read status of notification
$(".send-action").click(function(){
     //var value = $(".notify_select:checked").val();
     var value = $(".notify_select").prop('checked') == true ? 1 : 0; 
   // alert(value)
        $.ajax({
          type:"POST",
          url: '/mark-as-read-notification',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          dataType: "json",
          data: {
                    _token: '{{csrf_token()}}',
                    "notificatione_id": value
                },
          // data: {
          //   selectIdArray:(selectSubModuleArray.length==0)?[0]:selectSubModuleArray,
          // },
           beforeSend: function()
            {
                $(".loader").show();
            },
          success: function(data){
              
              var response = data
          if (response.code == 200) { 
            $(".loader").hide();
            $(".notify_select").prop("checked",false);
            $(".notify_select_all").prop("checked",false);
            var startD = startDate;
            var endD = endDate;
            $(document).find('.notification_list_all').html('');
            call_notification(startD,endD,'');
            var notification_count = $(".notification_count").text();
            $.ajax({
                type: "POST",
                url: "{{url('get-notifications')}}",
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    if (notification_count != data.notificationsCount) {
                        //$(".notification_count").html(data.notificationsCount);
                    }
                },
            });
            $.notify(response.msg, "success");
            
          }else{
              $(".loader").hide();
            $.notify(response.msg, "warning");
          }
          },
          
        });
})
//function fetch records of notifications
function call_notification(start='',end='',index='')
    {
        index=(index=='')?0:index;
        $.ajax({
          type:"POST",
          url: '/get-all-notifications', 
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          dataType: "json",
          data: {
            start:start,end:end,index:index,datesArray:((datesArray.length==0)?["0000-00-00"]:datesArray)
          },
           beforeSend: function()
            {
                $(".loader").show();
            },
          success: function( data ) {
//              console.log(data);
                $(".loader").hide();
                $(document).find('.notification_list_all').html(data.html);
                $("#endIndex").val(data.endIndex);
                datesArray=(data.dates.length>0)?data.dates:datesArray;

          },
          complete:function()
          {            
          }
          

        });
         initialize_tooltip();
    }
  //for check box select  
  $(document).on("change",".notify_select",function(){

   if ($(this).prop("checked"))
   {       
    if(!selectSubModuleArray.includes($(this).attr('data-id')))
    {
      selectSubModuleArray.push($(this).attr('data-id'));

    }
  }
  else
  {
    if(selectSubModuleArray.includes($(this).attr('data-id')))
    {
      place=selectSubModuleArray.indexOf($(this).attr('data-id'));
      selectSubModuleArray.splice(place,1); 
    }
    
    
  }
});
//mark as read on link select
// $(document).on("mouseup", ".notification_list_all a", function(e) {
//         var value = $(this).attr("data-val");
//         alert(value)
//         if (e.which == 1 || e.which == 2) {
//             $.ajax({
//                 url: "{{url('update-notifications')}}",
//                 dataType: "json",
//                 type: "POST",
//                 data: {
//                     _token: '{{csrf_token()}}',
//                     "notificatione_id": value
//                 },
//                 success: function(data) {

//                 },

//             });
//         }
//     });
    
    $(document).on('change','.notify_select_all',function(){
    if ($(this).prop("checked"))
   {

        $(".notify_select").prop("checked",true);
        var a = document.getElementsByClassName("notify_select");
        if(a){
            $.each( a, function( key, value ) {
                
            if(!selectSubModuleArray.includes($(this).attr('data-id')))
                {
                  selectSubModuleArray.push($(value).attr('data-id'));

                }
            });               
        }
        
   }
   else
   {
        $(".notify_select").prop("checked",false);
        selectSubModuleArray.splice(0,selectSubModuleArray.length);
   }

});

$("#reset_list_data").click(function(){
    $('#date_range').val('');
     $(document).find('.notification_list_all').html('');
    call_notification();
     initialize_tooltip();
});

      function remove(id){
            //alert(id);
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
                             $.ajax({
                            type: "POST",
                            url:  "{{route('notification.delete')}}",
                            data: {
                                id: id,
                                _token: '{{csrf_token()}}'
                            },
                            success: function(data) {
                                console.log(data.success)
                                     $.notify(data.success, "success");
                                 //$('#listing_table').DataTable().ajax.reload();
                                  window.location.reload();

                            },
                        });
                    },
                }
            }
        });

        }
</script>
    
<script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
<!-- <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script> -->
<script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
<script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script>
<!-- <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>  -->
<script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

<script src="{{ asset('js/widget-data.js') }}"></script>
@endpush
@endsection
