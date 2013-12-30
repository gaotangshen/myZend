<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

class UserTable {
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	public function fetchAll() {
		$resultSet = $this->tableGateway->select ();
		return $resultSet;
	}
	public function getUser($username, $password) {
// 		echo $password;
		// $username = $username;
		$rowset = $this->tableGateway->select ( array (
				'username' => $username,
				'password' => $password 
		) );
		$row = $rowset->current ();
// 		var_dump($row);
		
		if (! $row) {
// 			return new ViewModel ( array (
// 					'fail' => 'fail' 
// 			) );
			throw new \Exception("Could not find row $username");
		}
		return $row;
	}
	public function saveUser(User $user) {
		if (isset ( $_COOKIE ['password'] )) {
			$current_password = $_COOKIE ['password'];
			$current_username = $_COOKIE ['username'];
		}
		if ($user->password == "") {
			$password = $current_password;
		} else {
			$password = md5 ( $user->password );
		}
		$data = array (
				'username' => $user->username,
				'password' => $password 
		);
		
		$id = ( int ) $user->id;
		if ($id == 0) {
			$this->tableGateway->insert ( $data );
		} else {
			$data = array (
					'username' => $user->username,
					'password' => $password 
			);
			if ($this->getUser ( $current_username, $current_password )) {
				$this->tableGateway->update ( $data, array (
						'id' => $id 
				) );
				setcookie("username", $user->username,time()+3600,'/'); // set cookie
				setcookie("password", $password,time()+3600,'/');
				
			} else {
				throw new \Exception ( 'User id does not exist' );
			}
		}
	}
	public function deleteUser($id) {
		$this->tableGateway->delete ( array (
				'id' => ( int ) $id 
		) );
	}
}