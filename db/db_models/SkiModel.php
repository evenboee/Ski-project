<?php


require_once 'db/DB.php';

class SkiModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    //TODO: ADD model parameter
    public function addSkiToDB(int $size, string $weight, string $model){

        $query = 'INSERT INTO ski (size, weight, model) VALUES (:size, :weight, :model)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':size', $size);
        $stmt->bindValue(':weight', $weight);
        $stmt->bindValue(':model', $model);
        $stmt->execute();


    }


    function verifyResource(array $resource, bool $ignoreId = false): array
    {

        //TODO:
        return array("");
    }


}