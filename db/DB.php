<?php
require_once 'dbCredentials.php';

/**
 * Class DB root for model - and other - classes needing access to the database
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DB.php
 * @author Rune Hjelsvold and Even B. Bøe
 */
abstract class DB {
    /**
     * @var PDO
     */
    protected $db;

    public function __construct($user = DB_USER, $pass = DB_PWD)
    {
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
            $user, $pass,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

}
