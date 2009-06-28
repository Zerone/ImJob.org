<?php
class Model_DbTable_Lists extends Zend_Db_Table
{
	protected $_name = 'lists';
	
	public function __construct($config = array())
	{
		global $application;
		parent::__construct($config);
		$resources = $application->getOption("resources");
		if( isset($resources['db']['settings']['tableprefix']))
		{
			$this->_name = $resources['db']['settings']['tableprefix'] . $this->_name;
		}
	}
}