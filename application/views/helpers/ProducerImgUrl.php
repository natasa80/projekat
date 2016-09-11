<?php

class Zend_View_Helper_ProducerImgUrl extends Zend_View_Helper_Abstract
{
    public function producerImgUrl($producer){
        
        
        $producerImgFileName = $producer['id'] . '.jpg';
        $producerImgFilePath = PUBLIC_PATH. '/uploads/producers/' . $producerImgFileName;
        //Helper ima property view koji je Zend View i preko 
        //kojeg pozivamo ostale view helpere
        //na prmer $this ->view->baseUrl();
        
        
        if(is_file($producerImgFilePath)){
            return $this->view->baseUrl('/uploads/producers/' . $producerImgFileName);
        }else {
            return  $this->view->baseUrl('/uploads/producers/no-image.jpg');
        } 
    }
}