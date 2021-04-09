<?php

require_once 'db/DB.php';
require_once 'db/dbCredentials.php';


/**
 * Class OrderModel
 *
 * @author Amund Helgestad and Even Bøe
 */
class OrderModel extends DB {

    public function __construct() {
        parent::__construct(REP_USER, REP_PWD);
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
        // TODO: Check that employee exists and is customer rep
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

        $price_total = $rec['price']*$resource['quantity'];


        $res = array();
        $query = 'INSERT INTO ski_order (total_price, state, ref_larger_order, customer_id, shipment_number) VALUES (:total_price, :state, :ref, :id, :shipment)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':total_price', $price_total);
        $stmt->bindValue(':state', "new");
        $stmt->bindValue(':ref', NULL);
        $stmt->bindValue(":id", $resource['customer_id']);
        $stmt->bindValue(":shipment", NULL);
        $stmt->execute();

        $res['order_number'] = intval($this->db->lastInsertId());
        $res['total_price'] = $price_total;
        $res['customer_id'] = $resource['customer_id'];


        $query = 'INSERT INTO ski_type_order (order_number, size, weight, model, quantity) VALUES (:order_number, :size, :weight, :model,:quantity)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number', $res['order_number']);
        $stmt->bindValue(':size', $resource['size']);
        $stmt->bindValue(':weight', $resource['weight']);
        $stmt->bindValue(':quantity', $resource['quantity']);
        $stmt->bindValue(':model', $resource['model']);
        $stmt->execute();
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

            $query = 'DELETE FROM `ski_order` WHERE `order_number` = :order_num AND customer_id = :customer_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':order_num', $order_num);
            $stmt->bindValue(':customer_id', $resource['customer_id']);
            $stmt->execute();

            $query = "DELETE FROM ski_type_order WHERE order_number = :order_num";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':order_num', $order_num);
            $stmt->execute();

            $res['order_number'] = $order_num;
            $res['deletion'] = 'success';
            return $res;
    }

    /**
     * Verifies that the given resource is on the correct format to be considered an order.
     *
     * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php#L150
     * @author Rune Hjelsvold
     *
     * @param array $resource to verify
     * @return array response including an http error code and, if an error is encountered, a message.
     *
     */
    protected function verifyOrder(array $resource): array
    {
        $res = array();

        if (count($resource) != 5) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'There should be exactly 5 values.';
            return $res;
        }

        // Just check if all necessary keys are there...
        if (!array_key_exists('customer_id', $resource) || !array_key_exists('weight', $resource) || !array_key_exists('quantity', $resource) ||
            !array_key_exists('size', $resource)        || !array_key_exists('model', $resource)) {

            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'One or more attributes are missing.';
            return $res;
        }

        if (!is_int($resource['quantity'])) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Attribute 'quantity' must be an int!";
            return $res;
        }

        if (!(new SkiModel)->doesSkiTypeExist($resource['model'], $resource['size'], $resource['weight'])) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = 'Specified ski does not match any existing ski types!';
            return $res;
        }

        $query = 'SELECT MSRP FROM `ski_type` WHERE model = :model AND weight_class = :weight AND size = :size';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $resource['model']);
        $stmt->bindValue(':weight', $resource['weight']);
        $stmt->bindValue(':size', $resource['size']);
        $stmt->execute();

        $res['code'] = RESTConstants::HTTP_OK;
        $res['price'] = $stmt->fetch(PDO::FETCH_ASSOC)['MSRP'];
        return $res;
    }

}
