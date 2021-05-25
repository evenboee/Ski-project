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
        $roles = [['rep', '97a134dbbcbecefa823f6ca3cfb68d3c84899cd8'], ['storekeeper', '5627b0ea56d96c8d0a1da0bf7816ae6df9e0277d'], ['shipper', '99211ca0bba8148f1800715fd959fe64931da9df'], ['', '']];
        foreach ($roles as $role) {
            $this->tester->assertEquals($role[0], $model->getRole($role[1]));
        }
    }

    public function testInvalidToken() {
        $model = new AuthorizationModel();
        $role = 'dhslkjh';
        $this->tester->assertNotEquals($role, $model->getRole($role));
    }
}
