<?php

class UserController extends Zend_Controller_Action
{
	 /**
     * FlashMessenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->initView();
        $this->view->messages = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
    	$auth = Zend_Auth::getInstance();
        $request = $this->getRequest();
    	$form = new Form_User_Login();

    	if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
            	$bootstrap = $this->getInvokeArg('bootstrap');
        		$resource = $bootstrap->getPluginResource('db');
        		$dbAdapter = $resource->getDbAdapter();
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
				$authAdapter
				->setTableName('users')
				->setIdentityColumn('email')
				->setCredentialColumn('password')
				->setIdentity($form->getValue('email'))
				->setCredential(hash('ripemd160',$form->getValue('password')))
				;
				
				$authResult = $auth->authenticate($authAdapter);
				
				
				if($authResult->isValid()){
					//$auth->getStorage()->write($adapter->getResultRowObject());
					$userId = $authAdapter->getResultRowObject('id');
					$user = new Model_User();
					$user = $user->find($userId);
					$globalSession = Zend_Registry::get('dlo.session');
					$globalSession->user = $user;
					
					return $this->_helper->redirector('index');
				}else{
					$email = $form->getElement('email');
            		$email->addError("Sorry either entered email or password is incorrect.");
            	}
            }
        }
    	$this->view->form = $form;
    }

    public function registerAction()
    {
        $request = $this->getRequest();
    	$form = new Form_User_Registration();
    	if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                
            	$model = new Model_User($form->getValues());
                $user_id = $model->save();
                $model->setId($user_id);
                
                $globalSession = Zend_Registry::get('dlo.session');
                $globalSession->user = $model;

                //Zend_Loader::loadClass('Zend_View');
                $view = new Zend_View();
                $view->activationLink = "http://DrivingLessonOnline.com/user/verify-email/id/".$model->getId()."/guid/".hash('sha1',$model->getSalt().$model->getId().$model->getPassword())."/";
                $view->setBasePath(APPLICATION_PATH."/views/");
				$mailBodyHtml = $view->render('Templates/Account-Activation-Email.phtml');
				
                //send email verification email before user can start using their account.
                $mail = new Zend_Mail();
                $mail->setBodyHtml($mailBodyHtml);
                $mail->setFrom('no.reply@DrivingLessonOnline.com','Registration');
                $mail->addTo($model->getEmail(),$model->getDisplayName());
                $mail->setSubject($model->getDisplayName().' activiate your account for Driving Lesson Online.com');
                $mail->send();
        
        		//thank user and inform to check their email to enable their account.
                $this->_redirect('/user/registered');

            }
        
        

        }
        
        

        
        
        

    	$this->view->form = $form;
    }

    public function selectRoleAction()
    {
        $globalSession = Zend_Registry::get('dlo.session');
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        
        if($auth->hasIdentity())
        {
        	$auth->clearIdentity();
        }
        
        $this->_flashMessenger->addMessage("You have successfully logged off.");
        $this->_redirect('/user/login');
        
    }

    public function registeredAction()
    {
        $globalSession = Zend_Registry::get('dlo.session');
        
        if(!isset($globalSession->user))
        {
        	$this->_redirect('/user/login');
        	return;
        }
        
        $this->view->user = $globalSession->user;
    }

    public function verifyEmailAction()
    {
        $user_id = (int)$this->getRequest()->getParam('id');
        $guid = (string)$this->getRequest()->getParam('guid');
        
        
        if(!empty($user_id) && !empty($guid))
        {
        	$model = new Model_User();
        	$model = $model->find($user_id);
        	if($model->getEnabled())
        	{
        		$this->_flashMessenger->addMessage("Your account has already been activated, you can now Login.");
        		$this->_redirect('/user/login/');
        		return;
        	}
        	if($guid ==  (hash('sha1',$model->getSalt().$model->getId().$model->getPassword())) )
        	{
        		$model->setEnabled(1);
        		$model->setEmailVerified(1);
        		$model->save();
        		$this->user = $model;
        		
        		$this->_flashMessenger->addMessage("Thanks for activating your account, you can now Login.");
        		$this->_redirect('/user/login/');
        		return;
        		
        	}else
        	{
        		$this->_redirect('/user/register/');
        		return;
        	}
        }else
        {
        	$this->_redirect('/');
        }
    }
}

