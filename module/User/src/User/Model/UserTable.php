<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\View\Model\ViewModel;

class UserTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getUser($username,$password)
	{
		$password = md5($password);
// 		$username  =  $username;
		$rowset = $this->tableGateway->select(array('username' => $username,'password' => $password));
		$row = $rowset->current();
		if (!$row) {
			return new ViewModel(array('fail'=>'fail'));
//  			throw new \Exception("Could not find row $username");
		}
		return $row;
	}

	public function saveUser(User $user)
	{
		$data = array(
				'username' => $user->username,
				'password'  => $user->password,
		);

		$id = (int) $user->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getUser($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('User id does not exist');
			}
		}
	}

	public function deleteUser($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}