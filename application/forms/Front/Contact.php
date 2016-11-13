<?php

class Application_Form_Front_Contact extends Zend_Form
{
    
   
    public function init(){
        
        $name = new Zend_Form_Element_Text('name');
        $name->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->addFilter('StripTags')
                ->setRequired(true);
        $this->addElement($name);
        
        $email = new Zend_Form_Element_Text('email');
        $email->addFilter('StringTrim')
                ->addValidator('EmailAddress', false, array('domain'=> false))
                ->addFilter('StripTags')
                ->setRequired(true);
        $this->addElement($email);
        
        $subject = new Zend_Form_Element_Text('subject');
        $subject->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setRequired(true);
        $this->addElement($subject);
        
        
        $message = new Zend_Form_Element_Textarea('message');
        $message->addFilter('StringTrim')
                ->addFilter('StripTags')
                ->setRequired(true);
        $this->addElement($message);
        
    }
}