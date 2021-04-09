<?php

class StoreKeeperEndpointTest extends \Codeception\Test\Unit
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
     * Positive test:
     * tests if POST request to add new ski to db is handled properly.
     * @throws APIException should not be thrown; If it does, the test has failed.
     *                      However, test may fail even if no @APIException has been thrown.
     */
    public function testAddSkiToDB()
    {
        $uri = array('ski');
        $endpointPath = '/storekeeper';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['size'] = 150;
        $payload['weight'] = "40-50";
        $payload['model'] = "Redline";


        $queries = array();
        $endpoint = new StorekeeperEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);

        $this->tester->assertArrayHasKey('result', $res);
        $this->tester->assertArrayHasKey('status', $res);

        $this->tester->assertEquals(RESTConstants::HTTP_CREATED, $res['status']);

        $this->tester->assertArrayHasKey('production_number', $res['result']);
        $this->tester->assertArrayHasKey('size', $res['result']);
        $this->tester->assertArrayHasKey('weight', $res['result']);
        $this->tester->assertArrayHasKey('model', $res['result']);

        $this->tester->assertEquals($payload['weight'], $res['result']['weight']);
        $this->tester->assertEquals($payload['model'], $res['result']['model']);
        $this->tester->assertEquals($payload['size'], $res['result']['size']);
    }

    /**
     * Negative test:
     * Has an empty uri, which should normally be 'ski'. This should not be accepted, and should throw an @APIException.
     * Test fails if no @APIException has been thrown.
     */
    public function testRequestToMalformedEndpoint(){

        $uri = array();
        $endpointPath = '/storekeeper';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['size'] = 150;
        $payload['weight'] = "40-50";
        $payload['model'] = "Redline";


        $queries = array();
        $endpoint = new StorekeeperEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Storekeeper endpoint failed to throw exception when malformed uri was send');
        } catch (APIException $e){}
    }

    /**
     * Negative test:
     * Should throw an @APIException as METHOD_DELETE is not allowed here.
     */
    public function testInvalidMethod(){
        $uri = array('ski');
        $endpointPath = '/storekeeper';
        $requestMethod = RESTConstants::METHOD_DELETE;
        $payload['size'] = 150;
        $payload['weight'] = "40-50";
        $payload['model'] = "Redline";


        $queries = array();
        $endpoint = new StorekeeperEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Storekeeper endpoint failed to throw exception when invalid request method was used.');
        } catch (APIException $e){}
    }
}