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
                <i class="fas fa-utensils"></i> Gestion des Articles
            </h1>
            <p class="text-muted">Administrer les plats et leurs ingrédients</p>
        </div>
        <a href="/resto-campus/index.php?controller=article&action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvel article
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($articles)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun article trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier plat.</p>
                    <a href="/resto-campus/index.php?controller=article&action=add" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un article
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-utensils"></i> Article</th>
                                <th><i class="fas fa-list"></i> Ingrédients</th>
                                <th><i class="fas fa-calendar"></i> Disponibilités</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($article['nom']); ?></strong>
                                        <?php if (!empty($article['description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($article['description']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($article['ingredients'])): ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($article['ingredients']); ?></span>
                                        <?php else: ?>
                                            <small class="text-muted">Aucun ingrédient défini</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/resto-campus/index.php?controller=dispo&action=list" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-calendar"></i> Gérer
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=article&action=edit&id=<?php echo $article['idArticle']; ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/resto-campus/index.php?controller=article&action=delete&id=<?php echo $article['idArticle']; ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               title="Supprimer"
                                               data-confirm-delete="Êtes-vous sûr de vouloir supprimer cet article ? Toutes les disponibilités et commandes associées seront supprimées.">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Total: <?php echo count($articles); ?> article(s)
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
