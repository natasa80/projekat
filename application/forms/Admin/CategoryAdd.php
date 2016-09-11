<?php

class Application_Form_Admin_CategoryAdd extends Zend_Form

{   //override jer vec postoji u zend formi
    public function init() {
        
       

        $title = new Zend_Form_Element_Text('title ');
        
        $title->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))//false znaci da ako posle ovog ima jos validatora da ne prekida ispitivanje i validaciju i ako ne prodje ovu
                ->setRequired(true);
       
        $this->addElement($title);
        
     
       
                
    }

}