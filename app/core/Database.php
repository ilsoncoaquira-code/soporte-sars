<?php
// app/models/Database.php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct($config) {
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['name']};charset=utf8",
                $config['user'],
                $config['pass']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance($config = null) {
        if (self::$instance === null && $config !== null) {
            self::$instance = new self($config);
        }
        return self::$instance->connection;
    }
}
?>