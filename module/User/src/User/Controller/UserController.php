<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User; // <-- Add this import
use User\Form\UserForm;

/**
 * @author shen
 *
 */
class UserController extends AbstractActionController {
	protected $userTable;
	public function indexAction() {
// 		$form = new UserForm ();
// 		$form->get ( 'submit' )->setValue ( 'login' );
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$login = $request->getPost ( 'submit' );
			if ($login == 'signup') {
				return $this->redirect ()->toUrl ( 'http://localhost/myZend/public/user/signup' );
			}
			$username = $request->getPost ( 'username' );
			$password = $request->getPost ( 'password' );
			$user = $this->getUserTable ()->getUser ( $username, $password );
			if (! isset ( $user->fail )) {
				setcookie("username", $username,time()+3600); // set cookie
				return $this->redirect ()->toUrl ( 'http://localhost/myZend/public' );
			}
			// Redirect to list of albums
			
			return $this->redirect ()->toUrl ( 'http://localhost/myZend/public/user' );
		}
// 		return array (
// 				'form' => $form
// 		);
	}
	public function signupAction() {
		$form = new UserForm ();
		$form->get ( 'submit' )->setValue ( 'signup' );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$user = new User ();
			// $form->setInputFilter ( $user->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				$user->exchangeArray ( $form->getData () );
				$this->getUserTable ()->saveUser ( $user );
				
				// Redirect to list of albums
				return $this->redirect ()->toRoute ( 'user' );
			}
		}
		return array (
				'form' => $form 
		);
	}
	public function getUserTable() {
		if (! $this->userTable) {
			$sm = $this->getServiceLocator ();
			$this->userTable = $sm->get ( 'User\Model\UserTable' );
		}
		return $this->userTable;
	}
	// public function editAction() {
	// $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
	// if (! $id) {
	// return $this->redirect ()->toRoute ( 'album', array (
	// 'action' => 'add'
	// ) );
	// }
	
	// Get the Album with the specified id. An exception is thrown
	// if it cannot be found, in which case go to the index page.
	// try {
	// $album = $this->getAlbumTable ()->getAlbum ( $id );
	// } catch ( \Exception $ex ) {
	// return $this->redirect ()->toRoute ( 'album', array (
	// 'action' => 'index'
	// ) );
	// }
	
	// $form = new AlbumForm ();
	// $form->bind ( $album );
	// $form->get ( 'submit' )->setAttribute ( 'value', 'Edit' );
	
	// $request = $this->getRequest ();
	// if ($request->isPost ()) {
	// $form->setInputFilter ( $album->getInputFilter () );
	// $form->setData ( $request->getPost () );
	
	// if ($form->isValid ()) {
	// $this->getAlbumTable ()->saveAlbum ( $album );
	
	// // Redirect to list of albums
	// return $this->redirect ()->toRoute ( 'album' );
	// }
	// }
	
	// return array (
	// 'id' => $id,
	// 'form' => $form
	// );
	// }
	// public function deleteAction() {
	// $id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
	// if (! $id) {
	// return $this->redirect ()->toRoute ( 'user' );
	// }
	
	// $request = $this->getRequest ();
	// if ($request->isPost ()) {
	// $del = $request->getPost ( 'del', 'No' );
	
	// if ($del == 'Yes') {
	// $id = ( int ) $request->getPost ( 'id' );
	// $this->getAlbumTable ()->deleteAlbum ( $id );
	// }
	
	// // Redirect to list of albums
	// return $this->redirect ()->toRoute ( 'album' );
	// }
	
	// return array (
	// 'id' => $id,
	// 'album' => $this->getAlbumTable ()->getAlbum ( $id )
	// );
	// }
	// public function getAlbumTable() {
	// if (! $this->albumTable) {
	// $sm = $this->getServiceLocator ();
	// $this->albumTable = $sm->get ( 'Album\Model\AlbumTable' );
	// }
	// return $this->albumTable;
	// }
}