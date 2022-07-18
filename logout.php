<?php
    session_start();
      //redirect to login if session not set
    if(!isset($_SESSION['id'])){
        header("location: login.php");
        die();
    }
    //close all sessions
    unset($_SESSION['lg_key']);
    unset($_SESSION['id']);
    $_SESSION['lo_out_success'] = "true";
    $_SESSION['lo_out_success_time'] = time()+2;
    header("location: login.php");
?>