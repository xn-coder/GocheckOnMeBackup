<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use PHPMailer\PHPMailer\SMTP;


class Helper
{
public static function sendMail($email,$verificationLink, $stubject = NULL, $message = NULL){
  // dd($email);
         require base_path("vendor/autoload.php");
         $mail = new PHPMailer(true);     // Passing `true` enables exceptions
         try{
          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host = "smtp.gmail.com";
          $mail->Port = 587;
          $mail->SMTPSecure = "tls";
          $mail->SMTPAuth = true;
          $mail->Username = "aiassistant002@gmail.com";
          $mail->Password = "azmagjzvlfnfuncz";
          $mail->addAddress($email, "User Name");
          $mail->Subject = $stubject;
          $mail->isHTML();
          $mail->Body = $verificationLink;
          $mail->setfrom("aiassistant002@gmail.com");
          $mail->FromName = "Gocheckonme";
          if($mail->send()){
            return 1;
            // dd($mail);
          }else{
            return 0;
         }
        }catch(Exception $e) {
          return 0;
      }
    }
}