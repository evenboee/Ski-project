<?php

require_once 'db/db_models/AuthorizationModel.php';

/**
 * Class AuthorizationTest
 *
 * Testing functionality of AuthorizationModel - Getting role from token
 * Note: Test database uses simplified tokens. For physical model (skies.sql) tokens are longer
 *
 * @author Even B. Bøe
 */
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

    public function testValidToken() {
        $model = new AuthorizationModel();
        $roles = ['rep', 'storekeeper', 'shipper'];
        foreach ($roles as $role) {
            $this->tester->assertEquals($role, $model->getRole($role)); // Tokens in test database are simplified
        }
    }

    public function testInvalidToken() {
        $model = new AuthorizationModel();
        $role = 'dhslkjh';
        $this->tester->assertNotEquals($role, $model->getRole($role));
    }
}
