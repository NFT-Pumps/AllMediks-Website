

function getSpecializations(){
    var data        =       new FormData();
    data.append("action", 'get_specialities_list');
    $.ajax
    ({
        type: "POST",
        url: 'main/db_controller.php',
        data: data,
        contentType: false,
        cache: false,
        processData:false,
        success: function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                if(response["list"].length  > 0)
                {
                    $.each(response["list"], function(index, element){
                        $("#services").append('<option value="' + element["S_ID"] + '">' + element["S_DESC"] + '</option>')
                    });
                }
            }
            
        },
      
    });
}




$(document).ready(function(){

    $("#profile_image_input").cropzee({startSize: [85, 85, '%'],});
    
    $('#images').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});
    $("#register_form").validate({
        rules:
        {
            name:
            {
                required : true
            },
            address:
            {
                required : true
            },
            house_of_operation:
            {
                required : true
            },
            services:
            {
                required : true
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

    $("#register_form").on("submit", function(event){
        event.preventDefault();
        debugger;
        var error = false;
        var status                  =       $(this).valid();
        var profile_img_counter     =       $("#profile_image_input").get(0).files.length;
        var images_counter          =       $("#images").get(0).files.length;
        var services                =       $("#services").val();;

        if(profile_img_counter <= 0)
        {
            error = true;
            $("#profile_image_input_error").text("Please Choose Your Profile Picture");

        }
        else
        {

            $("#profile_image_input_error").text("");

                
        }
        if(services.length <= 0)
        {
            error = true;
            $("#services_error").text("This Field is required.");

        }
        else
        {
            $("#services_error").text("");

        }

        if((status == true) && (error == false))
        {
            $("#register_btn").prop("disabled", true);
            $("#register_btn").text("Please Wait...");
            var data        =       new FormData();
            data.append("action", 'pharmacy_register');
            data.append("data", $(this).serialize());
            $.each($("#images")[0].files, function(i, file) {
                data.append('images[]', file);
            });
            data.append("profile_image_input", $("#profile_image_input").get(0).files[0]);
            data.append("services", services);
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
    $( "#services" ).select2({
        theme: "bootstrap",
        placeholder: "Choose Hospital Services",
        containerCssClass: ':all:'
    });

    
    getSpecializations();





});