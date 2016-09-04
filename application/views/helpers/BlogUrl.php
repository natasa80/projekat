<?php


class Zend_View_Helper_BlogUrl extends Zend_View_Helper_Abstract
{
    public function blogUrl($blog){
        
        return $this->view->url(array(
            'id'=>$blog['id'],
            'blog_slug'=>$blog['title']
            
        ), 'blog-route', true);
       
    }
  
}