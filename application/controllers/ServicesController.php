<?php

class ServicesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    
       {
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' =>  $flashMessenger->getMessages('errors'),
        );
        
        
        //prikaz svih servisa
        $cmsServicesDbTable = new Application_Model_DbTable_CmsServices();
       
        
           $services = $cmsServicesDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsServices::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC',
            ),
            'limit' => 3
        ));
       
        
//            $sitemapPageId = (int) $request->getParam('sitemap_page_id');
////          print_r($sitemapPageId);
////        die();
//        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
//        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
//        
//        if ($sitemapPageId <= 0) {
//            throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
//        }
//
//        if (!$sitemapPage) {
//
//            throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
//        }
//
//        if (
//                $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED
//                //check if user is not logged in, than preview is not available
//                //for disabled pages
//                && !Zend_Auth::getInstance()->hasIdentity()
//        ) {
//            throw new Zend_Controller_Router_Exception('No sitemap page is disabled ', 404);
//        }
        $this->view->services = $services;
        $this->view->systemMessages =  $systemMessages;
       // $this->view->sitemapPage = $sitemapPage;
        
    }

}


