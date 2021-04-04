<?php

require_once 'DB.php';

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

    public function setStateOfOrder($id, string $state): void { // TODO: Return if something was changed
        $query = 'UPDATE ski_order SET state = :state WHERE order_number = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':state', $state);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
