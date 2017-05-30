<?php

namespace ApplicationTest\Controller;

use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use ApplicationTest\Bootstrap;
use Application\Controller\LoginController;
use Application\Controller\RegisterController;
use Zend\Stdlib\Parameters;


class RegisterControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $serviceManager;
    
    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->controller = new RegisterController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'register'));
        $this->event      = new MvcEvent();
        $config = $this->serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);
    }
    
    public function testIndexActionCanBeAccessed()
    {   
        $this->routeMatch->setParam('action', 'index');
        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    public function testRegisterActionWithPostData()
    {
        $this->routeMatch->setParam('action', 'confirm');
        
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        
    }
    
    
    
}