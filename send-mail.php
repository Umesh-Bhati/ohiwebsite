<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    // $subject = $_POST["subject"];
    $message = $_POST["message"];
    $response = "";

    // Perform validation
    if (empty($name)) {
        $response = "Name is required.";
    } elseif(empty($email)){
        $response =  "Email is required";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Invalid email format.";
    } elseif(empty($phone)){
        $response =   "Phone number is required ";
    }
    // elseif(empty($subject)){
    //     $response =   "Subject is required ";
    // }
    elseif(empty($message)){
        $response =   "Message is required ";
    }else {
        $template = file_get_contents("tabel.txt");
        $searchArr = ["%NAME%","%EMAIL%","%PHONE%","%MESSAGE%"];
        $replaceArr = [$name,$email,$phone,$message];

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp-relay.brevo.com';                   
            $mail->SMTPAuth   = true;                                  
            $mail->Username   = 'sonujat482@gmail.com';                     
            $mail->Password   = 'Yd61E4vmwRHX9rjC';                               
            $mail->SMTPSecure = "tls";          
            $mail->Port       = 587;
            $mail->setFrom($email, $name); 
            $mail->addAddress('contact@ohiapp.com', "Ohi App"); 
            $mail->addReplyTo($email, $name); 
    
   
            $mail->isHTML(true);                                 
            $mail->Subject = 'Contact Form';
            $mail->Body    = str_replace($searchArr,$replaceArr,$template);

            $mail->send();
            $response = "Form submitted successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
    }

    echo $response;
}
