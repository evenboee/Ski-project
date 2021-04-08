<?php
require_once 'db/DB.php';

class ProductionPlanModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves the id, start/end dates, and the quantity of each ski_type in a production plan.
     * This information is retrieved from production_plan table and production_plan_reference table.
     *
     * There are currently no authentication.
     *
     * @param string $plan_id the id of the plan to retrieve
     * @return array the response as a json array, if successful.
     * @throws APIException is thrown if the specified plan_id does not exist in database.
     */
    public function retrieveProductionPlan(string $plan_id):array {

        if (!$this->doesPlanExist($plan_id)) {
            throw new APIException(RESTConstants::HTTP_NOT_ACCEPTABLE, RESTConstants::API_URI, 'Specified plan does not exist in database!');
        }

        $rec = array();
        $query = 'SELECT id, start_date, end_date FROM `production_plan` WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $plan_id);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rec['plan'] = $row;
        }

        $ref = array();
        $query = 'SELECT model, size, weight, quantity FROM `production_plan_reference` WHERE plan_id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $plan_id);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $ref[] = $row;
        }

        $response['id'] = $rec['plan']['id'];
        $response['start_date'] = $rec['plan']['start_date'];
        $response['end_date'] = $rec['plan']['end_date'];
        $response['plans'] = $ref;

        return $response;

    }




    /**
     * Simple method that checks that the given plan_id exists in database
     * @param string $plan_id the production_plan(id) to check for in database
     * @return bool returns true if the specified id exists, and false if not
     */
    protected function doesPlanExist(string $plan_id):bool {
        $query = 'SELECT COUNT(*) FROM production_plan WHERE id = :plan_id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':plan_id', $plan_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_NUM);
        if ($row[0] == 0) {
            return false;
        }

        return true;
    }

}