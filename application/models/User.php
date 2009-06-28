<?php

class Model_User
{
    protected $_id;
    protected $_email;
    protected $_password;
    protected $_role = 'unknown';
    protected $_date_created;
    protected $_date_modified;
    protected $_email_verified = 0;
    protected $_enabled = 0;
    protected $_last_login = 0;
    protected $_salt;
    protected $_firstname;
    protected $_lastname;
    protected $_mapper;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid User property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid User property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
	public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }
    
    /**
     * 
     * @return Model_UserMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Model_UserMapper());
        }
        return $this->_mapper;
    }

    public function save()
    {
        return $this->getMapper()->save($this);
    }

    /**
     * 
     * @param int $id
     * @return Model_User $User
     */
    public function find($id)
    {
        $this->getMapper()->find($id, $this);
        
        return $this;
    }

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }
    
    public function setId($id)
    {
    	$this->_id = (int)$id;
    	return $this;
    }

    public function getId()
    {
        return $this->_id;
    }
    
    public function setEmail($email)
    {
    	$this->_email = (string)$email;
    	return $this;
    }
    
    public function getEmail()
    {
    	return $this->_email;
    }
    
    public function setPassword($password)
    {
    	$this->_password = (string) $password;
    	return $this;
    }
    
    public function getPassword()
    {
    	return $this->_password;
    }
    
    public function setFirstname($firstname)
    {
    	$this->_firstname = (string)$firstname;
    	return $this;
    }
    
    public function getFirstname()
    {
    	return $this->_firstname;
    }
	
    public function setLastname($lastname)
    {
    	$this->_lastname = (string)$lastname;
    	return $this;
    }
    
    public function getLastname()
    {
    	return $this->_lastname;
    }
    
    public function setRole($role)
    {
    	/*
    	switch($role)
    	{
    		case 1:
    			$this->_role = 'learner';
    			break;
    		case 2:
    			$this->_role = 'driving_instructor';
    			break;
    		
    		case 'learner':
    			$this->_role = 'learner';
    			break;
    			
    		case 'driving_instructor':
    			$this->_role = 'driving_instructor';
    			break;
    	} */
    	$this->_role = (int) $role;
    	
    	return $this;
    }
    
    public function getRole()
    {
    	return $this->_role;
    }
    
    public function setDateCreated($date_created)
    {
    	$this->_date_created = (int) $date_created;
    	return $this;
    }
    
    public function getDateCreated()
    {
    	return $this->_date_created;
    }
    
    public function setDateModified($date_modified)
    {
    	$this->_date_modified = (int) $date_modified;
    	return $this;
    }
    
    public function getDataModified()
    {
    	return $this->_date_modified;
    }
    
    public function setEmailVerified($email_verified)
    {
    	$this->_email_verified = (int) $email_verified;
    	return $this;
    }
    
    public function getEmailVerified()
    {
    	return $this->_email_verified;
    }
    
    public function setEnabled($enabled)
    {
    	$this->_enabled = (int) $enabled;
    	return $this;
    }
    
    public function getEnabled()
    {
    	return $this->_enabled;
    }
    
    public function setLastLogin($last_login)
    {
    	$this->_last_login = (int) $last_login;
    	
    	return $this;
    }
    
    public function getLastLogin()
    {
    	return $this->_last_login;
    	
    }
    
    public function setSalt($salt)
    {
    	$this->_salt = (string) $salt;
    	return $this;
    }
    
    public function getSalt()
    {
    	return $this->_salt;
    }
    
    
    public function setCaptcha($salt)
    {
    	if(is_array($salt) && isset($salt['input']))
    		$this->_salt = (string) $salt['input'];
    	else
    		$this->_salt = (string)$salt;
    		
    	return $this; 
    }
    
}
