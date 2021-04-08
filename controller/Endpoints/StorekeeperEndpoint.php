<?php

require_once 'RESTConstants.php';
require_once 'db/db_models/SkiModel.php';

/**
 * Class StorekeeperEndpoint
 *
 * @author Amund Helgestad
 */
class StorekeeperEndpoint extends RequestHandler {

    public function __construct()
    {
        parent::__construct();

        //$this->validRequests[] = RESTConstants::ENDPOINT_STOREKEEPER;
        //$this->validMethods[RESTConstants::ENDPOINT_STOREKEEPER] = array();
        $this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;
    }

    /**
     * Handles POST requests to the storekeeper endpoint at http://localhost/storekeeper/ski
     *
     * @param array $uri  the link used
     * @param string $endpointPath  the endpoint that handled this request
     * @param string $requestMethod the request method; GET, POST, PUT, etc..
     * @param array $queries
     * @param array $payload the 'body' of a request, if any.
     * @return array an array with a response.
     * @throws APIException if anything goes wrong.
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {

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
     * Attempts to add a new ski to database
     * @param array $payload - the resources and attributes for the new ski
     * @return array - an array of the resulting ski with all its registered attributes,
     * including its product number, which is automatically assigned.
     * @throws APIException - if the param payload cannot be added as a ski, it throws an APIException
     */
    protected function doAddSkiToDB(array $payload): array{

        return (new SkiModel())->addSkiToDB($payload);
    }


}