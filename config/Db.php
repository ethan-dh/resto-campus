<?php
class Db {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=localhost;dbname=RestoCampus;charset=utf8mb4",
                "root", // Changez selon votre configuration
                ""      // Changez selon votre configuration
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>
