<?php

require_once 'RESTConstants.php';
require_once 'db/db_models/SkiModel.php';

class StorekeeperEndpoint extends RequestHandler {

    public function __construct()
    {
        parent::__construct();

        $this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;
    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {


        // TODO: ...
        // http://localhost/storekeeper/ski

        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' not allowed');
        }

        if ($uri[0] == RESTConstants::ENDPOINT_SKI) {
            if (count($uri) == 1) {
                $res['result'] = $this->doAddSkiToDB($payload);
                $res['status'] = RESTConstants::HTTP_CREATED;
                return $res;

            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');
            }
        }

         throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint of storekeeper not found');


    }

    /**
     * @param array $payload
     * @return array
     * @throws APIException
     */
    protected function doAddSkiToDB(array $payload): array{

        return (new SkiModel())->addSkiToDB($payload);
    }


}