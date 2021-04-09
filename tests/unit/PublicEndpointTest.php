<?php

require_once 'controller/Endpoints/PublicEndpoint.php';
require_once 'RESTConstants.php';
class PublicEndpointTest extends \Codeception\Test\Unit
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
     * Positive test
     * @throws APIException
     */
    public function testWaxFilter()
    {
        $uri = [];
        $endpointPath = '/public';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries['grip'] = 'wax';
        $payload = array();

        $endpoint = new PublicEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertCount(3, $res);


        foreach ($res as $value) {
            $this->tester->assertArrayHasKey('model', $value);
            $this->tester->assertArrayHasKey('skiing_type', $value);
            $this->tester->assertArrayHasKey('description', $value);
            $this->tester->assertArrayHasKey('grip_system', $value);
            $this->tester->assertArrayHasKey('historical', $value);
            $this->tester->assertArrayHasKey('temperature', $value);
            $this->tester->assertArrayHasKey('url', $value);
            $this->tester->assertArrayHasKey('size', $value);
            $this->tester->assertArrayHasKey('weight_class', $value);
            $this->tester->assertArrayHasKey('MSRP', $value);

            $this->tester->assertEquals("wax", $value['grip_system']);
        }

    }


    /**
     * Positive test
     * @throws APIException
     */
    public function testModelFilter()
    {
        $uri = [];
        $endpointPath = '/public';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries['model'] = 'Redline';
        $payload = array();

        $endpoint = new PublicEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertCount(2, $res);


        foreach ($res as $value) {
            $this->tester->assertArrayHasKey('model', $value);
            $this->tester->assertArrayHasKey('skiing_type', $value);
            $this->tester->assertArrayHasKey('description', $value);
            $this->tester->assertArrayHasKey('grip_system', $value);
            $this->tester->assertArrayHasKey('historical', $value);
            $this->tester->assertArrayHasKey('temperature', $value);
            $this->tester->assertArrayHasKey('url', $value);
            $this->tester->assertArrayHasKey('size', $value);
            $this->tester->assertArrayHasKey('weight_class', $value);
            $this->tester->assertArrayHasKey('MSRP', $value);

            $this->tester->assertEquals("Redline", $value['model']);
        }

    }


    /**
     * Positive test
     * @throws APIException
     */
    public function testModelGripFilter(){
        $uri = [];
        $endpointPath = '/public';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries['model'] = 'Frogger';
        $queries['grip'] = 'wax';
        $payload = array();

        $endpoint = new PublicEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertCount(1, $res);

        $this->tester->assertArrayHasKey('model', $res[0]);
        $this->tester->assertArrayHasKey('skiing_type', $res[0]);
        $this->tester->assertArrayHasKey('description', $res[0]);
        $this->tester->assertArrayHasKey('grip_system', $res[0]);
        $this->tester->assertArrayHasKey('historical', $res[0]);
        $this->tester->assertArrayHasKey('temperature', $res[0]);
        $this->tester->assertArrayHasKey('url', $res[0]);
        $this->tester->assertArrayHasKey('size', $res[0]);
        $this->tester->assertArrayHasKey('weight_class', $res[0]);
        $this->tester->assertArrayHasKey('MSRP', $res[0]);

        $this->tester->assertEquals("Frogger", $res[0]['model']);
        $this->tester->assertEquals("wax", $res[0]['grip_system']);
        $this->tester->assertEquals("double pole", $res[0]['skiing_type']);
        $this->tester->assertEquals("1", $res[0]['historical']);
        $this->tester->assertEquals("cold", $res[0]['temperature']);
        $this->tester->assertEquals("30-40", $res[0]['weight_class']);
    }

    public function testBadRequestPublicEndpoint() {
        $uri = [];
        $endpointPath = '/public';
        $requestMethod = RESTConstants::METHOD_POST;
        $queries['model'] = 'Redline';
        $payload = array();

        $endpoint = new PublicEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Public endpoint failed to throw exception');
        } catch (APIException $e) {}
    }

    public function testNonExistingModelFilter(){
        $uri = [];
        $endpointPath = '/public';
        $requestMethod = RESTConstants::METHOD_GET;
        $queries['model'] = 'Grege';
        $payload = array();

        $endpoint = new PublicEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertCount(0, $res);
    }





}