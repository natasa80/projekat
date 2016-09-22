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
        
        //get sitemap page
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
        
        
        
        $form = new Application_Form_Front_FilterProducts();
        
        $form->populate(array(
        ));
        
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
        
   
        
        //ukoliko je izabran filter
         if ($request->isPost() && $request->getPost('task') === 'save') {//ispitujemo da lije pokrenuta forma
            try {
                // die('forma');
                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for products');
                }

                $formData = $form->getValues();
//             print_r($formData);
//                die();
                 //get all products
                
                //ukoliko nije filtrirano po nekoj od kategorija
                $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();
                
                        if(empty($formData['category_id'])){
                           foreach ($categories as $category) {
                               $formData['category_id'][] = $category['id'];
                           }
                        }
                        if(empty($formData['pet_id'])){
                           foreach ($pets as $pet) {
                               $formData['pet_id'][] = $pet['id'];
                           }
                        }
                        
                        
                $products = $cmsProductsDbTable->search(array(
                    'filters' => array(
                        'status' => Application_Model_DbTable_CmsProducts::STATUS_ENABLED,
                        'category_id' => $formData['category_id'],
                        'pet_id' => $formData['pet_id'],
                    ),
                    'orders' => array(
                        'order_number' => 'ASC',
                    ),
                        //'limit' => 4,
                        //'page' => 2
                ));
              $this->view->products = $products;
                
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        } else {
           
            //get all products ukoliko nema filtera
        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();

        $products = $cmsProductsDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsProducts::STATUS_ENABLED
            ),
            'orders' => array(
                'order_number' => 'ASC',
            ),
                //'limit' => 4,
                //'page' => 2
        ));
        $this->view->products =  $products;
       
        }
       
       
        $this->view->actionProducts =  $actionProducts;
        $this->view->systemMessages =  $systemMessages;
        $this->view->sitemapPage = $sitemapPage;
        
        $this->view->pets =  $pets;
        $this->view->categories =  $categories;
        $this->view->producers =  $producers;
        $this->view->form =  $form;
    }
    
     public function productAction()
    {
        //get one Product
        $request = $this->getRequest();
       
        $id = $request->getParam('id');
        $id = trim($id);
        $id = (int) $id;

        if (empty($id)) {

            throw new Zend_Controller_Router_Exception('No product with  id', 404);
        }



        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();
        
        
        $select = $cmsProductsDbTable->select();
        $select->where('id =?', $id)
                ->where('status = ?', Application_Model_DbTable_CmsProducts::STATUS_ENABLED);

        $foundProducts = $cmsProductsDbTable->fetchAll($select);


        if (count($foundProducts) <= 0) {

            throw new Zend_Controller_Router_Exception('No product is found for: ' . $id, 404);
        }


        $product = $foundProducts[0];
        
        $cmsSitemapPageDbTable = new Application_Model_DbTable_CmsSitemapPages();


       //get all products
        $select = $cmsProductsDbTable->select();
        $select->where('status = ?', Application_Model_DbTable_CmsProducts::STATUS_ENABLED)
                ->where('id != ?', $id)
                ->order('order_number');

        $products = $cmsProductsDbTable->fetchAll($select);
        
        
        //get all producers
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        $producer = $cmsProducersDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsProducers::STATUS_ENABLED,
                'id' => $product['producer_id']
            )
                    ));
        
        $producer = $producer[0];
        
        //get pet category
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
        $category = $cmsCategoriesDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsCategories::STATUS_ENABLED,
                'id'=> $product['category_id']
            ),
            'orders' => array(
                'order_number' => 'ASC',
            )
                    ));
        
        $category = $category[0];
        
        
        //get similiar Products
        $cmsSimiliarProductsDbTable = new Application_Model_DbTable_CmsProducts();
        $similiarProducts = $cmsSimiliarProductsDbTable->search(array(
            'filters' => array(
                'status' => Application_Model_DbTable_CmsProducts::STATUS_ENABLED,
                'category_id' => $product['category_id'],
                'id_exclude' => $product['id']
            ),
            'orders' =>array(
                'order_number' => 'ASC',
                
            ),
            'limit' => 4
        ));
        

        //get shopsitemap page
        
        //sitemappage Shop
        $shopSitemapPages = $cmsSitemapPageDbTable->search(array(
			'filters' => array(
				'status' => Application_Model_DbTable_CmsSitemapPages::STATUS_ENABLED,
				'type' => 'ShopPage'
			),
                        'limit'=> 1
		));
        $shopSitemapPages = !empty($shopSitemapPages) ? $shopSitemapPages[0] : null;
        
        $this->view->products = $products;
        $this->view->product = $product;
        $this->view->producer = $producer;
        $this->view->pets = $pets;
        $this->view->category = $category;
        $this->view->similiarProducts = $similiarProducts;
        $this->view->shopSitemapPages = $shopSitemapPages;
         
         
    }
}

