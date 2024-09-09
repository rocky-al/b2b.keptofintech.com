//text show hide 
 $(document).ready(function() {
        //$(".normal_availability-text")
           // jQuery('.fc-content')..css("display","block");
          var person_availability_date1 = $("#person_day1").prop('checked') == true ? 1 : 0;
          var person_availability_date2 = $("#person_day2").prop('checked') == true ? 1 : 0;
          var person_availability_date3 = $("#person_day3").prop('checked') == true ? 1 : 0;
          var person_availability_date4 = $("#person_day4").prop('checked') == true ? 1 : 0;
          var person_availability_date5 = $("#person_day5").prop('checked') == true ? 1 : 0;
          var person_availability_date6 = $("#person_day6").prop('checked') == true ? 1 : 0;
          var person_availability_date7 = $("#person_day7").prop('checked') == true ? 1 : 0;

           $("#person_day1").change(function(){
             var person_availability_date1 = $("#person_day1").prop('checked') == true ? 1 : 0
             if(person_availability_date1 == 1){
                jQuery('.person_availability1-text').hide();
                jQuery('.person_availability1-text-true').show();
                jQuery('.person_availability1-time-true').show();

            }else{
               jQuery('.person_availability1-text').show();
                jQuery('.person_availability1-text-true').hide();
                jQuery('.person_availability1-time-true').hide();

            }
         });

            $("#person_day2").change(function(){
             var person_availability_date2 = $("#person_day2").prop('checked') == true ? 1 : 0
             if(person_availability_date2 == 1){
               jQuery('.person_availability2-text').hide();
                jQuery('.person_availability2-text-true').show();
                jQuery('.person_availability2-time-true').show();

            }else{
              jQuery('.person_availability2-text').show();
                jQuery('.person_availability2-text-true').hide();
                jQuery('.person_availability2-time-true').hide();

            }
         });

             $("#person_day3").change(function(){
                //alert('fghhufj')
             var person_availability_date3 = $("#person_day3").prop('checked') == true ? 1 : 0
             if(person_availability_date3 == 1){
               jQuery('.person_availability3-text').hide();
                jQuery('.person_availability3-text-true').show();
                jQuery('.person_availability3-time-true').show();

            }else{
              jQuery('.person_availability3-text').show();
                jQuery('.person_availability3-text-true').hide();
                jQuery('.person_availability3-time-true').hide();

            }
         });

             $("#person_day4").change(function(){
             var person_availability_date4 = $("#person_day4").prop('checked') == true ? 1 : 0
             if(person_availability_date4 == 1){
               jQuery('.person_availability4-text').hide();
                jQuery('.person_availability4-time-true').show();
                jQuery('.person_availability4-text-true').show();

            }else{
              jQuery('.person_availability4-text').show();
                jQuery('.person_availability4-text-true').hide();
                jQuery('.person_availability4-time-true').hide();

            }
         });

              $("#person_day5").change(function(){
             var person_availability_date5 = $("#person_day5").prop('checked') == true ? 1 : 0
             if(person_availability_date5 == 1){
               jQuery('.person_availability5-text').hide();
                jQuery('.person_availability5-text-true').show();
                jQuery('.person_availability5-time-true').show();

            }else{
              jQuery('.person_availability5-text').show();
                jQuery('.person_availability5-text-true').hide();
                jQuery('.person_availability5-time-true').hide();

            }
         });

        $("#person_day6").change(function(){
             var person_availability_date6 = $("#person_day6").prop('checked') == true ? 1 : 0
             if(person_availability_date6 == 1){
               jQuery('.person_availability6-text').hide();
                jQuery('.person_availability6-text-true').show();
                jQuery('.person_availability6-time-true').show();

            }else{
              jQuery('.person_availability6-text').show();
                jQuery('.person_availability6-text-true').hide();
                jQuery('.person_availability6-time-true').hide();

            }
         });
         $("#person_day7").change(function(){
             var person_availability_date7 = $("#person_day7").prop('checked') == true ? 1 : 0
             if(person_availability_date7 == 1){
               jQuery('.person_availability7-text').hide();
                jQuery('.person_availability7-text-true').show();
                jQuery('.person_availability7-time-true').show();

            }else{
              jQuery('.person_availability7-text').show();
                jQuery('.person_availability7-text-true').hide();
                jQuery('.person_availability7-time-true').hide();

            }
         });

          if(person_availability_date1 == 1){
               jQuery('.person_availability1-text').hide();
                jQuery('.person_availability1-text-true').show();
                jQuery('.person_availability1-time-true').show();

            }else{
              jQuery('.person_availability1-text').show();
                jQuery('.person_availability1-text-true').hide();
                jQuery('.person_availability1-time-true').hide();

            }

            if(person_availability_date2 == 1){
               jQuery('.person_availability2-text').hide();
                jQuery('.person_availability2-text-true').show();
                jQuery('.person_availability2-time-true').show();

            }else{
              jQuery('.person_availability2-text').show();
                jQuery('.person_availability2-text-true').hide();
                jQuery('.person_availability2-time-true').hide();

            }

            if(person_availability_date3 == 1){
               jQuery('.person_availability3-text').hide();
                jQuery('.person_availability3-text-true').show();
                jQuery('.person_availability3-time-true').show();

            }else{
              jQuery('.person_availability3-text').show();
                jQuery('.person_availability3-text-true').hide();
                jQuery('.person_availability3-time-true').hide();

            }

            if(person_availability_date4 == 1){
               jQuery('.person_availability4-text').hide();
                jQuery('.person_availability4-text-true').show();
                jQuery('.person_availability4-time-true').show();

            }else{
              jQuery('.person_availability4-text').show();
                jQuery('.person_availability4-text-true').hide();
                jQuery('.person_availability4-time-true').hide();

            }

            if(person_availability_date5 == 1){
               jQuery('.person_availability5-text').hide();
                jQuery('.person_availability5-text-true').show();
                jQuery('.person_availability5-time-true').show();

            }else{
              jQuery('.person_availability5-text').show();
                jQuery('.person_availability5-text-true').hide();
                jQuery('.person_availability5-time-true').hide();

            }

             if(person_availability_date6 == 1){
               jQuery('.person_availability6-text').hide();
                jQuery('.person_availability6-text-true').show();
                jQuery('.person_availability6-time-true').show();

            }else{
               jQuery('.person_availability6-text').show();
                jQuery('.person_availability6-text-true').hide();
                jQuery('.person_availability6-time-true').hide();

            }

            /* if(person_availability_date6 == 1){
               jQuery('.person_availability6-text').hide();
                jQuery('.person_availability6-text-true').show();
                jQuery('.person_availability6-time-true').show();

            }else{
               jQuery('.person_availability-text').show();
                jQuery('.person_availability6-text-true').hide();
                jQuery('.person_availability6-time-true').hide();

            }*/

             if(person_availability_date7 == 1){
               jQuery('.person_availability7-text').hide();
                jQuery('.person_availability7-text-true').show();
                jQuery('.person_availability7-time-true').show();

            }else{
               jQuery('.person_availability7-text').show();
                jQuery('.person_availability7-text-true').hide();
                jQuery('.person_availability7-time-true').hide();

            }


         var normal_availability_date0 = $("#sunday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date1 = $("#monday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date2 = $("#tuesday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date3 = $("#wednesday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date4 = $("#thursday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date5 = $("#friday-text").prop('checked') == true ? 1 : 0;
         var normal_availability_date6 = $("#saturday-text").prop('checked') == true ? 1 : 0;
         $("#sunday-text").change(function(){
            var normal_availability_date0 = $("#sunday-text").prop('checked') == true ? 1 : 0;
             if(normal_availability_date0 == 1){
                jQuery('.normal_availability-text').hide();
                jQuery('.normal_availability-text-true').show();

            }else{
               jQuery('.normal_availability-text').show();
                jQuery('.normal_availability-text-true').hide();

            }
         });
          $("#monday-text").change(function(){
            var normal_availability_date1 = $("#monday-text").prop('checked') == true ? 1 : 0;
              if(normal_availability_date1 == 1){
                jQuery('.normal_availability1-text').hide();
                jQuery('.normal_availability1-text-true').show();
            }else{
               jQuery('.normal_availability1-text').show();
                jQuery('.normal_availability1-text-true').hide();

            }
         });

           $("#tuesday-text").change(function(){
            var normal_availability_date2 = $("#tuesday-text").prop('checked') == true ? 1 : 0;
              if(normal_availability_date2 == 1){
                jQuery('.normal_availability2-text').hide();
                jQuery('.normal_availability2-text-true').show();
            }else{
               jQuery('.normal_availability2-text').show();
                jQuery('.normal_availability2-text-true').hide();

            }
         });

            $("#wednesday-text").change(function(){
            var normal_availability_date3 = $("#wednesday-text").prop('checked') == true ? 1 : 0;
            if(normal_availability_date3 == 1){
                jQuery('.normal_availability3-text').hide();
                jQuery('.normal_availability3-text-true').show();
            }else{
               jQuery('.normal_availability3-text').show();
                jQuery('.normal_availability3-text-true').hide();

            }
         });

        $("#thursday-text").change(function(){
           var normal_availability_date4 = $("#thursday-text").prop('checked') == true ? 1 : 0;
             if(normal_availability_date4 == 1){
                jQuery('.normal_availability4-text').hide();
                jQuery('.normal_availability4-text-true').show();
            }else{
               jQuery('.normal_availability4-text').show();
                jQuery('.normal_availability4-text-true').hide();

            }
         });

         $("#friday-text").change(function(){
           var normal_availability_date5 = $("#friday-text").prop('checked') == true ? 1 : 0;
             if(normal_availability_date5 == 1){
                jQuery('.normal_availability5-text').hide();
                jQuery('.normal_availability5-text-true').show();
            }else{
               jQuery('.normal_availability5-text').show();
                jQuery('.normal_availability5-text-true').hide();

            }
         });

          $("#saturday-text").change(function(){
            var normal_availability_date6 = $("#saturday-text").prop('checked') == true ? 1 : 0;
             if(normal_availability_date6 == 1){
                jQuery('.normal_availability6-text').hide();
                jQuery('.normal_availability6-text-true').show();
            }else{
               jQuery('.normal_availability6-text').show();
                jQuery('.normal_availability6-text-true').hide();

            }
         });


            if(normal_availability_date0 == 1){
                jQuery('.normal_availability-text').hide();
                jQuery('.normal_availability-text-true').show();

            }else{
               jQuery('.normal_availability-text').show();
                jQuery('.normal_availability-text-true').hide();

            }

             if(normal_availability_date1 == 1){
                jQuery('.normal_availability1-text').hide();
                jQuery('.normal_availability1-text-true').show();
            }else{
               jQuery('.normal_availability1-text').show();
                jQuery('.normal_availability1-text-true').hide();

            }

             if(normal_availability_date2 == 1){
                jQuery('.normal_availability2-text').hide();
                jQuery('.normal_availability2-text-true').show();
            }else{
               jQuery('.normal_availability2-text').show();
                jQuery('.normal_availability2-text-true').hide();

            }

             if(normal_availability_date3 == 1){
                jQuery('.normal_availability3-text').hide();
                jQuery('.normal_availability3-text-true').show();
            }else{
               jQuery('.normal_availability3-text').show();
                jQuery('.normal_availability3-text-true').hide();

            }

             if(normal_availability_date4 == 1){
                jQuery('.normal_availability4-text').hide();
                jQuery('.normal_availability4-text-true').show();
            }else{
               jQuery('.normal_availability4-text').show();
                jQuery('.normal_availability4-text-true').hide();

            }

             if(normal_availability_date5 == 1){
                jQuery('.normal_availability5-text').hide();
                jQuery('.normal_availability5-text-true').show();
            }else{
               jQuery('.normal_availability5-text').show();
                jQuery('.normal_availability5-text-true').hide();

            }

             if(normal_availability_date6 == 1){
                jQuery('.normal_availability6-text').hide();
                jQuery('.normal_availability6-text-true').show();
            }else{
               jQuery('.normal_availability6-text').show();
                jQuery('.normal_availability6-text-true').hide();

            }
    });