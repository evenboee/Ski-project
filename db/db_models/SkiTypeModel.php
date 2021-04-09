<?php
require_once 'db/DB.php';

/**
 * Class SkiTypeModel
 *
 * @author Amund Helgestad
 */


class SkiTypeModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves a list with all ski types by the given model that exists in database.
     *
     * @param string $model the model
     * @return array response with all the information of each of the ski types that matched the search.
     */
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

    /**
     * Retrieves a list with all ski types by the given grip system that exists in database.
     *
     * @param string $grip_system the grip system
     * @return array response with all the information of each of the ski types that matched the search.
     */
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

    /**
     * Retrieves a list with all ski types by the given grip system and model that exists in database.
     * In other words: the result must be a ski type that has BOTH the specified model AND grip system.
     *
     * @param string $model the model to filter for
     * @param string $grip_system the grip system to filter for
     * @return array response with all the information of each of the ski types that matched the search.
     */
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


    /**
     * Retrieves a list with all ski types in database
     *
     * @return array response with all the information about all of the skies in the database.
     */
    public function getAllSkiTypes(): array {
        $res = array();
        $query = 'SELECT model, skiing_type, description, grip_system, historical, temperature, url, size, weight_class, MSRP FROM `ski_model_type_view`';
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }
}
