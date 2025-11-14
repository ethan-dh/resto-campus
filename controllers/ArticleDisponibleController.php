<?php
require_once 'models/ArticleDisponibleModel.php';
require_once 'models/ArticleModel.php';

class ArticleDisponibleController {
    private $dispoModel;
    private $articleModel;

    public function __construct() {
        $this->dispoModel = new ArticleDisponibleModel();
        $this->articleModel = new ArticleModel();
        $this->startSession();
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function checkAuthentication() {
        if (!isset($_SESSION['user'])) {
            header('Location: /resto-campus/index.php?controller=user&action=login');
            exit;
        }
    }

    private function checkAdminAccess() {
        $this->checkAuthentication();
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }
    }

    public function list() {
        $this->checkAdminAccess();

        $disponibilites = $this->dispoModel->getAllDisponibilites();

        $pageTitle = 'Gestion des Disponibilités';
        require_once 'views/layout/header.php';
        require_once 'views/dispo/admin_list.php';
        require_once 'views/layout/footer.php';
    }

    public function add() {
        $this->checkAdminAccess();

        $articles = $this->articleModel->getAllArticles();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idArticle' => $_POST['idArticle'] ?? '',
                'dateHeureDebut' => $_POST['dateHeureDebut'] ?? '',
                'dateHeureFin' => $_POST['dateHeureFin'] ?? '',
                'quantiteMax' => (int)($_POST['quantiteMax'] ?? 0)
            ];

            if (empty($data['idArticle']) || empty($data['dateHeureDebut']) || empty($data['dateHeureFin']) || $data['quantiteMax'] < 1) {
                $error = 'Tous les champs sont obligatoires et la quantité doit être supérieure à 0.';
            } elseif (strtotime($data['dateHeureDebut']) >= strtotime($data['dateHeureFin'])) {
                $error = 'La date de fin doit être postérieure à la date de début.';
            } else {
                if ($this->dispoModel->createDisponibilite($data)) {
                    $success = 'Disponibilité créée avec succès.';
                    header('refresh:2;url=/resto-campus/index.php?controller=dispo&action=list');
                } else {
                    $error = 'Erreur lors de la création de la disponibilité.';
                }
            }
        }

        $pageTitle = 'Ajouter une Disponibilité';
        require_once 'views/layout/header.php';
        require_once 'views/dispo/add.php';
        require_once 'views/layout/footer.php';
    }

    public function edit() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=dispo&action=list');
            exit;
        }

        $disponibilite = $this->dispoModel->getDisponibiliteById($id);
        if (!$disponibilite) {
            header('Location: /resto-campus/index.php?controller=dispo&action=list');
            exit;
        }

        $articles = $this->articleModel->getAllArticles();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idArticle' => $_POST['idArticle'] ?? '',
                'dateHeureDebut' => $_POST['dateHeureDebut'] ?? '',
                'dateHeureFin' => $_POST['dateHeureFin'] ?? '',
                'quantiteMax' => (int)($_POST['quantiteMax'] ?? 0)
            ];

            if (empty($data['idArticle']) || empty($data['dateHeureDebut']) || empty($data['dateHeureFin']) || $data['quantiteMax'] < 1) {
                $error = 'Tous les champs sont obligatoires et la quantité doit être supérieure à 0.';
            } elseif (strtotime($data['dateHeureDebut']) >= strtotime($data['dateHeureFin'])) {
                $error = 'La date de fin doit être postérieure à la date de début.';
            } else {
                if ($this->dispoModel->updateDisponibilite($id, $data)) {
                    $success = 'Disponibilité modifiée avec succès.';
                    $disponibilite = $this->dispoModel->getDisponibiliteById($id);
                } else {
                    $error = 'Erreur lors de la modification de la disponibilité.';
                }
            }
        }

        $pageTitle = 'Modifier une Disponibilité';
        require_once 'views/layout/header.php';
        require_once 'views/dispo/edit.php';
        require_once 'views/layout/footer.php';
    }

    public function delete() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=dispo&action=list');
            exit;
        }

        // Vérifier s'il y a des commandes pour cette disponibilité
        if ($this->dispoModel->hasCommandes($id)) {
            $_SESSION['error'] = 'Impossible de supprimer cette disponibilité car elle contient des commandes.';
            header('Location: /resto-campus/index.php?controller=dispo&action=list');
            exit;
        }

        if ($this->dispoModel->deleteDisponibilite($id)) {
            $_SESSION['success'] = 'Disponibilité supprimée avec succès.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de la disponibilité.';
        }

        header('Location: /resto-campus/index.php?controller=dispo&action=list');
        exit;
    }

    public function view() {
        $this->checkAuthentication();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }

        $disponibilite = $this->dispoModel->getDisponibiliteById($id);
        if (!$disponibilite) {
            $_SESSION['error'] = 'Disponibilité non trouvée.';
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }

        // Récupérer les ingrédients de l'article
        $ingredients = $this->articleModel->getIngredientsByArticleId($disponibilite['idArticle']);

        // Récupérer les commandes pour cette disponibilité (pour admin seulement)
        $commandes = [];
        if ($_SESSION['user']['role'] === 'admin') {
            require_once 'models/CommandeModel.php';
            $commandeModel = new CommandeModel();
            $commandes = $commandeModel->getCommandesByDisponibilite($id);
        }

        $pageTitle = 'Détails - ' . $disponibilite['article_nom'];
        require_once 'views/layout/header.php';
        require_once 'views/dispo/view.php';
        require_once 'views/layout/footer.php';
    }
}
?>
