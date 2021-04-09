<?php

require_once 'controller/Endpoints/CustomerRepEndpoint.php';
require_once 'RESTConstants.php';
require_once 'controller/APIException.php';

/**
 * Class CustomerRepEndpointTest
 *
 * Testing functionality of CustomerRepEndpoint and by extention partially testing OrderModel
 *
 * @author Even B. Bøe
 */
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
        $uri = ['order', 'open', '2'];
        $endpointPath = '/order';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array();
        $payload = array('employee_number' => 1);

        $endpoint = new CustomerRepEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertArrayHasKey('result', $res);
        $res = $res['result'];
        $this->tester->assertArrayHasKey('state', $res);
        $this->tester->assertEquals($res['state'], 'open');

        $this->tester->seeNumRecords(4, 'order_log');
        $this->tester->seeInDatabase('order_log', ['log_number' => 4, 'employee_number' => 1, 'order_number' => 2, 'new_state' => 'open']);
    }

    public function testHandleBadOrderRequest() {
        $uri = ['order', 'open'];
        $endpointPath = '/order';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array();
        $payload = array('employee_number' => 1);

        $endpoint = new CustomerRepEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Expected APIException');
        } catch (APIException $e) {
            $this->tester->assertEquals($e->getCode(), RESTConstants::HTTP_BAD_REQUEST);
        }
    }

    public function testHandleNewOrdersRequest() {
        $uri = ['orders'];
        $endpointPath = '/orders';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries = array('state' => 'new');
        $payload = array();

        $endpoint = new CustomerRepEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertArrayHasKey('result', $res);
        $res = $res['result'];

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
        } catch (APIException $e) {}
    }

    /**
     * Test handling of illegal method
     */
    public function testHandleInvalidMethod() {
        $uri = ['orders'];
        $endpointPath = '/orders';
        $requestMethod = RESTConstants::METHOD_DELETE;
        $queries = array('state' => 'new');
        $payload = array();

        $endpoint = new CustomerRepEndpoint();
        try {
            $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        } catch (APIException $e) {
            $this->tester->assertEquals($e->getCode(), RESTConstants::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}
