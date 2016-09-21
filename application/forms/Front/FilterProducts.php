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
        
     
        
        
  
                //CheckBox for Category
                $categoryId = new Zend_Form_Element_MultiCheckbox('category_id');
                
                    foreach ($categories as $category) {

                        $categoryId->addMultiOption($category['id'],$category['title']);

                    };
                    
		$this->addElement($categoryId);
                
                //CheckBox for Pet
               
                    $petId = new Zend_Form_Element_MultiCheckbox('pet_id');
                
                    foreach ($pets as $pet) {

                        $petId->addMultiOption($pet['id'],$pet['title']);

                    };
                    
		$this->addElement($petId);
               
    }

}