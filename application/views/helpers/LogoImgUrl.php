
<?php

class Zend_View_Helper_LogoImgUrl extends Zend_View_Helper_Abstract
{
    public function logoImgUrl($logo){
        
        
        $logoImgFileName = $logo['id'] . '.jpg';
        $logoImgFilePath = PUBLIC_PATH. '/uploads/logos/' . $logoImgFileName;
       
        
        if(is_file($logoImgFilePath)){
            return $this->view->baseUrl('/uploads/logos/' . $logoImgFileName);
        }else {
            return  $this->view->baseUrl('/uploads/logos/no-image.jpg');
        } 
    }
}