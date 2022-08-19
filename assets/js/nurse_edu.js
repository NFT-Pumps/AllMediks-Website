
function removeEducation(id)
{
    var data        =       new FormData();
    data.append("action", 'delete_education');
    data.append("id", id);

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
                alert("Education Deleted Successfully.");
                getNurseEducations();
            }
            else
            {
                alert(response["error_msg"]);

            }

        },
      
    });

}


function getNurseEducations(){
    var data        =       new FormData();
    data.append("action", 'get_nurse_educations');
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
                if(response["EDUCATIONS"].length > 0)
                {   
                    $("#table_wrapper").show();
                    $("#educations_table").html('');
                    $.each(response["EDUCATIONS"], function(index, element)
                    {
                        $("#educations_table").append('<tr><td>' + element["SR"] + '</td><td>' + element["degree"] + '</td><td>' + element["institute"] + '</td><td>' + element["start_year"] + '</td><td>' + element["ending_year"] + '</td><td><button id="' + element["id"] + '" class="btn btn-outline-danger btn-sm" onclick="removeEducation(this.id)">Remove</button></td></tr>');
                    });
                }
                
            }

        },
      
    });

}




$(document).ready(function(){

    $("#start").datepicker();
    $("#end").datepicker();
    $('#degree_img').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false,
     
	});
    $("#register_form").validate({
        rules:
        {
            degree:
            {
                required : true
            },
            start:
            {
                required : true
            },
            end:
            {
                required : true
            },
            institute:
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
        var degree_counter          =       $("#degree_img").get(0).files.length;
        if(degree_counter <= 0)
        {
            error = true;
            $("#degree_img_error").text("Please Upload Degree Image.");

        }
        else
        {

            $("#degree_img_error").text("");

                
        }
        if((status == true) && (error == false))
        {
            $("#register_btn").prop("disabled", true);
            $("#register_btn").text("Please Wait...");
            var data        =       new FormData();
            data.append("action", 'nurse_edu_register');
            data.append("data", $(this).serialize());
            data.append("degree_img", $("#degree_img").get(0).files[0]);
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
                        window.location.reload();
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
    getNurseEducations();

    
    





});