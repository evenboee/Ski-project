<?php


require_once 'db/DB.php';

class SkiModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Attempts to add a ski to database with attributes corresponding to the given $resource
     * @param array $resource - the resource to be added to db if it is ok.
     * @return array - an array containing a response for if the adding was successfull
     * @throws APIException - if something is not correct, it throws such an exception which may contain http error code and a message.
     */
    public function addSkiToDB(array $resource): array {

        //TODO: Make a check to make sure input parameter is ok

        $this->db->beginTransaction();
        $rec = $this->verifySkiResource($resource);
        if ($rec['code'] != RESTConstants::HTTP_OK) {
            $this->db->rollBack();
            if (isset($rec['message'])) {
                throw new APIException($rec['code'], RESTConstants::API_URI, $rec['message']);
            } else {
                throw new APIException($rec['code'], RESTConstants::API_URI, "");
            }
        }




        $res = array();
        $query = 'INSERT INTO ski (size, weight, model) VALUES (:size, :weight, :model)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':size', $resource["size"]);
        $stmt->bindValue(':weight', $resource["weight"]);
        $stmt->bindValue(':model', $resource["model"]);
        $stmt->execute();

        $res['product_number'] = intval($this->db->lastInsertId());
        $res['size'] = $resource['size'];
        $res['weight'] = $resource['weight'];
        $res['model'] = $resource['model'];
        $this->db->commit();

        return $res;
    }


    /**
     * Verifies the given array-resource to make sure it has correct values before it can be added to the database.
     * It checks the size of the array, if it contains correct values and if the given model actually is a model that exists in database.
     * @param array $resource - the given resource to be verified
     * @return array - an array with a http code and sometimes a message explaining the error, if any.
     */
    function verifySkiResource(array $resource): array
    {

        $res = array();
        if (array_count_values($resource) != 3) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Bad request: There should be exactly 3 values.";
            return $res;
        }

        if (!array_key_exists('size', $resource) || !array_key_exists('weight', $resource) || !array_key_exists('model', $resource)) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Bad request: One or more attributes are missing.";
            return $res;
        }

        if (!$this->doesSkiModelExist($resource['model'])) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Bad request: Model does not exist";
            return $res;
        }

        $res['code'] = RESTConstants::HTTP_OK;
        return $res;
    }




    function doesSkiModelExist(string $model_name): bool {
        //TODO: add body...
        return false;
    }
}