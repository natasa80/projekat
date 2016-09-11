<?php

class Admin_CategoriesController extends Zend_Controller_Action
{
	    public function indexAction() {

        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );
       //prikaz svih category-a
        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();

        $categories = $cmsCategoriesDbTable->search(array(
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
//        print_r($pets);
//        die();
       
        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $categories = $cmsCategoriesDbTable->search();
        
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        $producers = $cmsProducersDbTable->search();
//      print_r($categories);
//     die();
        $this->view->categories = $categories;
        $this->view->categories = $categories;
        $this->view->pets = $pets;
        $this->view->producers = $producers;
         $this->view->sitemapPagePets = $sitemapPagePets;
        $this->view->systemMessages = $systemMessages;
    }

    public function addAction() {
        //prikaz svih category-a


        $request = $this->getRequest(); //podaci iz url-a iz forme koje dobijemo
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_CategoryAdd();

        $form->populate(array(
        ));



        if ($request->isPost() && $request->getPost('task') === 'save') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new category');
                }

                //get form data//vrednosti iz forme se uzimaju preko getVaues i upisuju u niz
                //to su filtrirani i validirani podaci
                $formData = $form->getValues();


                //remove key memebr_photo from form data because there is no column memebr_photo in cms _categories
               



                //insertujemo zapis u bazu
                $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();

                //insert category returns ID of the new category
                $categoryId = $cmsCategoriesDbTable->insertCategory($formData);

                


                $flashMessenger->addMessage('Category has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_categories',
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
            throw new Zend_Controller_Router_Exception('Invalid category id: ' . $id, 404);
        }


       $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $category = $cmsCategoriesDbTable->getCategoryById($id);

        if (empty($category)) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('No category is found with id ' . $id, 404);
        }


        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_CategoryAdd();

        //default form data//mi nemamo default vrednosti
        $form->populate($category);



        if ($request->isPost() && $request->getPost('task') === 'update') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for  category');
                }


                $formData = $form->getValues();
                



                $cmsCategoriesDbTable->updateCategory($category['id'], $formData);

                // do actual task
                //save to database etc
                //set system message
                $flashMessenger->addMessage('Category has been updated', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_categories',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;

        $this->view->category = $category;
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
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id, 'errors');
            }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsCategories();
            $category = $cmsCategoriesTable->getCategoryById($id);

            if (empty($category)) {

                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id, 'errors');
            }

            $cmsCategoriesTable->deleteCategory($id);


            $flashMessenger->addMessage('Category: ' . $category['first_name'] . ' ' . $category['last_name'] . 'has been deleted', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
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
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id, 'errors');
            }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsCategories();
            $category = $cmsCategoriesTable->getCategoryById($id);

            if (empty($category)) {

                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id, 'errors');
            }

            $cmsCategoriesTable->disableCategory($id);


            $flashMessenger->addMessage('Category: ' . $category['first_name'] . ' ' . $category['last_name'] . 'has been disabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
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
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {



            //read $_POST
            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid category id: ' . $id, 'errors');
            }

            $cmsCategoriesTable = new Application_Model_DbTable_CmsCategories();
            $category = $cmsCategoriesTable->getCategoryById($id);

            if (empty($category)) {

                throw new Application_Model_Exception_InvalidInput('No category is found with id: ' . $id, 'errors');
            }

            $cmsCategoriesTable->enableCategory($id);


            $flashMessenger->addMessage('Category: ' . $category['first_name'] . ' ' . $category['last_name'] . 'has been enabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
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
                        'controller' => 'admin_categories',
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

            $cmsCategoriesTable = new Application_Model_DbTable_CmsCategories();

            $cmsCategoriesTable->updateCategoryOfOrder($sortedIds);


            $flashMessenger->addMessage('Order is successfully saved', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_categories',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function dashboardAction() {


        $cmsCategoriesDbTable = new Application_Model_DbTable_CmsCategories();
        $totalNumberOfCategories = $cmsCategoriesDbTable->count();
        $activeCategories = $cmsCategoriesDbTable->count(array(
            'status' => Application_Model_DbTable_CmsCategories::STATUS_ENABLED
        ));

        $this->view->activeCategories = $activeCategories;
        $this->view->totalNumberOfCategories = $totalNumberOfCategories;
    }

	
}