<?php

class Application_Form_Front_Login extends Zend_Form 
{
    
    public function init(){
     
                
		$email = new Zend_Form_Element_Text('email');
		$email->addFilter('StringTrim')
			->addValidator('EmailAddress', false, array('domain' => false))
			->setRequired(true);
		$this->addElement($email);
                
                
                $password = new Zend_Form_Element_Password('password');
		$password->addValidator('StringLength', false, array('min' => 5, 'max' => 255))
			->setRequired(true);
		$this->addElement($password);
		
        
    }
    
}

