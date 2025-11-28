<?php

class Database {

    public function connect() {
        $host = "localhost";
        $user = "root";
        $password = "root";
        $database = "sars_db";

        $connection = new mysqli($host, $user, $password, $database);

        if ($connection->connect_error) {
            die("Error de conexión: " . $connection->connect_error);
        }

        return $connection;
    }
}

?>