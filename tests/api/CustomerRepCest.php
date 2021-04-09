<?php

/**
 * Class CustomerRepCest
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/tests/api/DealersRetrievalCest.php
 *   By Rune Hjelsvold
 *
 * @author Even B. Bøe
 */
class CustomerRepCest
{
    public function _before(ApiTester $I)
    {
    }

    public function testGetNewOrders(ApiTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/json');

        // https://stackoverflow.com/questions/36334244/rest-module-get-set-cookies/36335651
        $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'rep');
        $I->getClient()->getCookieJar()->set($cookie);

        $I->sendGet('/rep/orders?state=new');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();

        $I->assertEquals(2, count(json_decode($I->grabResponse())));
    }

    public function testRepBadAuthToken(ApiTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/json');

        $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'hgkjhgkgjfjhgfgv');
        $I->getClient()->getCookieJar()->set($cookie);

        $I->sendGet('/shipper/ship/2');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'Status' => 'integer',
            'Message' => 'string',
            'Instance' => 'string'
        ]);

        $I->seeResponseContainsJson(['Status' => \Codeception\Util\HttpCode::FORBIDDEN]);
    }

    public function testBadRequestForOrders(ApiTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/json');

        $cookie = new Symfony\Component\BrowserKit\Cookie('auth_token', 'rep');
        $I->getClient()->getCookieJar()->set($cookie);

        $I->sendGet('/rep/orders?state=ne'); // ne instead of new
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'Status' => 'integer',
            'Message' => 'string',
            'Instance' => 'string'
        ]);

        $I->seeResponseContainsJson(['Status' => \Codeception\Util\HttpCode::BAD_REQUEST]);
    }
}
