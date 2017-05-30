<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\RegisterController;
use Application\Controller\LoginController;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {        
        $e->getApplication()->getServiceManager()->get('translator');
        
        $eventManager        = $e->getApplication()->getEventManager();
        $viewModel = $e->getViewModel();
        $authService = new AuthenticationService();
        $isUserLogged = $authService->hasIdentity();
        $loggedUser = $authService->getIdentity();
        $viewModel->isUserLogged = $isUserLogged;
        $viewModel->loggedUser = $loggedUser;
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, function(MvcEvent $event) use ($isUserLogged){
                
                $controller = $event->getTarget();
                $controllerName = $event->getRouteMatch()->getParam('controller', null);
                $controllerName .= 'Controller';
                $actionName = $event->getRouteMatch()->getParam('action', null);
                
                if($controllerName == RegisterController::class 
                    && $actionName == 'confirm'
                    &&!$event->getRequest()->getServer('HTTP_REFERER')) {
                        
                    $controller->redirect()->toRoute('home');
                }
                
                if($isUserLogged) {           
                    $skipControllers  = array(
                        RegisterController::class,
                        LoginController::class
                    );
                    
                    if(in_array($controllerName, $skipControllers)) {                            
                        $controller->redirect()->toRoute('home');   
                    }
                    
                }
                
            }, 100);
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);
        
       var_dump($controllerName);
       var_dump($actionName);
    }
}
