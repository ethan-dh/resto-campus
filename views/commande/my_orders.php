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
                <i class="fas fa-shopping-cart"></i> Mes Commandes
            </h1>
            <p class="text-muted">Suivez vos réservations de repas</p>
        </div>
        <a href="/resto-campus/index.php?controller=article&action=listDispo" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvelle commande
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($commandes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune commande trouvée</h5>
                    <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                    <a href="/resto-campus/index.php?controller=article&action=listDispo" class="btn btn-success">
                        <i class="fas fa-utensils"></i> Voir les articles disponibles
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-utensils"></i> Article</th>
                                <th><i class="fas fa-calendar"></i> Date/Heure</th>
                                <th><i class="fas fa-hashtag"></i> Quantité</th>
                                <th><i class="fas fa-info-circle"></i> Statut</th>
                                <th><i class="fas fa-clock"></i> Commandé le</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($commande['article_nom']); ?></strong>
                                        <?php if (!empty($commande['ingredients'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($commande['ingredients']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($commande['dateHeureDebut'])); ?><br>
                                        <small class="text-muted">
                                            <?php echo date('H:i', strtotime($commande['dateHeureDebut'])); ?> -
                                            <?php echo date('H:i', strtotime($commande['dateHeureFin'])); ?>
                                        </small>
                                    </td>
                                    <td><?php echo $commande['quantite']; ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = '';
                                        switch ($commande['statut']) {
                                            case 'réservée': $badgeClass = 'bg-warning text-dark'; break;
                                            case 'récupérée': $badgeClass = 'bg-success'; break;
                                            case 'annulée': $badgeClass = 'bg-danger'; break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($commande['statut']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($commande['dateCommande'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=commande&action=view&id=<?php echo $commande['idCommande']; ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($commande['statut'] === 'réservée' && strtotime($commande['dateHeureDebut']) > time()): ?>
                                                <a href="/resto-campus/index.php?controller=commande&action=cancel&id=<?php echo $commande['idCommande']; ?>"
                                                   class="btn btn-sm btn-outline-danger"
                                                   title="Annuler"
                                                   data-confirm-delete="Êtes-vous sûr de vouloir annuler cette commande ?">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
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
                        Total: <?php echo count($commandes); ?> commande(s)
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
