<?php
  session_start(); //session start
  require_once('config.php'); //database connect (var = $connection)
  require_once('mail_settings.php');
  $errors = array(); //recive all errors
  $success = array(); //recive all success
  $tocken = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUBWXYZ0123456789";
  $tocken = str_shuffle($tocken);
  $tocken = substr($tocken,0,25);
  //global variable recive
  if(isset($_POST['submit_forget'])){
    $email = mysqli_real_escape_string($connection,$_POST['email']);
    //form validition
    if(empty($email)){
      array_push($errors,"* Enter username or email");
    }
    //check for login if no error
    if( (count($errors)) === 0){
      $sql_to_validate = " SELECT * FROM user_info WHERE email LIKE '$email' OR username LIKE '$email' ";
      $query_to_validate = mysqli_query($connection,$sql_to_validate);
      $reasult_to_validate = mysqli_num_rows($query_to_validate); //check the username for correct
      if($reasult_to_validate > 0 ){
        $data_from_reasult = mysqli_fetch_assoc($query_to_validate); //dump data from selected row
        //query for tocken update
        $sql_for_tocken_update = "UPDATE `user_info` SET `forget_key` = '$tocken' WHERE `id` = {$data_from_reasult['id']}";
        $query_for_tocken_update = mysqli_query($connection,$sql_for_tocken_update);
        if($query_for_tocken_update){

          //email send start
          $mail->addAddress("{$data_from_reasult['email']}", "{$data_from_reasult['name']}");     //Add a recipient
          //Content
          $mail->Subject = 'Password reset requested.';
          $mail->Body    = "Hello <b style='color:blue;'>{$data_from_reasult['name']}</b>!.</br>Your can reset your password by clicking <a href=\"http://srturl.me/reset-verify.php?username={$data_from_reasult['username']}&tocken=$tocken\">here</a>.</br></br></br>Regards,</br>Atiqur Rahman";
          $mail->AltBody = "Hello {$data_from_reasult['name']}!\nYour can reset your password by clicking http://srturl.me/reset-verify.php?username={$data_from_reasult['username']}&tocken=$tocken.\n\n\nRegards,\nAtiqur Rahman";
          $mail->send();
          //email send end

          array_push($success,"Please check your email for next step.");
        }
      }else{
        array_push($errors,"* Sorry! User not found.");
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset Password</title>
	<link rel="stylesheet" href="css/ssin.css">
  <link rel="stylesheet" href="css/font.css">
</head>
<body>
<div class="wrapper">
    <div class="title">
      Reset Password
    </div>
    <form class="form" method="POST">
        <?php //count all error an if found shaw it
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
                ?>
        <div class="inputfield">
          <label>Username/Email</label>
          <input type="text" class="input" name="email" >
        </div> 
      <div class="inputfield">
        <input type="submit" value="Reset" class="btn" name="submit_forget">
      </div>
    </form>
    <div class="signup_link">Back to <a href="login.php">Log In</a></div>
</body>
</html>