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
                <i class="fas fa-clock"></i> Nouvelles Commandes
            </h1>
            <p class="text-muted">Commandes récentes nécessitant une attention</p>
        </div>
        <a href="/resto-campus/index.php?controller=commande&action=list" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Toutes les commandes
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($commandes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">Toutes les commandes sont à jour !</h5>
                    <p class="text-muted">Aucune nouvelle commande à traiter pour le moment.</p>
                    <a href="/resto-campus/index.php?controller=commande&action=list" class="btn btn-primary">
                        <i class="fas fa-list"></i> Voir toutes les commandes
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong><?php echo count($commandes); ?> nouvelles commande(s)</strong> à traiter.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-utensils"></i> Article</th>
                                <th><i class="fas fa-user"></i> Client</th>
                                <th><i class="fas fa-calendar"></i> Créneau</th>
                                <th><i class="fas fa-hashtag"></i> Qté</th>
                                <th><i class="fas fa-clock"></i> Commandé le</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary">#<?php echo $commande['idCommande']; ?></strong>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($commande['article_nom']); ?></strong>
                                        <?php if (!empty($commande['article_description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($commande['article_description']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($commande['utilisateur_prenom'] . ' ' . $commande['utilisateur_nom']); ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($commande['utilisateur_login']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo date('d/m/Y H:i', strtotime($commande['dateHeureDebut'])); ?> -
                                            <?php echo date('H:i', strtotime($commande['dateHeureFin'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $commande['quantite']; ?></span>
                                    </td>
                                    <td>
                                        <span class="text-nowrap"><?php echo date('d/m/Y H:i', strtotime($commande['dateCommande'])); ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=commande&action=view&id=<?php echo $commande['idCommande']; ?>"
                                               class="btn btn-sm btn-info"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/resto-campus/index.php?controller=commande&action=updateStatus&id=<?php echo $commande['idCommande']; ?>&statut=récupérée"
                                               class="btn btn-sm btn-success"
                                               title="Marquer comme récupérée">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="/resto-campus/index.php?controller=commande&action=updateStatus&id=<?php echo $commande['idCommande']; ?>&statut=annulée"
                                               class="btn btn-sm btn-danger"
                                               title="Annuler">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
