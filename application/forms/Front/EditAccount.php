<?php

class Application_Form_Front_EditAccount extends Zend_Form 
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
		
                
		$email = new Zend_Form_Element_Text('email');
		$email->addFilter('StringTrim')
			->addValidator('EmailAddress', false, array('domain' => false))
			->setRequired(false);
		$this->addElement($email);
                
                
             
        
    }
    
}

