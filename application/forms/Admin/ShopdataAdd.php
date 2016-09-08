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
        
        $twitter = new Zend_Form_Element_Text('twitter');//atribut je isti kao sa forme
        
        
        $twitter->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($twitter);
        
        $facebook = new Zend_Form_Element_Text('facebook');
        $facebook->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($facebook);
        
        $googlePlus = new Zend_Form_Element_Text('google_plus');
        $googlePlus->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($googlePlus);
        
        $linkedin = new Zend_Form_Element_Text('linkedin');
        $linkedin->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($linkedin);
        
        $city = new Zend_Form_Element_Text('city');
        $city->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($city);
        
        $logoPhoto = new Zend_Form_Element_File('logo_photo');
        $logoPhoto->addValidator('Count', true, 1)//ogranicavamo broj fajlova koji se mogu uploud-ovati 
                    ->addValidator('MimeType', true, array('image/jpeg', 'image/gif', 'image/png'))
                    ->addValidator('ImageSize', false, array(
                        'minwidth' => 50,
                        'minheight' => 50,
                        'maxwidth' => 2000,
                        'maxheight' => 2000
                    ))
                    ->addValidator('Size', false, array(
                        'max' => '10MB'
                    ))
                    // disable move file to destination when calling method getValues
                    ->setValueDisabled(true)
                    ->setRequired(false);
        
            $this->addElement($logoPhoto);
                
    }

}