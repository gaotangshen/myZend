<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album; // <-- Add this import
use Album\Form\AlbumForm;

class AlbumController extends AbstractActionController {
	protected $albumTable;
	public function indexAction() {
		if(isset($_COOKIE['username'])){
			$username = $_COOKIE['username'];
		}else{
		 	$this->redirect ()->toUrl ( 'http://localhost/myZend/public/user' );
// 			return $this->redirect ()->toRoute ( 'album' );
		}
		return new ViewModel ( array (
				'albums' => $this->getAlbumTable ()->fetchAll () ,
				'username'=>$username
		) );
	}
	public function addAction() {
		$form = new AlbumForm ();
		$form->get ( 'submit' )->setValue ( 'Add' );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$album = new Album ();
			$form->setInputFilter ( $album->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				$album->exchangeArray ( $form->getData () );
				$this->getAlbumTable ()->saveAlbum ( $album );
				
				// Redirect to list of albums
				return $this->redirect ()->toRoute ( 'album' );
			}
		}
		return array (
				'form' => $form 
		);
	}
	public function editAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			return $this->redirect ()->toRoute ( 'album', array (
					'action' => 'add' 
			) );
		}
		
		// Get the Album with the specified id. An exception is thrown
		// if it cannot be found, in which case go to the index page.
		try {
			$album = $this->getAlbumTable ()->getAlbum ( $id );
		} catch ( \Exception $ex ) {
			return $this->redirect ()->toRoute ( 'album', array (
					'action' => 'index' 
			) );
		}

		$form = new AlbumForm ();
		var_dump($album);
		$form->bind ( $album );
		$form->get ( 'submit' )->setAttribute ( 'value', 'Edit' );
			var_dump($album);
// 				die();
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setInputFilter ( $album->getInputFilter () );
			$form->setData ( $request->getPost () );
			
			if ($form->isValid ()) {
				var_dump($album);
				die();
				$this->getAlbumTable ()->saveAlbum ( $album );
// 				var_dump($album);
// 				die();
				// Redirect to list of albums
				return $this->redirect ()->toRoute ( 'album' );
			}
		}
		
		return array (
				'id' => $id,
				'form' => $form 
		);
	}
	public function deleteAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			return $this->redirect ()->toRoute ( 'album' );
		}
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$del = $request->getPost ( 'del', 'No' );
			
			if ($del == 'Yes') {
				$id = ( int ) $request->getPost ( 'id' );
				$this->getAlbumTable ()->deleteAlbum ( $id );
			}
			
			// Redirect to list of albums
			return $this->redirect ()->toRoute ( 'album' );
		}
		
		return array (
				'id' => $id,
				'album' => $this->getAlbumTable ()->getAlbum ( $id ) 
		);
	}
	public function getAlbumTable() {
		if (! $this->albumTable) {
			$sm = $this->getServiceLocator ();
			$this->albumTable = $sm->get ( 'Album\Model\AlbumTable' );
		}
		return $this->albumTable;
	}
}