<?php

class SessionController extends Zend_Controller_Action
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
	
	public function loginAction() {
           
		$loginForm = new Application_Form_Front_Login();
		
		$request = $this->getRequest();
		$request instanceof Zend_Controller_Request_Http;
		
		$flashMessenger = $this->getHelper('FlashMessenger');
		
		$systemMessages = array(
			
			'success' => $flashMessenger->getMessages('success'),
			'errors' => $flashMessenger->getMessages('errors')
		);
		
		if ($request->isPost() && $request->getPost('task') === 'login') {
			
			if ($loginForm->isValid($request->getPost())) {
//                            print_r($loginForm);
//                            die();
				$authAdapter = new Zend_Auth_Adapter_DbTable();
				$authAdapter->setTableName('cms_front_users')
					->setIdentityColumn('email')
					->setCredentialColumn('password')
					->setCredentialTreatment('MD5(?)');
                                
				$authAdapter->getDbSelect()->where('role =?', 'user');
                                
				$authAdapter->setIdentity($loginForm->getValue('email'));
				$authAdapter->setCredential($loginForm->getValue('password'));
                                 
				
				$auth = Zend_Auth::getInstance();
				
				$result = $auth->authenticate($authAdapter);
				
				if ($result->isValid()) {
					
					// Smestanje kompletnog reda iz tabele cms_users kao identifikator da je korisnik ulogovan
					// Po defaultu se semsta samo username, a ovako smestamo asocijativni niz tj row iz tabele
					// Asocijativni niz $user ima kljuceve koji su u stvari nazivi kolona u tabeli cms_users
					$user = (array) $authAdapter->getResultRowObject();
					$auth->getStorage()->write($user);
					
					$redirector = $this->getHelper('Redirector');
					$redirector instanceof Zend_Controller_Action_Helper_Redirector;

					$redirector->setExit(true)
						->gotoRoute(array(

							'controller' => 'index',
							'action' => 'index'

						), 'default', true);
					
					
				} else {
					$systemMessages['errors'][] = 'Wrong username or password';
				}
				
			} else {
				$systemMessages['errors'][] = 'Username and password are required';
			}
		}
		
		$this->view->systemMessages = $systemMessages;
	}
	
	public function logoutAction() {
            
	$auth = Zend_Auth::getInstance();


        //brise indikator da je neko ulogovan
        $auth->clearIdentity();
        
        
        $flashMessenger = $this->getHelper('FlashMessenger');
        
        $flashMessenger->addMessage('You have been logged out', 'success');

        //ovde ide redirect na login stranu
        //ovako dobijamo abstraktni helper 
        $redirector = $this->getHelper('Redirector');


        //ovo koristino da bi smo posle imali hintove za redirect
        $redirector instanceof Zend_Controller_Action_Helper_Redirector;


        //prvi parametar gde rutiramo, drugi parametar: koje rutiranje, treci parametar: true-resetuj mi zapamcene vrednosti
        $redirector->setExit(true)
                ->gotoRoute(array(
                    'controller' => 'session', // idi na ovaj kontroler i na akciju login
                    'action' => 'login'
                        ), 'default', true);


	}
        
        
}