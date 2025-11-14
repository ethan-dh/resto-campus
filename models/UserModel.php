<?php
require_once 'config/Db.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function authenticate($login, $password) {
        $sql = "SELECT idUtilisateur, login, nom, prenom, role FROM Utilisateur WHERE login = ? AND motDePasse = ?";
        $stmt = $this->db->query($sql, [$login, $password]);
        return $stmt->fetch();
    }

    public function getAllUsers() {
        $sql = "SELECT idUtilisateur, login, nom, prenom, role FROM Utilisateur ORDER BY nom, prenom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getUserById($id) {
        $sql = "SELECT idUtilisateur, login, nom, prenom, role FROM Utilisateur WHERE idUtilisateur = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function createUser($data) {
        $sql = "INSERT INTO Utilisateur (login, nom, prenom, motDePasse, role) VALUES (?, ?, ?, ?, ?)";
        $this->db->query($sql, [
            $data['login'],
            $data['nom'],
            $data['prenom'],
            $data['motDePasse'],
            $data['role']
        ]);
        return $this->db->lastInsertId();
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE Utilisateur SET login = ?, nom = ?, prenom = ?, role = ? WHERE idUtilisateur = ?";
        $params = [
            $data['login'],
            $data['nom'],
            $data['prenom'],
            $data['role'],
            $id
        ];

        // Ajouter le mot de passe seulement s'il est fourni
        if (!empty($data['motDePasse'])) {
            $sql = "UPDATE Utilisateur SET login = ?, nom = ?, prenom = ?, motDePasse = ?, role = ? WHERE idUtilisateur = ?";
            $params = [
                $data['login'],
                $data['nom'],
                $data['prenom'],
                $data['motDePasse'],
                $data['role'],
                $id
            ];
        }

        $this->db->query($sql, $params);
        return true;
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM Utilisateur WHERE idUtilisateur = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    public function isLoginAvailable($login, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM Utilisateur WHERE login = ?";
        $params = [$login];

        if ($excludeId) {
            $sql .= " AND idUtilisateur != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }

    public function getUsersByRole($role) {
        $sql = "SELECT idUtilisateur, login, nom, prenom FROM Utilisateur WHERE role = ? ORDER BY nom, prenom";
        $stmt = $this->db->query($sql, [$role]);
        return $stmt->fetchAll();
    }
}
?>
