<?php


require_once 'DB.php';

class SkiTypeModel extends DB
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSkiTypesWithModelFilter($model): array
    {
        $res = array();

        $query = 'SELECT size, weight_class FROM `ski_type` WHERE model = :model';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }
}
