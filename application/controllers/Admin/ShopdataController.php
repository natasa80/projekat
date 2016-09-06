<?php

class Admin_ShopdataController extends Zend_Controller_Action
{
	public function indexAction() {
		
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );
        
        $cmsInformationsDbTable = new Application_Model_DbTable_CmsShopData();
        
        $informations = $cmsInformationsDbTable->search();
        
        $this->view->informations = $informations;
        $this->view->systemMessages = $systemMessages;
		
	}
        
        
      public function addAction() {
        
        $request = $this->getRequest(); 
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_MemberAdd();


        $form->populate(array(
        ));


        if ($request->isPost() && $request->getPost('task') === 'save') {
            try {

                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new data');
                }

                $formData = $form->getValues();


                //insertujemo zapis u bazu
                $cmsDataTable = new Application_Model_DbTable_CmsShopData();

                //insert member returns ID of the new member
                $dataId = $cmsDataTable->insertData($formData);

                // do actual task
                //save to database etc
                //set system message
                $flashMessenger->addMessage('Data has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_shopdata',
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
            throw new Zend_Controller_Router_Exception('Invalid data id: ' . $id, 404);
        }


        $cmsDataTable = new Application_Model_DbTable_CmsShopData();
        $data = $cmsDataTable->getDataById($id);

        if (empty($data)) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('No data is found with id ' . $id, 404);
        }


        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_ShopdataAdd();

        //default form data//mi nemamo default vrednosti
        $form->populate($data);



        if ($request->isPost() && $request->getPost('task') === 'update') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for data');
                }


                $formData = $form->getValues();


                $cmsDataTable->updateData($data['id'], $formData);

                $flashMessenger->addMessage('Data has been updated', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_shopdata',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;

        $this->view->data = $data;
    }

    public function deleteAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'delete') {

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {

            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid data id: ' . $id, 'errors');
            }

            $cmsDataTable = new Application_Model_DbTable_CmsShopData();
            $data = $cmsDataTable->getDataById($id);

            if (empty($data)) {

                throw new Application_Model_Exception_InvalidInput('No data is found with id: ' . $id, 'errors');
            }

            $cmsDataTable->deleteData($id);


            $flashMessenger->addMessage('Data has been deleted', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function disableAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'disable') {

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {

            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid data id: ' . $id, 'errors');
            }

            $cmsDataTable = new Application_Model_DbTable_CmsShopData();
            $data = $cmsDataTable->getDataById($id);

            if (empty($data)) {

                throw new Application_Model_Exception_InvalidInput('No data is found with id: ' . $id, 'errors');
            }

            $cmsataTable->disableData($id);


            $flashMessenger->addMessage('Data has been disabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function enableAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'enable') {

            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }

        $flashMessenger = $this->getHelper('FlashMessenger');

        try {

            $id = (int) $request->getPost('id');


            if ($id <= 0) {
                throw new Application_Model_Exception_InvalidInput('Invalid data id: ' . $id, 'errors');
            }

            $cmsDataTable = new Application_Model_DbTable_CmsShopData();
            $data = $cmsDataTable->getDataById($id);

            if (empty($data)) {

                throw new Application_Model_Exception_InvalidInput('No data is found with id: ' . $id, 'errors');
            }

            $cmsDataTable->enableData($id);


            $flashMessenger->addMessage('Data has been enabled', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }
    }

    public function updateorderAction() {

        $request = $this->getRequest();

        if (!$request->isPost() || $request->getPost('task') != 'saveOrder') {

    
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
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

            $cmsDataTable = new Application_Model_DbTable_CmsShopData();

            $cmsDataTable->updateDataOfOrder($sortedIds);


            $flashMessenger->addMessage('Order is successfully saved', 'success');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        } catch (Application_Model_Exception_InvalidInput $ex) {

            $flashMessenger->addMessage($ex->getMessage(), 'errors');

            //redirect on another page
            $redirector = $this->getHelper('Redirector');
            $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'admin_shopdata',
                        'action' => 'index'
                            ), 'default', true);
        }
    }   
    
        
}