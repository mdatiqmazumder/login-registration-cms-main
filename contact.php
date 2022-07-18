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
  $message_from_form = ""; //blank variable for message
  //select data from databas
  $sql_for_data_dump = " SELECT * FROM user_info WHERE id='{$_SESSION['id']}' ";
  $squery_for_data_dump = mysqli_query($connection,$sql_for_data_dump);
  $data_recive = mysqli_fetch_assoc($squery_for_data_dump);
  //data recive for insert from form
  if(isset($_POST['submit_contact'])){ 
    $name_from_form = $data_recive['name'];
    $email_from_form = $data_recive['email'];
    $id_from_form = $data_recive['id'];
    $message_from_form = mysqli_real_escape_string($connection,$_POST['message']);
    //email option
    //form validation
    if(empty($message_from_form)){ //if empty
      array_push($errors,"* SMS box is required.");
    }
    if((strlen($message_from_form) > 0) && (strlen($message_from_form) < 5 ) ){ //if too short
      array_push($errors,"* The feedback is too short.Please! enter more than 5 character.");
    }
    //insert data if no errors
    if(count($errors) === 0){
      //sql for data insert
    $sql_for_data_insert = "INSERT INTO `contact_form` (`name`, `email`, `message`) VALUES ('$name_from_form', '$email_from_form', '$message_from_form'); "; 
    //query for data insert
    $sqery_for_data_insert = mysqli_query($connection,$sql_for_data_insert);
    //shaw sms if data sent success
    if($sqery_for_data_insert){
      //email send start
      $mail->addAddress("{$data_recive['email']}", "{$data_recive['name']}");     //Add a recipient
      //Content
      $mail->Subject = 'We have recived your feedback.';
      $mail->Body    = "Hello <b style='color:blue;'>{$data_recive['name']}</b>!</br>We have recived your feedback.We will notify you when we lunch.</br></br></br>Regards,</br>Atiqur Rahman";
      $mail->AltBody = "Hello {$data_recive['name']}!\nWe have recived your feedback.We will notify you when we lunch.\n\n\nRegards,\nAtiqur Rahman";
      $mail->send();
      //email send end
      array_push($success,"Thanks! ".$data_recive['name'].".</br>We will notify you when we lunch.");
    }
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> <?php echo $data_recive['name'] ?> - Feedback </title>
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/font.css">
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
      <div class="topic-text">Contuct Us | Send Feedback</div>
        <p>If you have any query,suggestions or you find any bug about this site.You can Sent us an report.</p>
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
          <input type="text" placeholder="Enter your name" value="<?php echo $data_recive['name'] ?>" readonly>
        </div>
        <div class="input-box">
          <input type="text" placeholder="Enter your email" value="<?php echo $data_recive['email'] ?>" readonly>
        </div>
        <div class="input-box message-box">
          <textarea placeholder="Enter your valueable comment" name="message"><?php  ?></textarea>
        </div>
        <div class="button">
          <input type="submit" value="Submit" name="submit_contact">
        </div>
      </form>
    </div>
    </div>
  </div>

</body>
</html>
