<?php

require_once 'controller/CustomerRepEndpoint.php';
require_once 'RESTConstants.php';

class CustomerRepEndpointTest extends \Codeception\Test\Unit
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

    public function testHandleRequest() {
        $uri = ['orders', 'new'];
        $endpointPath = '/orders/new';
        $requestMethod = RESTConstants::METHOD_PUT;
        $queries = array();
        $payload = array();

        $endpoint = new CustomerRepEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertCount(2, $res);
        $this->tester->assertIsArray($res);
        $this->tester->assertEquals($res[0]['order_number'], 1);
        $this->tester->assertEquals($res[1]['order_number'], 3);
    }
}
