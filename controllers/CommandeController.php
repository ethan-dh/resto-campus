<?php
require_once 'models/CommandeModel.php';
require_once 'models/ArticleDisponibleModel.php';

class CommandeController {
    private $commandeModel;
    private $dispoModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
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

        $commandes = $this->commandeModel->getAllCommandes();

        $pageTitle = 'Gestion des Commandes';
        require_once 'views/layout/header.php';
        require_once 'views/commande/list.php';
        require_once 'views/layout/footer.php';
    }

    public function myOrders() {
        $this->checkAuthentication();

        $commandes = $this->commandeModel->getCommandesByUtilisateur($_SESSION['user']['idUtilisateur']);

        $pageTitle = 'Mes Commandes';
        require_once 'views/layout/header.php';
        require_once 'views/commande/my_orders.php';
        require_once 'views/layout/footer.php';
    }

    public function view() {
        $this->checkAuthentication();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: /resto-campus/index.php?controller=commande&action=list');
            } else {
                header('Location: /resto-campus/index.php?controller=commande&action=myOrders');
            }
            exit;
        }

        $commande = $this->commandeModel->getCommandeById($id);
        if (!$commande) {
            $_SESSION['error'] = 'Commande non trouvée.';
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: /resto-campus/index.php?controller=commande&action=list');
            } else {
                header('Location: /resto-campus/index.php?controller=commande&action=myOrders');
            }
            exit;
        }

        // Vérifier que l'utilisateur peut voir cette commande
        if ($_SESSION['user']['role'] !== 'admin' && $commande['idUtilisateur'] != $_SESSION['user']['idUtilisateur']) {
            $_SESSION['error'] = 'Accès non autorisé.';
            header('Location: /resto-campus/index.php?controller=commande&action=myOrders');
            exit;
        }

        $pageTitle = 'Détails de la Commande';
        require_once 'views/layout/header.php';
        require_once 'views/commande/view.php';
        require_once 'views/layout/footer.php';
    }

    public function updateStatus() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        $statut = $_GET['statut'] ?? null;

        if (!$id || !in_array($statut, ['réservée', 'récupérée', 'annulée'])) {
            $_SESSION['error'] = 'Paramètres invalides.';
            header('Location: /resto-campus/index.php?controller=commande&action=list');
            exit;
        }

        if ($this->commandeModel->updateCommandeStatus($id, $statut)) {
            $_SESSION['success'] = 'Statut de la commande mis à jour avec succès.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour du statut.';
        }

        header('Location: /resto-campus/index.php?controller=commande&action=list');
        exit;
    }

    public function cancel() {
        $this->checkAuthentication();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=commande&action=myOrders');
            exit;
        }

        if ($this->commandeModel->annulerCommandeUtilisateur($id, $_SESSION['user']['idUtilisateur'])) {
            $_SESSION['success'] = 'Commande annulée avec succès.';
        } else {
            $_SESSION['error'] = 'Impossible d\'annuler cette commande.';
        }

        header('Location: /resto-campus/index.php?controller=commande&action=myOrders');
        exit;
    }

    public function recap() {
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

        // Récupérer toutes les commandes pour cette disponibilité
        $commandes = $this->commandeModel->getCommandesByDisponibilite($idDispo);

        // Calculer les statistiques
        $stats = [
            'total_commandes' => count($commandes),
            'reservees' => count(array_filter($commandes, function($c) { return $c['statut'] === 'réservée'; })),
            'recuperees' => count(array_filter($commandes, function($c) { return $c['statut'] === 'récupérée'; })),
            'annulees' => count(array_filter($commandes, function($c) { return $c['statut'] === 'annulée'; })),
            'quantite_totale' => array_sum(array_column($commandes, 'quantite'))
        ];

        $pageTitle = 'Récapitulatif - ' . $disponibilite['article_nom'];
        require_once 'views/layout/header.php';
        require_once 'views/commande/recap.php';
        require_once 'views/layout/footer.php';
    }

    public function nyorders() {
        $this->checkAdminAccess();

        $commandes = $this->commandeModel->getCommandesRecentes(20);

        $pageTitle = 'Nouvelles Commandes';
        require_once 'views/layout/header.php';
        require_once 'views/commande/nyorders.php';
        require_once 'views/layout/footer.php';
    }

    public function delete() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=commande&action=list');
            exit;
        }

        if ($this->commandeModel->deleteCommande($id)) {
            $_SESSION['success'] = 'Commande supprimée avec succès.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de la commande.';
        }

        header('Location: /resto-campus/index.php?controller=commande&action=list');
        exit;
    }
}
?>
