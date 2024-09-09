<!--<script src="{{ asset('plugins/jquery/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script> -->
<script src="{{ asset('all.js') }}"></script>
<script src="{{ asset('plugins/jquery-validate/jquery.validate-1.19.3.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-confirm/jquery-confirm-3.3.4.min.js') }}"></script>
<script src="{{ asset('plugins/notify/notify.min.js')}}"></script>
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/datepicker.min.js') }}"></script>

<script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('dist/js/theme.js') }}"></script>
<!-- <script src="{{ asset('js/chat.js') }}"></script> -->
<script src="{{ asset('plugins/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-minicolors/jquery.minicolors.min.js') }}"></script>
<script src="{{ asset('plugins/multi-select/jquery.multiselect.js') }}"></script>
<script src="{{ asset('js/form-picker.js') }}"></script>
<script src="{{ asset('js/socket.io.js') }}"></script>
<script src="{{ asset('plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/socket.io.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js">
    </script>
<script>
    //var site_base_url = 'https://vjroot.orbitnapp.com:3020';
    //user_id = '<?php echo Auth::user()->id; ?>';
    //initialize socket
    // var socket = io.connect(site_base_url, {
    //     query: 'user_id=' + user_id
    // });
    //show notification
    // socket.on('show_notification', function(data) {
    //     $.notify(data.message, {
    //         className: 'info',
    //         elementPosition: 'bottom right',
    //         globalPosition: 'bottom right',
    //     });

    //     var notification_count = $(".notification_count").text();
    //     $.ajax({
    //         type: "POST",
    //         url: "{{url('get-notifications')}}",
    //         data: {
    //             _token: '{{csrf_token()}}'
    //         },
    //         success: function(data) {
    //             if (notification_count != data.notificationsCount) {
    //                 $(".notification_count").html(data.notificationsCount);
    //             }
    //         },
    //     });
    // });
    
//    $(document).ready(function() {
//    
//        $('body').addClass('sidebar-mini'); 
//    });
</script>
<!-- Stack array for including inline js or scripts -->
<script>
    function initialize_tooltip() {
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
    }
    //intialize popover
    function initialize_popover() {
        $('[data-toggle="popover"]').popover({
            trigger: "hover",
            html: true
        });
    }
    
    function initialize_popover_click() {
        $('[data-toggle="popover"]').popover({
            trigger: "click",
            html: true
        });
    }
    
    
    $(function() {
        
        //for hide tooltip onclick
        $(document).on('click','[data-toggle="tooltip"]',function(){
                $(this).tooltip('hide')
        });
        

        $(document).on('keypress', "input[class*='only_number']", function(event) {
            event = (event) ? event : window.event;
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;

        });
        // decimal number 
        $(document).on('keydown', "input[class*='decimal']", function(event) {
            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

            } else {
                event.preventDefault();
            }

            if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();

        });
        // number 
        $(document).on('keypress', "input[class*='number']", function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });
        //for alpha numeric

        $(document).on('keypress', "input[class*='alphanum']", function(evt) {

            return ((evt.charCode >= 97 && evt.charCode <= 122) || (evt.charCode >= 65 && evt.charCode <= 90) || (evt.charCode >= 48 && evt.charCode <= 57));
        });
        
          $(document).on('keyup',"input[class*='phone_pattern']",function (e)
        {
            if (e.keyCode != '8') {
                temp_val = this.value;
                value = temp_val.replace(/ /gi, "");
                addsub = parseInt(value.length /2);
                count_array = 0;
                while (addsub > count_array)
                {
                    if (count_array == 0)
                    {
                      value = value.replace(/(\d+)(\d{4}.*)/, value.substr(0, 4).concat(' ')).concat(value.substr(4, 9));
                       
                    } else if (count_array == 1)
                    {
                      // value = value.replace(value.substr(4, 5), value.substr(4, 5).concat(' '));   
                    }     
                    count_array++;
                    //value= value.substr(0, 2);
                }

                this.value = value;
            }
        });
        setTimeout(function() {
            var notification_count = $(".notification_count").text();
            $.ajax({
                type: "POST",
                url: "{{url('get-notifications')}}",
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    if (notification_count != data.notificationsCount) {
                        $(".notification_count").html(data.notificationsCount);
                    }
                },
            });
        }, 200);
    });
    // only integer value
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    // calculate time duration between two time
    function CalculateTimeDuration(start_time, end_time) {
        var shift_start_time = new Date("1970/01/01 " + start_time);
        var shift_end_time = new Date("1970/01/01 " + end_time);
        if (shift_end_time >= shift_start_time) {
            var shift_duration = (new Date("1970/01/01 " + end_time) - new Date("1970/01/01 " + start_time)) / 1000 / 60 / 60;
        } else {
            var shift_duration = (new Date("1970/01/02 " + end_time) - new Date("1970/01/01 " + start_time)) / 1000 / 60 / 60;
        }
        return shift_duration;
    }

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
    // get the notification list for the login user 
    function GetNotifications(id) {

        $.ajax({
            type: "POST",
            url: "{{url('get-notifications')}}",
            data: {
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $(".notifications-wrap").html('');
                if (data.notifications != '') {
                    $(".notifications-wrap").removeClass('p10');

                    $.each(data.notifications, function(key, value) {
                        var baseUrl = "{{asset('/all-notifications')}}" /*+ value.redirect_path*/;
                        if (value.description != '' && value.description != null) {
                            var description = value.description.substring(0, 30);
                        } else {
                            var description = "";
                        }
                        $(".notifications-wrap").append('<a href="' + baseUrl + '" data-val="' + value.id + '" class="media unread"><span class="d-flex"><i class="ik ik-bell"></i></span><span class="media-body"><span class="heading-font-family media-heading">' + value.title + '</span><p class="heading-font-family media-heading">' + description + '...</p></a>');
                    });
                } else {
                    $(".notifications-wrap").append('There are no notification');
                    $(".notifications-wrap").addClass('p10');
                }
            },
        });
    }
    $(document).on("mouseup", ".notifications-wrap a", function(e) {
        var value = $(this).attr("data-val");

        if (e.which == 1 || e.which == 2) {
            $.ajax({
                url: "{{url('update-notifications')}}",
                dataType: "json",
                type: "POST",
                data: {
                    _token: '{{csrf_token()}}',
                    "notificatione_id": value
                },
                success: function(data) {

                },

            });
        }
    });
    
    //time picker 
    $(document).on('focus','.time-picker',function(){
        $('.time-picker').datetimepicker({
            format: 'HH:mm',
            showClear: false,
            useCurrent: true
        }).on('dp.hide', function(e) {
           var attr_value = $(this).attr('data-set_value');
           if(attr_value != undefined){
               $(this).attr('value',$(this).val());
               
           }
        });
    });
         $(document).on("click", ".sidebar-action", function(e) {
                $( "body" ).add( ".sidebar-mini" );
         });
</script>

@stack('script')