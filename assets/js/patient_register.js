$(document).ready(function(){

    $("#date_of_birth").datepicker();
    $("#profile_image_input").cropzee({startSize: [85, 85, '%'],});
    $("#patient_register_info").validate({
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
            address: {
                required: true,
            },
            identification_card:{
                required: true
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

    $("#patient_register_info").on("submit", function(event){
        event.preventDefault();
        var error = false;
        debugger;
        var status                  =       $(this).valid();
        var profile_img_counter     =       $("#profile_image_input").get(0).files.length;
        var driver_permit_counter   =       $("#driver_permit").get(0).files.length;
        var passport_counter        =       $("#passport").get(0).files.length;
        var national_card_counter   =       $("#identity_card").get(0).files.length;
        if(profile_img_counter <= 0)
        {
            error = true;
            $("#profile_image_input_error").text("Please Choose Your Profile Picture");

        }
        else
        {

            $("#profile_image_input_error").text("");

                
        }
        if(driver_permit_counter <= 0)
        {
            error = true;
            $("#driver_permit_error").text("Please Upload Identification Card Images");

        }
        else
        {
            
            $("#driver_permit_error").text("");

                
        }
        if(passport_counter <= 0)
        {
            error = true;
            $("#passport_error").text("Please Upload Your Passport");

        }
        else
        {
            
            $("#passport_error").text("");

                
        }
        if(national_card_counter <= 0)
        {
            error = true;
            $("#identity_card_error").text("Please Upload Your National Card Images");

        }
        else
        {
            
            $("#identity_card_error").text("");

                
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
                        alert(response["msg"]);
                        window.location.href = "home/";
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

    $('#driver_permit').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});
    $('#passport').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});
    $('#identity_card').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});





});