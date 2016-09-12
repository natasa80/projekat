<?php

class ShopController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' =>  $flashMessenger->getMessages('errors'),
        );
        
        $request = $this->getRequest();
        //get sitemapPage
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        
        if ($sitemapPageId <= 0) {
            throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
       }

       if (!$sitemapPage) {

           throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
        }

        if ( $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED
                                    && !Zend_Auth::getInstance()->hasIdentity()
        ) {
            throw new Zend_Controller_Router_Exception('No sitemap page is disabled ', 404);
       }
       
       
       
        
        //get all pets
        $sitemapPagePets = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
                'short_title' => 'Categories'
            )
        ));
        
        $petsId = $sitemapPagePets[0]['id'];
       
        $pets = $cmsSitemapPageDbTable->search(array(
            'filters' => array(
                'parent_id' => $petsId
            )
        ));
        
        
        
        //get all categories
        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $categories = $cmsCategoriesDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsCategories::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC',
            )
                    ));
        
        
         //get all producers
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        $producers = $cmsProducersDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsProducers::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC',
            )
                    ));
        
        
        //get all products
        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();

        $products = $cmsProductsDbTable->search(array(
//            'filters' => array(
//                'pet_id' => 14,
//            ),
            'orders' => array(
                'order_number' => 'ASC',
            ),
                //'limit' => 4,
                //'page' => 2
        ));
        
        
//                   print_r($pets);
//                    die();
        $this->view->systemMessages =  $systemMessages;
        $this->view->sitemapPage = $sitemapPage;
        $this->view->products =  $products;
        $this->view->pets =  $pets;
        $this->view->categories =  $categories;
        $this->view->producers =  $producers;
    }
    
     public function productAction()
    {
        
    }
}

