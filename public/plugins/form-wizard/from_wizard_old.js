
var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

function check_fields(){
   // alert('123');
  var phone = $('#phone').val();  
  var last_name = $('#last_name').val();  
  var first_name = $('#first_name').val();  
  var type = $('#type').val();  
  var email = $('#email').val();  
  var dob = $('#dob').val();  
  var employee_type = document.getElementById('employee_type').value;  
  var standard_time = $('#standard_time').val();  
  var hire_date = $('#hire_date').val();  
  var flag = 0;
  var document_len = $(".document").length;
  
  if((phone != '') && (last_name != '') && (first_name != '') && (type != '') && (email != '') && (dob != '') && (employee_type != '') && (standard_time != '') && (hire_date != '') && (document_len > 0))
  {
     if(type == '2'){
        var permit_type = $('#permit_type').val();  
         if((permit_type == '')){
          flag = 1; 
         } 
     }
     
     if(employee_type == '0'){
         var role_val = $('#role_val').val();  
         if((role_val == '')){
           flag = 1; 
         }
     }
     
     if(employee_type == '1'){
        var position = $('#position_val').val();
        if((position == '')){
            flag = 1; 
         }
     }
     if(flag == 1){
     return true;
    }else{
      return false;  
    }
  }else{
       
      return true;
  }
 
}

$(".next").click(function(){
    
var check_fiel = check_fields();
var check_e = $("#check_edit").val();

if(check_fiel == true){
    if(check_e == '0'){
       $("#addEditForm").submit(); 
    }
    if(check_e == '1'){
      $("#employeeGeneralEditForm").submit();  
    }
 
}else{
current_fs = $(this).parent().parent();
next_fs = $(this).parent().parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});
}
});

$(".previous").click(function(){

current_fs = $(this).parent().parent();
previous_fs = $(this).parent().parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

// $(".submit").click(function(){
// return false;
// })

