<?php

require_once 'db/DB.php';

class OrderModel extends DB {

    public function __construct() {
        parent::__construct();
    }

    public function getOrdersWithState($state): array {
        $res = array();

        $query = 'SELECT order_number FROM `ski_order` WHERE state = :state';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':state', $state);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }

        return $res;
    }

    public function setStateOfOrder($id, string $state, int $employee_number=1): array { // TODO: Return if something was changed
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
                $newOrder[] = $row;
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
     * @param array $resource
     * @return array
     * @throws APIException
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

        $res = array();
        $query = 'INSERT INTO ski_order (total_price, state, ref_larger_order, customer_id, shipment_number) VALUES (:total_price, :state, :ref, :id, :shipment)';


        if (!array_key_exists('price', $rec)) {
            throw new APIException(RESTConstants::HTTP_INTERNAL_SERVER_ERROR, RESTConstants::API_URI, 'Error determining price');
        }
        $price_total = $rec['price']*$resource['quantity'];


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

        $query = 'INSERT INTO ski_type_order (order_number, size, weight, quantity) VALUES (:order_number, :size, :weight, :quantity)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number', $res['order_number']);
        $stmt->bindValue(':size', $resource['size']);
        $stmt->bindValue(':weight', $resource['weight']);
        $stmt->bindValue(':quantity', $resource['quantity']);
        $stmt->execute();
        $this->db->commit();



        return $res;

    }

    function verifyOrder(array $resource): array
    {
        $res = array();
        if (count($resource) != 5) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "There should be exactly 5 values.";
            return $res;
        }
        // Just check if all necessary keys are there...
        if (!array_key_exists('customer_id', $resource) || !array_key_exists('weight', $resource) || !array_key_exists('quantity', $resource) ||
            !array_key_exists('size', $resource)        || !array_key_exists('model', $resource)) {

            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "One or more attributes are missing.";
            return $res;
        }

        if (!(new SkiModel)->doesSkiTypeExist($resource['model'], $resource['size'], $resource['weight'])) {
            $res['code'] = RESTConstants::HTTP_BAD_REQUEST;
            $res['message'] = "Specified ski does not match any existing ski types!";
            return $res;
        }

        $query = 'SELECT MSRPP FROM `ski_type` WHERE model = :model AND weight_class = :weight AND size = :size';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $resource['model']);
        $stmt->bindValue(':weight', $resource['weight']);
        $stmt->bindValue(':size', $resource['size']);
        $stmt->execute();

        $res['code'] = RESTConstants::HTTP_OK;
        $res['price'] = $stmt->fetch(PDO::FETCH_ASSOC)['MSRPP'];
        return $res;
    }

}
