<?php

require_once 'controller/Endpoints/ShipperEndpoint.php';
require_once 'RESTConstants.php';
require_once 'controller/APIException.php';

/**
 * Class ShipperTest
 *
 * Testing functionality of ShipperEndpoint and by extention ShipmentModel
 *
 * @author Even B. Bøe
 */
class ShipperTest extends \Codeception\Test\Unit
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
     * Testing valid request. Expecting to pass
     */
    public function testValidRequest() {
        $uri = ['ship', '2'];
        $endpointPath = '/ship';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array();
        $payload = array();

        $model = new ShipperEndpoint();
        $res = 0;
        try {
            $res = $model->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        } catch (APIException $e) {
            $this->tester->fail('APIException not expected');
        }

        $this->tester->assertIsArray($res);
        $values = array('number' => '2', 'store_name' => 'XXL Sport', 'shipping_address' => 'Vegvegen 0 0000By', 'state' => 'shipped', 'driver_id' => '2', 'repNo' => '1');
        foreach ($values as $k => $val) {
            $this->tester->assertArrayHasKey($k, $res);
            $this->tester->assertEquals($res[$k], $val);
        }

        $this->tester->seeNumRecords(2, 'Shipment_transition_log');
        $this->tester->seeInDatabase('Shipment_transition_log', ['log_number' => 2, 'shipment_number' => 2]);
    }

    /**
     * Testing that endpoint thows APIException when uri is not correct
     */
    public function testInvalidRequestURI() {
        $uri = ['ship'];
        $endpointPath = '/ship';
        $requestMethod = RESTConstants::METHOD_PATCH;
        $queries = array();
        $payload = array();

        $model = new ShipperEndpoint();
        try {
            $model->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('APIException was expected');
        } catch (APIException $e) {
            $this->tester->assertEquals($e->getCode(), RESTConstants::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Testing that endpoint throws APIException when method is not correct
     */
    public function testInvalidMethod() {
        $uri = ['ship', '2'];
        $endpointPath = '/ship';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries = array();
        $payload = array();

        $model = new ShipperEndpoint();
        try {
            $model->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('APIException was expected');
        } catch (APIException $e) {
            $this->tester->assertEquals($e->getCode(), RESTConstants::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}