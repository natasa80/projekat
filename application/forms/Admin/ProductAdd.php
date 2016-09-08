<?php

class Application_Form_Admin_ProductAdd extends Zend_Form

{   //override jer vec postoji u zend formi
    public function init() {
        
        
        $cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPagePets = $cmsSitemapPagesDbTable->search(array(
            'filters' => array(
                'short_title' => 'Categories'
            )
        ));
        $petsId = $sitemapPagePets[0]['id'];
        $pets = $cmsSitemapPagesDbTable->search(array(
            'filters' => array(
                'parent_id' => $petsId
            )
        ));

        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $categories = $cmsCategoriesDbTable->search();
        
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        $producers = $cmsProducersDbTable->search();


        $title = new Zend_Form_Element_Text('title');
        
        $title->addFilter('StringTrim')
                ->addValidator('StringLength', false, array('min' => 3, 'max' => 255))//false znaci da ako posle ovog ima jos validatora da ne prekida ispitivanje i validaciju i ako ne prodje ovu
                ->setRequired(true);
       
        $this->addElement($title);
        
        $price = new Zend_Form_Element_Text('price');//atribut je isti kao sa forme
        
        
        $price->addFilter('Digits')
                ->addValidator('Digits')
                ->setRequired(FALSE);
        $this->addElement($price);
        
        $about = new Zend_Form_Element_Text('about');
        $about->addFilter('StringTrim')
                ->addValidator('StringLength')
                ->setRequired(FALSE);
        $this->addElement($about);
        
       
        
        $petId = new Zend_Form_Element_Select('pet_id');
		$petId->addMultiOption('', '-- Select Category --')
                ->setRequired(true);
		
		foreach ($pets as $pet) {
			$petId->addMultiOption($pet['id'], $pet['short_title']);
		}
		
		$this->addElement($petId);
        
        $producerId = new Zend_Form_Element_Select('producer_id');
		$producerId->addMultiOption('', '-- Select Category --')
                ->setRequired(true);
		
		foreach ($peroducers as $producer) {
			$producerId->addMultiOption($producer['id'], $producer['short_title']);
		}
		
		$this->addElement($producerId);
                
                $categoryId = new Zend_Form_Element_Select('category_id');
		$categoryId->addMultiOption('', '-- Select Category --')
                ->setRequired(true);
		
		foreach ($categories as $category) {
			$categoryId->addMultiOption($category['id'], $category['short_title']);
		}
		
		$this->addElement($categoryId);
        
        
        //na nivou elementa, ako imamo true parametar, i oko izbaci gresku za tu validaciju i ne ispituje dalje 
        $productPhoto = new Zend_Form_Element_File('product_photo');
        $productPhoto->addValidator('Count', true, 1)//ogranicavamo broj fajlova koji se mogu uploud-ovati 
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
        
            $this->addElement($productPhoto);
                
    }

}