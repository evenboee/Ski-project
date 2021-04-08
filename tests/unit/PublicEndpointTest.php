<?php

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

    // tests
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


    // tests
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
}