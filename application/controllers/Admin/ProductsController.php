<?php

class Admin_ProductsController extends Zend_Controller_Action {

    public function indexAction() {

        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );
       //prikaz svih product-a
        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();

        $products = $cmsProductsDbTable->search(array(
//            'filters' => array(
//                'first_name' => array('Aleksandar', 'Aleksandra', 'Bojan')
            // ),
            'orders' => array(
                'order_number' => 'ASC',
            ),
                //'limit' => 4,
                //'page' => 2
        ));
        
        
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
//        print_r($petsId);
//        die();
//       
        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $categories = $cmsCategoriesDbTable->search();
        
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        $producers = $cmsProducersDbTable->search();
//      print_r($categories);
//     die();
        $this->view->products = $products;
        $this->view->categories = $categories;
        $this->view->pets = $pets;
        $this->view->producers = $producers;
         $this->view->sitemapPagePets = $sitemapPagePets;
        $this->view->systemMessages = $systemMessages;
    }

    public function addAction() {
        //prikaz svih product-a


        $request = $this->getRequest(); //podaci iz url-a iz forme koje dobijemo
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_ProductAdd();

        $form->populate(array(
        ));



        if ($request->isPost() && $request->getPost('task') === 'save') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new product');
                }

                //get form data//vrednosti iz forme se uzimaju preko getVaues i upisuju u niz
                //to su filtrirani i validirani podaci
                $formData = $form->getValues();


                //remove key memebr_photo from form data because there is no column memebr_photo in cms _products
                unset($formData['product_photo']);



                //insertujemo zapis u bazu
                $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();

                //insert product returns ID of the new product
                $productId = $cmsProductsDbTable->insertProduct($formData);

                if ($form->getElement('product_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('product_photo')->getFileInfo('product_photo');
                    $fileInfo = $fileInfos['product_photo'];
                    //$fileInfo = $_FILES["product_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $productPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $productPhoto->fit(273, 408);
                        $productPhoto->save(PUBLIC_PATH . '/uploads/products/' . $productId . '.jpg');
                    } catch (Exception $ex) {

                        $flashMessenger->addMessage('Product has been saved, but error occured during image processing', 'errors');

                        //redirect to same or another page
                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_products',
                                    'action' => 'edit',
                                    'id' => $productId
                                        ), 'default', true);
                    }
                }


                $flashMessenger->addMessage('Product has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_products',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;
    }

    public function editAction() {


        $request = $this->getRequest();

        $id = (int) $request->getParam('id');

        if ($id <= 0) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('Invalid product id: ' . $id, 404);
        }


       $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();
        $product = $cmsProductsDbTable->getProductById($id);

        if (empty($product)) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('No product is found with id ' . $id, 404);
        }


        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_ProductAdd();

        //default form data//mi nemamo default vrednosti
        $form->populate($product);



        if ($request->isPost() && $request->getPost('task') === 'update') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for  product');
                }


                $formData = $form->getValues();
                unset($formData['product_photo']);

                if ($form->getElement('product_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('product_photo')->getFileInfo('product_photo');
                    $fileInfo = $fileInfos['product_photo'];
                    //$fileInfo = $_FILES["product_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $productPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $productPhoto->fit(273, 408);

                        $productPhoto->save(PUBLIC_PATH . '/uploads/products/' . $product['id'] . '.jpg');
                    } catch (Exception $ex) {

                        throw new Application_Model_Exception_InvalidInput('Error occured during image processing');
                    }
                }


                $cmsProductsDbTable->updateProduct($product['id'], $formData);

                // do actual task
                //save to database etc
                //set system message
                $flashMessenger->addMessage('Product has been updated', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_products',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;

        $this->view->product = $product;
    }

    public function deleteAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'delete') {

            //request is not post, 
            //or task is not delete
            //redirecting to index page

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid product id: ' . $id, 'errors');
            }

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();
            $product = $cmsProductsTable->getProductById($id);

            if (empty($product)) {

                throw new Application_Model_Exception_InvalidInput('No product is found with id: ' . $id, 'errors');
            }

            $cmsProductsTable->deleteProduct($id);


            $flashMessenger->addMessage('Product: ' . $product['first_name'] . ' ' . $product['last_name'] . 'has been deleted', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function disableAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'disable') {

            //request is not post, 
            //or task is not delete
            //redirecting to index page

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid product id: ' . $id, 'errors');
            }

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();
            $product = $cmsProductsTable->getProductById($id);

            if (empty($product)) {

                throw new Application_Model_Exception_InvalidInput('No product is found with id: ' . $id, 'errors');
            }

            $cmsProductsTable->disableProduct($id);


            $flashMessenger->addMessage('Product: ' . $product['first_name'] . ' ' . $product['last_name'] . 'has been disabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function enableAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'enable') {

            //request is not post, 
            //or task is not delete
            //redirecting to index page

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid product id: ' . $id, 'errors');
            }

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();
            $product = $cmsProductsTable->getProductById($id);

            if (empty($product)) {

                throw new Application_Model_Exception_InvalidInput('No product is found with id: ' . $id, 'errors');
            }

            $cmsProductsTable->enableProduct($id);


            $flashMessenger->addMessage('Product: ' . $product['first_name'] . ' ' . $product['last_name'] . 'has been enabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function updateorderAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'saveOrder') {

            //request is not post, 
            //or task is not saveOrder
            //redirecting to index page

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');


        try {

            $sortedIds = $request->getPost('sorted_ids');


            if (empty($sortedIds)) {

                throw new Application_Model_Exception_InvalidInput('Sorted ids are not sent');
            }

            $sortedIds = trim($sortedIds, ' ,');

            if (!preg_match('/^[0-9]+(,[0-9]+)*$/', $sortedIds)) {
                throw new Application_Model_Exception_InvalidInput('Invalid  sorted ids ' . $sortedIds);
            }

            $sortedIds = explode(',', $sortedIds);

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();

            $cmsProductsTable->updateProductOfOrder($sortedIds);


            $flashMessenger->addMessage('Order is successfully saved', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function dashboardAction() {


        $cmsProductsDbTable = new Application_Model_DbTable_CmsProducts();
        $totalNumberOfProducts = $cmsProductsDbTable->count();
        $activeProducts = $cmsProductsDbTable->count(array(
            'status' => Application_Model_DbTable_CmsProducts::STATUS_ENABLED
        ));

        $this->view->activeProducts = $activeProducts;
        $this->view->totalNumberOfProducts = $totalNumberOfProducts;
    }
    
    
    
      public function onAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'on') {

            //request is not post, 
            //or task is not delete
            //redirecting to index page

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {

            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid product id: ' . $id, 'errors');
            }

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();
            $product = $cmsProductsTable->getProductById($id);

            if (empty($product)) {

                throw new Application_Model_Exception_InvalidInput('No product is found with id: ' . $id, 'errors');
            }

            $cmsProductsTable->actionOnProduct($id);


            $flashMessenger->addMessage('Product: ' . $product['title'] .  ' has been saved', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function offAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'off') {

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {

            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid product id: ' . $id, 'errors');
            }

            $cmsProductsTable = new Application_Model_DbTable_CmsProducts();
            $product = $cmsProductsTable->getProductById($id);

            if (empty($product)) {

                throw new Application_Model_Exception_InvalidInput('No product is found with id: ' . $id, 'errors');
            }

            $cmsProductsTable->actionOffProduct($id);


            $flashMessenger->addMessage('Product: ' . $product['title']  . ' has been removed', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_products',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

}
