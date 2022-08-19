

$(document).ready(function () {

    $("#login_form").validate({
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
                required: "Please Enter Your Password",
                
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

    $("#login_form").on("submit", function (event) {
        event.preventDefault();
        if (($(this).valid() == true)) {
            $("#login_form_btn").prop("disabled", true);
            $("#login_form_btn").text("Please Wait...");
            var data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: 'main/db_controller.php',
                data: data,
                success: function (response) 
                {
                    response    =   JSON.parse(response);
                    if(response["error"] == false)
                    {
                        if(response["user"])
                        {
                            if(response["user"]["profile_type"]  == false)
                            {
                                window.location.href    =   "profile_type.html";
                            }
                            else
                            {
                                window.location.href = "home/";

                            }

                        }
                        
                    }
                    if(response["error"] == true)
                    {
                        alert(response["error_msg"]);
                        $("#login_form_btn").prop("disabled", false);
                        $("#login_form_btn").text("Sign In");
                        
                    }
                    
                },
              
            });



        }
        else {
            alert("Invalid");


        }


    });




});