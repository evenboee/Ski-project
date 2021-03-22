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
        $uri = ['jhfgkdjhkjfhkj'];
        $endpointPath = '';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries = array();
        $payload = array();

        $controller = new APIController();
        try {
            $res = $controller->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail("Expected APIException");
        } catch (APIException $exc) {
            $this->tester->assertEquals(RESTConstants::HTTP_NOT_FOUND, $exc->getCode());
        }
    }
}
