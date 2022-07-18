<?php
    $dbhost = "localhost";
    $dbusrname = "root";
    $dbpass = "root";
    $dbname = "usercms";
    $connection = mysqli_connect($dbhost,$dbusrname,$dbpass,$dbname);
    if(!$connection){
        die(mysqli_connect_error());
    }
?>