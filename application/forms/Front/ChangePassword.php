<?php

class Application_Form_Front_ChangePassword extends Zend_Form 
{
    
    public function init(){
        
                $oldPassword = new Zend_Form_Element_Password('old_password');
		$oldPassword->addValidator('Identical', false, array(
			'token' => 'password',
			'messages' => array(
				Zend_Validate_Identical::NOT_SAME => 'Wrong password'
			)
		))->setRequired(true);
		$this->addElement($oldPassword);
     
                $password = new Zend_Form_Element_Password('password');
		$password->addValidator('StringLength', false, array('min' => 5, 'max' => 255))
			->setRequired(true);
		$this->addElement($password);
		
		$passwordConfirm = new Zend_Form_Element_Password('password_confirm');
		$passwordConfirm->addValidator('Identical', false, array(
			'token' => 'password',
			'messages' => array(
				Zend_Validate_Identical::NOT_SAME => 'Passwords do not match'
			)
		))->setRequired(true);
		$this->addElement($passwordConfirm);
		
        
    }
    
}

