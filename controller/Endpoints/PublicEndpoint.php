<?php

require_once 'RESTConstants.php';
require_once 'db/db_models/SkiTypeModel.php';

/**
 * Class PublicEndpoint
 *
 * @author Amund Helgestad
 */
class PublicEndpoint extends RequestHandler {


    public function __construct()
    {
        parent::__construct();

        //$this->validRequests[] = RESTConstants::ENDPOINT_PUBLIC;
        //$this->validMethods[RESTConstants::ENDPOINT_PUBLIC] = array();
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
    }
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {

        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' not allowed');
        }
        if (count($uri) == 0) {

            if (isset($queries['model']) && isset($queries['grip'])) {
                $ski_model = $queries['model'];
                $grip_system = $queries['grip'];
                return $this->doGetSkiTypeByModelAndGrip($ski_model, $grip_system);
            } else if (isset($queries['model'])) {
                $ski_model = $queries['model'];
                return $this->doGetSkiTypeByModel($ski_model);
            } else if (isset($queries['grip'])) {
                $grip_system = $queries['grip'];
                return $this->doGetSkiTypeByGrip($grip_system);
            } else {
                return $this->doGetAllSkiTypes();
            }
        } else {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');
        }

    }


    /**
     * Attempts to get all ski types in database that has the specified model.
     * @param string $ski_model the model to search for
     * @return array response with all the matches.
     */
    protected function doGetSkiTypeByModel(string $ski_model): array {
        $model = new SkiTypeModel();
        return $model->getSkiTypesWithModelFilter($ski_model);
    }

    /**
     * Attempts to get all ski types in database that has the specified grip system.
     * @param string $grip_system the specified grip system
     * @return array response with the result.
     */
    protected function doGetSkiTypeByGrip(string $grip_system): array {
        return (new SkiTypeModel())->getSkiTypesWithGripFilter($grip_system);
    }

    /**
     * Attempts to get all ski types in database that has both the specified ski model AND the grip system.
     * @param string $ski_model the specified ski model.
     * @param string $grip_system the specified grip system.
     * @return array response with the result.
     */
    protected function doGetSkiTypeByModelAndGrip(string $ski_model, string $grip_system): array {
        return (new SkiTypeModel())->getSkiTypesWithModelGripFilter($ski_model, $grip_system);
    }

    /**
     * Retrieves all the ski types that are currently in the database.
     * @return array response with the result.
     */
    protected function doGetAllSkiTypes(): array {
        $model = new SkiTypeModel();
        return $model->getAllSkiTypes();
    }
}
