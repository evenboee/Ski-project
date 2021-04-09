<?php

class CustomerEndpointTest extends \Codeception\Test\Unit
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

    /**
     * Attempts to get an existing production plan from a valid request. Should not cause any errors.
     * The resulting response is then also checked if it contains all the neccessary data.
     *
     * @throws APIException if this is thrown, the test has failed. However, the test may fail even if there is no thrown @APIException.
     */
    public function testValidProductionPlanRequest()
    {
        $uri = array('production-plan','3');
        $endpointPath = '/customer';
        $requestMethod = RESTConstants::METHOD_GET;
        $payload = array();


        $queries = array();
        $endpoint = new CustomerEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertArrayHasKey('result', $res);
        $this->tester->assertArrayHasKey('id', $res['result']);
        $this->tester->assertArrayHasKey('start_date', $res['result']);
        $this->tester->assertArrayHasKey('end_date', $res['result']);
        $this->tester->assertArrayHasKey('plans', $res['result']);
        $this->tester->assertArrayHasKey('status', $res);

        $this->tester->assertEquals('3', $res['result']['id']);
        $this->tester->assertIsArray($res['result']['plans'], 'plans attribute is array - correct!');

        $this->tester->assertEquals(RESTConstants::HTTP_OK, $res['status']);
    }


    /**
     * Attempts to retrieve a production plan that does not exist in database. Should result in a @APIException,
     * which is caught. If not, the test will fail.
     */
    public function testNonExistingProductionPlanRequest(){
        $uri = array('production-plan','4');
        $endpointPath = '/customer';
        $requestMethod = RESTConstants::METHOD_GET;
        $payload = array();

        $queries = array();
        $endpoint = new CustomerEndpoint();
        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Customer endpoint failed to throw exception when non-existing production plan was requested.');
        } catch (APIException $e){}
    }

    /**
     * Attempts to send a request to the customer endpoint by an incorrect uri (bad request). Should result in a @APIException,
     * which is caught. If not, the test will fail.
     */
    public function testBadProductionPlanRequest(){
        $uri = array('production-p','2');
        $endpointPath = '/customer';
        $requestMethod = RESTConstants::METHOD_GET;
        $payload = array();

        $queries = array();
        $endpoint = new CustomerEndpoint();
        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Customer endpoint failed to throw exception when receiving bad request.');
        } catch (APIException $e){}
    }


    /**
     * Attempts to request response from customer endpoint with invalid method PUT.
     * Test fails, if a @APIException is not thrown.
     */
    public function testInvalidMethod(){
        $uri = array('production-plan','3');
        $endpointPath = '/customer';
        $requestMethod = RESTConstants::METHOD_PUT;
        $payload = array();


        $queries = array();
        $endpoint = new CustomerEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Customer endpoint failed to throw exception when invalid method PUT was requested.');
        } catch (APIException $e) {}
    }


    /**
     * Attempts to post a new order, which should be valid and accepted.
     * If any exception is thrown, such as an @APIException, the test will fail.
     *
     * @throws APIException if it fails, but test may fail without throwing this particular exception.
     */
    public function testCreateValidOrder(){
        $uri = array('order','new');
        $endpointPath = '/customer/new';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['customer_id'] = 1;
        $payload['weight'] = "40-50";
        $payload['size'] = 165;
        $payload['model'] = "Fisher";
        $payload['quantity'] = 3;

        // 1 of this particular ski type should cost 2100

        $queries = array();
        $endpoint = new CustomerEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertArrayHasKey('result', $res);
        $this->tester->assertArrayHasKey('status', $res);
        $this->tester->assertEquals(RESTConstants::HTTP_CREATED, $res['status']);
        $this->tester->assertEquals(6300, $res['result']['total_price']); // 3 * 2100 should be 6300
    }

    /**
     * Attempts to do a valid create order request with an invalid HTTP request method.
     *
     * If an @APIException is not thrown, the test will fail.
     */
    public function testInvalidMethodToCreateOrder(){
        $uri = array('order','new');
        $endpointPath = '/customer/new';
        $requestMethod = RESTConstants::METHOD_GET;
        $payload['customer_id'] = 1;
        $payload['weight'] = "40-50";
        $payload['size'] = 165;
        $payload['model'] = "Fisher";
        $payload['quantity'] = 3;


        $queries = array();
        $endpoint = new CustomerEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('customer/order/new endpoint failed to throw exception when invalid method GET was requested.');
        } catch (APIException $e) {}
    }

    /**
     * Attempts to request the creation of a new order with a 0 in quantity. This, similar to negative values, should
     * not be accepted and should result in a @APIException being thrown. If not, the test fails.
     */
    public function testInvalidQuantityInCreateOrder(){
        $uri = array('order','new');
        $endpointPath = '/customer/new';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['customer_id'] = 1;
        $payload['weight'] = "40-50";
        $payload['size'] = 165;
        $payload['model'] = "Fisher";
        $payload['quantity'] = 0;


        $queries = array();
        $endpoint = new CustomerEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('customer/order/new endpoint failed to throw exception when invalid order with quantity 0 was requested to be created.');
        } catch (APIException $e) {}
    }

    /**
     * Tests that the system does not create an order of a ski type that is not registered in the database.
     * If there currently are instances of type ski of that ski-type or not, doesnt matter.
     *
     */
    public function testCreateOrderOfNonExistingSkiType(){
        $uri = array('order','new');
        $endpointPath = '/customer/new';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['customer_id'] = 1;
        $payload['weight'] = "40-50";
        $payload['size'] = 170;
        $payload['model'] = "Frogger";
        $payload['quantity'] = 2;


        $queries = array();
        $endpoint = new CustomerEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('customer/order/new endpoint failed to throw exception when order with invalid ski type was requested.');
        } catch (APIException $e) {}
    }


    /**
     * Positive test that should successfully delete a given order by its order_number.
     * @throws APIException
     */
    public function testDeleteExistingOrder(){
        $uri = array('order','3');
        $endpointPath = '/customer/order';
        $requestMethod = RESTConstants::METHOD_DELETE;
        $payload['customer_id'] = 2;


        $queries = array();
        $endpoint = new CustomerEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertArrayHasKey('result', $res);
        $this->tester->assertArrayHasKey('status', $res);

        $this->tester->assertEquals(RESTConstants::HTTP_OK, $res['status']);
        $this->tester->assertEquals("4", $res['result']['order_number']);
        //$this->tester->assertEquals("success", $res['result']['deletion']); // TODO: This particular message may be changed in the future.
    }


    /**
     * Tests if system throws an @APIException when customer tries to delete an order that does not exist.
     *
     * Test fails if no @APIException is thrown.
     */
    public function testDeleteNonExistingOrder(){
        $uri = array('order','5');
        $endpointPath = '/customer/order';
        $requestMethod = RESTConstants::METHOD_DELETE;
        $payload['customer_id'] = 2;


        $queries = array();
        $endpoint = new CustomerEndpoint();

        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('customer/order/5 endpoint failed to throw exception when requesting the deletion of non existing order.');
        } catch (APIException $e) {}
    }

    /**
     * Tests if system throws an @APIException when wrong customer id is
     * passed for that particular order when trying to delete that order.
     */
    public function testDeleteExistingOrderByInvalidCustomerId(){
        $uri = $uri = array('order','1');; // order with order number 1
        $endpointPath = '/customer/order';
        $requestMethod = RESTConstants::METHOD_DELETE;
        $payload['customer_id'] = 2; // This customer did not create order 1


        $queries = array();
        $endpoint = new CustomerEndpoint();

        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('customer/order/1 endpoint failed to throw exception when accompanied customer id did not match the specified order.');
        } catch (APIException $e) {}
    }

}