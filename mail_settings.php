<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailler/Exception.php';
    require 'phpmailler/PHPMailer.php';
    require 'phpmailler/SMTP.php';

    $mail = new PHPMailer(true);


        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'localhost';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'user@example.com';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 1025;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->isHTML(true); 

        //Recipients
        $mail->setFrom('XXXXXXXXX', 'XXXXXXXXXXXX');
        $mail->addReplyTo('XXXXXXXX', 'XXXXXXXXXXXX');




        // $mail->send();

?>