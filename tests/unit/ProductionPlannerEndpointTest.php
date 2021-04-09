<?php

class ProductionPlannerEndpointTest extends \Codeception\Test\Unit
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
     * Tests a valid production plan request is handled properly.
     *
     * @throws APIException should not be thrown; If it does, test has failed.
     */
    public function testAddProductionPlan()
    {
        $uri = array('production-plan');
        $endpointPath = '/production-planner';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['start_date'] = '2020-01-01';
        $payload['planner_id'] = 2;

        $payload['plan'][0]['model'] = 'Redline';
        $payload['plan'][0]['size'] = 140;
        $payload['plan'][0]['weight'] = '20-30';
        $payload['plan'][0]['quantity'] = 4;

        $payload['plan'][1]['model'] = 'Fisher';
        $payload['plan'][1]['size'] = 140;
        $payload['plan'][1]['weight'] = '40-50';
        $payload['plan'][1]['quantity'] = 4;


        $queries = array();
        $endpoint = new ProductionPlannerEndpoint();
        $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
        $this->tester->assertArrayHasKey('result', $res);
        $this->tester->assertArrayHasKey('status', $res);
        $this->tester->assertEquals(RESTConstants::HTTP_CREATED, $res['status']);

        $this->tester->assertArrayHasKey('plan_id', $res['result']);
        $this->tester->assertArrayHasKey('end_date', $res['result']);
        $this->tester->assertArrayHasKey('employee_id', $res['result']);
        $this->tester->assertArrayHasKey('count', $res['result']);

        $this->tester->assertEquals(2, $res['result']['count']);
        $this->tester->assertEquals('2020-01-29', $res['result']['end_date']);
        $this->tester->assertEquals(2, $res['result']['employee_id']);
        $this->tester->assertIsInt($res['result']['plan_id']);
    }

    /**
     * Negative test:
     * Tests if an invalid (or not implemented) method request is used is throwing an @APIException.
     * If no @APIException is thrown, test fails.
     */
    public function testInvalidMethod(){
        $uri = array('production-plan');
        $endpointPath = '/production-planner';
        $requestMethod = RESTConstants::METHOD_GET;
        $payload['start_date'] = '2020-01-01';
        $payload['planner_id'] = 2;

        $payload['plan'][0]['model'] = 'Redline';
        $payload['plan'][0]['size'] = 140;
        $payload['plan'][0]['weight'] = '20-30';
        $payload['plan'][0]['quantity'] = 4;


        $queries = array();
        $endpoint = new ProductionPlannerEndpoint();
        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Production planner endpoint failed to throw exception when request method GET is invalid/not implemented.');
        } catch (APIException $e) {}
    }


    /**
     * Negative test:
     * Test that sends an invalid uri. Should throw an @APIException; If not, test fails.
     */
    public function testBadRequest(){
        $uri = array('produon-plan'); // Should be 'production-plan', but is now malformed.
        $endpointPath = '/production-planner';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['start_date'] = '2020-01-01';
        $payload['planner_id'] = 2;

        $payload['plan'][0]['model'] = 'Redline';
        $payload['plan'][0]['size'] = 140;
        $payload['plan'][0]['weight'] = '20-30';
        $payload['plan'][0]['quantity'] = 4;


        $queries = array();
        $endpoint = new ProductionPlannerEndpoint();
        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Production planner endpoint failed to throw exception when uri was malformed');
        } catch (APIException $e) {}
    }

    /**
     * Negative test:
     * Should throw an @APIException, as the "plan" array now has 2 elements,
     * one missing quantity and one missing everything but quantity.
     */
    public function testInvalidBodyOnRequest(){
        $uri = array('production-plan');
        $endpointPath = '/production-planner';
        $requestMethod = RESTConstants::METHOD_POST;
        $payload['start_date'] = '2020-01-01';
        $payload['planner_id'] = 2;

        $payload['plan'][0]['model'] = 'Redline';
        $payload['plan'][0]['size'] = 140;
        $payload['plan'][0]['weight'] = '20-30';
        $payload['plan'][1]['quantity'] = 4;


        $queries = array();
        $endpoint = new ProductionPlannerEndpoint();
        try{
            $res = $endpoint->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
            $this->tester->fail('Production planner endpoint failed to throw exception when payload was malformed');
        } catch (APIException $e) {}
    }
}