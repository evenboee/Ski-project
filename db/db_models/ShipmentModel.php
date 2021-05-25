<?php

require_once 'db/DB.php';

/**
 * Class ShipmentModel
 *
 * @author Even B. Bøe
 */
class ShipmentModel extends DB {

    public function __construct() {
        parent::__construct();
    }

    /**
     * setStateOfShipment updates state of shipment to given state
     *
     * @param $shipment_id => ID of the shipment to be changed
     * @param string $state => new state that the shipments state is set to. Default value is 'shipped'
     * @return array => the updated element. if any
     * @throws PDOException
     */
    public function setStateOfShipment($shipment_id, $state = 'shipped'): array {
        $getQuery = 'SELECT `number`, `order_number`, `store_name`, `shipping_address`, `state`, `driver_id`, `repNo` FROM `shipment` WHERE `number` = :id';
        $updateQuery = 'UPDATE `shipment` SET `state` = :state WHERE `number` = :id';
        $logQuery = 'INSERT INTO `shipment_transition_log` (`shipment_number`) VALUES (:id)';

        $res = array();
        $this->db->beginTransaction();

        if ($this->shipmentExists($shipment_id)) {
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindValue(':state', $state);
            $updateStmt->bindValue(':id', $shipment_id);
            $updateStmt->execute();

            $logStmt = $this->db->prepare($logQuery);
            $logStmt->bindValue(':id', $shipment_id);
            $logStmt->execute();

            $getStmt = $this->db->prepare($getQuery);
            $getStmt->bindValue(':id', $shipment_id);
            $getStmt->execute();

            if ($row = $getStmt->fetch(PDO::FETCH_ASSOC)) {
                $res = $row;
            }
        }

        $this->db->commit();

        return $res;
    }

    /**
     * shipmentExists is used to check if a shipment exists in the database
     * @param $shipment_id => id to check if exists in the database
     * @return bool => whether the id is in the database or not
     * @throws PDOException
     */
    public function shipmentExists($shipment_id): bool {
        $shipmentCountQuery = 'SELECT COUNT(*) FROM `shipment` WHERE `number` = :id';
        $stmt = $this->db->prepare($shipmentCountQuery);
        $stmt->bindValue(':id', $shipment_id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_NUM);
        return $res[0] > 0;
    }
}
