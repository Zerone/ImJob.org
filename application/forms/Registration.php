<?php
class Form_Registration extends Zend_Form
{
	public function init(){
		$this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'email', array(
        	'description'=> 'Your email is kept private.',
            'label'      => 'Your email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the comment element
        $this->addElement('password', 'password', array(
            'label'      => 'Choose Password:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 16))
                )
        ));

        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Image',
        		'font'	  => APPLICATION_PATH.'/../fonts/arial.ttf',
        		'DotNoiseLevel'=>'5',
        		'LineNoiseLevel'=>'5', 
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
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
		
	}
}