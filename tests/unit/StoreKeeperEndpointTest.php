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
        $uri = 'ski';
        $endpointPath = '/storekeeper';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['size'] = 150;
        $payload['weight'] = "40-50";
        $payload['model'] = "Redline";


        $queries = '';
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
     * Has an empty uri, which should normally be 'ski'. This should not be accepted, and should throw an @APIException.
     * Test fails if no @APIException has been thrown.
     */
    public function testRequestToMalformedEndpoint(){

        $uri = '';
        $endpointPath = '/storekeeper';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['size'] = 150;
        $payload['weight'] = "40-50";
        $payload['model'] = "Redline";


        $queries = '';
        $endpoint = new StorekeeperEndpoint();
        try {
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Production planner endpoint failed to throw exception when request method GET is invalid/not implemented.');
        } catch (APIException $e){}


    }
}