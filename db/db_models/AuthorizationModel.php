<?php

require_once 'db/DB.php';

/**
 * Class AuthorizationModel
 *
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/AuthorisationModel.php
 *   By Rune Hjelsvold
 *
 * @author Even B. Bøe
 */
class AuthorizationModel extends DB {

    public function __construct() {
        parent::__construct();
    }

    /**
     * getRole validates a user
     *
     * @param $token => a token passed by the user to be checked
     * @return string the role the token provides. If no role is associated with token: returns ''
     */
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