<?php

class AboutusController extends Zend_Controller_Action
{
    public function indexAction()
    {
         $request = $this->getRequest();
        $sitemapPageId = (int) $request->getParam('sitemap_page_id');
//          print_r($sitemapPageId);
//        die();
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();
        $sitemapPage = $cmsSitemapPageDbTable->getSitemapPageById($sitemapPageId);
        
        if ($sitemapPageId <= 0) {
            throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
        }

        if (!$sitemapPage) {

            throw new Zend_Controller_Router_Exception('Invalid sitemap  is found with id ' . $sitemapPageId, 404);
        }




        if (
                $sitemapPage['status'] == Application_Model_DbTable_CmsSitemapPages::STATUS_DISABLED
                //check if user is not logged in, than preview is not available
                //for disabled pages
                && !Zend_Auth::getInstance()->hasIdentity()
        ) {
            throw new Zend_Controller_Router_Exception('No sitemap page is disabled ', 404);
        }
        
        
        //prikay member-a
        $cmsMembersDbTable = new Application_Model_DbTable_CmsMembers();


        //select je objekat klase Zend_Db_ Select
        $select = $cmsMembersDbTable->select();
        $select->where('status = ?', Application_Model_DbTable_CmsMembers::STATUS_ENABLED);
        $members = $cmsMembersDbTable->fetchAll($select);
        
        $this->view->sitemapPage = $sitemapPage;
        $this->view->members = $members;

    }
    
    public function memberAction()
    {
        
    }
}

