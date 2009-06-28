<?php
class Form_User_Login extends Zend_Form
{
	public function init(){
		$this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'email', array(
        	'description'=> 'Your email is used for login and will be kept private.',
            'label'      => 'Your email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the comment element
        $this->addElement('password', 'password', array(
        	'description'=> 'Please choose a login password.',
            'label'      => 'Choose Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 16))
                )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Login',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
		
	}
}