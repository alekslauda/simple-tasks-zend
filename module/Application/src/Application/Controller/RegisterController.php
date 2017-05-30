<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\RegisterForm;
use Application\Form\RegisterFilter;
use Application\Model\User;
use Application\Model\UserTable;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\Db\NoRecordExists;
use Zend\Form\Form;


class RegisterController extends AbstractActionController
{
    protected function processForm(Form $form)
    {
        $request = $this->getRequest();
        $error = false;
        
        if($request->isPost()) {
            
            $post = $request->getPost();
            $inputFilter = new RegisterFilter();
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => NoRecordExists::class,
                        'options' => array(
                            'table' => 'user',
                            'field' => 'email',
                            'adapter' => $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'),
                            'message' => 'Email address already in use'
                        )
                    ),
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'domain' => true,
                        ),
                    )
                )
            ));
            $form->setInputFilter($inputFilter);
            $form->setData($post);
            if ($form->isValid()) {
                //create user and redirect
                $this->createUser($form->getData());
                return $this->redirect()->toRoute('register/confirm');
            } else {
                $error = true;
            }
        }
        $viewModel = new ViewModel(array(
            'error' => $error,
            'form' => $form
        )
            );
        return $viewModel;
    }
    
    public function indexAction()
    {
        return $this->processForm(new RegisterForm());
    }
    public function confirmAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }
    
    protected function createUser(array $data)
    {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
        
        $resultSetPrototype->setArrayObjectPrototype(new
            \Application\Model\User);
        $tableGateway = new \Zend\Db\TableGateway\TableGateway('user',$dbAdapter, null, $resultSetPrototype);
        $user = new User();
        $user->exchangeArray($data);
        $userTable = new UserTable($tableGateway);
        $userTable->saveUser($user);
        return true;
    }
}