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
		
		
	}
	
	public function logoutAction() {
		
	}
        
        
}