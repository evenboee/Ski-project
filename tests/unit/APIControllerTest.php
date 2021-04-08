<?php

require_once 'controller/APIController.php';
require_once 'RESTConstants.php';
require_once 'controller/APIException.php';

class APIControllerTest extends \Codeception\Test\Unit
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

    public function testNonExistentPath() {

    }
}
