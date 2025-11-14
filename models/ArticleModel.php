<?php
require_once 'config/Db.php';

class ArticleModel {
    private $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function getAllArticles() {
        $sql = "SELECT a.idArticle, a.nom, a.description,
                       GROUP_CONCAT(i.nom SEPARATOR ', ') as ingredients
                FROM Article a
                LEFT JOIN Ingredient i ON a.idArticle = i.idArticle
                GROUP BY a.idArticle, a.nom, a.description
                ORDER BY a.nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getArticleById($id) {
        $sql = "SELECT idArticle, nom, description FROM Article WHERE idArticle = ?";
        $stmt = $this->db->query($sql, [$id]);
        $article = $stmt->fetch();

        if ($article) {
            // Récupérer les ingrédients
            $article['ingredients'] = $this->getIngredientsByArticleId($id);
        }

        return $article;
    }

    public function createArticle($data) {
        $sql = "INSERT INTO Article (nom, description) VALUES (?, ?)";
        $this->db->query($sql, [$data['nom'], $data['description']]);
        $articleId = $this->db->lastInsertId();

        // Ajouter les ingrédients si fournis
        if (isset($data['ingredients']) && is_array($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                if (!empty($ingredient['nom'])) {
                    $this->addIngredient($articleId, $ingredient);
                }
            }
        }

        return $articleId;
    }

    public function updateArticle($id, $data) {
        $sql = "UPDATE Article SET nom = ?, description = ? WHERE idArticle = ?";
        $this->db->query($sql, [$data['nom'], $data['description'], $id]);

        // Mettre à jour les ingrédients
        if (isset($data['ingredients']) && is_array($data['ingredients'])) {
            // Supprimer les anciens ingrédients
            $this->deleteIngredientsByArticleId($id);

            // Ajouter les nouveaux ingrédients
            foreach ($data['ingredients'] as $ingredient) {
                if (!empty($ingredient['nom'])) {
                    $this->addIngredient($id, $ingredient);
                }
            }
        }

        return true;
    }

    public function deleteArticle($id) {
        // Les ingrédients seront supprimés automatiquement grâce à la contrainte FOREIGN KEY CASCADE
        $sql = "DELETE FROM Article WHERE idArticle = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    public function getIngredientsByArticleId($articleId) {
        $sql = "SELECT idIngredient, nom, description FROM Ingredient WHERE idArticle = ? ORDER BY nom";
        $stmt = $this->db->query($sql, [$articleId]);
        return $stmt->fetchAll();
    }

    public function addIngredient($articleId, $ingredient) {
        $sql = "INSERT INTO Ingredient (nom, description, idArticle) VALUES (?, ?, ?)";
        $this->db->query($sql, [
            $ingredient['nom'],
            $ingredient['description'] ?? '',
            $articleId
        ]);
        return $this->db->lastInsertId();
    }

    public function updateIngredient($id, $data) {
        $sql = "UPDATE Ingredient SET nom = ?, description = ? WHERE idIngredient = ?";
        $this->db->query($sql, [$data['nom'], $data['description'] ?? '', $id]);
        return true;
    }

    public function deleteIngredient($id) {
        $sql = "DELETE FROM Ingredient WHERE idIngredient = ?";
        $this->db->query($sql, [$id]);
        return true;
    }

    private function deleteIngredientsByArticleId($articleId) {
        $sql = "DELETE FROM Ingredient WHERE idArticle = ?";
        $this->db->query($sql, [$articleId]);
        return true;
    }

    public function getIngredientById($id) {
        $sql = "SELECT idIngredient, nom, description, idArticle FROM Ingredient WHERE idIngredient = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function isArticleNameAvailable($nom, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM Article WHERE nom = ?";
        $params = [$nom];

        if ($excludeId) {
            $sql .= " AND idArticle != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }

    public function getArticlesWithAvailability() {
        $sql = "SELECT DISTINCT a.idArticle, a.nom, a.description,
                       GROUP_CONCAT(DISTINCT i.nom SEPARATOR ', ') as ingredients
                FROM Article a
                LEFT JOIN Ingredient i ON a.idArticle = i.idArticle
                INNER JOIN ArticleDisponible ad ON a.idArticle = ad.idArticle
                WHERE ad.dateHeureFin > NOW() AND ad.quantiteMax > 0
                GROUP BY a.idArticle, a.nom, a.description
                ORDER BY a.nom";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>
