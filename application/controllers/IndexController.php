<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$cmsIndexSlidesDbTable = new Application_Model_DbTable_CmsIndexSlides();
		
		$indexSlides = $cmsIndexSlidesDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsIndexSlides::STATUS_ENABLED
			),
			'orders' => array(
				'order_number' => 'ASC'
			)
		));
		
		
        
        //prikaz servisa
        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
       
       
        $services = $cmsServicesDbTable->search(array(
           'filters' => array(
               'status' => Application_Model_DbTable_CmsServices::STATUS_ENABLED
           ),
           'orders' => array(
                'order_number' => 'ASC',
            ),
            'limit'=> 4
       ));
        
        //sitemappage
        
        $cmsSitemapPagesDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $servicesSitemapPages = $cmsSitemapPagesDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED,
				'type' => 'ServicesPage'
			),
                        'limit'=> 1
		));
        $servicesSitemapPages = !empty($servicesSitemapPages) ? $servicesSitemapPages[0] : null;
        
        
        $shopSitemapPages = $cmsSitemapPagesDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED,
				'type' => 'ShopPage'
			),
                        'limit'=> 1
		));
        $shopSitemapPages = !empty($shopSitemapPages) ? $shopSitemapPages[0] : null;
        
        //prikay member-a
        $cmsMembersDbTable = new Application_Model_DbTable_CmsMembers();


        //select je objekat klase Zend_Db_ Select
        $select = $cmsMembersDbTable->select();
        $select->where('status = ?', Application_Model_DbTable_CmsMembers::STATUS_ENABLED);
        $members = $cmsMembersDbTable->fetchAll($select);
        
        
        
        
        //prikaz proizvoda na akciji
        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();
       
       
        $actionProducts = $cmsProductsDbTable->search(array(
           'filters' => array(
               'action' => Application_Model_DbTable_CmsProducts::ACTION_ENABLED
           ),
           'orders' => array(
                'order_number' => 'ASC',
            ),
            'limit'=> 4
       ));
        
        $this->view->services = $services;
        $this->view->actionProducts = $actionProducts;
        $this->view->servicesSitemapPages = $servicesSitemapPages;
        $this->view->shopSitemapPages = $shopSitemapPages;
        $this->view->members = $members;
        $this->view->indexSlides = $indexSlides;
        
        
    }
     public function testAction()
    {
        /* Initialize action controller here */
    }
}

