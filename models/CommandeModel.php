<?php
require_once 'config/Db.php';

class CommandeModel {
    private $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function getAllCommandes() {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, u.login as utilisateur_login,
                       a.nom as article_nom, a.description as article_description,
                       ad.dateHeureDebut, ad.dateHeureFin
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                ORDER BY c.dateCommande DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getCommandeById($id) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, u.login as utilisateur_login,
                       a.nom as article_nom, a.description as article_description,
                       ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                WHERE c.idCommande = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function getCommandesByUtilisateur($idUtilisateur) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       a.nom as article_nom, a.description as article_description,
                       ad.dateHeureDebut, ad.dateHeureFin,
                       GROUP_CONCAT(DISTINCT i.nom SEPARATOR ', ') as ingredients
                FROM Commande c
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                LEFT JOIN Ingredient i ON a.idArticle = i.idArticle
                WHERE c.idUtilisateur = ?
                GROUP BY c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                         a.nom, a.description, ad.dateHeureDebut, ad.dateHeureFin
                ORDER BY c.dateCommande DESC";
        $stmt = $this->db->query($sql, [$idUtilisateur]);
        return $stmt->fetchAll();
    }

    public function getCommandesByDisponibilite($idDispo) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, u.login as utilisateur_login
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                WHERE c.idDispo = ?
                ORDER BY c.dateCommande";
        $stmt = $this->db->query($sql, [$idDispo]);
        return $stmt->fetchAll();
    }

    public function createCommande($data) {
        $sql = "INSERT INTO Commande (idUtilisateur, idDispo, quantite, statut)
                VALUES (?, ?, ?, 'réservée')";
        $this->db->query($sql, [
            $data['idUtilisateur'],
            $data['idDispo'],
            $data['quantite'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

    public function updateCommandeStatus($id, $statut) {
        $sql = "UPDATE Commande SET statut = ? WHERE idCommande = ?";
        $this->db->query($sql, [$statut, $id]);
        return true;
    }

    public function updateCommande($id, $data) {
        $sql = "UPDATE Commande SET quantite = ?, statut = ? WHERE idCommande = ?";
        $this->db->query($sql, [
            $data['quantite'],
            $data['statut'],
            $id
        ]);
        return true;
    }

    public function deleteCommande($id) {
        $sql = "DELETE FROM Commande WHERE idCommande = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    public function canUserOrder($idUtilisateur, $idDispo, $quantite = 1) {
        // Vérifier si l'utilisateur n'a pas déjà commandé cette disponibilité
        $sql = "SELECT COUNT(*) as count FROM Commande
                WHERE idUtilisateur = ? AND idDispo = ? AND statut IN ('réservée', 'récupérée')";
        $stmt = $this->db->query($sql, [$idUtilisateur, $idDispo]);
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            return false;
        }

        // Vérifier la disponibilité des quantités
        require_once 'models/ArticleDisponibleModel.php';
        $dispoModel = new ArticleDisponibleModel();
        return $dispoModel->checkDisponibilite($idDispo, $quantite);
    }

    public function getCommandesByStatut($statut) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom, u.login as utilisateur_login,
                       a.nom as article_nom, a.description as article_description,
                       ad.dateHeureDebut, ad.dateHeureFin
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                WHERE c.statut = ?
                ORDER BY c.dateCommande DESC";
        $stmt = $this->db->query($sql, [$statut]);
        return $stmt->fetchAll();
    }

    public function getCommandesRecentes($limit = 10) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom,
                       a.nom as article_nom,
                       ad.dateHeureDebut, ad.dateHeureFin
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                ORDER BY c.dateCommande DESC
                LIMIT ?";
        $stmt = $this->db->query($sql, [$limit]);
        return $stmt->fetchAll();
    }

    public function getStatsCommandes() {
        $sql = "SELECT
                    COUNT(*) as total_commandes,
                    SUM(CASE WHEN statut = 'réservée' THEN 1 ELSE 0 END) as reservees,
                    SUM(CASE WHEN statut = 'récupérée' THEN 1 ELSE 0 END) as recuperees,
                    SUM(CASE WHEN statut = 'annulée' THEN 1 ELSE 0 END) as annulees,
                    COUNT(DISTINCT idUtilisateur) as utilisateurs_actifs
                FROM Commande";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    public function getCommandesByDateRange($dateDebut, $dateFin) {
        $sql = "SELECT c.idCommande, c.idUtilisateur, c.idDispo, c.quantite, c.statut, c.dateCommande,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom,
                       a.nom as article_nom,
                       ad.dateHeureDebut, ad.dateHeureFin
                FROM Commande c
                INNER JOIN Utilisateur u ON c.idUtilisateur = u.idUtilisateur
                INNER JOIN ArticleDisponible ad ON c.idDispo = ad.idDispo
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                WHERE c.dateCommande >= ? AND c.dateCommande <= ?
                ORDER BY c.dateCommande DESC";
        $stmt = $this->db->query($sql, [$dateDebut, $dateFin]);
        return $stmt->fetchAll();
    }

    public function annulerCommandeUtilisateur($idCommande, $idUtilisateur) {
        // Vérifier que la commande appartient à l'utilisateur et qu'elle peut être annulée
        $sql = "SELECT statut FROM Commande WHERE idCommande = ? AND idUtilisateur = ?";
        $stmt = $this->db->query($sql, [$idCommande, $idUtilisateur]);
        $commande = $stmt->fetch();

        if (!$commande || $commande['statut'] !== 'réservée') {
            return false;
        }

        return $this->updateCommandeStatus($idCommande, 'annulée');
    }
}
?>
