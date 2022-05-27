<?php  
$serverName = "(local)";  
  
$uid = "user_manager";  
$pwd = "abc123";  
$connectionInfo = array( "Database"=>"Lawyers",
                         "UID"=>$uid,  
                         "PWD"=>$pwd,
                         "CharacterSet" => "UTF-8");  

$link = sqlsrv_connect( $serverName, $connectionInfo);  

if( $link === false )  
{  
     echo "Unable to connect.</br>";  
     die( print_r( sqlsrv_errors(), true));  
}
?> 