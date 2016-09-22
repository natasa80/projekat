<?php

class UserController extends Zend_Controller_Action
{
//	public function indexAction() {
//		
//		//provera da li je korisnik ulogovan
//		if (Zend_Auth::getInstance()->hasIdentity()) {
//			//ulogovan je
//			
//			//redirect na admin_dasboard kontroler i index akciju
//			$redirector = $this->getHelper('Redirector');
//			$redirector instanceof Zend_Controller_Action_Helper_Redirector;
//
//			$redirector->setExit(true)
//				->gotoRoute(array(
//
//					'controller' => 'admin_dashboard',
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
//					'controller' => 'admin_session',
//					'action' => 'login'
//
//				), 'default', true);
//		}
//			
//		
//	}
	
	
	
	public function logoutAction() {
		
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
                                if($formData['terms_and_conditions'] == 0){
                                    
                                    $flashMessenger->addMessage('Please confirm conditions', 'error');
                                    $redirector = $this->getHelper('Redirector');
                                    $redirector->setExit(true)
					->gotoRoute(array(
						'controller' => 'user',
						'action' => 'register'
						), 'default', true);
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