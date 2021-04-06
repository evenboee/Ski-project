<?php


require_once 'db/DB.php';

class SkiModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    //TODO: ADD model parameter
    public function addSkiToDB(int $size, string $weight){

        $query = 'INSERT INTO ski (size, weight) VALUES (:size, :weight)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':size', $size);
        $stmt->bindValue(':weight', $weight);
        $stmt->execute();


    }


    function verifyResource(array $resource, bool $ignoreId = false): array
    {

        //TODO:
        return array("");
    }


}