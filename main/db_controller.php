<?php


session_start();
require_once('db_connection.php');
require_once('functions.php');

if((isset($_POST["action"]) && ($_POST["action"] == "check_email")))
{
    $email = $_POST["email"];
    $sql = "SELECT * FROM pf_mst WHERE email = ?";
    if($stmt = mysqli_prepare($db, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result    = $stmt->get_result();
        if($result)
        {
            $row    =   mysqli_fetch_assoc($result);
            if($row)
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error, This Email Address is already registered.";

            }
        }
    } 
    else
    {
        $response["error"]      =   true;
        $response["error_msg"]  =   "Error ". mysqli_error($db);
    }
    mysqli_stmt_close($stmt);
    if($response["error"] == false)
    {
        $sql = "INSERT INTO pf_mst(email, login_password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $email, $_POST["password"]);
            mysqli_stmt_execute($stmt);
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }
        mysqli_stmt_close($stmt);
    }
    echo(json_encode($response));
    


}
if((isset($_POST["action"]) && ($_POST["action"] == "get_profile_type")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT type_id, initcap(type_desc) type_desc  FROM pf_types WHERE type_id NOT IN  (select type_id from pf_dtl_types where register_id = ?) ORDER BY type_id";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]      =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "register_profile_type")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $type_ID = $_POST["profile_type"];
        $sql = "INSERT INTO pf_dtl_types(register_id, type_id) VALUES (?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $type_ID);
            $result =   mysqli_stmt_execute($stmt);
            if($result)
            {
                $response["profile_type"]   =   $type_ID;
            }
            if(!$result)
            {
                $response["error"]  =   true;
                $response["error_msg"]  =   "Error, Unable to update profile type.";

            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
            
        }

        if(($response["error"] == false) && ($response["profile_type"]))
        {
            $sql = "SELECT M.type_id type_id, D.type_desc type_desc 
                    FROM pf_dtl_types M, pf_types D
                    WHERE M.type_id = D.type_id
                    AND M.register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                mysqli_stmt_execute($stmt);
                $result     =   $stmt->get_result();
                if($result)
                {
                    $row    =   mysqli_fetch_assoc($result);
                    if($row)
                    {
                        $_SESSION["allmediks_user"]["type_desc"]   =   $row["type_desc"];


                    }
                    
                }
                if(!$result)
                {
                    $response["error"]  =   true;
                    $response["error_msg"]  =   "Error, Unable to update profile type.";

                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            
            }


        }


    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "register_patient")))
{
    if(isset($_SESSION["allmediks_user"]))
    {

        $allowed_image_extension = array("png","jpg","jpeg");
        $register_ID    =   $_SESSION["allmediks_user"]["pf_id"];
        $sql = "INSERT INTO patient_mst(register_id, fname, lname, date_of_birth, age, height, weight, gender, martial_status, eg_contact, eg_relation, eg_phone, eg_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $_SESSION["allmediks_user"]["pf_id"], $_POST["firstname"], $_POST["lastname"], $_POST["date_of_birth"], $_POST["age"], $_POST["height"], $_POST["weight"], $_POST["gender"], $_POST["martial_status"], $_POST["emergency_contact"] ,$_POST["relationship"], $_POST["phone_number"], $_POST["address"]);
            if(!mysqli_stmt_execute($stmt))
            {
                $response["error"]      =    false;
                $response["error_msg"]  =    $stmt->error;
                $response["error_no"]   =    $stmt->errno;
            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
            
        }
        if($response["error"] == false)
        {
            $directory                  =   "../uploads/patients/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $profile_img_counter        =   count($_FILES['profile_image_input']['name']);
            $driver_permit_counter      =   count($_FILES['driver_permit']['name']);
            $passport_counter           =   count($_FILES['passport']['name']);
            $id_card_counter            =   count($_FILES['identity_card']['name']);
            if($driver_permit_counter > 0)
            {
                for($i = 0; $i < $driver_permit_counter; $i ++)
                {
                    $temp       =   explode('.', basename($_FILES['driver_permit']['name'][$i]));
                    $f_ext      =   end($temp);
                    if(in_array($f_ext, $v_extns))
                    {
                        if($_FILES["driver_permit"]["size"][$i] <  100000)
                        {
                            if(!file_exists($directory))
                            {
                                if(!mkdir($directory, 0777, true))
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error, Unable To Create Directory...";

                                }


                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "driverpermit_".$register_ID.$i.".".$f_ext;
                                if (move_uploaded_file($_FILES['driver_permit']['tmp_name'][$i], $directory.$f_name)) 
                                {
                                    $sql         =      "INSERT INTO patient_dtl(patient_id, doc_type, path) VALUES (?, 'DP', ?)";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $register_ID, $f_name);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                
                                        }
            
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading driver permit file.";
                                }
                               
                            }
                            
                            
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Driver Permit File Size Exceeds 100 Kb.";

                        }

                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Driver Permit Format is Invalid";

                    }

                }
                

            }
            if($passport_counter > 0)
            {
                for($i = 0; $i < $passport_counter; $i ++)
                {
                    $temp       =   explode('.', basename($_FILES['passport']['name'][$i]));
                    $f_ext      =   end($temp);
                    if(in_array($f_ext, $v_extns))
                    {
                        if($_FILES["passport"]["size"][$i] <  100000)
                        {
                            if(!file_exists($directory))
                            {
                                if(!mkdir($directory, 0777, true))
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error, Unable To Create Directory...";

                                }


                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "passport_".$register_ID.$i.".".$f_ext;
                                if (move_uploaded_file($_FILES['passport']['tmp_name'][$i], $directory.$f_name)) 
                                {
                                    $sql         =      "INSERT INTO patient_dtl(patient_id, doc_type, path) VALUES (?, 'PP', ?)";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $register_ID, $f_name);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                
                                        }
            
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading passport file.";
                                }
                               
                            }
                            
                            
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Passport File Size Exceeds 100 Kb.";

                        }

                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Passport Format is Invalid";

                    }

                }
                

            }
            if($id_card_counter > 0)
            {
                for($i = 0; $i < $id_card_counter; $i ++)
                {
                    $temp       =   explode('.', basename($_FILES['identity_card']['name'][$i]));
                    $f_ext      =   end($temp);
                    if(in_array($f_ext, $v_extns))
                    {
                        if($_FILES["identity_card"]["size"][$i] <  100000)
                        {
                            if(!file_exists($directory))
                            {
                                if(!mkdir($directory, 0777, true))
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error, Unable To Create Directory...";

                                }


                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "identitycard_".$register_ID.$i.".".$f_ext;
                                if (move_uploaded_file($_FILES['identity_card']['tmp_name'][$i], $directory.$f_name)) 
                                {
                                    $sql         =      "INSERT INTO patient_dtl(patient_id, doc_type, path) VALUES (?, 'IC', ?)";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $register_ID, $f_name);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                
                                        }
            
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading Identity Card file.";
                                }
                               
                            }
                            
                            
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Identity Card File Size Exceeds 100 Kb.";

                        }

                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Identity Card Format is Invalid";

                    }

                }
                

            }
            if($profile_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['profile_image_input']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["profile_image_input"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";

                            }


                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "profile".$register_ID.".".$f_ext;
                                if (move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $directory.$f_name)) 
                                {
                                    $sql         =      "UPDATE patient_mst SET profile_img = ? WHERE register_id = ?";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $f_name, $register_ID);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                
                                        }
            
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading Profile Image";
                                }
                               
                            }
                            
                            
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";

                        }

                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Format is Invalid";

                    }
                

            }
       
            

        }

        if($response["error"] == false)
        {
            $response["msg"]    =   "Registration Successfull...";
        }
        if($response["error"] == true)
        {
            $sql    =      "DELETE FROM patient_dtl WHERE patient_id = ?";
            $stmt   =       mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $sql    =      "DELETE FROM patient_mst WHERE register_id = ?";
            $stmt   =       mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            xrmdir($directory);
                             
        }


    }
    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "login")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        unset($_SESSION["allmediks_user"]);
    }
    $email      =   $_POST["email"];
    $password   =   $_POST["password"];
    $sql        =   "SELECT P.pf_id pf_id, P.email email, P.active active_status 
                     FROM pf_mst P
                     WHERE  UPPER(P.email) = UPPER(?) 
                     AND P.login_password = ?";
    if($stmt    =   mysqli_prepare($db, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        $result    = $stmt->get_result();
        if($result)
        {
            $row    =   mysqli_fetch_assoc($result);
            if($row)
            {
                $response["error"]          =   false;
                $_SESSION["allmediks_user"] =   $row;                          
            }
            if(!$row)
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, Invalid Email Or Password.";



            }
        }
    }
    if(($response["error"] == false) && (isset($_SESSION["allmediks_user"])))
    {
        $sql        =   "SELECT M.register_id REGISTER_ID, M.type_id TYPE_ID, D.type_desc TYPE_DESC 
                         FROM pf_dtl_types M, pf_types D
                         WHERE M.type_id = D.type_id
                         AND M.register_id = ?";
        if($stmt    =   mysqli_prepare($db, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
        mysqli_stmt_execute($stmt);
        $result    = $stmt->get_result();
        if($result)
        {
            $row    =   mysqli_fetch_assoc($result);
            if($row)
            {
                $response["error"]  =   false;
                $_SESSION["allmediks_user"]["type_desc"]         =   $row["TYPE_DESC"];
                $response["user"]   =   $_SESSION["allmediks_user"];

            }
            if(!$row)
            {
                $response["user"]["profile_type"]   =   false;
            }
        }
    }


    }
    
    echo(json_encode($response));
    


}
if((isset($_POST["action"]) && ($_POST["action"] == "register_doctor_personal")))
{
    if(isset($_SESSION["allmediks_user"]))
    {

        $allowed_image_extension = array("png","jpg","jpeg");
        $register_ID    =   $_SESSION["allmediks_user"]["pf_id"];
        $sql = "INSERT INTO doctor_mst(register_id, fname, lname, date_of_birth, age, height, weight, gender, martial_status, reg_at, about) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssssssssss", $_SESSION["allmediks_user"]["pf_id"], $_POST["firstname"], $_POST["lastname"], $_POST["date_of_birth"], $_POST["age"], $_POST["height"], $_POST["weight"], $_POST["gender"], $_POST["martial_status"], $_POST["about"]);
            if(!mysqli_stmt_execute($stmt))
            {
                $response["error"]      =    true;
                $response["error_msg"]  =    $stmt->error;
                $response["error_no"]   =    $stmt->errno;
            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
            
        }
        if($response["error"] == false)
        {
            $directory                  =   "../uploads/doctors/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $profile_img_counter        =   count($_FILES['profile_image_input']['name']);
            if($profile_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['profile_image_input']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["profile_image_input"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";

                            }


                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "profile".$register_ID.".".$f_ext;
                            if (move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE doctor_mst SET profile_img = ? WHERE register_id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $f_name, $register_ID);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";

                    }

                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";

                }
                

            }
            
           
       
            

        }

        if($response["error"] == false)
        {
            $response["msg"]            =   "Registration Successfull...";
            $response["register_ID"]    =   $register_ID;
        }
        if($response["error"] == true)
        {
            $sql    =      "DELETE FROM doctor_mst WHERE register_id = ?";
            $stmt   =       mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            xrmdir($directory);
                             
        }


    }
    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_specialities_list")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        // $sql = "DELETE FROM prices_temp WHERE register_id = ?";
        // if($stmt = mysqli_prepare($db, $sql))
        // {
        //     mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
        //     $result    =    mysqli_stmt_execute($stmt);
            
        // } 
        // else
        // {
        //     $response["error"]      =   true;
        //     $response["error_msg"]  =   "Error ". mysqli_error($db);
        // }
        $sql = "select s.id S_ID, initcap(s.description) S_DESC  from specialities s order by 1";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $f_name, $register_ID);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_durations")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT D.d_id D_ID, D.d_desc D_DESC FROM durations D WHERE  D.d_id NOT IN (SELECT p.d_id from prices_temp p where p.register_id = ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "register_price")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
       
        if($response["error"] == false)
        {
            $sql = "INSERT INTO prices_temp(register_id, d_id, price) VALUES (?, ?, ?)";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sss", $_SESSION["allmediks_user"]["pf_id"], $_POST["duration"], $_POST["price"]);
                $result    =    mysqli_stmt_execute($stmt);
                if(!$result)
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error, Please try again later...";

                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }

        }
        if($response["error"] == false)
        {
            $sql = "SELECT p.d_id D_ID, d.d_desc D_DESC, p.price PRICE FROM prices_temp p, durations d WHERE p.d_id = d.d_id  AND p.register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                mysqli_stmt_execute($stmt);
                $result = $stmt->get_result();
                if($result)
                {
                    $sr     =   1;
                    while($row  =   mysqli_fetch_assoc($result))
                    {
                        $row["SR"]              =   $sr;
                        $response["list"][]     =   $row;
                        $sr ++;


                    }


                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }

        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "register_doc_professional")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
       
        if($response["error"] == false)
        {
            $sql = "UPDATE doctor_mst SET specialization_id =  ?, hospital =  ? WHERE register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sss", $_POST["specialization"], $_POST["hospital"], $_SESSION["allmediks_user"]["pf_id"]);
                $result     =   mysqli_stmt_execute($stmt);
                if(!$result)
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = $stmt->error;
                    
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }

        }
        if($response["error"] == false)
        {
            foreach($_POST["other_specs"] as $spec)
            {
                $sql = "INSERT INTO doctor_specialities(register_id, speciality) VALUES (?, ?)";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $spec);
                    $result     =   mysqli_stmt_execute($stmt);
                    if(!$result)
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = $stmt->error;
                    
                    }
                } 
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);
                }

            }

        }
        if($response["error"] == false)
        {
            foreach($_POST["services"] as $service)
            {
                $sql = "INSERT INTO doctor_services (register_id, service) VALUES (?, ?)";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $service);
                    $result     =   mysqli_stmt_execute($stmt);
                    if(!$result)
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = $stmt->error;
                    
                    }
                } 
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);
                }

            }

        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "doc_edu_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        if($response["error"] == false)
        {

            $sql = "INSERT INTO doctor_edu(register_id, degree, start_year, end_year, institute, ent_date) VALUES (?, ?, ?, ?, ?,  NOW())";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "sssss", $_SESSION["allmediks_user"]["pf_id"],  $_POST["degree"], $_POST["start"], $_POST["ending"], $_POST["institute"]);
                $result     =   mysqli_stmt_execute($stmt);
                if(!$result)
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = $stmt->error;
                
                }
                if($result)
                {
                    $response["doc_edu_id"]     =   $db->insert_id;
                
                }

            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
            $certificate_counter    =   count($_FILES['degree_certificate']['name']);
            if($certificate_counter > 0)
            {
                $directory  =   "../uploads/doctors/".$_SESSION["allmediks_user"]["pf_id"]."/";
                $v_extns                    =   array("jpeg", "jpg", "png");
                $temp       =   explode('.', basename($_FILES['degree_certificate']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["degree_certificate"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";

                            }


                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "edu_certificate_".$response["doc_edu_id"]."_".$_SESSION["allmediks_user"]["pf_id"].".".$f_ext;
                            if (move_uploaded_file($_FILES['degree_certificate']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE doctor_edu SET certificate_path = ? WHERE id = ? AND register_id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "sss", $f_name, $response["doc_edu_id"] ,$_SESSION["allmediks_user"]["pf_id"]);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";

                    }

                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";

                }
                

            }
           

        }

        if($response["error"]  == true)
        {
            $sql = "DELETE FROM doctor_edu WHERE register_id = ? AND id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $response["doc_edu_id"]);
                $result     =   mysqli_stmt_execute($stmt);
                if(!$result)
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = $stmt->error;
                
                }
                

            } 

        }
        
    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_doc_edu")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, degree, start_year, end_year, institute, ent_date, certificate_path FROM doctor_edu WHERE register_id";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                $counter = 1;
                while($row = mysqli_fetch_assoc($result))
                {
                    $row["sr"]  =   $counter;
                    $list[] = $row;
                    $counter ++;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "register_doc_exp")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "INSERT INTO doctor_dtl_exp(register_id, institute, job_title, job_from, job_end, total_years) VALUES (?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssssss", $_SESSION["allmediks_user"]["pf_id"], $_POST["institute_name"], $_POST["job_title"], $_POST["from_date"], $_POST["to_date"], $_POST["total_experiance"]);
            $result    =    mysqli_stmt_execute($stmt);
            if(!$result) 
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   $db->error;

            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_doc_experiances")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, institute, job_title, job_from, job_end, total_years FROM doctor_dtl_exp WHERE register_id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                $counter = 1;
                while($row = mysqli_fetch_assoc($result))
                {
                    $row["sr"]  =   $counter;
                    $list[] = $row;
                    $counter ++;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "del_doc_exp")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM doctor_dtl_exp WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      = false;

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$db->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "del_edu_doc")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM doctor_edu WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      = false;

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$db->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "reg_doc_certificate")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "INSERT INTO doc_dtl_awards(register_id, title, description, award_date) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION["allmediks_user"]["pf_id"], $_POST["title"], $_POST["description"], $_POST["award_date"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      = false;

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$db->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_doc_certificates")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, title, description, award_date FROM doc_dtl_awards WHERE register_id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                $counter = 1;
                while($row = mysqli_fetch_assoc($result))
                {
                    $row["sr"]  =   $counter;
                    $list[] = $row;
                    $counter ++;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }
    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "del_cert_doc")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM doc_dtl_awards WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      = false;

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$db->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "check_login")))
{
    if(!isset($_SESSION["allmediks_user"]))
    {
        $response["error"]          =   true;
        $response["error_msg"]      =   "Error, Please Login to Continue...";
    }
    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "logout")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        unset($_SESSION["allmediks_user"]);
        $response["error"]  =   false;
    }
    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_user_data")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT PF.REGISTER_ID,  PF.USERNAME, PF.EMAIL, PF.ACTIVE_STATUS, PF.P_IMG, PF.P_TYPE 
                FROM 
                (
                    SELECT M.pf_id REGISTER_ID, CONCAT(D.fname, ' ', D.lname) USERNAME, M.email EMAIL, M.active ACTIVE_STATUS, CONCAT('uploads/doctors/', D.register_id, '/', D.profile_img) P_IMG, 'DOCTOR' P_TYPE 
                    FROM pf_mst M, doctor_mst D
                    WHERE D.register_id = M.pf_id
                    UNION ALL
                    SELECT M.pf_id REGISTER_ID, CONCAT(P.fname, ' ', P.lname) USERNAME, M.email EMAIL, M.active ACTIVE_STATUS, CONCAT('uploads/patients/', P.register_id, '/', P.profile_img) P_IMG, 'PATIENT' P_TYPE FROM pf_mst M, patient_mst P
                    WHERE P.register_id = M.pf_id
                    UNION ALL
                    SELECT M.pf_id REGISTER_ID, CONCAT(N.f_name, ' ', N.l_name) USERNAME, M.email EMAIL, M.active ACTIVE_STATUS, CONCAT('uploads/nurses/', N.register_id, '/', N.pf_img) P_IMG, 'NURSE' P_TYPE FROM pf_mst M, nurse_mst N
                    WHERE N.register_id = M.pf_id
                    UNION ALL
                    SELECT M.pf_id REGISTER_ID, CONCAT(H.name) USERNAME, M.email EMAIL, M.active ACTIVE_STATUS, CONCAT('uploads/hospitals/', H.register_id, '/', H.profile_img) P_IMG, 'HOSPITAL' P_TYPE FROM pf_mst M,  hospital_mst H
                    WHERE H.register_id = M.pf_id
                    UNION ALL
                    SELECT M.pf_id REGISTER_ID, CONCAT(PH.name) USERNAME, M.email EMAIL, M.active ACTIVE_STATUS, CONCAT('uploads/pharmacies/', PH.register_id, '/', PH.profile_img) P_IMG, 'PHARMACY' P_TYPE FROM pf_mst M,  pharmacy_mst PH
                    WHERE PH.register_id = M.pf_id
                ) PF 
                WHERE REGISTER_ID = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result     =   $stmt->get_result();
            if($result) 
            {
                $row    =   mysqli_fetch_assoc($result);
                if($row)
                {
                    $response["USER"]   =   $row;

                }
                if(!$row)
                {
                    $response["error"]          =   true;
                    $response["error_msg"]      =   "Error, No Record Found...";


                }

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$db->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }



    }
    else
    {
        $response["error"]          =   true;
        $response["error_msg"]      =   "Error, Request From Unauthorized Source";
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_data")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT D.register_id D_ID, CONCAT(D.fname, ' ', D.lname) AS D_NAME, D.about ABOUT , S.description DESCRIPTION, D.profile_img PROFILE_IMG  
                FROM doctor_mst D, specialities S 
                WHERE D.specialization_id = S.id";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $html = '';
                while($row = mysqli_fetch_assoc($result))
                {
                    $html.='<div class="col-lg-4">
                                    <img src="../uploads/doctors/'.$row["D_ID"].'/'.$row["PROFILE_IMG"].'" class="img-thumbnail" style="max-height: 300px; width: 300px;"/>
                                    <h3 style="margin-top: 20px; color: #3fbbc0">Dr. '.$row["D_NAME"].'</h3>
                                    <h6 class="text-muted">Specialized In    '.$row["DESCRIPTION"].'</h6>
                                    <p></p>
                                    <div class="row" style="margin-top:25px">
                                        <div class="col-lg-12">
                                            <a class="btn btn-outline-danger btn-rounded btn-fw" href="doctor.html?Id='.$row["D_ID"].'">View Profile</a>
                                            <a href="doctor.html?Id='.$row["D_ID"].'" class="btn btn-outline-primary btn-rounded btn-fw">Book Appointment</a>
                         

                                        </div>
                                      

                                    </div>
                                </div>
                            </div>';
                    
                }              
                
                
                $response["html"]   =   $html;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_doctor_data")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT M.register_id D_ID, CONCAT(M.FNAME, ' ', M.LNAME) D_NAME, M.hospital HOSPITAL, S.description SPECIALIZATION, M.about ABOUT, M.profile_img IMG  
                FROM doctor_mst M, specialities S
                WHERE M.specialization_id = S.id
                AND M.register_id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $row = mysqli_fetch_assoc($result);
                if($row)
                {
                    $row["IMG"]                     =   '../uploads/doctors/'.$_POST["register_ID"].'/'.$row["IMG"];
                    $response["DOCTOR"]["ABOUT"]    =   $row;
                }
                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }
        if($response["error"] == false)
        {
            $sql = "SELECT DS.id ID, initcap(DS.service)  SERVICE FROM doctor_services DS WHERE DS.register_id = ? ORDER BY  DS.id";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $response["SERVICES"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }
        if($response["error"] == false)
        {
            $sql = "SELECT M.id S_ID, M.speciality S_DESC FROM doctor_specialities M WHERE M.register_id = ? ORDER BY M.id";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $response["SPECIALTIES"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }
        if($response["error"] == false)
        {
            $sql = "SELECT D.d_desc PRICE_TYPE, P.price PRICE FROM prices_temp P, durations D WHERE P.d_id = D.d_id AND P.register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $response["PRICES"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }
        if($response["error"] == false)
        {
            $sql = "SELECT E.id E_ID, initcap(E.degree) E_DESC, E.start_year E_START, E.end_year E_END, E.institute INSTITUTE, E.certificate_path IMG 
                    FROM doctor_edu E
                    WHERE E.register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $row["IMG"]                 =   "../uploads/doctors/".$_POST["register_ID"]."/".$row["IMG"];
                        $response["EDUCATION"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }
        if($response["error"] == false)
        {
            $sql = "SELECT M.register_id REGISTER_ID ,M.id ID, M.job_title TITLE, M.institute INSTITUTE, M.job_from JOB_FROM, M.job_end JOB_END, M.total_years TOTAL_EXP FROM doctor_dtl_exp M WHERE M.register_id =  ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      
                        $response["EXPERIANCE"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }
        if($response["error"] == false)
        {
            $sql = "SELECT M.register_id REGISTER_ID, M.id ID, M.title TITLE, M.description DESCRIPTION, M.award_date AWARD_DT FROM doc_dtl_awards M WHERE M.register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                      
                        $response["AWARDS"][]    =   $row;

                    }                    
                
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_doc_durations")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT P.d_id D_ID, CONCAT(P.price,'$/' , D.d_desc) AS D_DESC 
                FROM prices_temp P, durations D
                WHERE P.d_id = D.d_id
                AND P.register_id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_POST["register_ID"]);
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                $counter = 1;
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                    $counter ++;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "new_appointment")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "INSERT INTO appointment(allergies, allergies_reason, diabetic, diabetic_reason, asthmatic, asthmatic_reason, hypertensive, hypertensive_reason, smoke, smoke_reason, alcohol, alcohol_reason, lung_infection, lung_infection_reason, surgery, surgery_reason, covid_vacinated, covid_vacinated_reason, covid_contact, covid_contact_reason, ent_date, duration_id, appoint_to, problem, appoint_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssss", $_POST["appointment"]["allergies"], $_POST["appointment"]["allergies_reason"], $_POST["appointment"]["diabetic"], $_POST["appointment"]["diabetic_reason"], $_POST["appointment"]["asthmatic"], $_POST["appointment"]["asthmatic_reason"], $_POST["appointment"]["hypertensive"], $_POST["appointment"]["hypertensive_reason"], $_POST["appointment"]["smoke"], $_POST["appointment"]["smoke_reason"], $_POST["appointment"]["alcohol"], $_POST["appointment"]["alcohol_reason"], $_POST["appointment"]["lung_infection"], $_POST["appointment"]["lung_infection_reason"], $_POST["appointment"]["surgery"], $_POST["appointment"]["surgery_reason"], $_POST["appointment"]["covid_vacinated"], $_POST["appointment"]["covid_vacinated_reason"], $_POST["appointment"]["covid_contact"], $_POST["appointment"]["covid_contact_reason"], $_POST["appointment"]["duration"], $_POST["appointment"]["doctor_id"], $_POST["appointment"]["problem"], $_SESSION["allmediks_user"]["pf_id"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      =   false;
                $response["msg"]  =   "Your Appointment is Scheduled Successfully.";

            }
            if(!$result) 
            {
                $response["error"] = true;
                $response["error_msg"]  =   "Error, ".$stmt->error;

            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_departments")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "select s.id S_ID, initcap(s.description) S_DESC  from specialities s order by 1";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "nurse_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $register_ID    =   $_SESSION["allmediks_user"]["pf_id"];
        $data   =   array();
        parse_str($_POST["data"], $data);
        $sql = "INSERT INTO nurse_mst(register_id, f_name, l_name, dt_of_birth, age, height, weight, gender, martial_status, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssssssssss", $_SESSION["allmediks_user"]["pf_id"], $data["firstname"] , $data["lastname"] , $data["date_of_birth"] , $data["age"] , $data["height"] , $data["weight"] , $data["gender"] , $data["martial_status"] , $data["address"]);
            $result    = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["error"]      =   false;

            }
            if(!$result) 
            {
                $response["error"] = true;
                $response["error_msg"]  =   "Error, ".$stmt->error;

            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

        if($response["error"] == false)
        {
            $directory                  =   "../uploads/nurses/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $profile_img_counter        =   count($_FILES['profile_image_input']['name']);
            if($profile_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['profile_image_input']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["profile_image_input"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";

                            }


                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "nurse_profile_".$register_ID.".".$f_ext;
                            if (move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE nurse_mst SET pf_img = ? WHERE register_id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $f_name, $register_ID);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";

                    }

                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";

                }
                

            }


        }

        if($response["error"] == false)
        {
            $license_counter        =   count($_FILES['license']['name']);
            if($license_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['license']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["license"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";

                            }


                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "nurse_license_".$register_ID.".".$f_ext;
                            if (move_uploaded_file($_FILES['license']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE nurse_mst SET license_img = ? WHERE register_id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $f_name, $register_ID);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";

                    }

                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";

                }
                

            }

        }
        if($response["error"] == false)
        {
            $specializations    =   explode(',', $_POST["specializations"]);
            for($i = 0; $i < count($specializations); $i ++)
            {
                $sql         =      "INSERT INTO nurse_dtl_specializations(specialization_in, register_id) VALUES (?, ?)";
                if($stmt     =       mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $specializations[$i], $register_ID);
                    if(!mysqli_stmt_execute($stmt))
                    {
                        $response["error"]      =    false;
                        $response["error_msg"]  =    $stmt->error;

                    }

                }    
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);

                }


            }
        }


        if($response["error"] == true)
        {
            $sql = "DELETE FROM nurse_mst WHERE register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                mysqli_stmt_execute($stmt);
                
            } 
            $sql = "DELETE FROM nurse_dtl_specializations WHERE register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                mysqli_stmt_execute($stmt);
                
            } 

        }



    }
    else
    {
        $response["error"]          =   true;
        $response["error_msg"]      =   "Error, Access Denied.";



        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "nurse_edu_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $register_ID    =       $_SESSION["allmediks_user"]["pf_id"];
        $data           =       array();
        parse_str($_POST["data"], $data);
        $sql            = "INSERT INTO nurse_dtl_edu(register_id, degree, start_year, ending_year, institute) VALUES (?, ?, ?, ?, ?)";
        if($stmt        = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "sssss", $_SESSION["allmediks_user"]["pf_id"], $data["degree"], $data["start"], $data["end"], $data["institute"]);
            $result     = mysqli_stmt_execute($stmt);
            if($result) 
            {
                $response["ID"]     =   $db->insert_id;

            }
            if(!$result) 
            {
                $response["error"]          =   true;
                $response["error_msg"]      =   "Error, ".$stmt->error;

                
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }
        if(($response["error"] == false) && (isset($response["ID"])))
        {
            $directory                  =   "../uploads/nurses/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $degree_img_counter         =   count($_FILES['degree_img']['name']);
            if($degree_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['degree_img']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["degree_img"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";
    
                            }
    
    
                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "nurse_profile_".$register_ID."_".$response["ID"].".".$f_ext;
                            if (move_uploaded_file($_FILES['degree_img']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE nurse_dtl_edu SET degree_img = ? WHERE register_id = ? AND id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "sss", $f_name, $register_ID, $response["ID"]);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";
    
                    }
    
                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";
    
                }
                
    
            }
        }
   





    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_nurse_educations")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, degree, start_year, ending_year, institute, degree_img FROM nurse_dtl_edu WHERE register_id = ?  ORDER BY id DESC";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result     =   $stmt->get_result();
            if($result)
            {
                $counter = 1;
                while($row  =   mysqli_fetch_assoc($result))
                {
                    $row["SR"]                  =   $counter;
                    $response["EDUCATIONS"][]   =   $row;
                    $counter ++;

                }

            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "delete_education")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM nurse_dtl_edu WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "nurse_experiance_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $data   =   array();
        parse_str($_POST["data"], $data);
        $sql = "INSERT INTO nurse_dtl_experiances(register_id, job_title, institute, description, experiance) VALUES (?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "sssss", $_SESSION["allmediks_user"]["pf_id"], $data["job_title"], $data["institute"], $data["description"], $data["experiance"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_nurse_experiances")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, job_title, institute, description, experiance FROM nurse_dtl_experiances WHERE register_id = ?  ORDER BY id DESC";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result     =   $stmt->get_result();
            if($result)
            {
                $counter = 1;
                while($row  =   mysqli_fetch_assoc($result))
                {
                    $row["SR"]                  =   $counter;
                    $response["EXPERIANCES"][]   =   $row;
                    $counter ++;

                }

            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "nurse_certificate_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $data   =   array();
        parse_str($_POST["data"], $data);
        $sql = "INSERT INTO nurse_dtl_certifications(register_id, title, description, award_date) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION["allmediks_user"]["pf_id"], $data["title"], $data["description"], $data["date"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_nurse_certificates")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "SELECT id, register_id, title, award_date, description FROM nurse_dtl_certifications WHERE register_id = ?  ORDER BY id DESC";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
            mysqli_stmt_execute($stmt);
            $result     =   $stmt->get_result();
            if($result)
            {
                $counter = 1;
                while($row  =   mysqli_fetch_assoc($result))
                {
                    $row["SR"]                      =   $counter;
                    $response["CERTIFICATES"][]     =   $row;
                    $counter ++;

                }

            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "delete_nurse_certificate")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM nurse_dtl_certifications WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            
        }  
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "del_nurse_exp")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "DELETE FROM nurse_dtl_experiances WHERE register_id = ? AND id = ?";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $_POST["id"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            
        }  
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "get_departments")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $sql = "select s.id S_ID, initcap(s.description) S_DESC  from specialities s order by 1";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_execute($stmt);
            $result    = $stmt->get_result();
            if($result) 
            {
                $list = array();
                while($row = mysqli_fetch_assoc($result))
                {
                    $list[] = $row;
                }              
                
                
                $response["list"] = $list;
            }
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "hospital_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $data   =   array();
        parse_str($_POST["data"], $data);
        $services   =   explode(',', $_POST["services"]);
        $sql = "INSERT INTO hospital_mst (register_id, name, address, hours_of_operations) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION["allmediks_user"]["pf_id"], $data["name"], $data["address"], $data["house_of_operation"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            if($result)
            {
                $response["ID"]      = $stmt->insert_id;


            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }
        if($response["error"] == false)
        {
            foreach($services as $service_id)
            {
                $sql = "INSERT INTO hospital_dtl_services (register_id, service_id) VALUES (?, ?)";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $service_id);
                    $result = mysqli_stmt_execute($stmt);
                    if(!$result)
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  =  "Error, ".$stmt->error;
        
        
                    }
                    
                } 

            }
        }
        if($response["error"] == false)
        {
            $register_ID                =   $_SESSION["allmediks_user"]["pf_id"];
            $directory                  =   "../uploads/hospitals/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $profile_img_counter        =   count($_FILES['profile_image_input']['name']);
            if($profile_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['profile_image_input']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["profile_image_input"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";
    
                            }
    
    
                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "hospital".$register_ID."_".$response["ID"].".".$f_ext;
                            if (move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE hospital_mst SET profile_img = ? WHERE register_id = ? AND id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "sss", $f_name, $register_ID, $response["ID"]);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";
    
                    }
    
                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";
    
                }
                
    
            }
        }
        if($response["error"] == false)
        {
            $register_ID                =   $_SESSION["allmediks_user"]["pf_id"];
            $directory                  =   "../uploads/hospitals/".$register_ID."/gallery/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $img_counter                =   count($_FILES['images']['name']);
            if($img_counter > 0)
            {
                 for($i = 0; $i < $img_counter; $i ++)
                {
                    $temp       =   explode('.', basename($_FILES['images']['name'][$i]));
                    $f_ext      =   end($temp);
                    if(in_array($f_ext, $v_extns))
                    {
                        if($_FILES['images']["size"][$i] <  100000)
                        {
                            if(!file_exists($directory))
                            {
                                if(!mkdir($directory, 0777, true))
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error, Unable To Create Directory...";
        
                                }
        
        
                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "hospital_".$register_ID."_".($i + 1).".".$f_ext;
                                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $directory.$f_name)) 
                                {
                                    $sql         =      "INSERT INTO hospital_dtl_images (register_id, img) VALUES (?, ?)";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $register_ID, $f_name);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                    
                                        }
                
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
                
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading Profile Image";
                                }
                                   
                            }
                                
                                
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";
        
                        }
        
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Format is Invalid";
        
                    }

                    
                }

                
    
            }
            

        }


        if($response["error"] == false)
        {
            $response["msg"]  =   "Thanks, your profile is completed successfully.";
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }
    echo(json_encode($response));

}
if((isset($_POST["action"]) && ($_POST["action"] == "pharmacy_register")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $data   =   array();
        parse_str($_POST["data"], $data);
        $services   =   explode(',', $_POST["services"]);
        $sql = "INSERT INTO pharmacy_mst (register_id, name, address, hours_of_operations) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($db, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION["allmediks_user"]["pf_id"], $data["name"], $data["address"], $data["house_of_operation"]);
            $result = mysqli_stmt_execute($stmt);
            if(!$result)
            {
                $response["error"]      = true;
                $response["error_msg"]  =  "Error, ".$stmt->error;


            }
            if($result)
            {
                $response["ID"]      = $stmt->insert_id;


            }
            
        } 
        else
        {
            $response["error"]      =   true;
            $response["error_msg"]  =   "Error ". mysqli_error($db);
        }
        if($response["error"] == false)
        {
            foreach($services as $service_id)
            {
                $sql = "INSERT INTO pharmacy_dtl_services (register_id, service_id) VALUES (?, ?)";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["allmediks_user"]["pf_id"], $service_id);
                    $result = mysqli_stmt_execute($stmt);
                    if(!$result)
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  =  "Error, ".$stmt->error;
        
        
                    }
                    
                } 

            }
        }
        if($response["error"] == false)
        {
            $register_ID                =   $_SESSION["allmediks_user"]["pf_id"];
            $directory                  =   "../uploads/pharmacies/".$register_ID."/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $profile_img_counter        =   count($_FILES['profile_image_input']['name']);
            if($profile_img_counter > 0)
            {
                $temp       =   explode('.', basename($_FILES['profile_image_input']['name']));
                $f_ext      =   end($temp);
                if(in_array($f_ext, $v_extns))
                {
                    if($_FILES["profile_image_input"]["size"] <  100000)
                    {
                        if(!file_exists($directory))
                        {
                            if(!mkdir($directory, 0777, true))
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error, Unable To Create Directory...";
    
                            }
    
    
                        }
                        if (file_exists($directory)) 
                        {
                            $f_name         =   "hospital".$register_ID."_".$response["ID"].".".$f_ext;
                            if (move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $directory.$f_name)) 
                            {
                                $sql         =      "UPDATE pharmacy_mst SET profile_img = ? WHERE register_id = ? AND id = ?";
                                if($stmt     =       mysqli_prepare($db, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "sss", $f_name, $register_ID, $response["ID"]);
                                    if(!mysqli_stmt_execute($stmt))
                                    {
                                        $response["error"]      =    false;
                                        $response["error_msg"]  =    $stmt->error;
                
                                    }
            
                                }    
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error ". mysqli_error($db);
            
                                }
                            }
                            else
                            {
                                $response["error"]      =   true;
                                $response["error_msg"]  =   "Error while uploading Profile Image";
                            }
                               
                        }
                            
                            
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";
    
                    }
    
                }
                else
                {
                    $response["error"]      = true;
                    $response["error_msg"]  = "Error, Profile Image Format is Invalid";
    
                }
                
    
            }
        }
        if($response["error"] == false)
        {
            $register_ID                =   $_SESSION["allmediks_user"]["pf_id"];
            $directory                  =   "../uploads/pharmacies/".$register_ID."/gallery/";
            $v_extns                    =   array("jpeg", "jpg", "png");
            $img_counter                =   count($_FILES['images']['name']);
            if($img_counter > 0)
            {
                 for($i = 0; $i < $img_counter; $i ++)
                {
                    $temp       =   explode('.', basename($_FILES['images']['name'][$i]));
                    $f_ext      =   end($temp);
                    if(in_array($f_ext, $v_extns))
                    {
                        if($_FILES['images']["size"][$i] <  100000)
                        {
                            if(!file_exists($directory))
                            {
                                if(!mkdir($directory, 0777, true))
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error, Unable To Create Directory...";
        
                                }
        
        
                            }
                            if (file_exists($directory)) 
                            {
                                $f_name         =   "hospital_".$register_ID."_".($i + 1).".".$f_ext;
                                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $directory.$f_name)) 
                                {
                                    $sql         =      "INSERT INTO pharmacy_dtl_images (register_id, img) VALUES (?, ?)";
                                    if($stmt     =       mysqli_prepare($db, $sql))
                                    {
                                        mysqli_stmt_bind_param($stmt, "ss", $register_ID, $f_name);
                                        if(!mysqli_stmt_execute($stmt))
                                        {
                                            $response["error"]      =    false;
                                            $response["error_msg"]  =    $stmt->error;
                    
                                        }
                
                                    }    
                                    else
                                    {
                                        $response["error"]      =   true;
                                        $response["error_msg"]  =   "Error ". mysqli_error($db);
                
                                    }
                                }
                                else
                                {
                                    $response["error"]      =   true;
                                    $response["error_msg"]  =   "Error while uploading Profile Image";
                                }
                                   
                            }
                                
                                
                        }
                        else
                        {
                            $response["error"]      = true;
                            $response["error_msg"]  = "Error, Profile Image Size Exceeds 100 Kb.";
        
                        }
        
                    }
                    else
                    {
                        $response["error"]      = true;
                        $response["error_msg"]  = "Error, Profile Image Format is Invalid";
        
                    }

                    
                }

                
    
            }
            

        }


        if($response["error"] == false)
        {
            $response["msg"]  =   "Thanks, your profile is completed successfully.";
        }

    }
    else
    {
        $response["error"]          =   true;
        

    }
    echo(json_encode($response));

}


if((isset($_POST["action"]) && ($_POST["action"] == "get_profile_data")))
{
     if(isset($_SESSION["allmediks_user"]))
    {
        $register_id    =   $_SESSION["allmediks_user"]["pf_id"];
        $html   =   '';
        $data   =   array();
        $profile_type   =   $_SESSION["allmediks_user"]["type_desc"];
        if((isset($profile_type)) && ($profile_type == "PATIENT"))
        {
            $sql = "SELECT id, register_id, fname, lname, date_of_birth, age, height, weight, CASE WHEN gender = 'M' THEN 'Male' ELSE 'Female' END gender, CASE WHEN martial_status = 'S' THEN  'Single' WHEN martial_status = 'M' THEN 'Married'  WHEN martial_status = 'D' THEN  'Divorced' END martial_status , eg_contact, eg_relation, eg_phone, eg_address, reg_at, profile_img FROM patient_mst WHERE register_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, 's', $register_id);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    $row    =   mysqli_fetch_assoc($result);
                    if($row)
                    {
                        $data["patient_mst"]    =   $row;
                    }
                    if(!$row)
                    {
                        $response["error"]      =   true;
                        $response["error_msg"]  =   "Error, No Record Found.";
                    }
                }
                if(!$result) 
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error, No Record Found.";

                    
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
            $sql = "SELECT id, patient_id, CASE WHEN doc_type = 'IC' THEN 'Identity Card' WHEN doc_type = 'PP' THEN 'Passport' WHEN doc_type = 'DP' THEN 'Driver Permit' ELSE doc_type END as doc_type , path FROM patient_dtl WHERE  patient_id = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                mysqli_stmt_bind_param($stmt, 's', $register_id);
                mysqli_stmt_execute($stmt);
                $result    = $stmt->get_result();
                if($result) 
                {
                    while($row    =   mysqli_fetch_assoc($result))
                    {
                        if($row)
                        {   
                            $data["patient_dtl"][]    =   $row;
                        }
                        if(!$row)
                        {   
                            $data["patient_dtl"]    =   false;

                        }

                    }
                    
                   
                }
                if(!$result) 
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error, No Record Found.";

                    
                }
            } 
            else
            {
                $response["error"]      =   true;
                $response["error_msg"]  =   "Error ". mysqli_error($db);
            }
            if(($response["error"] == false) && (isset($data["patient_mst"])))
            {
                $html.='<div class="card shadow-sm p-3 mb-5 bg-white rounded" id="patient_card">
                            <div class="card-body">
                                <div class="form-group">
                                    <div>
                                        <img src="../uploads/patients/'.$data["patient_mst"]["register_id"].'/'.$data["patient_mst"]["profile_img"].'" class="img-thumbnail" style="max-height: 150px;"/>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label style="margin-bottom: 0px;" class="card-description">Name</label>
                                        <h1 style="color: #00264b;">'.$data["patient_mst"]["fname"].' '.$data["patient_mst"]["lname"].'</h1>
                                    </div>
                                </div>
                                <h6 class="display-1" style="font-size: 18px;">Personal Information</h6>
                                <hr>
                                <form action="#" method="POST" id="patient_info_form" style="margin-top: 50px;">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Gender</label>
                                                <input type="text" class="form-control" id="gender" name="gender" value="'.$data["patient_mst"]["gender"].'" readonly>
                                        </div>
          
                                    </div>
  
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Birth Date</label>
                                                <input type="text" class="form-control" id="birth_date" name="birth_date" value="'.$data["patient_mst"]["date_of_birth"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Age</label>
                                                <input type="text" class="form-control" id="age" name="age" value="'.$data["patient_mst"]["age"].' years" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Height</label>
                                                <input type="text" class="form-control" id="height" name="height" value="'.$data["patient_mst"]["height"].' CM" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Martial Status</label>
                                                <input type="text" class="form-control" id="m_status" name="m_status" value="'.$data["patient_mst"]["martial_status"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Weight</label>
                                                <input type="text" class="form-control" id="weight" name="weight" value="'.$data["patient_mst"]["weight"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Emergency Contact</label>
                                                <input type="text" class="form-control" id="e_contact" name="e_contact" value="'.$data["patient_mst"]["eg_contact"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Relation</label>
                                                <input type="text" class="form-control" id="e_relation" name="e_relation" value="'.$data["patient_mst"]["eg_relation"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Phone</label>
                                                <input type="text" class="form-control" id="e_phone" name="e_phone" value="'.$data["patient_mst"]["eg_phone"].'" readonly>
                                            </div>
              
                                        </div>
      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="margin-bottom: 0px;" class="card-description">Address</label>
                                                <textarea rows="8" type="text" class="form-control" id="e_address" name="e_address" readonly>'.$data["patient_mst"]["eg_address"].'</textarea>
                                            </div>
              
                                        </div>
      
                                    </div>
              
                                </form>';


            }
            if(sizeof($data["patient_dtl"]) > 0)
            {
                $html.='<h1>My Documents</h1>
                        <div class="timeline">
                            <div class="outer">';
                foreach($data["patient_dtl"] as $doc)
                {
                    $html.='<div class="card" style="border: none;">
                                <div class="info">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h4 class="title">'.$doc["doc_type"].'</h4>
                                        </div> 
                                        <div class="col-lg-8">
                                            <img src="../uploads/patients/'.$data["patient_mst"]["register_id"].'/'.$doc["path"].'" style="max-height: 350px" />
                                        </div>

                                    </div>
                                </div>
                            </div>';

                }
                $html.='    </div>
                          
                        </div>
                              
                    </div>
                </div> ';

            }
            $response["html"]   =   $html;
            


        }


    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_data_index")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $profile_type    =   $_SESSION["allmediks_user"]["type_desc"];
        if((isset($profile_type)))
        {
            if($profile_type == "PATIENT")
            {
                $sql = "SELECT D.id D_ID, D.register_id REGISTER_ID, CONCAT(D.fname, ' ', D.lname) AS D_NAME, S.description SPECIALIZATION, D.about ABOUT, D.profile_img PF_IMG, D.hospital LOCATION 
                FROM doctor_mst D, specialities S
                WHERE D.specialization_id = S.id
                AND D.register_id != ? ORDER BY D.register_id DESC ";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                    mysqli_stmt_execute($stmt);
                    $result     =   $stmt->get_result();
                    if($result)
                    {
                        $counter = 1;
                        while($row  =   mysqli_fetch_assoc($result))
                        {
                            $row["SR"]  =   $counter;
                            $response["doctors"][]    =     $row;   
                            $counter ++;
        
                        }
        
                    }
                    
                } 
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);
                }

                if($response["error"] == false)
                {
                    $sql = "SELECT M.id ID, M.register_id REGISTER_ID ,M.name NAM, M.address ADDRESS, M.profile_img PF_IMG FROM pharmacy_mst M WHERE M.register_id != ?";
                    if($stmt = mysqli_prepare($db, $sql))
                    {
                        mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                        mysqli_stmt_execute($stmt);
                        $result     =   $stmt->get_result();
                        if($result)
                        {
                            $counter = 1;
                            while($row  =   mysqli_fetch_assoc($result))
                            {
                                $row["SR"]  =   $counter;
                                $response["pharmacies"][]    =     $row;   
                                $counter ++;
            
                            }
            
                        }
                        
                    } 
                    else
                    {
                        $response["error"]      =   true;
                        $response["error_msg"]  =   "Error ". mysqli_error($db);
                    }


                }
                if($response["error"] == false)
                {
                    $sql = "SELECT H.id ID, H.register_id REGISTER_ID, H.name NAM, H.address ADDRESS, H.hours_of_operations HOURS_OF_OPERATION, H.profile_img PG_IMG FROM hospital_mst H WHERE  H.register_id != ?";
                    if($stmt = mysqli_prepare($db, $sql))
                    {
                        mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                        mysqli_stmt_execute($stmt);
                        $result     =   $stmt->get_result();
                        if($result)
                        {
                            $counter = 1;
                            while($row  =   mysqli_fetch_assoc($result))
                            {
                                $row["SR"]  =   $counter;
                                $response["hospitals"][]    =     $row;   
                                $counter ++;
            
                            }
            
                        }
                        
                    } 
                    else
                    {
                        $response["error"]      =   true;
                        $response["error_msg"]  =   "Error ". mysqli_error($db);
                    }


                }

            }
            if(($profile_type == "DOCTOR") || ($profile_type == "HOSPITAL") || ($profile_type == "PHARMACY"))
            {
                if($response["error"] == false)
                {
                    $sql = "SELECT M.id ID, M.register_id REGISTER_ID, CONCAT(M.f_name, ' ', M.l_name) AS NAM, M.pf_img PF_IMG, M.age AGE, M.gender GENDER, M.address ADDRESS 
                            FROM nurse_mst M 
                            WHERE M.register_id != ? ";
                    if($stmt = mysqli_prepare($db, $sql))
                    {
                        mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                        mysqli_stmt_execute($stmt);
                        $result     =   $stmt->get_result();
                        if($result)
                        {
                            $counter = 1;
                            while($row  =   mysqli_fetch_assoc($result))
                            {
                                $row["SR"]  =   $counter;
                                $response["nurses"][]    =     $row;   
                                $counter ++;
            
                            }
            
                        }
                        
                    } 
                    else
                    {
                        $response["error"]      =   true;
                        $response["error_msg"]  =   "Error ". mysqli_error($db);
                    }


                }
                
            }


        }
        else
        {
            $response["error"]  =   true;
            $response["error_msg"]  =   "Error, No Profile Type.";

        }
        

    }
    else
    {
        $response["error"]          =   true;
        

    }


    echo(json_encode($response));

}

if((isset($_POST["action"]) && ($_POST["action"] == "get_profile_based_data")))
{
    if(isset($_SESSION["allmediks_user"]))
    {
        $profile_type    =   $_SESSION["allmediks_user"]["type_desc"];
        $html   =   '';
        if($profile_type == "PATIENT")
        {
            $html.='<div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="me-md-3 me-xl-5">
                                        <h2 class="display-3">My Appointments</h2>
                                        <p>You Can Easily Track Your Appointments status from here. Click to View Details.</p>
                            
                                    </div>
                                </div>

                            </div>
               
                        </div>
                    </div>';
            if($response["error"] == false)
            {
                $sql = "SELECT M.id A_ID, M.ent_date ENT_DT, M.appoint_to D_ID, CONCAT(D.fname, ' ' ,D.lname) D_NAME, M.problem PROBLEM FROM appointment M, doctor_mst D 
                        WHERE M.appoint_to = D.register_id
                        AND M.appoint_by = ? ";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                    mysqli_stmt_execute($stmt);
                    $result     =   $stmt->get_result();
                    if($result)
                    {
                        $counter = 1;
                        while($row  =   mysqli_fetch_assoc($result))
                        {
                            $row["SR"]  =   $counter;
                            $response["appointments"][]    =     $row;   
                            $counter ++;
                
                        }
                
                    }
                            
                } 
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);
                }
    
    
            }
            if(isset($response["appointments"]))
            {
                if(sizeof($response["appointments"]) > 0)
                {
                    foreach($response["appointments"] as $key => $value)
                    {
                        $html.=' <div class="row">
                                <div class="col-md-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="template-demo d-flex flex-nowrap">
                                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><strong>'.$value["SR"].'</strong></button>
                                                <h4 class="display-3 text-primary" style="margin-top: 5px; border-top: none;">'.$value["D_NAME"].'</h4>
                                            </div>
                                            <blockquote class="blockquote">
                                                <h6 class="display-6" style="font-size: 18px;">Problem Description</h6>
                                                <p class="mb-0" style="line-height: 25px; font-size: 14px;">'.$value["PROBLEM"].'</p>
                                            </blockquote>
                                            
                                        </div>
                         
                                    </div>
                                </div>

                            </div> ';
                    }
                }
                

            }
            else
            {
                $html.='<p>No Appointments Found.</p>';
            }

        }
        if($profile_type == "DOCTOR")
        {
            $html.='<div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="me-md-3 me-xl-5">
                                        <h2 class="display-3">Appointment Requests</h2>
                                        <p>Following are the patients appointments requests.</p>
                            
                                    </div>
                                </div>

                            </div>
               
                        </div>
                    </div>';
            if($response["error"] == false)
            {
                $sql = "SELECT M.id A_ID, M.ent_date ENT_DT, M.appoint_by P_ID, CONCAT(P.fname, ' ' , P.lname) P_NAME, M.problem PROBLEM 
                        FROM appointment M, patient_mst P 
                        WHERE M.appoint_by = P.register_id
                        AND M.appoint_to = ?";
                if($stmt = mysqli_prepare($db, $sql))
                {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION["allmediks_user"]["pf_id"]);
                    mysqli_stmt_execute($stmt);
                    $result     =   $stmt->get_result();
                    if($result)
                    {
                        $counter = 1;
                        while($row  =   mysqli_fetch_assoc($result))
                        {
                            $row["SR"]  =   $counter;
                            $response["appointments"][]    =     $row;   
                            $counter ++;
                
                        }
                
                    }
                            
                } 
                else
                {
                    $response["error"]      =   true;
                    $response["error_msg"]  =   "Error ". mysqli_error($db);
                }
    
    
            }
            if(isset($response["appointments"]))
            {
                if(sizeof($response["appointments"]) > 0)
                {
                    foreach($response["appointments"] as $key => $value)
                    {
                        $html.=' <div class="row">
                                <div class="col-md-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="template-demo d-flex flex-nowrap">
                                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><strong>'.$value["SR"].'</strong></button>
                                                <h4 class="display-3 text-primary" style="margin-top: 5px; border-top: none;">'.$value["P_NAME"].'</h4>
                                            </div>
                                            <blockquote class="blockquote">
                                                <h6 class="display-6" style="font-size: 18px; margin-bottom: 0px">Problem Description</h6>
                                                <p class="mb-0" style="line-height: 25px; font-size: 14px;">'.$value["PROBLEM"].'</p>
                                            </blockquote>
                                            
                                        </div>
                         
                                    </div>
                                </div>

                            </div> ';
                    }
                }
                

            }
            else
            {
                $html.='<p>No Appointments Found.</p>';
            }

        }










        $response["html"]   =   $html;

        
        

    }
    else
    {
        $response["error"]              =   true;
        $response["error_msg"]          =   "Error, Unautorized Access.";

        

    }



    echo(json_encode($response));

}


?>