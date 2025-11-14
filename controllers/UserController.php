<?php
require_once 'models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->startSession();
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        // Rediriger si déjà connecté
        if (isset($_SESSION['user'])) {
            $this->redirectBasedOnRole();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = trim($_POST['login'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($login) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->userModel->authenticate($login, $password);

                if ($user) {
                    $_SESSION['user'] = $user;
                    $this->redirectBasedOnRole();
                } else {
                    $error = 'Login ou mot de passe incorrect.';
                }
            }
        }

        $pageTitle = 'Connexion';
        require_once 'views/layout/header.php';
        require_once 'views/user/login.php';
        require_once 'views/layout/footer.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /resto-campus/index.php?controller=user&action=login');
        exit;
    }

    private function redirectBasedOnRole() {
        if (!isset($_SESSION['user'])) {
            header('Location: /resto-campus/index.php?controller=user&action=login');
            exit;
        }

        if ($_SESSION['user']['role'] === 'admin') {
            header('Location: /resto-campus/index.php?controller=user&action=list');
        } else {
            header('Location: /resto-campus/index.php?controller=article&action=listDispo');
        }
        exit;
    }

    public function list() {
        $this->checkAdminAccess();

        $users = $this->userModel->getAllUsers();

        $pageTitle = 'Gestion des Utilisateurs';
        require_once 'views/layout/header.php';
        require_once 'views/user/list.php';
        require_once 'views/layout/footer.php';
    }

    public function add() {
        $this->checkAdminAccess();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'login' => trim($_POST['login'] ?? ''),
                'nom' => trim($_POST['nom'] ?? ''),
                'prenom' => trim($_POST['prenom'] ?? ''),
                'motDePasse' => trim($_POST['motDePasse'] ?? ''),
                'role' => $_POST['role'] ?? 'etudiant'
            ];

            if (empty($data['login']) || empty($data['nom']) || empty($data['prenom']) || empty($data['motDePasse'])) {
                $error = 'Tous les champs sont obligatoires.';
            } elseif (!$this->userModel->isLoginAvailable($data['login'])) {
                $error = 'Ce login est déjà utilisé.';
            } else {
                if ($this->userModel->createUser($data)) {
                    $success = 'Utilisateur créé avec succès.';
                    // Rediriger après 2 secondes
                    header('refresh:2;url=/resto-campus/index.php?controller=user&action=list');
                } else {
                    $error = 'Erreur lors de la création de l\'utilisateur.';
                }
            }
        }

        $pageTitle = 'Ajouter un Utilisateur';
        require_once 'views/layout/header.php';
        require_once 'views/user/add.php';
        require_once 'views/layout/footer.php';
    }

    public function edit() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=user&action=list');
            exit;
        }

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            header('Location: /resto-campus/index.php?controller=user&action=list');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'login' => trim($_POST['login'] ?? ''),
                'nom' => trim($_POST['nom'] ?? ''),
                'prenom' => trim($_POST['prenom'] ?? ''),
                'role' => $_POST['role'] ?? 'etudiant'
            ];

            // Ajouter le mot de passe seulement s'il est fourni
            if (!empty(trim($_POST['motDePasse'] ?? ''))) {
                $data['motDePasse'] = trim($_POST['motDePasse']);
            }

            if (empty($data['login']) || empty($data['nom']) || empty($data['prenom'])) {
                $error = 'Les champs login, nom et prénom sont obligatoires.';
            } elseif (!$this->userModel->isLoginAvailable($data['login'], $id)) {
                $error = 'Ce login est déjà utilisé.';
            } else {
                if ($this->userModel->updateUser($id, $data)) {
                    $success = 'Utilisateur modifié avec succès.';
                    $user = $this->userModel->getUserById($id); // Recharger les données
                } else {
                    $error = 'Erreur lors de la modification de l\'utilisateur.';
                }
            }
        }

        $pageTitle = 'Modifier un Utilisateur';
        require_once 'views/layout/header.php';
        require_once 'views/user/edit.php';
        require_once 'views/layout/footer.php';
    }

    public function delete() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /resto-campus/index.php?controller=user&action=list');
            exit;
        }

        if ($this->userModel->deleteUser($id)) {
            $_SESSION['success'] = 'Utilisateur supprimé avec succès.';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de l\'utilisateur.';
        }

        header('Location: /resto-campus/index.php?controller=user&action=list');
        exit;
    }

    public function profile() {
        $this->checkAuthentication();

        $user = $this->userModel->getUserById($_SESSION['user']['idUtilisateur']);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'login' => trim($_POST['login'] ?? ''),
                'nom' => trim($_POST['nom'] ?? ''),
                'prenom' => trim($_POST['prenom'] ?? '')
            ];

            // Ajouter le mot de passe seulement s'il est fourni
            if (!empty(trim($_POST['motDePasse'] ?? ''))) {
                $data['motDePasse'] = trim($_POST['motDePasse']);
            }

            if (empty($data['login']) || empty($data['nom']) || empty($data['prenom'])) {
                $error = 'Les champs login, nom et prénom sont obligatoires.';
            } elseif (!$this->userModel->isLoginAvailable($data['login'], $_SESSION['user']['idUtilisateur'])) {
                $error = 'Ce login est déjà utilisé.';
            } else {
                if ($this->userModel->updateUser($_SESSION['user']['idUtilisateur'], $data)) {
                    $success = 'Profil modifié avec succès.';
                    // Mettre à jour la session
                    $_SESSION['user']['login'] = $data['login'];
                    $_SESSION['user']['nom'] = $data['nom'];
                    $_SESSION['user']['prenom'] = $data['prenom'];
                    $user = $this->userModel->getUserById($_SESSION['user']['idUtilisateur']);
                } else {
                    $error = 'Erreur lors de la modification du profil.';
                }
            }
        }

        $pageTitle = 'Mon Profil';
        require_once 'views/layout/header.php';
        require_once 'views/user/profile.php';
        require_once 'views/layout/footer.php';
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
}
?>
