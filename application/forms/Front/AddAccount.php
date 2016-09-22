<?php

class Application_Form_Front_AddAccount extends Zend_Form 
{
    
    public function init(){
     
                    
		$firstName = new Zend_Form_Element_Text('first_name');
                $firstName->addFilter('StringTrim')
			->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
			->setRequired(true);
		
		$this->addElement($firstName);
		
		$lastName = new Zend_Form_Element_Text('last_name');
		$lastName->addFilter('StringTrim')
			->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
			->setRequired(true);
		$this->addElement($lastName);
                
                $address = new Zend_Form_Element_Text('address');
		$address->addFilter('StringTrim')
			->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
			->setRequired(true);
		$this->addElement($address);
                
                $phone = new Zend_Form_Element_Text('telephone');
		$phone->addFilter('StringTrim')
			->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
			->setRequired(true);
		$this->addElement($phone);
		
		$username = new Zend_Form_Element_Text('username');
		$username->addFilter('StringTrim')
			->addValidator('StringLength', false, array('min' => 3, 'max' => 50))
			->addValidator(new Zend_Validate_Db_NoRecordExists(array(
				'table' => 'cms_users',
				'field' => 'username'
			)))
			->setRequired(true);
		$this->addElement($username);
                
		$email = new Zend_Form_Element_Text('email');
		$email->addFilter('StringTrim')
			->addValidator('EmailAddress', false, array('domain' => false))
			->setRequired(false);
		$this->addElement($email);
                
                
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
		))
			->setRequired(true);
		$this->addElement($passwordConfirm);
                
                $newslatter = new Zend_Form_Element_Checkbox('newsletter');
                $newslatter->setRequired(false);
                $this->addElement($newslatter);
                
                $termsCondition = new Zend_Form_Element_Checkbox('terms_and_conditions');
                $termsCondition->setRequired(true);
                $this->addElement($termsCondition);
                
                $personalData = new Zend_Form_Element_Checkbox('personal_data');
                $personalData->setRequired(true);
                $this->addElement($personalData);
        
    }
    
}

