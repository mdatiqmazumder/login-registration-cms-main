<?php
  session_start(); //session start
  require('config.php'); //database connect (var = $connection)
  $errors = array(); //recive all errors
  $success = array(); //recive all success
  //show message for sign up success
  if( isset( $_SESSION['sg_up_success'] ) && $_SESSION['sg_up_success_time'] > time()){
    array_push($success,"Account create successfull");
  }
  //show message for logout success
  if( isset( $_SESSION['lo_out_success'] ) && $_SESSION['lo_out_success_time'] > time()){
    array_push($success,"Logout success");
  }
  //show message for pass reset
  if( isset( $_SESSION['pass_rst'] ) && $_SESSION['pass_rst_time'] > time()){
    array_push($success,"Password reset success");
  }
  //show message for invalid tocken
  if( isset( $_SESSION['in_val_tocken'] ) && $_SESSION['in_val_tocken_time'] > time()){
    array_push($errors,"Invalid Verification Link");
  }
  $username = "";
  $password = "";
  //global variable recive
  if(isset($_POST['submit_lgin'])){
    $username = mysqli_real_escape_string($connection,$_POST['username']);
    $password = mysqli_real_escape_string($connection,$_POST['password']);
    $md5_password = md5($password); //password encrypt
    //form validition
    if(empty($username)){
      array_push($errors,"* Enter username or email");
    }
    if(empty($password)){
      array_push($errors,"* Enter Password");
    }
    //check for login if no error
    if( (count($errors)) === 0){
      $sql_to_validate = " SELECT * FROM user_info WHERE username='$username' AND password='$md5_password' LIMIT 1";
      $query_to_validate = mysqli_query($connection,$sql_to_validate);
      $reasult_to_validate = mysqli_num_rows($query_to_validate); //check the username for correct
      if($reasult_to_validate > 0 ){
        $data_from_reasult = mysqli_fetch_assoc($query_to_validate); //dump data from selected row
        $_SESSION['id'] = $data_from_reasult['id'];
        $_SESSION['lg_key'] = "true";
        header("location: index.php");
      }else{
        array_push($errors,"* Username/email or password wrong.");
      }
    }
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="css/ssin.css">
  <link rel="stylesheet" href="css/font.css">
</head>
<body>
<div class="wrapper">
    <div class="title">
      Login
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
          <input type="text" class="input" name="username" value="<?php echo $username; ?>">
       </div>   
       <div class="inputfield">
          <label>Password</label>
          <input type="password" class="input" name="password">
       </div>  
      <div class="inputfield">
        <input type="submit" value="Log In" class="btn" name="submit_lgin">
      </div>
    </form>
    <div class="signup_link">Forgotten Password? <a href="forgot-password.php">Reset Now</a></div>
    <div class="signup_link">No account yet? <a href="signup.php">Sign Up</a> Now</div>
</div>	
	
</body>
</html>