<?php

class UserController extends Zend_Controller_Action
{
	public function indexAction() {
            
            $request = $this->getRequest();
            
            $cmsFrontUsersDbTable = new Application_Model_DbTable_CmsFrontUsers();
            
            $request = $this->getRequest();
        
        $cmsFrontUsersDbTable = new Application_Model_DbTable_CmsFrontUsers();
        
        $loggedInUser = Zend_Auth::getInstance()->getIdentity();
        
        if (!empty($loggedInUser)){
            $frontUsers = $cmsFrontUsersDbTable->search(array(
                'filters' => array(
                    'id' => $loggedInUser['id'],
                    'status' => Application_Model_DbTable_CmsFrontUsers::STATUS_ENABLED,
                    ),
                'orders' => array(
                    'order_number' => 'ASC'
                ),
            ));
            
            $frontUser = $frontUsers[0];
            
            $user = $cmsFrontUsersDbTable->getFrontUserById($loggedInUser['id']);
            
            $form = new Application_Form_EditFrontUser();
            
            $form->populate($user);
            
            if ($request->isPost() && $request->getPost('task') === 'update') {
                $form->populate($request->getPost());
                $formData = $form->getValues();
                
                unset($formData['password']);
                
                $cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
                $cmsUsersTable->updateFrontUser($user['id'], $formData);

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'frontuser',
                        'action' => 'index'
                        ), 'default', true);
            }
            
            $formPassword = new Application_Form_ChangePassword();
            
            if ($request->isPost() && $request->getPost('task') === 'newpassword') {
                $formPassword->populate($request->getPost());
                $formData = $formPassword->getValues();
                
                unset($formData['password_confirm']);
                
                $cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
                $cmsUsersTable->changeFrontUserPassword($user['id'], $formData['password']);

                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                    ->gotoRoute(array(
                        'controller' => 'frontuser',
                        'action' => 'index'
                        ), 'default', true);
            }
            
        } else {
            $redirector = $this->getHelper('Redirector');
            $redirector instanceof Zend_Controller_Action_Helper_Redirector;
            $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'session',
                    'action' => 'login'
                ), 'default', true);
        }
        
        $this->view->form = $form;
        $this->view->frontUser = $frontUser;
		
		//provera da li je korisnik ulogovan
//		if (Zend_Auth::getInstance()->hasIdentity()) {
//			//ulogovan je
//			
//			//redirect na admin_dasboard kontroler i index akciju
//			$redirector = $this->getHelper('Redirector');
//			$redirector instanceof Zend_Controller_Action_Helper_Redirector;
//
//			$redirector->setExit(true)
//				->gotoRoute(array(
//					'controller' => 'user',
//					'action' => 'index'
//
//				), 'default', true);
//			
//		} else {
//			// nije ulogovan
//			
//			// redirect na login stranu
//			$redirector = $this->getHelper('Redirector');
//			$redirector instanceof Zend_Controller_Action_Helper_Redirector;
//
//			$redirector->setExit(true)
//				->gotoRoute(array(
//
//					'controller' => 'session',
//					'action' => 'login'
//
//				), 'default', true);
//		}
			
		
	}
	
	
	
        
        public function registerAction(){
            
            $request = $this->getRequest();

		$flashMessenger = $this->getHelper('FlashMessenger');

		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);


		$form = new Application_Form_Front_AddAccount();
                
		//default form data
		$form->populate(array(
		));

		if ($request->isPost() && $request->getPost('task') === 'save') {

			try {

				//check form is valid
				if (!$form->isValid($request->getPost())) {
					throw new Application_Model_Exception_InvalidInput('Invalid data was sent for new user');
				}

				//get form data
				$formData = $form->getValues();
                                unset($formData['password_confirm']);
                                
//                                print_r($formData);
//                                die();

				//Insertujemo novi zapis u tabelu
				$cmsFrontUsersTable = new Application_Model_DbTable_CmsFrontUsers();
                                
                                if(empty($formData['terms_and_conditions'])|| empty($formData['personal_data']) ){
                               
                                    throw new Application_Model_Exception_InvalidInput('Please confirm terms & conditions');
                                     
                                }
				//insert member returns ID of the new member
				$userId = $cmsFrontUsersTable->insertUser($formData);

				// do actual task
				//save to database etc
				//set system message
				$flashMessenger->addMessage('User has been saved', 'success');

				//redirect to same or another page
				$redirector = $this->getHelper('Redirector');
				$redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'user',
						'action' => 'register'
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				$systemMessages['errors'][] = $ex->getMessage();
			}
		}

		$this->view->systemMessages = $systemMessages;
		$this->view->form = $form;
            
        }
}