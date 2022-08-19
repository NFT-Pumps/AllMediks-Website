function deleteExperiance(Id){
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'del_doc_exp', 'id': Id},
        success     :       function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                window.location.reload();
            }

        },
      
    });

}

function getExperiances()
{
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'get_doc_experiances'},
        success     :       function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                if(response["list"].length > 0)
                {
                    $.each(response["list"], function(index, element){
                        $("#table").append('<tr><td>' + element["sr"] + '</td><td>' + element["job_title"] + '</td><td>' + element["job_from"] + '</td><td>' + element["job_end"] + '</td><td>' + element["total_years"] + '</td><td>' + element["institute"] + '</td><td><button id="' + element["id"] + '" onclick="deleteExperiance(this.id)" class="btn btn-outline-danger btn-sm"><i class="bx bxs-trash-alt"></i></button></td></tr>')
                    });
                    $("#table_wrapper").show();
                }
              
            }

        },
      
    });


}

$(document).ready(function()
{
    getExperiances();
    $(".datepicker").datepicker();
    $("#experiance_form").validate({
        rules:
        {
            institute_name:
            {
                required : true
            },
            job_title:
            {
                required : true,
               
            },
            from_date:
            {
                required : true, 
            },
            to_date:
            {
                required : true,
                
            },
            total_experiance:
            {
                required : true,
                
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

    $("#experiance_form").on("submit", function(event){
        event.preventDefault();
        var status  =   $(this).valid();
        if(status == true)
        {
            $("#submit_btn").prop("disabled", true);
            $("#submit_btn").text("Processing...");
            $.ajax
            ({
                type        :       "POST",
                url         :       'main/db_controller.php',
                data        :       $(this).serialize(),
                async       :       false,
                success     :       function (response) 
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
                        $("#submit_btn").text("Add Education");
                    }
                    
                    
                   
                    
                },
              
            });


        }
        


    });


});