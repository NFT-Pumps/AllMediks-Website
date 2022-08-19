<?php

$response   =   array();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
$db = mysqli_connect('http://allmediks.com/', 'allmeiks_dbuser', 'Y^S=ocDtO4O,', 'allmeiks_database');
if (mysqli_connect_errno()) {
    $response["error"]  =   true;
    $response["error_msg"]  =   mysqli_connect_error();
    
}
else{
    $response["error"]  =   false;
    mysqli_set_charset($mysqli, 'utf8mb4');


}
// echo(json_encode($response));






















?>