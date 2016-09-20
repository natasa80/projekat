<?php

class Application_Form_Front_FilterProducts extends Zend_Form

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
        
        
        
        $petId = new Zend_Form_Element_Multicheckbox('pet_id');
        	foreach ($pets as $pet) {
			$petId->addMultiOption($pet['id'], $pet['short_title']);
		}
                $this->addElement($petId);
        
        $producerId = new Zend_Form_Element_Multicheckbox('producer_id');
		
		foreach ($producers as $producer) {
			$producerId->addMultiOption($producer['id'], $producer['name']);
		}
		
		$this->addElement($producerId);
                
                $categoryId = new Zend_Form_Element_Multicheckbox('category_id');
		
		
		foreach ($categories as $category) {
			$categoryId->addMultiOption($category['id'], $category['title']);
		}
		
		$this->addElement($categoryId);
                
                //$range = new Zend_Form_Element_Range('range');
               
                
    }

}