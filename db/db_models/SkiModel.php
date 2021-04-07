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
     * @return array - an array with a http code and sometimes a message explaining the error, if any. It is solely used for debugging reasons and nothing else.
     */
    function verifySkiResource(array $resource): array
    {

        $res = array();
        if (count($resource) != 3) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "There should be exactly 3 values.";
            return $res;
        }

        if (!array_key_exists('size', $resource) || !array_key_exists('weight', $resource) || !array_key_exists('model', $resource)) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "One or more attributes are missing.";
            return $res;
        }

        if (!$this->doesSkiTypeExist($resource['model'], $resource['size'], $resource['weight'])) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Specified ski does not match any existing ski types!";
            return $res;
        }

        $res['code'] = RESTConstants::HTTP_OK;
        return $res;
    }


    /**
     * Checks whether or not the param model_name string is the same as an existing ski model in database.
     * Returns true if it exists and false if not
     * @param string $model_name
     * @return bool
     */
    protected function doesSkiModelExist(string $model_name): bool {
        $query = 'SELECT COUNT(*) FROM ski_model WHERE model = :model ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model_name);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        }

        return true;
    }


    /**
     * Checks whether or not the combination of model_name size and weight corresponds to an existing ski type.
     * This check is necessary to perform before adding records of a new ski, as a ski can only be an instance of an existing ski type.
     * @param string $model_name - the ski_model name
     * @param string $size - the size, should be an integer number
     * @param string $weight - the weight class, should be a range between two integer numbers (f.ex: "30-40")
     * @return bool
     */
    protected function doesSkiTypeExist(string $model_name, string $size, string $weight): bool {
        $query = 'SELECT COUNT(*) FROM ski_type WHERE model = :model AND weight_class = :weight AND size = :size';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model_name);
        $stmt->bindValue(':weight', $weight);
        $stmt->bindValue(':size', $size);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        }

        return true;


    }
}