
function ViewDoctorProfile(id)
{
    window.location.href = "doctor.html?id="+id;
}
function ViewNurseProfile(id)
{
    window.location.href = "profile.html?id=" + id + "&type=nurse";
}
function BookAppointment(id){
    window.location.href = "doc_appointment.html?id="+id;


}



function getData(register_ID)
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_data_index'},
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                var html    =   '';
                if(response["doctors"].length > 0)
                {
                    $.each(response["doctors"], function(index, element){
                        html+= '<div class="row" style="margin-top: 20px;"><div class="col-md-4 grid-margin stretch-card"><div class="card"><div class="card-body"><blockquote class="blockquote"><img src="../uploads/doctors/' + element["REGISTER_ID"] + '/' + element["PF_IMG"] + '" alt="' + element["D_NAME"] + '" class="img-fluid"/></blockquote><h4 class="display-5 text-primary" style="font-style: initial; font-weight: 600;">Dr. ' + element["D_NAME"] + '</h4><p class="card-description">Hi, I am Dr. '+ element["D_NAME"] +'. I am Specialized in ' + element["SPECIALIZATION"] + '. You can easily find me at ' + element["LOCATION"] + '.</p><div class="template-demo"><button type="button" class="btn btn-outline-danger btn-rounded btn-fw" id="' + element["REGISTER_ID"] + '" onclick="ViewDoctorProfile(this.id)">View Profile</button><button type="button" id="' + element["REGISTER_ID"] + '" onclick="BookAppointment(this.id)" class="btn btn-outline-primary btn-rounded btn-fw">Book Appointment</button></div></div></div></div></div>';
                    });

                }
                if(response["nurses"])
                {
                    if(response["nurses"].length > 0)
                    {
                        html+='<div class="d-flex justify-content-between flex-wrap"><div class="d-flex align-items-end flex-wrap"><div class="me-md-3 me-xl-5"><h2 class="display-3">Nurses</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div></div></div>';
                            $.each(response["nurses"], function(index, element){
                                html+= '<div class="row" style="margin-top: 20px;"><div class="col-md-4 grid-margin stretch-card"><div class="card"><div class="card-body"><blockquote class="blockquote"><img src="../uploads/nurses/' + element["REGISTER_ID"] + '/' + element["PF_IMG"] + '" alt="' + element["NAM"] + '" class="img-fluid"/></blockquote><h4 class="display-5 text-primary" style="font-style: initial; font-weight: 600;">' + element["NAM"] + '</h4><p class="card-description">Hi, I am ' + element["NAM"] + ' and I am working as a nurse from last several years. My age is ' + element["AGE"] + ' years old. </p><div class="template-demo"><button type="button" class="btn btn-outline-danger btn-rounded btn-fw" id="' + element["REGISTER_ID"] + '" onclick="ViewNurseProfile(this.id)">View Profile</button><button type="button" id="' + element["REGISTER_ID"] + '" onclick="HireNurse(this.id)" class="btn btn-outline-primary btn-rounded btn-fw">Hire Me</button></div></div></div></div></div>';
                            });

                    }
                }
                if(response["pharmacies"].length > 0)
                {
                    html+='<div class="d-flex justify-content-between flex-wrap"><div class="d-flex align-items-end flex-wrap"><div class="me-md-3 me-xl-5"><h2 class="display-3">Pharmacies</h2><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p></div></div></div>';
                    $.each(response["pharmacies"], function(index, element){
                        html+= '<div class="row" style="margin-top: 20px;"><div class="col-md-4 grid-margin stretch-card"><div class="card"><div class="card-body"><blockquote class="blockquote"><img src="../uploads/pharmacies/' + element["REGISTER_ID"] + '/' + element["PF_IMG"] + '" alt="' + element["NAM"] + '" class="img-fluid"/></blockquote><h4 class="display-5 text-primary" style="font-style: initial; font-weight: 600;">' + element["NAM"] + '</h4><p class="card-description">Hi, Our pharmacy name is ' + element["NAM"] + '. We deal with all kind of medicines. Feel free to reach out to us.</p><div class="template-demo"><button type="button" class="btn btn-outline-danger btn-rounded btn-fw" id="' + element["REGISTER_ID"] + '" onclick="ViewPharmacyProfile(this.id)">View Profile</button><button type="button" id="' + element["REGISTER_ID"] + '" onclick="(this.id)" class="btn btn-outline-primary btn-rounded btn-fw">Visit Now</button></div></div></div></div></div>';
                    });

                }
                


                $("#main").html(html);
               
            }
            
        },
      
    });


}




$(document).ready(function(){
    getData();




});