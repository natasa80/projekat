<?php

class Application_Form_Admin_ShopdataAdd extends Zend_Form

{   //override jer vec postoji u zend formi
    public function init() {
        
        $address = new Zend_Form_Element_Text('address');//atribut je isti kao sa forme
        
        $address->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))//false znaci da ako posle ovog ima jos validatora da ne prekida ispitivanje i validaciju i ako ne prodje ovu
                ->setRequired(false);//ispituje da li je prazan string
        
        //treba sada da se ubaci u formu
        $this->addElement($address);
        
        $phone = new Zend_Form_Element_Text('phone');//atribut je isti kao sa forme
        
        
        $phone->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($phone);
       
        
        $email = new Zend_Form_Element_Text('email');
        $email->addFilter('StringTrim')
                ->addValidator('EmailAddress', false, array('domain'=> false))//validira da li postoji domenski deo na netu, ta opcija treba da se iskljuci
                ->setRequired(FALSE);
        $this->addElement($email);
        
        $aboutUs = new Zend_Form_Element_Textarea('about_us');
        $aboutUs->addFilter('StringTrim')
                ->setRequired(false);
        $this->addElement($aboutUs);
        
       
                
    }

}