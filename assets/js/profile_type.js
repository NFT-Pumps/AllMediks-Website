
function get()
{
  $.ajax
  ({
    
    type      :   'POST',
    url       :   'main/db_controller.php',
    data      :   {'action' : 'get_profile_type'},
    success   :   function (response) 
    {
        debugger;
        response    =   JSON.parse(response);
        if(response["error"] == false)
        {
          if((response["list"].length)  > 0)
          {
            $.each(response["list"], function(index, element){
              $("#profile_type").append('<option value="' + element["type_id"] + '">' + element["type_desc"] + '</option>')

            });

          }

        }
        if(response["error"] == true)
        {
            
            $("#email_error").text('');
            $("#email_error").text(response["error_msg"]);
            $("#email_registration_Form_btn").prop("disabled", false);
            $("#email_registration_Form_btn").text("Sign Up");
            
        }
        
    },
  
});


}














$(document).ready(function () {
  get();
  $("#profile_image_input").cropzee({ startSize: [85, 85, "%"] });
  $("#personal_info").validate({
    rules: {
      profile_type: {
        required: true,
      },
    },
    messages: {
      profile_type: {
        required: "Please Choose Your Account Type",
      },
    },
    errorPlacement: function (error, element) {
      var element_ID = $(element).attr("id");
      $("#" + element_ID + "_error").text(error.text());
    },
    success: function (label, element) {
      var element_ID = $(element).attr("id");
      $("#" + element_ID + "_error").text("");
    },
  });

  $("#personal_info").on("submit", function (event) {
    event.preventDefault();
    var status = $(this).valid();
    if (status == true) 
    {
      var data    =   $(this).serialize();
      $("#register_profile_type_btn").prop("disabled", true);
      $("#register_profile_type_btn").text("Please Wait...");


      $.ajax
      ({
        
        type      :   'POST',
        url       :   'main/db_controller.php',
        data      :   data,
        success   :   function (response) 
        {
          
          // debugger;
          response    =   JSON.parse(response);
          if(response["error"] == false)
          {
            if(response["profile_type"] == 1)
            {
              window.location.href  = "patient_register.html";

            }
            if(response["profile_type"] == 2)
            {
              window.location.href  = "doctor_register.html";

            }
            if(response["profile_type"] == 3)
            {
              window.location.href  = "nurse_register.html";

            }
            if(response["profile_type"] == 4)
            {
              window.location.href  = "hospital_register.html";

            }
            if(response["profile_type"] == 5)
            {
              window.location.href  = "pharmacy_register.html";

            }



          }
          else
          {
            response["error_msg"];
          }
         
        
        },
  
      });
      
      
    
    
    }
  });
});
