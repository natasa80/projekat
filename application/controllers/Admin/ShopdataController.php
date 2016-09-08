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
        
          $cmsWorkingHoursDbTable = new Application_Model_DbTable_CmsWorkingHours();
        
        $workingHours = $cmsWorkingHoursDbTable->search(array(
            'orders' => array(
                'order_number' => 'asc',
            ),
        ));
        
        
        $this->view->workingHours = $workingHours;
        
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

        $form = new Application_Form_Admin_ShopdataAdd ();


        $form->populate(array(
        ));


        if ($request->isPost() && $request->getPost('task') === 'save') {
            try {

                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new data');
                }

                $formData = $form->getValues();

                 unset($formData['logo_photo']);


                //insertujemo zapis u bazu
                $cmsDataTable = new Application_Model_DbTable_CmsShopData();

                //insert logo returns ID of the new logo
                $dataId = $cmsDataTable->insertData($formData);
                  if ($form->getElement('logo_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('logo_photo')->getFileInfo('logo_photo');
                    $fileInfo = $fileInfos['logo_photo'];
                    //$fileInfo = $_FILES["logo_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $logoPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $logoPhoto->fit(203, 62);
                        $logoPhoto->save(PUBLIC_PATH . '/uploads/logos/' . $dataId . '.jpg');
                    } catch (Exception $ex) {

                        $flashMessenger->addMessage('Member has been saved, but error occured during image processing', 'errors');

                        //redirect to same or another page
                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_logos',
                                    'action' => 'edit',
                                    'id' => $logoId
                                        ), 'default', true);
                    }
                }
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
                 unset($formData['logo_photo']);

                $cmsDataTable->updateData($data['id'], $formData);

                if ($form->getElement('logo_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('logo_photo')->getFileInfo('logo_photo');
                    $fileInfo = $fileInfos['logo_photo'];
                    //$fileInfo = $_FILES["logo_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $logoPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $logoPhoto->fit(203, 62);

                        $logoPhoto->save(PUBLIC_PATH . '/uploads/logos/' . $data['id'] . '.jpg');
                    } catch (Exception $ex) {

                        throw new Application_Model_Exception_InvalidInput('Error occured during image processing');
                    }
                }
                
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

            $cmsDataTable->disableData($id);


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

    
    
     
      public function addhoursAction() {
        
        $request = $this->getRequest(); 
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_WorkingHoursAdd();

        $form->populate(array(
        ));


        if ($request->isPost() && $request->getPost('task') === 'save') {
            try {

                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new data');
                }

                $formData = $form->getValues();


                //insertujemo zapis u bazu
                $cmsWorkingHoursDbTable = new Application_Model_DbTable_CmsWorkingHours();

                $workingHours = $cmsWorkingHoursDbTable->insertData($formData);

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

    public function edithoursAction() {


        $request = $this->getRequest();

        $id = (int) $request->getParam('id');

        if ($id <= 0) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('Invalid data id: ' . $id, 404);
        }


        $cmsWorkingHoursDbTable = new Application_Model_DbTable_CmsWorkingHours();
        $workingHours = $cmsWorkingHoursDbTable->getHoursById($id);

        if (empty($workingHours)) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('No data is found with id ' . $id, 404);
        }


        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_WorkingHoursEdit();

        //default form data//mi nemamo default vrednosti
        $form->populate($workingHours);



        if ($request->isPost() && $request->getPost('task') === 'update') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for data');
                }


                $formData = $form->getValues();


                $cmsWorkingHoursDbTable->updateData($workingHours['id'], $formData);

                $flashMessenger->addMessage('Hours has been updated', 'success');

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

        $this->view->workingHours = $workingHours;
    }
    
        
}