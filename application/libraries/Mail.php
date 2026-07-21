<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mail{
    
  public function sendMail($to,$from,$sub,$body,$cc='',$bcc='',$setfrom='info@reliablepublications.com',$attachments='') {
  
      require_once(APPPATH.'third_party/PHPMailer/class.phpmailer.php');
      
      $mail= new PHPMailer();
      $mail->IsSMTP();
      $mail->Host          = "email-smtp.us-west-2.amazonaws.com";
      $mail->SMTPDebug  = 1;
      $mail->SMTPAuth      = true;
      $mail->SMTPSecure    = "tls";
      $mail->SMTPKeepAlive = true;
      $mail->Port          = 587;
      $mail->Username      = "AKIAWBAKBAKHBTLKMZVT";
      $mail->Password      = "BCvC/WdSy76CYnYVZ95i+8TRBO+fsesQrNkVzfgyxCE9";
      $mail->SetFrom($setfrom , APP_TITLE);
      $mail->AddReplyTo($from);
      $mail->Subject       = $sub;
      
      $mail->Body=("<html><body>".$body."</body></html>");
      
      $mail->IsHTML(true);
      
      if(!filter_var(trim($to), FILTER_VALIDATE_EMAIL)) { }
      else
      {
        if(trim($cc) != "")
         {
          $cc_arr = explode(",",$cc);
          foreach($cc_arr as $c_ar)
           {
            $mail->AddCC($c_ar);
           }
         }
        
        if(trim($bcc) != "")
         {
          $bcc_arr = explode(",",$bcc);
          foreach($bcc_arr as $bc_ar)
           {
            $mail->AddBCC($bc_ar);
           }
         }
        
        $mail->AddAddress(trim($to));
        
        //$mail->AddAddress(trim($to));
        //$mail->AddStringAttachment($row["photo"], "YourPhoto.jpg");
        if($mail->Send()) {  } else { 
         //mail($to , $sub , $body , "From:  ".$from."\r\nBcc:".$bcc."\r\nContent-Type: text/html; charset='iso-8859-1"); 
        }
        $mail->ClearAddresses();   // Clear all addresses and attachments for next loop
      }
  }

}
