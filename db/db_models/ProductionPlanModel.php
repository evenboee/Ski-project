<?php
require_once 'db/DB.php';

/**
 * Class ProductionPlanModel
 *
 * @author Amund Helgestad
 */
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
     * Creates a new production plan and adds it to database if the given resource is on the correct format.
     *
     * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php#L86
     * @author Rune Hjelsvold
     *
     * Creates a new production plan and adds it to the database
     * @param array $resource the resource to add as a production plan to database
     * @return array response with useful information of the newly created order.
     * @throws APIException if given resource is not up to standards.
     */
    public function createProductionPlan(array $resource): array{
        $rec = $this->verifyNewPlan($resource);
        if ($rec['code'] != RESTConstants::HTTP_OK) {
            $this->db->rollBack();
            if (isset($rec['message'])) {
                throw new APIException($rec['code'], RESTConstants::API_URI, $rec['message']);
            }
            throw new APIException($rec['code'], RESTConstants::API_URI, "");
        }


        // Checks if start date is on right format and calculates end date
        $end_date = $this->calculateNewDateFromDate($resource['start_date'],28);

        $res = array();
        $query = 'INSERT INTO production_plan (start_date, end_date, plannerNo) VALUES (:start_date, :end_date, :planner_no)';
        $this->db->beginTransaction();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':start_date', $resource['start_date']);
        $stmt->bindValue(':end_date', $end_date);
        $stmt->bindValue(':planner_no', $resource['planner_id']);
        $stmt->execute();

        $res['plan_id'] = intval($this->db->lastInsertId());
        $res['end_date'] = $end_date;
        $res['employee_id'] = $resource['planner_id'];

        $res['count'] = count($resource['plan']);
        $x = 0;

        $query = 'INSERT INTO production_plan_reference (plan_id, size, weight, model, quantity) VALUES (:plan_id, :size, :weight, :model, :quantity)';
        while ($x < $res['count']) {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':plan_id', $res['plan_id']);
            $stmt->bindValue(':size', $resource['plan'][$x]['size']);
            $stmt->bindValue(':weight', $resource['plan'][$x]['weight']);
            $stmt->bindValue(':quantity', $resource['plan'][$x]['quantity']);
            $stmt->bindValue(':model', $resource['plan'][$x]['model']);
            $stmt->execute();
            $x++;
        }
        $this->db->commit();

        return $res;
    }

    /**
     * Checks if given date is written in the correct format as well as returning a new date with the specified number of days later.
     *
     * @param string $date the date we base the return string on.
     * @param int $num_days the number of days after the date.
     * @return string the resulting date is returned as a string on the following format: YY-MM-DD.
     * @throws APIException is thrown if date is not on correct format.
     */

    protected function calculateNewDateFromDate(string $date, int $num_days): string {

        if (strlen($date) != 10) {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, RESTConstants::API_URI, 'Date must be on the following form: YY-MM-DD.');
        }

        $date_parse = date_parse($date);
        if (array_key_exists('warning_count', $date_parse)) {
            if($date_parse['warning_count'] != 0) {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, RESTConstants::API_URI, 'Date must be on the following form: YY-MM-DD.');
            }
        } else {throw new APIException(RESTConstants::HTTP_BAD_REQUEST, RESTConstants::API_URI, 'Date must be on the following form: YY-MM-DD.');}

        try {
            $start_dateTime = new DateTime($date);
            $start_dateTime->add(new DateInterval("P".$num_days."D"));

            return $start_dateTime->format('Y-m-d');

        } catch (Exception $e) {
            throw new APIException(RESTConstants::HTTP_INTERNAL_SERVER_ERROR, RESTConstants::API_URI, 'Unexpected error encountered');
        }


    }

    /**
     * Verifies that the specified resource array is on the correct format for it to be used as a new production plan.
     *
     * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php#L150
     * @author Rune Hjelsvold
     *
     * @param array $resource the production plan to be verified.
     * @return array return a new array with array['code'] = {http_error_code}. If something unexpected happens it also includes array['message'] = {string}.
     */
    protected function verifyNewPlan(array $resource):array {
        $res = array();
        if (count($resource) != 3 || !array_key_exists('start_date', $resource) ||
            !array_key_exists('planner_id', $resource) || !array_key_exists('plan', $resource))
        {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'Bad Request';
            return $res;
        }

        $ski_type_count = count($resource['plan']);
        $x = 0;
        while ($x < $ski_type_count) {

            if (!array_key_exists('model', $resource['plan'][$x]) || !array_key_exists('size', $resource['plan'][$x])
            || !array_key_exists('weight', $resource['plan'][$x]) || !array_key_exists('quantity', $resource['plan'][$x])
            ) {
                $rec['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $rec['message'] = 'Ski type object at index '.$x.' did not match criteria.';
                return $rec;
            }
            if (!is_int($resource['plan'][$x]['quantity'])) {
                $rec['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $rec['message'] = 'Ski type object at index '.$x.' has a quantity as a non-integer value.';
                return $rec;
            }
            if (!(new SkiModel())->doesSkiTypeExist($resource['plan'][$x]['model'],$resource['plan'][$x]['size'], $resource['plan'][$x]['weight'])) {

                $rec['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $rec['message'] = 'Ski type object at index '.$x.' did not match criteria.';
                return $rec;
            }
            $x++;

        }

        $res['code'] = RESTConstants::HTTP_OK;
        return $res;
    }




    /**
     * Simple method that checks that the given plan_id exists in database
     *
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