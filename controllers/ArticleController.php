<?php
require_once 'models/ArticleModel.php';
require_once 'models/ArticleDisponibleModel.php';

class ArticleController {
    private $articleModel;
    private $dispoModel;

    public function __construct() {
        $this->articleModel = new ArticleModel();
        $this->dispoModel = new ArticleDisponibleModel();
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

        $articles = $this->articleModel->getAllArticles();

        $pageTitle = 'Gestion des Articles';
        require_once 'views/layout/header.php';
        require_once 'views/article/list.php';
        require_once 'views/layout/footer.php';
    }

    public function add() {
        $this->checkAdminAccess();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => trim($_POST['nom'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'ingredients' => []
            ];

            // Traiter les ingrédients
            if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
                foreach ($_POST['ingredients'] as $ingredient) {
                    if (!empty(trim($ingredient['nom'] ?? ''))) {
                        $data['ingredients'][] = [
                            'nom' => trim($ingredient['nom']),
                            'description' => trim($ingredient['description'] ?? '')
                        ];
                    }
                }
            }

            if (empty($data['nom'])) {
                $error = 'Le nom de l\'article est obligatoire.';
            } elseif (!$this->articleModel->isArticleNameAvailable($data['nom'])) {
                $error = 'Un article avec ce nom existe déjà.';
            } else {
                if ($this->articleModel->createArticle($data)) {
                    $success = 'Article créé avec succès.';
                    header('refresh:2;url=/resto-campus/index.php?controller=article&action=list');
                } else {
                    $error = 'Erreur lors de la création de l\'article.';
                }
            }
        }

        $pageTitle = 'Ajouter un Article';
        require_once 'views/layout/header.php';
        require_once 'views/article/add.php';
        require_once 'views/layout/footer.php';
    }

    public function edit() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=article&action=list');
            exit;
        }

        $article = $this->articleModel->getArticleById($id);
        if (!$article) {
            header('Location: /resto-campus/index.php?controller=article&action=list');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => trim($_POST['nom'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'ingredients' => []
            ];

            // Traiter les ingrédients
            if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
                foreach ($_POST['ingredients'] as $ingredient) {
                    if (!empty(trim($ingredient['nom'] ?? ''))) {
                        $data['ingredients'][] = [
                            'nom' => trim($ingredient['nom']),
                            'description' => trim($ingredient['description'] ?? '')
                        ];
                    }
                }
            }

            if (empty($data['nom'])) {
                $error = 'Le nom de l\'article est obligatoire.';
            } elseif (!$this->articleModel->isArticleNameAvailable($data['nom'], $id)) {
                $error = 'Un article avec ce nom existe déjà.';
            } else {
                if ($this->articleModel->updateArticle($id, $data)) {
                    $success = 'Article modifié avec succès.';
                    $article = $this->articleModel->getArticleById($id);
                } else {
                    $error = 'Erreur lors de la modification de l\'article.';
                }
            }
        }

        $pageTitle = 'Modifier un Article';
        require_once 'views/layout/header.php';
        require_once 'views/article/edit.php';
        require_once 'views/layout/footer.php';
    }

    public function delete() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=article&action=list');
            exit;
        }

        if ($this->articleModel->deleteArticle($id)) {
            $_SESSION['success'] = 'Article supprimé avec succès.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de l\'article.';
        }

        header('Location: /resto-campus/index.php?controller=article&action=list');
        exit;
    }

    public function listDispo() {
        $this->checkAuthentication();

        $articles = $this->dispoModel->getDisponibilitesActives();

        $pageTitle = 'Articles Disponibles';
        require_once 'views/layout/header.php';
        require_once 'views/dispo/list.php';
        require_once 'views/layout/footer.php';
    }

    public function order() {
        $this->checkAuthentication();

        $idDispo = $_GET['id'] ?? null;
        if (!$idDispo) {
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }

        $disponibilite = $this->dispoModel->getDisponibiliteById($idDispo);
        if (!$disponibilite) {
            $_SESSION['error'] = 'Disponibilité non trouvée.';
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }

        // Vérifier si la disponibilité est encore active
        if (strtotime($disponibilite['dateHeureFin']) < time()) {
            $_SESSION['error'] = 'Cette disponibilité n\'est plus active.';
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantite = (int)($_POST['quantite'] ?? 1);

            if ($quantite < 1) {
                $error = 'La quantité doit être d\'au moins 1.';
            } else {
                require_once 'models/CommandeModel.php';
                $commandeModel = new CommandeModel();

                if (!$commandeModel->canUserOrder($_SESSION['user']['idUtilisateur'], $idDispo, $quantite)) {
                    $error = 'Vous ne pouvez pas commander cet article ou la quantité demandée n\'est pas disponible.';
                } else {
                    if ($commandeModel->createCommande([
                        'idUtilisateur' => $_SESSION['user']['idUtilisateur'],
                        'idDispo' => $idDispo,
                        'quantite' => $quantite
                    ])) {
                        $success = 'Commande effectuée avec succès !';
                        header('refresh:2;url=/resto-campus/index.php?controller=commande&action=myOrders');
                    } else {
                        $error = 'Erreur lors de la création de la commande.';
                    }
                }
            }
        }

        $pageTitle = 'Commander - ' . $disponibilite['article_nom'];
        require_once 'views/layout/header.php';
        require_once 'views/article/order.php';
        require_once 'views/layout/footer.php';
    }
}
?>
