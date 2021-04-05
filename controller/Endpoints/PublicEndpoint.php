<?php

class PublicEndpoint extends RequestHandler {

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
      // TODO: Add body...
        //echo '"message": "test"';
        return array("dei","hei");

    }
}
