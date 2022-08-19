

$(document).ready(function () {

    $("#email_registration_Form").validate({
        rules:
        {
            email:
            {
                required: true,
                email: true
            },
            password:
            {
                required: true,
                minlength: 8,
                maxlength : 20
            }
        },
        messages:
        {
            email:
            {
                required: "Please Enter Your Email Address ",
                email: "Please Enter a Valid Email Address"
            },
            password:
            {
                required: "Please Enter Account Password",
                minlength: "Minimum Required Length For Password is Eight",
                maxlength : "Maximum Length For Password is 20"
            }

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

    $("#email_registration_Form").on("submit", function (event) {
        event.preventDefault();
        if (($(this).valid() == true)) {
            $("#email_registration_Form_btn").prop("disabled", true);
            $("#email_registration_Form_btn").text("Please Wait...");
            var data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: 'main/db_controller.php',
                data: data,
                success: function (response) 
                {
                    debugger;
                    response    =   JSON.parse(response);
                    if(response["error"] == false)
                    {
                        window.location.href    =   "sign_in.html";
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
      


    });




});