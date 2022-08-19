
function getDoctorData(register_ID)
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_doctor_data', 'register_ID': register_ID},
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                $("#profile_img").attr('src', response["DOCTOR"]["ABOUT"]["IMG"]);
                $("#name").text(response["DOCTOR"]["ABOUT"]["D_NAME"]);
                $("#description").text("Hi, Thanks for visiting my profile. I am Dr." + response["DOCTOR"]["ABOUT"]["D_NAME"] + ". I am Specialized in " + response["DOCTOR"]["ABOUT"]["SPECIALIZATION"] + ". Feel free to contact me anytime. Thanks");
                $("#hospital").html('<i class="bx bxs-map" style="color: #3fbbc0"></i> ' + response["DOCTOR"]["ABOUT"]["HOSPITAL"] + '');
                $("#about").text(response["DOCTOR"]["ABOUT"]["ABOUT"]);
                if(response["SERVICES"].length > 0)
                {
                    $.each(response["SERVICES"], function(index, element){
                        $("#services").append('<div class="col-lg-6"><li>'  + element["SERVICE"] + '</li> </div>');
                    });
                }
                if(response["SPECIALTIES"].length > 0)
                {
                    $.each(response["SPECIALTIES"], function(index, element){
                        $("#specializations").append('<div class="col-lg-6"><li>'  + element["S_DESC"] + '</li> </div>');
                    });
                }
                if(response["PRICES"].length > 0)
                {
                    $.each(response["PRICES"], function(index, element){
                        $("#prices").append('<li class="text-muted" style="font-size: 16px"><i class="bx bx-time"></i>&nbsp;&nbsp;' + element["PRICE_TYPE"] + ' ' +  element["PRICE"]+ ' USD </li>');
                    });
                }
                if(response["EDUCATION"].length > 0)
                {
                    $.each(response["EDUCATION"], function(index, element){
                        $("#educations").append('<div class="card" style="border: none;"><div class="info"><div class="row"><div class="col-lg-8"><h4 class="title">' + element["E_DESC"] + '</h4><h6>' + element["INSTITUTE"] + '</h6><p class="text-muted">' + element["E_START"] + ' - ' + element["E_END"] + '</p></div> <div class="col-lg-4"><img src="' + element["IMG"] + '" style="max-height: 150px; width: 100%" class="img-thumbnail" /></div></div></div></div>');
                    });
                }
                if(response["EXPERIANCE"].length > 0)
                {
                    $.each(response["EXPERIANCE"], function(index, element){
                        $("#experiance").append('<div class="card" style="border: none;"><div class="info"><div class="row"><div class="col-lg-12"><h4 class="title">' + element["TITLE"] + '</h4><h6>' + element["INSTITUTE"] + '</h6><p class="text-muted">' + element["JOB_FROM"] + ' - ' + element["JOB_END"] + '</p></div> </div></div></div>');
                    });
                }
                if(response["AWARDS"].length > 0)
                {
                    $.each(response["AWARDS"], function(index, element){
                        $("#awards").append('<div class="card" style="border: none;"><div class="info"><div class="row"><div class="col-lg-12"><h4 class="title">' + element["TITLE"] + '</h4><h6>' + element["AWARD_DT"] + '</h6><p class="text-muted">' + element["DESCRIPTION"] + '</p></div> </div></div></div>');
                    });
                }


                
                
            }
            
        },
      
    });


}

$(document).ready(function(){
    var doctor_ID   =   getParameterFromUrl("id", window.location.href);
    getDoctorData(doctor_ID);
    $("#appointment_btn").on("click", function(){
        window.location.href = "doc_appointment.html?id=" + doctor_ID;

    });


});