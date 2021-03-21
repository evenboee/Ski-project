<?php
require_once 'dbCredentials.php';

/**
 * Class DB root for model - and other - classes needing access to the database
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DB.php
 * @author Rune Hjelsvold
 */
abstract class DB {
    /**
     * @var PDO
     */
    protected $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',
            DB_USER, DB_PWD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

}
