<?php
  session_start(); //session start
  include('config.php');
  //redirect to login if session not set
  if(!isset($_SESSION['id'])){
    header("location: login.php");
    die();
  }
  //select data from databas
  $sql_for_data_dump = " SELECT * FROM user_info WHERE id='{$_SESSION['id']}' ";
  $squery_for_data_dump = mysqli_query($connection,$sql_for_data_dump);
  $data_recive = mysqli_fetch_assoc($squery_for_data_dump);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $data_recive['name'] ?> - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <nav>
      <div class="logo"><a href="index.php"><?php echo $data_recive['name'] ?></a></div>
      <label for="btn" class="icon">
        <span ><img src="icon.svg" alt=""></span>
      </label>
      <input type="checkbox" id="btn">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li>
          <label for="btn-1" class="show">Account Settings</label>
          <a href="#">Account Settings</a>
          <input type="checkbox" id="btn-1">
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
    <div class="content">
      <header>Welcome ! <?php echo $data_recive['name'] ?></header>
      <p>Joined <?php echo $data_recive['create_date'] ?></p>
    </div>
    <script>
      $('.icon').click(function(){
        $('span').toggleClass("cancel");
      });
    </script>

  </body>
</html>
