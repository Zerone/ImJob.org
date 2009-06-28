<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoLoad(){
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
		'namespace'=>'',
		'basePath'=>APPLICATION_PATH
		));

		return $moduleLoader;
	}

	function _initViewHelpers()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
		$view->headTitle()->setSeparator(' - ');
		$view->headTitle('Nexus for job seekers, employer and employment agencies - IM Job.org');
		
		
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		
		$jquery = $view->jQuery();
		$jquery->setLocalPath('/js/jquery/js/jquery-1.3.2.min.js');
		$jquery->setUiLocalPath('/js/jquery/js/jquery-ui-1.7.2.custom.min.js');
		$jquery->addStylesheet('/js/jquery/css/smoothness/jquery-ui-1.7.2.custom.css');
		$jquery->enable();
		$jquery->uiEnable();
	}
	
	function _initSession()
	{
		Zend_Session::start();
		$globalSession = new Zend_Session_Namespace('dlo');
		if(!Zend_Registry::isRegistered('dlo.session')){
			Zend_Registry::set('dlo.session',$globalSession);
		}
	}
	
	function _initMail()
	{
		$config = array('auth' => 'login',
                'username' => 'webmaster@chatbazaar.com',
                'password' => '82YJMv3');
		$mailTransport = new Zend_Mail_Transport_Smtp('mail.chatbazaar.com',$config);
		Zend_Mail::setDefaultTransport($mailTransport);
	}
}

