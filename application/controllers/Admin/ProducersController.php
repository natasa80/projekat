
<?php

class Admin_ProducersController extends Zend_Controller_Action {
    
    public function indexAction() {

        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );
        
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
        
         $producers = $cmsProducersDbTable->search();


        $this->view->producers = $producers;
        $this->view->systemMessages = $systemMessages;
    }

    
    
    public function addAction() {
        


        $request = $this->getRequest(); //podaci iz url-a iz forme koje dobijemo
        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_ProducerAdd();

//default form data//mi nemamo default vrednosti
        $form->populate(array(
        ));



        if ($request->isPost() && $request->getPost('task') === 'save') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new producer');
                }

                //get form data//vrednosti iz forme se uzimaju preko getVaues i upisuju u niz
                //to su filtrirani i validirani podaci
                $formData = $form->getValues();
                 unset($formData['producer_photo']);

                //insertujemo zapis u bazu
                $cmsProducersTable = new Application_Model_DbTable_CmsProducers();
                
                $producerId = $cmsProducersTable->insertProducer($formData);
                
                if ($form->getElement('producer_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('producer_photo')->getFileInfo('producer_photo');
                    $fileInfo = $fileInfos['producer_photo'];
                    //$fileInfo = $_FILES["producer_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $producerPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $producerPhoto->fit(250, 350);
                        $producerPhoto->save(PUBLIC_PATH . '/uploads/producers/' . $producerId . '.jpg');
                    } catch (Exception $ex) {

                        $flashMessenger->addMessage('Producer has been saved, but error occured during image processing', 'errors');

                        //redirect to same or another page
                        $redirector = $this->getHelper('Redirector');
                        $redirector->setExit(true)
                                ->gotoRoute(array(
                                    'controller' => 'admin_producers',
                                    'action' => 'edit',
                                    'id' => $producerId
                                        ), 'default', true);
                    }
                }
                
           
                $flashMessenger->addMessage('Producer has been saved', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
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
            throw new Zend_Controller_Router_Exception('Invalid producer id: ' . $id, 404);
        }


        $cmsProducersTable = new Application_Model_DbTable_CmsProducers();
        $producer = $cmsProducersTable->getProducerById($id);

        if (empty($producer)) {
            //prekida se izvrsavanje proograma i prikazuje se page not found
            throw new Zend_Controller_Router_Exception('No producer is found with id ' . $id, 404);
        }


        $flashMessenger = $this->getHelper('FlashMessenger');

        $systemMessages = array(
            'success' => $flashMessenger->getMessages('success'),
            'errors' => $flashMessenger->getMessages('errors'),
        );

        $form = new Application_Form_Admin_ProducerAdd();

        //default form data//mi nemamo default vrednosti
        $form->populate($producer);



        if ($request->isPost() && $request->getPost('task') === 'update') {//ispitujemo da lije pokrenuta forma
            try {

                //check form is valid
                if (!$form->isValid($request->getPost())) {//sve sto je u post zahtevu prosledi formi na validaciju
                    throw new Application_Model_Exception_InvalidInput('Invalid data was sent for  producer' );
                }

                //get form data//vrednosti iz forme se uzimaju preko getVaues i upisuju u niz
                //to su filtrirani i validirani podaci
                $formData = $form->getValues();

                unset($formData['producer_photo']);

                if ($form->getElement('producer_photo')->isUploaded()) {
                    //photo is uploaded 

                    $fileInfos = $form->getElement('producer_photo')->getFileInfo('producer_photo');
                    $fileInfo = $fileInfos['producer_photo'];
                    //$fileInfo = $_FILES["producer_photo"];


                    try {
                        //open uploaded photo in temporary directory
                        $producerPhoto = Intervention\Image\ImageManagerStatic::make($fileInfo['tmp_name']);

                        $producerPhoto->fit(250, 350);

                        $producerPhoto->save(PUBLIC_PATH . '/uploads/producers/' . $producer['id'] . '.jpg');
                    } catch (Exception $ex) {

                        throw new Application_Model_Exception_InvalidInput('Error occured during image processing');
                    }
                }
                //iUpdate postojeceg zapisa u tabeli
                
                $cmsProducersTable->updateProducer($producer['id'], $formData);
                
                // do actual task
                //save to database etc
                //set system message
                $flashMessenger->addMessage('Producer has been updated', 'success');

                //redirect to same or another page
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
            } catch (Application_Model_Exception_InvalidInput $ex) {
                $systemMessages['errors'][] = $ex->getMessage();
            }
        }

        $this->view->systemMessages = $systemMessages;
        $this->view->form = $form;

        $this->view->producer = $producer;
    }
    
    
    public function deleteAction(){
      
         $request = $this->getRequest();
         
         if(!$request->isPost()|| $request->getPost('task') != 'delete'){
            
             
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }
         
         $flashMessenger = $this->getHelper('FlashMessenger');
         
         try {
             
            //read $_POST
           $id = (int) $request->getPost('id');


           if ($id <= 0) {
               throw new Application_Model_Exception_InvalidInput('Invalid producer id: ' . $id, 'errors');
           }

           $cmsProducersTable = new Application_Model_DbTable_CmsProducers();
           $producer = $cmsProducersTable->getProducerById($id);

           if (empty($producer)) {

               throw new Application_Model_Exception_InvalidInput('No producer is found with id: ' . $id, 'errors');

           }
           
           $cmsProducersTable->deleteProducer($id);
        
        
            $flashMessenger->addMessage('Producer: ' . $producer['title'] . 'has been deleted', 'success');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
             
         } catch (Application_Model_Exception_InvalidInput $ex) {
             
             $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }

    }
    
    public function disableAction(){
      
         $request = $this->getRequest();
         
         if(!$request->isPost()|| $request->getPost('task') != 'disable'){
             
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }
         
         $flashMessenger = $this->getHelper('FlashMessenger');
         
         try {
             
           $id = (int) $request->getPost('id');

           if ($id <= 0) {
               throw new Application_Model_Exception_InvalidInput('Invalid producer id: ' . $id, 'errors');
           }

           $cmsProducersTable = new Application_Model_DbTable_CmsProducers();
           $producer = $cmsProducersTable->getProducerById($id);

           if (empty($producer)) {

               throw new Application_Model_Exception_InvalidInput('No producer is found with id: ' . $id, 'errors');

           }
           
           $cmsProducersTable->disableProducer($id);
        
        
            $flashMessenger->addMessage('Producer: ' . $producer['title']  . 'has been disabled', 'success');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
             
         } catch (Application_Model_Exception_InvalidInput $ex) {
             
             $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }

    }
    
    public function enableAction(){
      
         $request = $this->getRequest();
         
         if(!$request->isPost()|| $request->getPost('task') != 'enable'){
          
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }
         
         $flashMessenger = $this->getHelper('FlashMessenger');
         
         try {
             //dovijamo id iz post-a
           $id = (int) $request->getPost('id');

           //ukoliko je nevalidan
           if ($id <= 0) {
               throw new Application_Model_Exception_InvalidInput('Invalid producer id: ' . $id, 'errors');
           }
           
           //pristupamo tabeli iz baze
           $cmsProducerTable = new Application_Model_DbTable_CmsProducers();
           //dobijamo zeljeni servis iz baze na osnovu dobijenog IDja
           $producer = $cmsProducerTable->getProducerById($id);
           
           //ukoliko dobijeni producer ne postoji
           if (empty($producer)) {

               throw new Application_Model_Exception_InvalidInput('No producer is found with id: ' . $id, 'errors');

           }
           
           //na dobijenu tabelu iz baze primenjujemo zeljenu fju za dobijeni ID
           $cmsProducerTable->enableProducer($id);
        
        
            $flashMessenger->addMessage('Producer: ' . $producer['title'] . 'has been enabled', 'success');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
             
         } catch (Application_Model_Exception_InvalidInput $ex) {
             
             $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }

    }
    
    
    
     public function updateorderAction(){
        
        $request = $this->getRequest();
         
         if(!$request->isPost()|| $request->getPost('task') != 'saveOrder'){
             
             //request is not post, 
             //or task is not saveOrder
             //redirecting to index page
             
             $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
         }
         
         $flashMessenger = $this->getHelper('FlashMessenger');
        
        
         try {
             
             $sortedIds = $request->getPost('sorted_ids');
             
             
            if(empty($sortedIds)){
                
                throw new Application_Model_Exception_InvalidInput('Sorted ids are not sent');
                
            }
            
            $sortedIds = trim($sortedIds, ' ,');
           
            
            if(!preg_match('/^[0-9]+(,[0-9]+)*$/', $sortedIds)){
                throw new Application_Model_Exception_InvalidInput('Invalid  sorted ids ' . $sortedIds);
            }
            
            $sortedIds = explode(',', $sortedIds);
            
            $cmsProducersTable = new Application_Model_DbTable_CmsProducers();
            
            $cmsProducersTable->updateProducerOfOrder($sortedIds);
            
            $flashMessenger->addMessage('Order is successfully saved', 'success');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
            
            
             
             
         } catch (Application_Model_Exception_InvalidInput $ex) {
             
              $flashMessenger->addMessage($ex->getMessage(), 'errors');
            
            //redirect on another page
            $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                            'controller' => 'admin_producers',
                            'action' => 'index'
                                ), 'default', true);
             
         }

}



 public function dashboardAction() {
      
        
        $cmsProducersDbTable = new Application_Model_DbTable_CmsProducers();
      
        $totalNumberOfProducers = $cmsProducersDbTable->count();
        $activeProducers = $cmsProducersDbTable->count(array(
            'status' => Application_Model_DbTable_CmsProducers::STATUS_ENABLED
        ));
       
       
                
        $this->view->activeProducers = $activeProducers;
        $this->view->totalNumberOfProducers = $totalNumberOfProducers;
       
      
    }

}
