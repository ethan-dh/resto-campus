<?php
require_once 'config/Db.php';

class ArticleDisponibleModel {
    private $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function getAllDisponibilites() {
        $sql = "SELECT ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                       a.nom as article_nom, a.description as article_description,
                       COALESCE(SUM(c.quantite), 0) as quantite_commandee,
                       (ad.quantiteMax - COALESCE(SUM(c.quantite), 0)) as quantite_restante
                FROM ArticleDisponible ad
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                LEFT JOIN Commande c ON ad.idDispo = c.idDispo AND c.statut IN ('réservée', 'récupérée')
                GROUP BY ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                         a.nom, a.description
                ORDER BY ad.dateHeureDebut DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getDisponibiliteById($id) {
        $sql = "SELECT ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                       a.nom as article_nom, a.description as article_description
                FROM ArticleDisponible ad
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                WHERE ad.idDispo = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function getDisponibilitesActives() {
        $sql = "SELECT ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                       a.nom as article_nom, a.description as article_description,
                       GROUP_CONCAT(DISTINCT i.nom SEPARATOR ', ') as ingredients,
                       COALESCE(SUM(c.quantite), 0) as quantite_commandee,
                       (ad.quantiteMax - COALESCE(SUM(c.quantite), 0)) as quantite_restante
                FROM ArticleDisponible ad
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                LEFT JOIN Ingredient i ON a.idArticle = i.idArticle
                LEFT JOIN Commande c ON ad.idDispo = c.idDispo AND c.statut IN ('réservée', 'récupérée')
                WHERE ad.dateHeureFin > NOW() AND ad.quantiteMax > 0
                GROUP BY ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                         a.nom, a.description
                HAVING quantite_restante > 0
                ORDER BY ad.dateHeureDebut ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function createDisponibilite($data) {
        $sql = "INSERT INTO ArticleDisponible (idArticle, dateHeureDebut, dateHeureFin, quantiteMax)
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [
            $data['idArticle'],
            $data['dateHeureDebut'],
            $data['dateHeureFin'],
            $data['quantiteMax']
        ]);
        return $this->db->lastInsertId();
    }

    public function updateDisponibilite($id, $data) {
        $sql = "UPDATE ArticleDisponible SET idArticle = ?, dateHeureDebut = ?, dateHeureFin = ?, quantiteMax = ?
                WHERE idDispo = ?";
        $this->db->query($sql, [
            $data['idArticle'],
            $data['dateHeureDebut'],
            $data['dateHeureFin'],
            $data['quantiteMax'],
            $id
        ]);
        return true;
    }

    public function deleteDisponibilite($id) {
        $sql = "DELETE FROM ArticleDisponible WHERE idDispo = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    public function getDisponibilitesByArticle($articleId) {
        $sql = "SELECT ad.idDispo, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                       COALESCE(SUM(c.quantite), 0) as quantite_commandee,
                       (ad.quantiteMax - COALESCE(SUM(c.quantite), 0)) as quantite_restante
                FROM ArticleDisponible ad
                LEFT JOIN Commande c ON ad.idDispo = c.idDispo AND c.statut IN ('réservée', 'récupérée')
                WHERE ad.idArticle = ?
                GROUP BY ad.idDispo, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax
                ORDER BY ad.dateHeureDebut DESC";
        $stmt = $this->db->query($sql, [$articleId]);
        return $stmt->fetchAll();
    }

    public function checkDisponibilite($idDispo, $quantiteDemandee = 1) {
        $sql = "SELECT ad.quantiteMax,
                       COALESCE(SUM(c.quantite), 0) as quantite_commandee,
                       (ad.quantiteMax - COALESCE(SUM(c.quantite), 0)) as quantite_restante
                FROM ArticleDisponible ad
                LEFT JOIN Commande c ON ad.idDispo = c.idDispo AND c.statut IN ('réservée', 'récupérée')
                WHERE ad.idDispo = ? AND ad.dateHeureFin > NOW()
                GROUP BY ad.idDispo, ad.quantiteMax";
        $stmt = $this->db->query($sql, [$idDispo]);
        $result = $stmt->fetch();

        if (!$result) {
            return false;
        }

        return $result['quantite_restante'] >= $quantiteDemandee;
    }

    public function getQuantiteRestante($idDispo) {
        $sql = "SELECT (ad.quantiteMax - COALESCE(SUM(c.quantite), 0)) as quantite_restante
                FROM ArticleDisponible ad
                LEFT JOIN Commande c ON ad.idDispo = c.idDispo AND c.statut IN ('réservée', 'récupérée')
                WHERE ad.idDispo = ?
                GROUP BY ad.idDispo, ad.quantiteMax";
        $stmt = $this->db->query($sql, [$idDispo]);
        $result = $stmt->fetch();
        return $result ? $result['quantite_restante'] : 0;
    }

    public function hasCommandes($idDispo) {
        $sql = "SELECT COUNT(*) as count FROM Commande WHERE idDispo = ?";
        $stmt = $this->db->query($sql, [$idDispo]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function getDisponibilitesByDateRange($dateDebut, $dateFin) {
        $sql = "SELECT ad.idDispo, ad.idArticle, ad.dateHeureDebut, ad.dateHeureFin, ad.quantiteMax,
                       a.nom as article_nom
                FROM ArticleDisponible ad
                INNER JOIN Article a ON ad.idArticle = a.idArticle
                WHERE ad.dateHeureDebut >= ? AND ad.dateHeureFin <= ?
                ORDER BY ad.dateHeureDebut";
        $stmt = $this->db->query($sql, [$dateDebut, $dateFin]);
        return $stmt->fetchAll();
    }
}
?>
