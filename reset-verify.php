<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Password Reset</title>
	<link rel="stylesheet" href="css/ssin.css">
  <link rel="stylesheet" href="css/font.css">
</head>
<body>
<?php
    session_start(); //session start
    require_once('config.php'); //database connect (var = $connection)
    require_once('mail_settings.php');
    $errors = array(); //recive all errors
    $success = array(); //recive all success
    //rendom_key for mailed tocken change
    $tocken_up = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUBWXYZ0123456789";
    $tocken_up = str_shuffle($tocken_up);
    $tocken_up = substr($tocken_up,0,20);
    //verification options
    if( isset($_REQUEST['username']) && isset($_REQUEST['tocken']) ){
        //recive variable value from link
        $username = mysqli_real_escape_string($connection,$_REQUEST['username']);
        $verify_tocken = mysqli_real_escape_string($connection,$_REQUEST['tocken']);
        //verify email and tocken is true or false
        $sql_for_tocken_verify = " SELECT * FROM user_info WHERE username LIKE '$username' AND  forget_key LIKE '$verify_tocken' ";
        $query_for_tocken_verify = mysqli_query($connection,$sql_for_tocken_verify);
        $tocken_verify_reasult = mysqli_num_rows($query_for_tocken_verify);
        if($tocken_verify_reasult > 0){ 
            
            $assoc_for_sc_name = mysqli_fetch_assoc($query_for_tocken_verify);
            array_push($success,"Thank You! {$assoc_for_sc_name['name']} !</br>Email verify success."); //shaw message if tocken and mail true
            if(isset($_POST['submit_pass'])){ //after reset password submit 
                $password = mysqli_real_escape_string($connection,$_POST['password']);
                $conf_password = mysqli_real_escape_string($connection,$_POST['conf_password']);
                $md5_password = md5($password); //hash password
                //form validation
                if(empty($password)){ //required password
                    array_push($errors,"* Password is required");
                }
                if(empty($conf_password)){ //required conf password
                    array_push($errors,"* Confirm Password is required");
                }
                if( !empty($password) && !empty($conf_password) && ($password != $conf_password)){ //required password equail
                    array_push($errors,"* Password and confirm Password not matched.");
                }
                if(count($errors) === 0){
                    $sql_for_pass_update = " UPDATE user_info SET password = '$md5_password' , forget_key = '$tocken_up' WHERE username LIKE '$username' AND forget_key LIKE '$verify_tocken' ";
                    $query_for_pass_update = mysqli_query($connection,$sql_for_pass_update);
                    if($query_for_pass_update){

                        $mail->addAddress("{$assoc_for_sc_name['email']}","{$assoc_for_sc_name['name']}");     //Add a recipient
                        //Content
                        $mail->Subject = 'Password reset success';
                        $mail->Body    = "Hello <b style='color:blue;'>{$assoc_for_sc_name['name']}</b>!.</br>Your Password reset reset successfull.You can <a href='http://srturl.me/index.php'>Login now.</a></br></br></br>Regards,</br>Atiqur Rahman";
                        $mail->AltBody = "Hello {$assoc_for_sc_name['email']}.\nYour password reset successfull.You can login http://srturl.me/index.php here.\n\n\nRegards,\nAiqur Rahman";
                        $mail->send();

                        $_SESSION['pass_rst'] = "true";
                        $_SESSION['pass_rst_time'] = time()+3;
                        header("location: login.php");
                    }else{
                        array_push($errors,"* Something went wrong!Try again.");
                    }
                }
            }
?>
            <div class="wrapper">
                <div class="title">
                পাসওয়ার্ড সেট করুন
                </div>
                <form class="form" method="POST">
                    <?php 
                        //count all success an if found shaw it
                        $count_success = count($success); 
                        if($count_success > 0){ ?>
                            <div class="success">
                                <?php 
                                    for($i = 0;$i < $count_success;$i ++){
                                        ?>
                                        <p><?php echo " {$success[$i]} "; ?></p>
                                    <?php
                                        }
                                    ?>
                            </div>
                            <?php   
                            }
                        //count all error an if found shaw it
                            $count_error = count($errors);
                            if($count_error > 0){ ?>
                                <div class="error">
                                <?php 
                                for($i = 0;$i < $count_error;$i ++){
                                    ?>
                                        <p><?php echo " {$errors[$i]} "; ?></p>
                                    <?php
                                }
                                    ?>
                                </div>
                                <?php   
                                    }
                                ?>
                    <div class="inputfield">
                    <label>New password</label>
                    <input type="password" class="input" name="password" >
                    </div>   
                    <div class="inputfield">
                        <label>Confirm Password</label>
                        <input type="password" class="input" name="conf_password">
                    </div>  
                    <div class="inputfield">
                        <input type="submit" value="Reset" class="btn" name="submit_pass">
                    </div>
                </form>
                <div class="signup_link">No account yet? <a href="signup.php">Sign up</a> Now</div>
</div>
       <?php     
        }else{
            $_SESSION['in_val_tocken'] = "true";
            $_SESSION['in_val_tocken_time'] = time()+2;
            header("location: login.php");
            die();
        }
    }else{
        $_SESSION['in_val_tocken'] = "true";
        $_SESSION['in_val_tocken_time'] = time()+2;
        header("location: login.php");
        die();
    }
?>
</body>
</html>