<?php

class Zend_View_Helper_ServiceImgUrl extends Zend_View_Helper_Abstract
{
    public function serviceImgUrl($service){
        
        
        $serviceImgFileName = 'section2-' . $service['id'] . '.jpg';
        $serviceImgFilePath = PUBLIC_PATH. '/uploads/services/' . $serviceImgFileName;
        //Helper ima property view koji je Zend View i preko 
        //kojeg pozivamo ostale view helpere
        //na prmer $this ->view->baseUrl();
        
        
        if(is_file($serviceImgFilePath)){
            return $this->view->baseUrl('/uploads/services/' . $serviceImgFileName);
        }else {
            return  $this->view->baseUrl('/uploads/services/section2-noimage.jpg');
        } 
    }
}