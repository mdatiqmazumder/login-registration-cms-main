<?php
  session_start(); //session start
  include('config.php');
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
  if(isset($_POST['edit_profile'])){
    //data recive for insert from form
    $typed_name = mysqli_real_escape_string($connection,$_POST['name']);
    $typed_email = mysqli_real_escape_string($connection,$_POST['email']);
    //form validation
    //form validation if no changes
    // if($typed_name === $data_recive['name'] || $typed_email === $data_recive['email']){
    //     array_push($errors,"* plz change");
    // }
    //form validation for short and empty
    if(empty($typed_name)){ //name input
      array_push($errors,"* Please enter name.");
    }
    if(empty($typed_email)){ //email input
      array_push($errors,"* Please enter email.");
    }
    if((strlen($typed_name) > 0 && (strlen($typed_name) < 5))){ //minimum name
      array_push($errors,"* The name is too short.Please! enter more than 5 character.");
    }
    if((strlen($typed_email) > 0 && (strlen($typed_email) < 7))){ //minimum email
        array_push($errors,"* The email is too short.");
      }
    //update prof if no erroe
    if(count($errors) === 0){ 
      $sql_for_prof_update = " UPDATE `user_info` SET `name` = '$typed_name', `email` = '$typed_email' WHERE `id` = {$_SESSION['id']}; "; //sql for update
      $query_for_prof_update = mysqli_query($connection,$sql_for_prof_update); //query for update
      if($query_for_prof_update){ //shaw message for success password update
        array_push($success,"Info update successfull");
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
    <title><?php echo $data_recive['name'] ?> - Edit Profile</title>
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
      <div class="topic-text">Edit Your profile</div>
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
          <input type="text" name="name" value="<?php echo $data_recive['name'] ?>" placeholder="">
        </div>
        <div class="input-box">
          <input type="text" placeholder="" name="email" value="<?php echo $data_recive['email'] ?>">
        </div>
        <div class="input-box">
          <input type="text" placeholder="" value="<?php echo $data_recive['username'] ?> (Username change not Possiable)" readonly> 
        </div>
        <div class="button">
          <input type="submit" value="Change" name="edit_profile">
        </div>
      </form>
    </div>
    </div>
  </div>

</body>
</html>
