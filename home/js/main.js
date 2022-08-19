



function getProfileData(register_ID)
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_profile_based_data'},
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                $("#main").html(response["html"]);
                
               
            }
            
        },
      
    });


}




$(document).ready(function(){
    getProfileData();




});