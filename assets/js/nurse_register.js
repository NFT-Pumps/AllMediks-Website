$(document).ready(function(){

    $("#date_of_birth").datepicker();
    $("#profile_image_input").cropzee({startSize: [85, 85, '%'],});
    $("#specializations").select2({
        theme: "bootstrap",
        tags: true,
        placeholder: "Type Your Specializations...",
        containerCssClass: ':all:'
    });
    $('#license').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});
    $("#register_form").validate({
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
            license:{
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

    $("#register_form").on("submit", function(event){
        event.preventDefault();
        debugger;
        var error = false;
        var status                  =       $(this).valid();
        var profile_img_counter     =       $("#profile_image_input").get(0).files.length;
        var license_counter         =       $("#license").get(0).files.length;
        var specializations         =       $("#specializations").val();;

        if(profile_img_counter <= 0)
        {
            error = true;
            $("#profile_image_input_error").text("Please Choose Your Profile Picture");

        }
        else
        {

            $("#profile_image_input_error").text("");

                
        }

        if(license_counter <= 0)
        {
            error = true;
            $("#license_error").text("Please Upload Your Nursing License.");

        }
        else
        {
            
            $("#license_error").text("");

                
        }
        if(specializations.length <= 0)
        {
            error = true;
            $("#specializations_error").text("Please Add Your Area Of Specialization.");

        }
        else
        {
            $("#specializations_error").text("");

        }

        if((status == true) && (error == false))
        {
            // $("#register_btn").prop("disabled", true);
            // $("#register_btn").text("Please Wait...");
            var data        =       new FormData();
            data.append("action", 'nurse_register');
            data.append("data", $(this).serialize());profile_image_input
            data.append("license", $("#license").get(0).files[0]);
            data.append("profile_image_input", $("#profile_image_input").get(0).files[0]);
            data.append("specializations", specializations);


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
                        window.location.href    =   "nurse_edu.html";
                    }
                    if(response["error"] == true)
                    {
                        alert(response["error_msg"]);
                        $("#register_btn").prop("disabled", false);
                        $("#register_btn").text("Submit");
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