<?php
/**
 * @copyright Copyright(c) S&A online Ltd UK
 * @version 0.1
 * @author Ali <ali@drivinglessononline.com>
 *
 */
class Model_UserMapper
{
	/**
	 *
	 * @var Model_DbTable_Users
	 */
	protected $_dbTable;

	/**
	 *
	 * @param Model_DbTable_Users
	 * @return Model_UserMapper
	 */
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Model_DbTable_Users) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * @return Model_DbTable_Users
	 */
	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Model_DbTable_Users');
		}
		return $this->_dbTable;
	}
	
	/**
	 * 
	 * @param Model_User $user
	 * @return int The primary key of the row inserted. OR The number of rows updated.
	 */
	public function save(Model_User $user)
	{
		$data = array(
            'email'   			=> $user->getEmail(),
        	'password'   		=> $user->getPassword(),
			'firstname'			=> $user->getFirstName(),
			'lastname'			=> $user->getLastName(),
        	'role'   			=> $user->getRole(),
        	'date_modified'   	=> time(),
        	'email_verified'   	=> $user->getEmailVerified(),
        	'enabled'   		=> $user->getEnabled(),
        	'last_login'   		=> $user->getLastLogin(),
        	'salt'				=> $user->getSalt()
		);

		if (null === ($id = $user->getId())) {
			unset($data['id']);
			$data['password'] = hash('ripemd160',$data['password']);
			$user->setPassword($data['password']);
			$data['date_created'] = time();
			return $this->getDbTable()->insert($data);
		} else {
			return $this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}
	
	/**
	 * 
	 * @param int $id
	 * @param Model_User $user
	 * @return boolean
	 */
	public function find($id, Model_User $user)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return false;
		}
		$row = $result->current();
		
		$user->setId($row->id)
				->setEmail($row->email)
				->setPassword($row->password)
				->setFirstName($row->firstname)
				->setLastName($row->lastname)
				->setRole($row->role)
				->setDateCreated($row->date_created)
				->setDateModified($row->date_modified)
				->setEmailVerified($row->email_verified)
				->setEnabled($row->enabled)
				->setLastLogin($row->last_login)
				->setSalt($row->salt)
				->setMapper($this);
				
		return true;
	}

	/**
	 * @return array An array of Model_User class.
	 */
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Model_User();
			$entry->setId($row->id)
				->setEmail($row->email)
				->setPassword($row->password)
				->setDisplayName($row->display_name)
				->setRole($row->role)
				->setEmailVerified($row->email_verified)
				->setEnabled($row->enabled)
				->setLastLogin($row->last_login)
				->setSalt($row->salt)
				->setMapper($this);
			$entries[] = $entry;
		}
		return $entries;
	}
}
