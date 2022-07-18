<?php
  session_start(); //session start
  require_once('config.php'); //database connect (var = $connection)
  require_once('mail_settings.php');
  $errors = array(); //recive all errors
  $success = array(); //recive all success
  $name = "";
  $username = "";
  $email ="";
  if(isset($_POST['submit_reg'])){
    //recive value after submit
    $name =  mysqli_real_escape_string($connection,$_POST['name']);
    $username =  mysqli_real_escape_string($connection,$_POST['username']);
    $email =  mysqli_real_escape_string($connection,$_POST['email']);
    $password =  mysqli_real_escape_string($connection,$_POST['password']);
    $password2 =  mysqli_real_escape_string($connection,$_POST['password2']);
    $md5_password = md5($password); //hash password in md5
    //form validation
    if(empty($name)){
      array_push($errors,"* Name is required");
    }
    if(empty($username)){
      array_push($errors,"* Username is required");
    }
    if(empty($email)){
      array_push($errors,"* Email is required");
    }
    if(empty($password)){
      array_push($errors,"* Password is required");
    }
    if(empty($password2)){
      array_push($errors,"* Confirm password is required");
    }
    if(!empty($password) && !empty($password2) && ($password != $password2)){
        array_push($errors,"* Confirm password not matched");
    }
    //check for username and email exist
    $sql_for_exist_ck = "SELECT * FROM user_info WHERE username='$username' or email='$email' ";
    $query_for_exist_ck = mysqli_query($connection,$sql_for_exist_ck);
    $count_form_exist_ck = mysqli_num_rows($query_for_exist_ck);
    if($count_form_exist_ck > 0){
      $reasult_for_exist_ck = mysqli_fetch_assoc($query_for_exist_ck);
      if($reasult_for_exist_ck['username'] === $username){ //check for username exist
        array_push($errors,"* This username already in used.");
      }
      if($reasult_for_exist_ck['email'] === $email){ //check for email exist
        array_push($errors,"* This email already in used.");
      }
    }
    //insert into database if no error 
    if(count($errors) === 0){
      $sql_for_insert = " INSERT INTO `user_info` (`name`, `username`, `email`, `password`) VALUES ('$name', '$username', '$email', '$md5_password'); ";
      $query_for_insert = mysqli_query($connection,$sql_for_insert );
      if($query_for_insert){ //if query true o false do massage
        //email send start
        $mail->addAddress("$email", "$name");     //Add a recipient
        //Content
        $mail->Subject = 'Account Create successfull.';
        $mail->Body    = "Thank You <b style='color:blue;'>$name</b>!.</br>Your Account create successfull.You can <a href='http://srturl.me/index.php'>Login now.</a></br></br></br>Regards,</br>Atiqur Rahman";
        $mail->AltBody = "Thank You $name!\nYour Account create successfull.You can http://srturl.me/index.php Login now.\n\n\nRegards,\nAtiqur Rahman";
        $mail->send();
        //email send end
        $_SESSION['sg_up_success'] = "yes";
        $_SESSION['sg_up_success_time'] = time()+2;
        header("location: login.php");
        die();
      }else{
        array_push($errors,"* Something went wrong!Try again.");
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Signup now</title>
	<link rel="stylesheet" href="css/ssin.css">
  <link rel="stylesheet" href="css/font.css">
</head>
<body>
<div class="wrapper">
    <div class="title">
      Signup Now
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
          ?>
       <div class="inputfield">
          <label>Full name</label>
          <input type="text" class="input" name="name" value="<?php echo $name; ?>">
       </div>  
        <div class="inputfield">
          <label>Username</label>
          <input type="text" class="input" name="username" value="<?php echo $username; ?>">
       </div>
       <div class="inputfield">
          <label>Your email</label>
          <input type="text" class="input" name="email" value="<?php echo $email; ?>">
       </div>   
       <div class="inputfield">
          <label>Password</label>
          <input type="password" class="input" name="password">
       </div>  
      <div class="inputfield">
          <label>Confirm Password</label>
          <input type="password" class="input" name="password2">
       </div> 
      <div class="inputfield">
        <input type="submit" value="Register Now" class="btn" name="submit_reg">
      </div>
    </form>
    <div class="signup_link">Already have an account ? <a href="login.php">Login now</a></div>
</div>	
	
</body>
</html>