

function getParameterFromUrl(name, url) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }
function getUserData()
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'get_user_data'},
        async           :       false,
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                if(response["USER"])
                {
                    $(".nav-profile-name").text(response["USER"]["USERNAME"]);
                    $(".navbar-profile-img").attr("src", "../" + response["USER"]["P_IMG"]);
                    $(".welcome_heading").text(response["heading"]);
                    $(".welcome_title").text(response["title"]);


                }
            }
            
        },
      
    });


}

function logout()
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'logout'},
        async           :       false,
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == false)
            {
                window.location.href = '../sign_in.html';   
            }
            
        },
      
    });


}
function checkLogin()
{
    $.ajax
    ({    
        type            :       "POST",
        url             :       '../main/db_controller.php',
        data            :       {'action': 'check_login'},
        async           :       false,
        success         :       function (response) 
        {
            response    =   JSON.parse(response);
            if(response["error"] == true)
            {
                window.location.href = '../sign_in.html';   
            }
            if(response["error"] == false)
            {
                getUserData();
            }
            
        },
      
    });


}



$(document).ready(function(){

    checkLogin();



});