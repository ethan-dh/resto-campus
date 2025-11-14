<?php
// Afficher les messages de session
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> ' . $_SESSION['success'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> ' . $_SESSION['error'] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['error']);
}
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-utensils"></i> Articles Disponibles
            </h1>
            <p class="text-muted">Découvrez nos plats et réservez votre repas</p>
        </div>
    </div>

    <?php if (empty($articles)): ?>
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Aucun article disponible actuellement</h5>
            <p class="text-muted">Revenez plus tard pour découvrir notre carte.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($articles as $article): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 plat-card">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <i class="fas fa-utensils text-success"></i> <?php echo htmlspecialchars($article['article_nom']); ?>
                            </h5>

                            <?php if (!empty($article['article_description'])): ?>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($article['article_description']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($article['ingredients'])): ?>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-list"></i> Ingrédients: <?php echo htmlspecialchars($article['ingredients']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted">Début</small>
                                        <br>
                                        <strong><?php echo date('H:i', strtotime($article['dateHeureDebut'])); ?></strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Fin</small>
                                        <br>
                                        <strong><?php echo date('H:i', strtotime($article['dateHeureFin'])); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Disponible:</small>
                                    <span class="badge badge-disponible">
                                        <?php echo $article['quantite_restante']; ?> / <?php echo $article['quantiteMax']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <?php if ($article['quantite_restante'] > 0): ?>
                                    <a href="/resto-campus/index.php?controller=article&action=order&id=<?php echo $article['idDispo']; ?>"
                                       class="btn btn-success w-100">
                                        <i class="fas fa-shopping-cart"></i> Commander
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times"></i> Épuisé
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
