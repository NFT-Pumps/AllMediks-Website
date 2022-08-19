

function getEducations()
{
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'get_doc_edu'},
        success     :       function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                $("#degree_table").html('');
                if(response["list"].length > 0)
                {
                    $.each(response["list"], function(index, element){
                        $("#degree_table").append('<tr><td>' + element["sr"] + '</td><td>' + element["degree"] + '</td><td>' + element["start_year"] + '</td><td>' + element["end_year"] + '</td><td>' + element["institute"] + '</td><td><button id="' + element["id"]  + '" class="btn btn-outline-danger btn-sm" onclick="deleteEducation(this.id)"><i class="bx bxs-trash-alt"></i></button></td></tr>')
                    });
                    $("#degree_wrapper").show();
                }
              
            }

        },
      
    });


}
function deleteEducation(Id){
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'del_edu_doc', 'id': Id},
        success     :       function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                getEducations();
            }

        },
      
    });


}


$(document).ready(function()
{
    
    getEducations();
    $('#degree_certificate').filer({
		showThumbs: true,
		allowDuplicates: false,
        limit : 1
     
	});
    $("#register_education_form").validate({
        rules:
        {
            degree:
            {
                required : true
            },
            start:
            {
                required : true,
                digits   : true
               
            },
            ending:
            {
                required : true,
                digits   : true
                
            },
            institute:
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

    $("#register_education_form").on("submit", function(event){
        event.preventDefault();
        var status  =   $(this).valid();
        if(status == true)
        {
            var degree_certificate_counter     =       $("#degree_certificate").get(0).files.length;
            if(degree_certificate_counter <= 0)
            {
                $("#degree_certificate_error").text("Please upload clear picture of degree certificate.");

            }
            else
            {
                $("#submit_btn").prop("disabled", true);
                $("#submit_btn").text("Processing...");
                $("#degree_certificate_error").text("");
                var data        =       new FormData(this);
                $.ajax
                ({
                    type        :       "POST",
                    url         :       'main/db_controller.php',
                    data        :       data,
                    contentType :       false,
                    cache       :       false,
                    processData :       false,
                    async       :       false,
                    success     :       function (response) 
                    {
                        response = JSON.parse(response);
                        if(response["error"] == false)
                        {
                            $("#register_education_form")[0].reset();
                            window.location.reload();
                        }
                        if(response["error"] == true)
                        {
                            $("#submit_btn").prop("disabled", false);
                            $("#submit_btn").text("Add Education");
                            alert(response["error_msg"]);
                            
                        }
                        
                        
                       
                        
                    },
                  
                });
              




            }


        }
        


    });


});