<?php
namespace Application\Model;

use Application\Model\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    protected $testData = array(
        'id'     => 123,
        'email' => 'test email',
        'name'  => 'test name', 
    );
    
    public function testCanCreateUserObject()
    {
        $user = new User();
        
        $this->assertInstanceOf(User::class, $user);
    }
    
    public function testUserInitialState()
    {
        $user = new User();
        
        $nullData = array_keys($this->testData);
        
        foreach($nullData as $key) {
            $this->assertNull(
                $user->{$key},
                '"{$key}" should be null'
                );
        }
    }
    
    public function testExchangeArray()
    {
        $user = new User();        
        $user->exchangeArray($this->testData);
        
        foreach($this->testData as $key => $value) {
            
            $this->assertSame(
                $value,
                $user->{$key},
                "{$key} was not set correctly"
            );
        }
        
    }
    
    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $user = new User();
        
        $user->exchangeArray($this->testData);
        $user->exchangeArray(array());
        
        foreach($this->testData as $key => $value) {
            $this->assertNull(
                $user->{$key},
                "{$key} should be null"
                );
        }
     
    }

}