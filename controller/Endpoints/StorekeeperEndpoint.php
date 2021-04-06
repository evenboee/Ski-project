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
                $json = file_get_contents('php://input');
                $data = json_decode($json);

                // TODO: Check if $data actually has variables called size and weight before trying to get their values
                if (is_int($data->size) && is_string($data->weight) && is_string($data->model)){
                    return $this->doAddSkiToDB($data->size, $data->weight, $data->model);
                } else {
                    throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath,'Malformed request body. One or more of produktnummer (int), size (int) and weight (string) is not their required type or does not exist!');
                }


            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');
            }
        }

         throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint of storekeeper not found');


    }


    protected function doAddSkiToDB(int $size, string $weight, string $ski_model): array{

        $model = new SkiModel();
        $model->addSkiToDB($size, $weight, $ski_model);
        return array("Added ski to database");
    }


}