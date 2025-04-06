<?php

namespace App\Database;

class Connection {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        
        try {
            $this->connection = new \PDO($dsn, $config['username'], $config['password']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->connection->exec("SET NAMES '{$config['charset']}'");
        } catch (\PDOException $e) {
            throw new \Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
} 