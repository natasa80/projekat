<?php

class Application_Form_Admin_ProducerAdd extends Zend_Form

{   //override jer vec postoji u zend formi
    public function init() {
        
       



        $name = new Zend_Form_Element_Text('name');
        
        $name->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))//false znaci da ako posle ovog ima jos validatora da ne prekida ispitivanje i validaciju i ako ne prodje ovu
                ->setRequired(true);
       
        $this->addElement($name);
        
        $address = new Zend_Form_Element_Text('address');//atribut je isti kao sa forme
        
        
        $address->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(FALSE);
        $this->addElement($address);
        
        $about = new Zend_Form_Element_Text('about');
        $about->addFilter('StringTrim')
                ->addValidator('StringLength')
                ->setRequired(FALSE);
        $this->addElement($about);
        
       
        
        
        
        //na nivou elementa, ako imamo true parametar, i oko izbaci gresku za tu validaciju i ne ispituje dalje 
        $producerPhoto = new Zend_Form_Element_File('producer_photo');
        $producerPhoto->addValidator('Count', true, 1)//ogranicavamo broj fajlova koji se mogu uploud-ovati 
                    ->addValidator('MimeType', true, array('image/jpeg', 'image/gif', 'image/png'))
                    ->addValidator('ImageSize', false, array(
                        'minwidth' => 150,
                        'minheight' => 150,
                        'maxwidth' => 2000,
                        'maxheight' => 2000
                    ))
                    ->addValidator('Size', false, array(
                        'max' => '10MB'
                    ))
                    // disable move file to destination when calling method getValues
                    ->setValueDisabled(true)
                    ->setRequired(false);
        
            $this->addElement($producerPhoto);
                
    }

}