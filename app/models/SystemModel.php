<?php

require_once "../app/config/Database.php";

class SystemModel {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function checkDatabase() {
        $query = $this->db->query("SELECT 1");

        if ($query) {
            return "conectado";
        }

        return "error";
    }
}

?>
