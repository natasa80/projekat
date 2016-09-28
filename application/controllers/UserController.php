<?php

class UserController extends Zend_Controller_Action
{
	public function indexAction() {
            
            $flashMessenger = $this->getHelper('FlashMessenger');

		$systemMessages = array(
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors'),
		);
            
          $request = $this->getRequest();
          $cmsUserDbTable = new Application_Model_DbTable_CmsFrontUsers();
          
          $loggedInUser = Zend_Auth::getInstance()->getIdentity();
          
            if (!empty($loggedInUser)){
//               print_r($loggedInUser);
//           die('ff');
                $frontUsers = $cmsUserDbTable->search(array(
                    'filters' => array(
                        'id' => $loggedInUser['id'],
                    ),
                    'orders' => array(
                        'order_number' => 'ASC'
                    ),
                    'limit' => 1
                ));
          
          
          //dobijamo niz vrednosti za logovanog usera
          $frontUser = $frontUsers[0];
          
          $user = $cmsUserDbTable->getUserById($loggedInUser['id']);
          

          //podaci potrebni ya editovanje user-a
          $form = new Application_Form_Front_EditAccount();
          $form->populate($user);
          
          
          if ($request->isPost() && $request->getPost('task') === 'update'){
             
                $form->populate($request->getPost());
                $formData = $form->getValues();

                unset($formData['password']);
                
                $cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
                $cmsUsersTable->updateUser($user['id'], $formData);
                
                
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                                'controller' => 'user',
                                'action' => 'index'
                                ), 'default', true);
          }
          
          //izmena lozinke
          $formPassword = new Application_Form_Front_ChangePassword();
          
          if ($request->isPost() && $request->getPost('task') === 'newpassword') {
               
                $formPassword->populate($request->getPost());
                $formData = $formPassword->getValues();

                unset($formData['password_confirm']);

                $cmsUsersTable = new Application_Model_DbTable_CmsFrontUsers();
                $cmsUsersTable->changeUserPassword($user['id'], $formData['password']);
                
                $flashMessenger->addMessage('Password has been chnged', 'success');
                $redirector = $this->getHelper('Redirector');
                $redirector->setExit(true)
                        ->gotoRoute(array(
                                'controller' => 'user',
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
        $this->view->systemMessages = $systemMessages;
        
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
						'action' => 'index'
						), 'default', true);
			} catch (Application_Model_Exception_InvalidInput $ex) {
				$systemMessages['errors'][] = $ex->getMessage();
			}
		}

		$this->view->systemMessages = $systemMessages;
		$this->view->form = $form;
            
        }
}