<?php


class Application_Model_Library_MailHelper
{
    public function sendmail($to_email, $from_email, $from_name,$subject, $message){
        
        
        $mail = new Zend_Mail('UTF-8');
        $mail->setSubject($subject);
        $mail->addTo($to_email);
        $mail->setFrom($from_email, $from_name);
        $mail->setBodyHtml($message);
        $mail->setBodyText($message);  //alternativa ukoliko ne postoji podrska za html
        
        return $result = $mail->send();
        
        
    }
    
}