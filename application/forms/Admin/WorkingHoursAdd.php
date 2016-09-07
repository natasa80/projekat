<?php

class Application_Form_Admin_WorkingHoursAdd extends Zend_Form

{   //override jer vec postoji u zend formi
    public function init() {
        
        $day = new Zend_Form_Element_Text('day');//atribut je isti kao sa forme
        
        $day->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))//false znaci da ako posle ovog ima jos validatora da ne prekida ispitivanje i validaciju i ako ne prodje ovu
                ->setRequired(true);//ispituje da li je prazan string
        
        //treba sada da se ubaci u formu
        $this->addElement($day);
        
        $time = new Zend_Form_Element_Text('time');//atribut je isti kao sa forme
        
        
        $time->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))
                ->setRequired(true);
        $this->addElement($time);
       
        $order = new Zend_Form_Element_Text('order_number');//atribut je isti kao sa forme
        
        
        $order->addFilter('Digits')
                ->addValidator('Digits', false, array('min' => 1, 'max' => 7))
                ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
				'table' => 'cms_working_hours',
				'field' => 'order_number'
			)))
                ->setRequired(true);
        $this->addElement($order);
        
                
    }

}