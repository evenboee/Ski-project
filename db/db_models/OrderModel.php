<?php

require_once 'db/DB.php';
require_once 'db/dbCredentials.php';


/**
 * Class OrderModel
 *
 * @author Amund Helgestad and Even Bøe
 */
class OrderModel extends DB {

    public function __construct(string $dbUser=DB_USER, string $dbPass=DB_PWD) { // When all users are implemented the default user should not have all access
        parent::__construct($dbUser, $dbPass);
    }

    /**
     * getOrdersWithState is used to retrieve a list of information about all orders with a given state
     * @param $state => the state of the orders to get
     * @return array => an array of orders as specified in API design
     */
    public function getOrdersWithState($state): array {
        $res = array();

        $this->db->beginTransaction();
        $query = 'SELECT order_number, total_price, state, ref_larger_order, customer_id, shipment_number FROM `ski_order` WHERE state = :state';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':state', $state);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        // model, size, weight, quantity
        for ($i = 0; $i < count($res); $i++) {
            $typeQuery = 'SElECT model, size, weight, quantity FROM `ski_type_order` WHERE order_number = :order_number';
            $typeStmt = $this->db->prepare($typeQuery);
            $typeStmt->bindValue(':order_number', $res[$i]['order_number']);
            $typeStmt->execute();
            $res[$i]['content'] = array();
            while ($row = $typeStmt->fetch(PDO::FETCH_ASSOC)) {
                $res[$i]['content'][] = $row;
            }
        }
        $this->db->commit();
        return $res;
    }

    /**
     * @param $id
     * @param string $state
     * @param int $employee_number
     * @return array
     */
    public function setStateOfOrder($id, string $state, int $employee_number): array {
        $updateQuery = 'UPDATE `ski_order` SET state = :state WHERE order_number = :id';
        $getQuery = 'SELECT `state` FROM `ski_order` WHERE `order_number` = :id';
        $logQuery = 'INSERT INTO `order_log` (`employee_number`, `order_number`, `old_state`, `new_state`) VALUES (:employee_number, :order_number, :old_state, :new_state)';
        $this->db->beginTransaction();

        $oldOrder = array();
        $newOrder = array();

        $getStmt = $this->db->prepare($getQuery);
        $getStmt->bindValue(':id', $id);
        $getStmt->execute();
        if ($row = $getStmt->fetch(PDO::FETCH_ASSOC)) {
            $oldOrder = $row;
        }

        if (count($oldOrder) > 0 && isset($oldOrder['state'])) {
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindValue(':state', $state);
            $updateStmt->bindValue(':id', $id);
            $updateStmt->execute();

            $getStmt->execute();
            if ($row = $getStmt->fetch(PDO::FETCH_ASSOC)) {
                $newOrder = $row;
            }
            if (count($newOrder) > 0) {
                $logStmt = $this->db->prepare($logQuery);
                $logStmt->bindValue(':employee_number', $employee_number);
                $logStmt->bindValue(':order_number', $id);
                $logStmt->bindValue(':old_state', $oldOrder['state']);
                $logStmt->bindValue(':new_state', $state);
                $logStmt->execute();
            }
        }

        $this->db->commit();

        return $newOrder;
    }

    public function customerRepExists($employee_number): bool {
        $query = "SELECT COUNT(*) FROM `employee` WHERE `department` = 'customer rep' AND `number` = :num";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':num', $employee_number);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_NUM);
        return $res[0] > 0;
    }

    /**
     * Creates a new order if given resource is on the correct format.
     *
     * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php#L86
     * @author Rune Hjelsvold
     *
     * @param array $resource the resource to create a new order from
     * @return array a response including some important details about the order.
     * @throws APIException is thrown if the specified resource is faulty.
     */
    public function createOrder(array $resource): array {

        $this->db->beginTransaction();
        $rec = $this->verifyOrder($resource);
        if ($rec['code'] != RESTConstants::HTTP_OK) {
            $this->db->rollBack();
            if (isset($rec['message'])) {
                throw new APIException($rec['code'], RESTConstants::API_URI, $rec['message']);
            }
            throw new APIException($rec['code'], RESTConstants::API_URI, "");
        }


        if (!array_key_exists('price', $rec)) {
            throw new APIException(RESTConstants::HTTP_INTERNAL_SERVER_ERROR, RESTConstants::API_URI, 'Error determining price');
        }


        $res = array();
        $query = 'INSERT INTO ski_order (total_price, state, ref_larger_order, customer_id, shipment_number) VALUES (:total_price, :state, :ref, :id, :shipment)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':total_price', $rec['price']);
        $stmt->bindValue(':state', "new");
        $stmt->bindValue(':ref',   NULL);  //TODO: Ref larger order option?
        $stmt->bindValue(":id",         $resource['customer_id']);
        $stmt->bindValue(":shipment",NULL);
        $stmt->execute();

        $res['order_number'] = intval($this->db->lastInsertId());
        $res['total_price'] = $rec['price'];
        $res['customer_id'] = $resource['customer_id'];


        $x = 0;
        while ($x<count($resource['types'])){
            // If there are duplicate ski types in the order,
            // then this query will be aborted and cancelled automatically by PhpMyAdmin, which is fine.
            $query = 'INSERT INTO ski_type_order (order_number, size, weight, model, quantity) VALUES (:order_number, :size, :weight, :model,:quantity)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':order_number', $res['order_number']);
            $stmt->bindValue(':size',         $resource['types'][$x]['size']);
            $stmt->bindValue(':weight',       $resource['types'][$x]['weight']);
            $stmt->bindValue(':quantity',     $resource['types'][$x]['quantity']);
            $stmt->bindValue(':model',        $resource['types'][$x]['model']);
            $stmt->execute();
            $x++; // Move to next ski type
        }

        $this->db->commit();


        return $res;

    }

    /**
     * Attempts to delete the order by the specified $order_num.
     * It will only delete if the $order_num AND the customer_id specified in the $resource match an existing order,
     * otherwise it will throw an @APIException.
     *
     * If there is an order matching the criteria, it will delete both the 'ski_order' AND the corresponding 'ski_type_order'.
     *
     * @param string $order_num the order number of the ski_order to delete (and the ski_type_order)
     * @param array $resource an array that should contain a customer_id
     * @return array a response that contains the order number of the order that was deleted as well as a clarification that is was deleted.
     * @throws APIException an exception which is thrown if there are no orders with the specified order number and customer id, or if the given resource is faulty.
     */
    public function deleteOrder(string $order_num, array $resource): array {
        if (!array_key_exists('customer_id', $resource)) {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, RESTConstants::API_URI, 'Missing customer_id attribute!');
        }

        $query = 'SELECT * FROM `ski_order` WHERE order_number = :order_num AND customer_id = :customer_id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':customer_id', $resource['customer_id']);
        $stmt->bindValue(':order_num', $order_num);
        $stmt->execute();

        $rec = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rec[] = $row;
        }
        if (count($rec) != 1) {
            throw new APIException(RESTConstants::HTTP_FORBIDDEN, RESTConstants::API_URI, 'Could not delete the specified order.');
        }

        $query = "DELETE FROM ski_type_order WHERE order_number = :order_num";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_num', $order_num);
        $stmt->execute();

        $query = 'DELETE FROM `ski_order` WHERE `order_number` = :order_num AND customer_id = :customer_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_num', $order_num);
        $stmt->bindValue(':customer_id', $resource['customer_id']);
        $stmt->execute();


        $res['order_number'] = $order_num;
        $res['deletion'] = 'success';
        return $res;
    }


    /**
     * Verifies that the given resource is on the correct format to be considered an order.
     * This order format supports multiple ski types in same order, but it does not check for duplicate ski_types.
     * This should, however, not be a problem with our current implementation of the database, as trying to add
     * duplicate entries will violate the primary key constraints for the affected tables and the operation will be
     * aborted automatically.
     *
     * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php#L150
     * @author Rune Hjelsvold
     *
     * @param array $resource to verify
     * @return array response including an http error code and, if an error is encountered, a message.
     * @return array response with http code ok if it is as expected and also the total price of the order.
     */
    protected function verifyOrder(array $resource): array
    {
        $res = array();

        if (!array_key_exists('customer_id', $resource)) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'Missing customer_id attribute.';
            return $res;
        }
        if (!is_int($resource["customer_id"])){
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'Wrong variable type. customer_id should be an integer!';
            return $res;
        }
        if (!array_key_exists('types', $resource)) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'Missing types attribute.';
            return $res;
        }

        for ($x = 0; $x < count($resource['types']); $x++) {

            if (count($resource['types'][$x]) != 4) {
                $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $res['message'] = 'There should be exactly 4 values.';
                return $res;
            }

            // Just check if all necessary keys are there...
            if (!array_key_exists('weight', $resource["types"][$x]) || !array_key_exists('quantity', $resource["types"][$x]) ||
                !array_key_exists('size', $resource["types"][$x]) || !array_key_exists('model', $resource["types"][$x])) {

                $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $res['message'] = 'One or more attributes are missing.';
                return $res;
            }

            if (!is_int($resource["types"][$x]['quantity'])) {
                $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $res['message'] = "Attribute 'quantity' must be an int!";
                return $res;
            }

            if ($resource["types"][$x]['quantity']<=0) {
                // Customer should not create orders with 0 or negative quantity.
                $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $res['message'] = 'Quantity must be greater than 0!';
                return $res;
            }

            if (!(new SkiModel)->doesSkiTypeExist($resource["types"][$x]['model'], $resource["types"][$x]['size'], $resource["types"][$x]['weight'])) {
                $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
                $res['message'] = 'Specified ski does not match any existing ski types!';
                return $res;
            }

            $query = 'SELECT MSRP FROM `ski_type` WHERE model = :model AND weight_class = :weight AND size = :size';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':model', $resource["types"][$x]['model']);
            $stmt->bindValue(':weight', $resource["types"][$x]['weight']);
            $stmt->bindValue(':size', $resource["types"][$x]['size']);
            $stmt->execute();

            $res['code'] = RESTConstants::HTTP_OK;
            if (!array_key_exists('price', $res)){
                $res['price'] = $stmt->fetch(PDO::FETCH_ASSOC)['MSRP'] * $resource["types"][$x]['quantity'];
            } else {
                $res['price'] += $stmt->fetch(PDO::FETCH_ASSOC)['MSRP'] * $resource["types"][$x]['quantity'];
            }
        }

        return $res;
    }

}
