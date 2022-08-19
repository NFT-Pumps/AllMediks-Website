function deleteCertificate(Id){
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'del_cert_doc', 'id': Id},
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

function getCertificates()
{
    $.ajax
    ({
        type        :       "POST",
        url         :       'main/db_controller.php',
        data        :       {'action': 'get_doc_certificates'},
        success     :       function (response) 
        {
            response = JSON.parse(response)
            if(response["error"] == false)
            {
                if(response["list"].length > 0)
                {
                    $.each(response["list"], function(index, element){
                        $("#table").append('<tr><td>' + element["sr"] + '</td><td>' + element["title"] + '</td><td>' + element["description"] + '</td><td>' + element["award_date"] + '</td><td class="text-center"><button id="' + element["id"] + '" onclick="deleteCertificate(this.id)" class="btn btn-outline-danger btn-sm"><i class="bx bxs-trash-alt"></i></button></td></tr>')
                    });
                    $("#table_wrapper").show();
                }
              
            }

        },
      
    });


}

$(document).ready(function()
{
    getCertificates();
    $(".datepicker").datepicker();
    $("#certficate_form").validate({
        rules:
        {
            title:
            {
                required : true
            },
            award_date:
            {
                required : true,
               
            },
            description:
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

    $("#certficate_form").on("submit", function(event){
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