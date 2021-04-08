<?php


require_once 'db/DB.php';

class SkiTypeModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSkiTypesWithModelFilter(string $model): array
    {
        $res = array();

        $query = 'SELECT model, skiing_type, description, grip_system, historical, temperature, url, size, weight_class, MSRP FROM `ski_model_type_view` WHERE model = :model';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }

    public function getSkiTypesWithGripFilter(string $grip_system): array {
        $res = array();

        $query = 'SELECT model, skiing_type, description, grip_system, historical, temperature, url, size, weight_class, MSRP FROM `ski_model_type_view` WHERE grip_system = :grip_system';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':grip_system', $grip_system);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }

    public function getSkiTypesWithModelGripFilter(string $model, string $grip_system): array{
        $res = array();

        $query = 'SELECT model, skiing_type, description, grip_system, historical, temperature, url, size, weight_class, MSRP FROM `ski_model_type_view` WHERE model = :model AND grip_system = :grip_system';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model);
        $stmt->bindValue(':grip_system', $grip_system);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }




    public function getAllSkiTypes(): array {
        $res = array();
        $query = 'SELECT size, weight_class FROM `ski_type`';
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }
}
