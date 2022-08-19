
function getProfileData()
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_profile_data'},
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
               $("#main").html('');
               $("#main").html(response["html"]);

                
            }
            else
            {
                alert(response["error_msg"]);
            }
            
        },
      
    });


}



$(document).ready(function(){
    
    getProfileData();

});