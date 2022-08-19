

function getDurations(register_ID)
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_doc_durations', 'register_ID': register_ID},
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                $.each(response["list"], function(index, element){
                    $("#duration").append('<option value="' + element["D_ID"] + '">' + element["D_DESC"] + '</option>');

                });   
            }
            
        },
      
    });


}

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
                $("#name").text('Dr.  ' + response["DOCTOR"]["ABOUT"]["D_NAME"]);
                $("#description").text("Specialized In " + response["DOCTOR"]["ABOUT"]["SPECIALIZATION"]);
                $("#hospital").html('<i class="bx bxs-map" style="color: #3fbbc0"></i> ' + response["DOCTOR"]["ABOUT"]["HOSPITAL"] + '');


                
                
            }
            
        },
      
    });


}

$(document).ready(function(){
    var doctor_ID   =   getParameterFromUrl("id", window.location.href);
    getDoctorData(doctor_ID);
    getDurations(doctor_ID);
    $("#appointment_date").datepicker({dateFormat: 'dd/mm/yy'});
    $("#allergies").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div></div></div>').insertAfter( "#allergies_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#diabetic").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#diabetic_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#asthmatic").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#asthmatic_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#hypertensive").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#hypertensive_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#smoke").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#smoke_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#alcohol").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#alcohol_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#lung_infection").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#lung_infection_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#surgery").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#surgery_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#covid_vacinated").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#covid_vacinated_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#covid_contracted").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#covid_contracted_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });
    $("#covid_contact").on("change", function(){
        var value   =   $(this).prop("checked");
        var element_ID  =   $(this).attr("id");
        if(value == true)
        {
           
            $('<div class="row" id="' + element_ID + '_reason_wrapper"><div class="col-lg-12"><div class="mb-4"><label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="' + element_ID + '_reason">Please Specify</label><div class="relative"><textarea  id="' + element_ID + '_reason" name="' + element_ID + '_reason" placeholder="Please Enter Details" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" rows="6"></textarea></div></div> </div></div>').insertAfter( "#covid_contact_wrapper" );

        }
        else
        {
            $("#" + element_ID + '_reason_wrapper').remove();

        }

    });

    $("#appointment_form").on("submit", function(event){
        event.preventDefault();
        debugger;
        var appointment     =   {};
        var error           =   false;
        var allergies   =   $("#allergies").prop("checked");
        appointment["doctor_id"]    =   doctor_ID;
        if(allergies == true)
        {
            appointment["allergies"]    =   "Y";
            var reason    =   $("#allergies_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#allergies_reason").focus();
                alert("Please Specify Allergies");



            }
            else
            {
                appointment["allergies_reason"]     =   reason;
            }

        }
        else
        {
            appointment["allergies"]            =   "N";
            appointment["allergies_reason"]     =   "";

        }
        var problem   =   $("#problem").val();
        if((problem == undefined) || (problem == "") || (problem == null))
        {
            alert("Please Enter Problem Statement.");
            $("#problem").focus();
        }
        else
        {
            appointment["problem"]            =   problem;

        }
        var diabetic   =   $("#diabetic").prop("checked");
        if(diabetic == true)
        {
            appointment["diabetic"]    =   "Y";
            var reason    =   $("#diabetic_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#diabetic_reason").focus();
                alert("Please Specify Diabetics");



            }
            else
            {
                appointment["diabetic_reason"]      =   reason;

            }
               
        }
        else
        {
            appointment["diabetic"]             =   "N";
            appointment["diabetic_reason"]      =   "";

            
        }
        var asthmatic   =   $("#asthmatic").prop("checked");
        if(asthmatic == true)
        {
            appointment["asthmatic"]    =   "Y";
            var reason    =   $("#asthmatic_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#asthmatic_reason").focus();
                alert("Please Specify Asthmatic");



            }
            else
            {
                appointment["asthmatic_reason"]     =   reason;

            }
               
        }
        else
        {
            appointment["asthmatic"]            =   "N";
            appointment["asthmatic_reason"]     =   "";

            
        }
        var hypertensive   =   $("#hypertensive").prop("checked");
        if(hypertensive == true)
        {
            appointment["hypertensive"]    =   "Y";
            var reason    =   $("#hypertensive_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#hypertensive_reason").focus();
                alert("Please Specify Hypertensive");



            }
            else
            {
                appointment["hypertensive_reason"]      =   reason;

            }
               
        }
        else
        {
            appointment["hypertensive"]             =   "N";
            appointment["hypertensive_reason"]      =   "";

            
        }
        var smoke   =   $("#smoke").prop("checked");
        if(smoke == true)
        {
            appointment["smoke"]    =   "Y";
            var reason    =   $("#smoke_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#smoke_reason").focus();
                alert("Please Specify Smoking Details");



            }
            else
            {
                appointment["smoke_reason"]     =   reason;

            }
               
        }
        else
        {
            appointment["smoke"]            =   "N";
            appointment["smoke_reason"]     =   "";

            
        }
        var alcohol   =   $("#alcohol").prop("checked");
        if(alcohol == true)
        {
            appointment["alcohol"]    =   "Y";
            var reason    =   $("#alcohol_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#alcohol_reason").focus();
                alert("Please Specify Alcohol Details");



            }
            else
            {
              appointment["alcohol_reason"]    =   reason;

            }
               
        }
        else
        {
            appointment["alcohol"]           =   "N";
            appointment["alcohol_reason"]    =   "";

            
        }
        var lung_infection   =   $("#lung_infection").prop("checked");
        if(lung_infection == true)
        {
            appointment["lung_infection"]    =   "Y";
            var reason    =   $("#lung_infection_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#lung_infection_reason").focus();
                alert("Please Specify Lung Infection");



            }
            else
            {
                appointment["lung_infection_reason"]    =   reason;

            }
               
        }
        else
        {
            appointment["lung_infection"]           =   "N";
            appointment["lung_infection_reason"]    =   "";

            
        }
        var surgery   =   $("#surgery").prop("checked");
        if(surgery == true)
        {
            appointment["surgery"]    =   "Y";
            var reason    =   $("#surgery_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#surgery_reason").focus();
                alert("Please Specify Surgery");



            }
            else
            {
                appointment["surgery_reason"]    =   reason;

            }
               
        }
        else
        {
            appointment["surgery"]           =   "N";
            appointment["surgery_reason"]    =   "";
            
        }
        var covid_vacinated   =   $("#covid_vacinated").prop("checked");
        if(covid_vacinated == true)
        {
            appointment["covid_vacinated"]    =   "Y";
            var reason    =   $("#covid_vacinated_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#covid_vacinated_reason").focus();
                alert("Please Specify Covid Vacinated Details");



            }
            else
            {
                appointment["covid_vacinated_reason"]       =   reason;

            }
               
        }
        else
        {
            appointment["covid_vacinated"]              =   "N";
            appointment["covid_vacinated_reason"]       =   "";
            
        }
        var covid_contact   =   $("#covid_contact").prop("checked");
        if(covid_contact == true)
        {
            appointment["covid_contact"]    =   "Y";
            var reason    =   $("#covid_contact_reason").val();
            if((reason == "") || (reason == null) || (reason == undefined))
            {
                error = true;
                $("#covid_contact_reason").focus();
                alert("Please Specify Covid Contact");



            }
            else
            {
                appointment["covid_contact_reason"]     =   reason;

            }
               
        }
        else
        {
            appointment["covid_contact"]            =   "N";
            appointment["covid_contact_reason"]     =   "";
            
        }
        var date    =   $("#appointment_date").val();
        if((date == "") || (date == null) || (date == undefined))
        {
            error = true;
            $("#appointment_date").focus();
            alert("Please Enter Appointment Date");



        }
        else
        {
            appointment["date"]    =   date;
        }
        var package    =   $("#duration").val();
        if((package == "") || (package == null) || (package == undefined))
        {
            error = true;
            $("#duration").focus();
            alert("Please Select Duration Of Appointment");



        }
        else
        {
            appointment["duration"]    =   package;
        }
        if(error == false)
        {
            $.ajax
            ({    
                type            :       "POST",
                url             :       '../main/db_controller.php',
                data            :       {'action': 'new_appointment', 'appointment': appointment},
                success         :       function (response) 
                {
                    response    =   JSON.parse(response);
                    if(response["error"] == false)
                    {
                        alert(response["msg"]);
                        window.location.href    =   "index.html";

                    }
                    else
                    {
                        alert(response["error_msg"]);
                    }
                
                },
          
            });
        }


        

    });


});