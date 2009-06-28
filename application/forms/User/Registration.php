<?php
class Form_User_Registration extends Zend_Form
{
	public function init(){
		
		$this->setMethod('post');
		
		$this->addElement('text', 'firstname', array(
        	'description'=> 'Please enter your first name.',
            'label'      => 'Enter first name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'validators' => array(
				array('validator'=>'Alpha','options'=>array($allowWhiteSpace = false)),
				array('validator' => 'StringLength', 'options' => array(2, 50))
            )
        ));
        
        $this->addElement('text', 'lastname', array(
        	'description'=> 'Please enter your last name.',
            'label'      => 'Enter last name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'validators' => array(
				array('validator'=>'Alnum','options'=>array($allowWhiteSpace = false)),
				array('validator' => 'StringLength', 'options' => array(2, 50))
            )
        ));

        // Add an email element
        $this->addElement('text', 'email', array(
        	'description'=> 'Your email is used for login and will be kept private.',
            'label'      => 'Enter email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                array('validator' => 'StringLength', 'options' => array(6, 255))
            )
        ));
        
        /*$this->addElement('text', 'confirm_email', array(
        	'description'=> 'Please confirm your email.',
            'label'      => 'Confirm email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));*/

        // Add the comment element
        $this->addElement('password', 'password', array(
        	'description'=> 'Please choose a login password.',
            'label'      => 'Choose Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 16))
                )
        ));
        
        $this->addElement('password', 'confirm_password', array(
        	'description'=> 'Please confirm password.',
            'label'      => 'Confirm Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 16))
                )
        ));
        
        $confirm_password = $this->getElement('confirm_password');
        $confirm_password->setAttrib('OnChange','registration_confirm_password("confirm_password","password");');
        
        $this->addElement('radio','role',
        	array(
        	'description'=> 'Please select account type.',
            'label'      => 'Please register me as:',
            'required'   => true
        	)
        );
        
        $role = $this->getElement('role');
        $role->addMultiOptions( array(
        1=>'Job Seeker',
        2=>'Emloyer',
        3=>'Recruitment Agency'
        ) );
        

        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Image',
        		'font'	  => APPLICATION_PATH.'/../fonts/arial.ttf',
        		'DotNoiseLevel'=>'1',
        		'LineNoiseLevel'=>'1', 
                'wordLen' => 5, 
                'timeout' => 300
            ),
            'label'      => 'Please enter the 5 letters displayed below:'
            
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Register Please',
        ));

        // And finally add some CSRF protection
        /*$this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));*/
	}
	
	public function isValid($data)
	{
		$isValid = parent::isValid($data);
		$password = $this->getElement('password');
		$confirm_password = $this->getElement('confirm_password');
		
		if($password->getValue() != $confirm_password->getValue() && $isValid) //true && true
		{
			$isValid = false;
			$confirm_password->addError("Password didn't match.");			
		}
		
		
		return  $isValid;
	}
}