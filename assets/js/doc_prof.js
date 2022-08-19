
function getSpecialities()
{
    $.ajax
    ({
        type        :   "POST",
        url         :   'main/db_controller.php',
        data        :   {'action': 'get_specialities_list'},
        success     :   function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                if(response["list"].length > 0)
                {
                    $.each(response["list"], function(index, element){
                        $("#specialization").append('<option value="' + element["S_ID"] + '">' + element["S_DESC"] + '</option>');


                    });

                }
            }
          
            
            
           
            
        },
      
    });


}
function removeDuration(Id)
{
    alert(Id);
}
function getDurations()
{
    $.ajax
    ({
        type        :   "POST",
        url         :   'main/db_controller.php',
        data        :   {'action': 'get_durations'},
        success     :   function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                if(response["list"].length > 0)
                {
                    
                    $.each(response["list"], function(index, element){
                        $("#duration").append('<option value="' + element["D_ID"] + '">' + element["D_DESC"] + '</option>');


                    });

                }
                else
                {
                    $("#prices_form_wrapper").hide();
                }
            }
          
            
            
           
            
        },
      
    });


}


$(document).ready(function()
{
    
    $( "#other_specialities" ).select2({
        theme: "bootstrap",
        tags: true,
        placeholder: "Type Other Speciality here",
        containerCssClass: ':all:'
    });
    $("#services").select2({
        theme: "bootstrap",
        tags: true,
        placeholder: "Type Services here",
        containerCssClass: ':all:'
    });
    getSpecialities();
    getDurations();
    $("#price_form").validate({
        rules:
        {
            duration:
            {
                required : true
            },
            price:
            {
                required : true,
                min : 1
            },

        
            
            
        },
        messages:{
            duration:
            {
                required : "This Field is required"
            },
            price:
            {
                required : "This Field is required",
                min : "Enter a Valid Price"
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

    $("#price_form").on("submit", function(event){
        event.preventDefault();
        var status  =   $("#price_form").valid();
        if(status == true)
        {
            var duration    =   $("#duration").val();
            var price       =   $("#price").val();
            $("#duration").val("").change();
            $("#price").val("");
            $.ajax
            ({
                type        :   "POST",
                url         :   'main/db_controller.php',
                data        :   {'action': 'register_price', 'duration': duration, 'price': price},
                success     :   function (response) 
                {
                    response = JSON.parse(response)
                    if(response["error"] == false)
                    {
                        if(response["list"].length > 0)
                        {
                            $("#prices_table").html('');
                            $("#prices_table_wrapper").show();
                            $.each(response["list"], function(index, element){
                                $("#prices_table").append('<tr><td style="text-align: right">' + element["SR"] + '</td><td style="text-align: center">' + element["D_DESC"] + '</td><td style="text-align: right">' + element["PRICE"] + '</td><td class="text-center"><button id="' + element["D_ID"] + '" class="btn btn-danger btn-sm" type="button" onclick="removeDuration(this.id)"><i class="bx bxs-trash-alt"></i></button></td></tr>')
                            });
        
                        }
                        $("#duration").html('');
                        $("#duration").append('<option value="">Choose Duration</option>');
                        getDurations();
                    }
                    else
                    {
                        alert(response["error_msg"]);
                    }
                  
                    
                    
                   
                    
                },
              
            });

        }

    });
    
    $("#doctor_register_form").validate({
        rules:
        {
            specialization:
            {
                required : true
            },
            other_specialities:
            {
                required : true
            },
            hospital:
            {
                required : true
            },
            services:
            {
                required : true
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

    $("#doctor_register_form").on("submit", function(event){
        event.preventDefault();
        var error = false;
        var status                  =       $(this).valid();
        if((status == true) && (error == false))
        {
            $("#submit_btn").prop("disabled", true);
            $("#submit_btn").text("Processing...");
            var specialization  =   $("#specialization").val();
            var hospital        =   $("#hospital").val();
            var other_specs     =   $("#other_specialities").select2().val();
            var services        =   $("#services").select2().val();
            $.ajax
            ({
                type    :   "POST",
                url     :   'main/db_controller.php',
                data    :   {'action': 'register_doc_professional', 'specialization': specialization, 'hospital': hospital,'other_specs': other_specs,'services': services },
                async   :   false,
                success :   function (response) 
                {
                    debugger;
                    response = JSON.parse(response)
                    if(response["error"] == false)
                    {
                        window.location.href    =   "doc_edu.html";
                    }
                    if(response["error"] == true)
                    {
                        alert(response["error_msg"]);
                        $("#submit_btn").prop("disabled", false);
                        $("#submit_btn").text("Submit");
                    }
                    
                    
                   
                    
                },
              
            });
            
        }
        else
        {
            alert("Please Provide All Your Details.")


        }


    });

    $("#submit_btn").on("click", function(){
        $("#doctor_register_form").trigger("submit");

    });

});