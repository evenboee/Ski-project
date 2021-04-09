<?php

class AuthorizationTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    protected function testValidToken() {
        $model = new AuthorizationModel();
        $roles = ['rep', 'storekeeper', 'shipper'];
        foreach ($roles as $role) {
            $this->tester->assertEquals($role, $model->getRole($role)); // Tokens in test database are simplified
        }
    }

    protected function testInvalidToken() {
        $model = new AuthorizationModel();
        $role = 'dhslkjh';
        $this->tester->assertNotEquals($role, $model->getRole($role));
    }
}
