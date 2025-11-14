<?php
// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction d'autoloading pour les classes
spl_autoload_register(function ($className) {
    $paths = [
        'controllers/',
        'models/',
        'config/'
    ];

    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Fonction pour nettoyer les entrées
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Nettoyer les données GET et POST
$_GET = sanitizeInput($_GET);
$_POST = sanitizeInput($_POST);

// Récupérer les paramètres de routage
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Liste des contrôleurs autorisés
$allowedControllers = [
    'home' => 'HomeController',
    'user' => 'UserController',
    'article' => 'ArticleController',
    'dispo' => 'ArticleDisponibleController',
    'commande' => 'CommandeController'
];

// Vérifier si le contrôleur est autorisé
if (!array_key_exists($controller, $allowedControllers)) {
    http_response_code(404);
    die('Contrôleur non trouvé');
}

$controllerClass = $allowedControllers[$controller];

// Vérifier si la classe du contrôleur existe
if (!class_exists($controllerClass)) {
    http_response_code(404);
    die('Classe du contrôleur non trouvée');
}

// Instancier le contrôleur
$controllerInstance = new $controllerClass();

// Liste des actions autorisées par contrôleur
$allowedActions = [
    'HomeController' => ['index'],
    'UserController' => ['login', 'logout', 'list', 'add', 'edit', 'delete', 'profile'],
    'ArticleController' => ['list', 'add', 'edit', 'delete', 'listDispo', 'order'],
    'ArticleDisponibleController' => ['list', 'add', 'edit', 'delete', 'view'],
    'CommandeController' => ['list', 'myOrders', 'view', 'updateStatus', 'cancel', 'recap', 'nyorders', 'delete']
];

// Vérifier si l'action est autorisée pour ce contrôleur
if (!in_array($action, $allowedActions[$controllerClass])) {
    http_response_code(404);
    die('Action non trouvée');
}

// Vérifier si la méthode existe
if (!method_exists($controllerInstance, $action)) {
    http_response_code(404);
    die('Méthode non trouvée');
}

// Exécuter l'action
try {
    $controllerInstance->$action();
} catch (Exception $e) {
    http_response_code(500);
    die('Erreur interne du serveur: ' . $e->getMessage());
}

// Contrôleur par défaut pour la page d'accueil
class HomeController {
    public function __construct() {
        $this->startSession();
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Rediriger selon le rôle de l'utilisateur
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: /resto-campus/index.php?controller=user&action=list');
            } else {
                header('Location: /resto-campus/index.php?controller=article&action=listDispo');
            }
            exit;
        }

        // Page d'accueil pour les utilisateurs non connectés
        $pageTitle = 'Bienvenue sur RestoCampus';
        require_once 'views/layout/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layout/footer.php';
    }
}
?>
