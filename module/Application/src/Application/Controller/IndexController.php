<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\UserTable;

class IndexController extends AbstractActionController
{
    
    public function indexAction()
    {  
        $users = $this->layout()->isUserLogged ? $this->getUserTable()->getAllUsers() : false;
        return new ViewModel(array(
            'users' => $users)
        );
    }
    
    protected function getUserTable()
    {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
        
        $resultSetPrototype->setArrayObjectPrototype(new
            \Application\Model\User);
        $tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
        return new UserTable($tableGateway);
    }
}
