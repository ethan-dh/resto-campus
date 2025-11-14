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
                <i class="fas fa-receipt"></i> Détails de la Commande
            </h1>
            <p class="text-muted">Commande #<?php echo $commande['idCommande']; ?></p>
        </div>
        <a href="<?php echo $_SESSION['user']['role'] === 'admin' ? '/resto-campus/index.php?controller=commande&action=list' : '/resto-campus/index.php?controller=commande&action=myOrders'; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informations de la commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-utensils"></i> Article</h6>
                            <p class="mb-0"><strong><?php echo htmlspecialchars($commande['article_nom']); ?></strong></p>
                            <?php if (!empty($commande['article_description'])): ?>
                                <p class="text-muted small"><?php echo htmlspecialchars($commande['article_description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar"></i> Créneau</h6>
                            <p class="mb-0">
                                <?php echo date('d/m/Y', strtotime($commande['dateHeureDebut'])); ?>
                            </p>
                            <p class="text-muted mb-0">
                                <?php echo date('H:i', strtotime($commande['dateHeureDebut'])); ?> -
                                <?php echo date('H:i', strtotime($commande['dateHeureFin'])); ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-hashtag"></i> Quantité</h6>
                            <p><span class="badge bg-primary fs-6"><?php echo $commande['quantite']; ?> portion(s)</span></p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-info-circle"></i> Statut</h6>
                            <?php
                            $badgeClass = '';
                            switch ($commande['statut']) {
                                case 'réservée': $badgeClass = 'bg-warning text-dark'; break;
                                case 'récupérée': $badgeClass = 'bg-success'; break;
                                case 'annulée': $badgeClass = 'bg-danger'; break;
                            }
                            ?>
                            <p><span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo ucfirst($commande['statut']); ?></span></p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-clock"></i> Commandé le</h6>
                            <p><?php echo date('d/m/Y à H:i', strtotime($commande['dateCommande'])); ?></p>
                        </div>
                    </div>

                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-user"></i> Client</h6>
                                <p class="mb-0"><?php echo htmlspecialchars($commande['utilisateur_prenom'] . ' ' . $commande['utilisateur_nom']); ?></p>
                                <small class="text-muted"><?php echo htmlspecialchars($commande['utilisateur_login']); ?></small>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-boxes"></i> Stock restant</h6>
                                <p><span class="badge bg-info"><?php echo $commande['quantiteMax']; ?> portions total</span></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <div class="d-grid gap-2">
                            <?php if ($commande['statut'] === 'réservée'): ?>
                                <a href="/resto-campus/index.php?controller=commande&action=updateStatus&id=<?php echo $commande['idCommande']; ?>&statut=récupérée"
                                   class="btn btn-success"
                                   data-confirm-delete="Confirmer la récupération de cette commande ?">
                                    <i class="fas fa-check"></i> Marquer comme récupérée
                                </a>
                                <a href="/resto-campus/index.php?controller=commande&action=updateStatus&id=<?php echo $commande['idCommande']; ?>&statut=annulée"
                                   class="btn btn-danger"
                                   data-confirm-delete="Confirmer l'annulation de cette commande ?">
                                    <i class="fas fa-times"></i> Annuler la commande
                                </a>
                            <?php endif; ?>

                            <a href="/resto-campus/index.php?controller=commande&action=delete&id=<?php echo $commande['idCommande']; ?>"
                               class="btn btn-outline-danger"
                               data-confirm-delete="Êtes-vous sûr de vouloir supprimer définitivement cette commande ?">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    <?php else: ?>
                        <?php if ($commande['statut'] === 'réservée' && strtotime($commande['dateHeureDebut']) > time()): ?>
                            <div class="d-grid">
                                <a href="/resto-campus/index.php?controller=commande&action=cancel&id=<?php echo $commande['idCommande']; ?>"
                                   class="btn btn-danger"
                                   data-confirm-delete="Êtes-vous sûr de vouloir annuler cette commande ?">
                                    <i class="fas fa-times"></i> Annuler ma commande
                                </a>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> L'annulation est possible jusqu'au début du créneau.
                            </small>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <?php if ($commande['statut'] === 'annulée'): ?>
                                    Cette commande a été annulée.
                                <?php elseif ($commande['statut'] === 'récupérée'): ?>
                                    Cette commande a été récupérée.
                                <?php else: ?>
                                    Aucune action disponible pour cette commande.
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($_SESSION['user']['role'] !== 'admin'): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informations importantes
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li><i class="fas fa-clock text-success"></i> Présentez-vous 5 min avant l'heure de retrait</li>
                            <li><i class="fas fa-id-card text-success"></i> Munissez-vous de votre carte étudiant</li>
                            <li><i class="fas fa-map-marker-alt text-success"></i> Point de retrait : Restaurant Universitaire</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
