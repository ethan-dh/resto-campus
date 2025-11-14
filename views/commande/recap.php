<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar"></i> Récapitulatif - <?php echo htmlspecialchars($disponibilite['article_nom']); ?>
            </h1>
            <p class="text-muted">Statistiques des commandes pour ce créneau</p>
        </div>
        <a href="/resto-campus/index.php?controller=article&action=listDispo" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux articles
        </a>
    </div>

    <!-- Informations du créneau -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-calendar"></i> Informations du créneau
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><?php echo htmlspecialchars($disponibilite['article_nom']); ?></h6>
                    <?php if (!empty($disponibilite['article_description'])): ?>
                        <p class="text-muted"><?php echo htmlspecialchars($disponibilite['article_description']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Créneau horaire</h6>
                    <p class="mb-0">
                        <strong><?php echo date('d/m/Y', strtotime($disponibilite['dateHeureDebut'])); ?></strong>
                    </p>
                    <p class="text-muted">
                        <?php echo date('H:i', strtotime($disponibilite['dateHeureDebut'])); ?> -
                        <?php echo date('H:i', strtotime($disponibilite['dateHeureFin'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?php echo $stats['total_commandes']; ?></h3>
                    <p class="text-muted mb-0">Total commandes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning"><?php echo $stats['reservees']; ?></h3>
                    <p class="text-muted mb-0">Réservées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success"><?php echo $stats['recuperees']; ?></h3>
                    <p class="text-muted mb-0">Récupérées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger"><?php echo $stats['annulees']; ?></h3>
                    <p class="text-muted mb-0">Annulées</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail des commandes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Détail des commandes
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($commandes)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                    <p class="text-muted">Aucune commande pour ce créneau</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Client</th>
                                <th><i class="fas fa-hashtag"></i> Quantité</th>
                                <th><i class="fas fa-info-circle"></i> Statut</th>
                                <th><i class="fas fa-clock"></i> Commandé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($commande['utilisateur_prenom'] . ' ' . $commande['utilisateur_nom']); ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($commande['utilisateur_login']); ?></small>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
