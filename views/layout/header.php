<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestoCampus - <?php echo isset($pageTitle) ? $pageTitle : 'Système de Réservation'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Styles personnalisés -->
    <link href="/resto-campus/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/resto-campus/">
                <i class="fas fa-utensils"></i> RestoCampus
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/resto-campus/">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i> Administration
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/resto-campus/index.php?controller=user&action=list">
                                        <i class="fas fa-users"></i> Gestion Utilisateurs
                                    </a></li>
                                    <li><a class="dropdown-item" href="/resto-campus/index.php?controller=article&action=list">
                                        <i class="fas fa-utensils"></i> Gestion Articles
                                    </a></li>
                                    <li><a class="dropdown-item" href="/resto-campus/index.php?controller=dispo&action=list">
                                        <i class="fas fa-calendar"></i> Gestion Disponibilités
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/resto-campus/index.php?controller=commande&action=list">
                                        <i class="fas fa-list"></i> Toutes les Commandes
                                    </a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/resto-campus/index.php?controller=article&action=listDispo">
                                    <i class="fas fa-utensils"></i> Commander
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/resto-campus/index.php?controller=commande&action=myOrders">
                                    <i class="fas fa-shopping-cart"></i> Mes Commandes
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/resto-campus/index.php?controller=user&action=profile">
                                    <i class="fas fa-user-edit"></i> Mon Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/resto-campus/index.php?controller=user&action=logout">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/resto-campus/index.php?controller=user&action=login">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0">
        <div class="container mt-4">
