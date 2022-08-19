
function DeleteExperiance(id)
{
    var data        =       new FormData();
    data.append("action", 'del_nurse_exp');
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
                window.location.reload();
            }
            else
            {
                alert(response["error_msg"]);

            }

        },
      
    });

}


function getNurseExperiances(){
    var data        =       new FormData();
    data.append("action", 'get_nurse_experiances');
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
                if(response["EXPERIANCES"].length > 0)
                {
                    $("#table_wrapper").show();
                    $("#table").html('');
                    $.each(response["EXPERIANCES"], function(index, element)
                    {
                        $("#table").append('<tr><td>' + element["SR"] + '</td><td>' + element["job_title"] + '</td><td>' + element["institute"] + '</td><td>' + element["description"] + '</td><td>' + element["experiance"] + '</td><td><button id="' + element["id"] + '" class="btn btn-outline-danger btn-sm" onclick="DeleteExperiance(this.id)">Remove</button></td></tr>');
                    });
                }
                
            }

        },
      
    });

}




$(document).ready(function(){

    $("#form").validate({
        rules:
        {
            job_title:
            {
                required : true
            },
            institute:
            {
                required : true
            },
            description:
            {
                required : true
            },
            experiance:
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

    $("#form").on("submit", function(event){
        event.preventDefault();
        debugger;
        var error = false;
        var status                  =       $(this).valid();
        if((status == true))
        {
            $("#submit_btn").prop("disabled", true);
            $("#submit_btn").text("Please Wait...");
            var data        =       new FormData();
            data.append("action", 'nurse_experiance_register');
            data.append("data", $(this).serialize());
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
                        $("#submit_btn").prop("disabled", false);
                        $("#submit_btn").text("Add Experiance");
                    }
                    
                    
                   
                    
                },
              
            });
            
        }
        else
        {
            alert("Please Provide All Your Details.")


        }


    });
    getNurseExperiances();

    
    





});