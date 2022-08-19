$(document).ready(function()
{
 
    $("#date_of_birth").datepicker();
    $("#profile_image_input").cropzee({startSize: [85, 85, '%'],});
    $("#doctor_register_form").validate({
        rules:
        {
            firstname:
            {
                required : true
            },
            lastname:
            {
                required : true
            },
            date_of_birth:
            {
                required : true
            },
            age:
            {
                required : true
            },
            height:
            {
                required : true
            },
            weight:
            {
                required : true
            },
            profile_image_input: {
                required: true,
            },
            gender: {
                required: true,
            },
            martial_status: {
                required: true,
            },
            emergency_contact: {
                required: true,
            },
            relationship: {
                required: true,
            },
            phone_number: {
                required: true,
            },
            about: {
                required: true,
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

    $("#doctor_register_form").on("submit", function(event){
        event.preventDefault();
        var error = false;
        debugger;
        var status                  =       $(this).valid();
        var profile_img_counter     =       $("#profile_image_input").get(0).files.length;
        if(profile_img_counter <= 0)
        {
            error = true;
            $("#profile_image_input_error").text("Please Choose Your Profile Picture");

        }
        else
        {

            $("#profile_image_input_error").text("");

                
        }
     
        if((status == true) && (error == false))
        {
            $("#patient_register_btn").prop("disabled", true);
            $("#patient_register_btn").text("Please Wait...");
            var data        =       new FormData(this);
            $.ajax
            ({
                type: "POST",
                url: 'main/db_controller.php',
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                async : false,
                success: function (response) 
                {
                    response = JSON.parse(response)
                    if(response["error"] == false)
                    {
                        if(response["register_ID"])
                        {
                            window.location.href = "doc_prof.html?Id=" + response["register_ID"];
                        }
                    }
                    if(response["error"] == true)
                    {
                        alert(response["error_msg"]);
                        $("#patient_register_btn").prop("disabled", false);
                        $("#patient_register_btn").text("Submit");
                    }
                    
                    
                   
                    
                },
              
            });
            
        }
        else
        {
            alert("Please Provide All Your Details.")


        }


    });

});