<?php

require_once 'controller/Endpoints/CustomerRepEndpoint.php';
require_once 'RESTConstants.php';
require_once 'controller/APIException.php';

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

    public function testHandleOrderRequest() {
        $uri = ['order', 'open', ''];
        $endpointPath = '/order';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array();
        $payload = array();

        $endpoint = new CustomerRepEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        // TODO: Include test for log change
    }

    public function testHandleNewOrdersRequest() {
        $uri = ['orders'];
        $endpointPath = '/orders';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array('state' => 'new');
        $payload = array();

        $endpoint = new CustomerRepEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertCount(2, $res);
        $this->tester->assertIsArray($res);
        $this->tester->assertEquals($res[0]['order_number'], 2);
        $this->tester->assertEquals($res[1]['order_number'], 3);
    }

    public function testHandleBadRequest() {
        $uri = ['orde']; // 'orde' is not an endpoint
        $endpointPath = '/orders';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries = array('state' => 'new');
        $payload = array();
        $endpoint = new CustomerRepEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Customer rep endpoint failed to throw exception');
        } catch (APIException) {}
    }
}
