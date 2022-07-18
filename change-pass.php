<?php
  session_start(); //session start
  include_once('config.php');
  require_once('mail_settings.php');
  //redirect to login if session not set
  if(!isset($_SESSION['id'])){
    header("location: login.php");
    die();
  }
  //blank array for success or error
  $errors = array(); //recive all errors
  $success = array(); //recive all success
  //select data from databas
  $sql_for_data_dump = " SELECT * FROM user_info WHERE id='{$_SESSION['id']}' ";
  $squery_for_data_dump = mysqli_query($connection,$sql_for_data_dump);
  $data_recive = mysqli_fetch_assoc($squery_for_data_dump);
  //data recive 
  if(isset($_POST['change_pass'])){
    //data recive for insert from form
    $typed_old_password = mysqli_real_escape_string($connection,$_POST['t_old_pass']);
    $typed_new_password = mysqli_real_escape_string($connection,$_POST['t_new_pass']);
    //hash into md5
    $md5_old_password = md5($typed_old_password);
    $md5_new_password = md5($typed_new_password);
    //form validation
    if(empty($typed_old_password)){ //old password input
      array_push($errors,"* Please enter old Password.");
    }
    if(empty($typed_new_password)){ //new password input
      array_push($errors,"* Please enter new Password");
    }
    if((strlen($typed_new_password) > 0 && (strlen($typed_new_password) < 5))){ //minimum password
      array_push($errors,"* The Password is too short.Please! enter more than 5 character.");
    }
    //check old password is true
    if(count($errors) === 0){
      if($md5_old_password != $data_recive['password']){
        array_push($errors,"* The previous Password is incorrect.");
      }
    }
    //update password if no erroe
    if(count($errors) === 0){ 
      $sql_for_pass_update = " UPDATE `user_info` SET `password` = '$md5_new_password' WHERE `id` = {$_SESSION['id']}; "; //sql for update
      $query_for_pass_update = mysqli_query($connection,$sql_for_pass_update); //query for update
      if($query_for_pass_update){ //shaw message for success password update
        //email send start
        $mail->addAddress("{$data_recive['email']}", "{$data_recive['name']}");     //Add a recipient
        //Content
        $mail->Subject = 'Your Password changed.';
        $mail->Body    = "Hello <b style='color:blue;'>{$data_recive['name']}</b>!.</br>Your password was changed.You can login now following <a href='http://srturl.me/index.php'>this button.</a></br></br></br>Regards,</br>Atiqur Rahman";
        $mail->AltBody = "Hello {$data_recive['name']}!\nYour password was changed.You can login now following http://srturl.me/index.php this url.\n\n\nRegards,\nAtiqur Rahman";
        $mail->send();
        //email send end
        array_push($success,"Password change successfull.");
      }else{
        array_push($errors,"* Something went wrong!Try again.");
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title><?php echo $data_recive['name'] ?> - Change Password</title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/font.css">
    <style>.right-side .input-box input {background: #F0F1F8;}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
<nav>
    <div class="logo"><a href="index.php"><?php echo $data_recive['name'] ?></a></div>
      <label for="btn" class="icon">
        <span ><img src="icon.svg" alt=""></span>
      </label>
      <input type="checkbox" id="btn" class="input_m">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li>
          <label for="btn-1" class="show">Account Settings</label>
          <a href="#">Account Settings</a>
          <input type="checkbox" id="btn-1" class="input_m">
          <ul>
            <li><a href="view-profile.php">See Profile</a></li>
            <li><a href="edit-profile.php">Edit Profile</a></li>
            <li><a href="change-pass.php">Change Password</a></li>
          </ul>
        </li>
        <li><a href="contact.php">Contact/Feedback</a></li>
        <li><a href="logout.php">Log Out (<?php echo $data_recive['username'] ?>)</a></li>
      </ul>
</nav>
  <div class="container-form">
    <div class="content_form">
      <div class="right-side">
      <div class="topic-text">Change Your Password</div>
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
      <form  method="POST" >
        <div class="input-box">
          <input type="text" placeholder="Old Password" name="t_old_pass">
        </div>
        <div class="input-box">
          <input type="text" placeholder="New Password" name="t_new_pass">
        </div>
        <div class="button">
          <input type="submit" value="Change" name="change_pass">
        </div>
      </form>
    </div>
    </div>
  </div>

</body>
</html>
