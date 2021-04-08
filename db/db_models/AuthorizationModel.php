<?php

require_once 'db/DB.php';

class AuthorizationModel extends DB {

    public function __construct() {
        parent::__construct();
    }

    public function getRole($token): string {
        $role = '';
        $res = array();
        $query = 'SELECT `role` FROM `auth_token` WHERE `token` = :token';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res = $row;
        }
        if (count($res) > 0) {
            $role = $res['role'];
        }

        return $role;
    }
}