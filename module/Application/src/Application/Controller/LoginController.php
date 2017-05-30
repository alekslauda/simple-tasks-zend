<?php
namespace Application\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Form\LoginForm;
use Zend\View\Model\ViewModel;
use Application\Form\LoginFilter;
use Zend\Form\Form;

class LoginController extends AbstractActionController{

    protected $authService;
    
    protected function processForm(Form $form)
    {         
        $error = false;
        $request = $this->getRequest();
        
        if ($request->isPost()){
            
            $inputFilter = new LoginFilter();
            $form->setInputFilter($inputFilter);
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                
                $this->getAuthService()->getAdapter()
                ->setIdentity($request->getPost('email'))
                ->setCredential($request->getPost('password'));
                
                $result = $this->getAuthService()->authenticate();
                //check authentication...
                if ($result->isValid()) {
                    
                    $this->getAuthService()->getStorage()->write($request->getPost('email'));
                    
                    $this->flashMessenger()
                        ->setNamespace('login')
                        ->addMessage('Congratulations. You have logged in successfuly!');
                    
                    return $this->redirect()->toRoute('home');
                } else {
                    $error = true;
                }
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
        return $this->processForm(new LoginForm());        
    }
    
    protected function getAuthService()
    {
        if (! $this->authService) {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');     
            
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter,
                'user','email','password', 'MD5(?)');
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authService= $authService;
        }
        return $this->authService;
    }
    
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
    
}

