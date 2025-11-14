<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar"></i> Détails de la Disponibilité
            </h1>
            <p class="text-muted">Informations sur le créneau de <?php echo htmlspecialchars($disponibilite['article_nom']); ?></p>
        </div>
        <a href="/resto-campus/index.php?controller=dispo&action=list" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informations de la disponibilité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-utensils"></i> Article</h6>
                            <p class="mb-0"><strong><?php echo htmlspecialchars($disponibilite['article_nom']); ?></strong></p>
                            <?php if (!empty($disponibilite['article_description'])): ?>
                                <p class="text-muted small"><?php echo htmlspecialchars($disponibilite['article_description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar"></i> Créneau horaire</h6>
                            <p class="mb-0">
                                <?php echo date('d/m/Y', strtotime($disponibilite['dateHeureDebut'])); ?>
                            </p>
                            <p class="text-muted mb-0">
                                <?php echo date('H:i', strtotime($disponibilite['dateHeureDebut'])); ?> -
                                <?php echo date('H:i', strtotime($disponibilite['dateHeureFin'])); ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-boxes"></i> Stock</h6>
                            <p><span class="badge bg-secondary fs-6"><?php echo $disponibilite['quantiteMax']; ?> portions</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-bar"></i> État</h6>
                            <?php if (strtotime($disponibilite['dateHeureFin']) < time()): ?>
                                <p><span class="badge bg-secondary">Terminée</span></p>
                            <?php else: ?>
                                <p><span class="badge bg-success">Active</span></p>
                            <?php endif; ?>
                        </div>
                    </div>
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
                    <div class="d-grid gap-2">
                        <a href="/resto-campus/index.php?controller=dispo&action=edit&id=<?php echo $disponibilite['idDispo']; ?>"
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>

                        <a href="/resto-campus/index.php?controller=commande&action=recap&id=<?php echo $disponibilite['idDispo']; ?>"
                           class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Voir les commandes
                        </a>

                        <?php if ($disponibilite['quantite_commandee'] == 0): ?>
                            <a href="/resto-campus/index.php?controller=dispo&action=delete&id=<?php echo $disponibilite['idDispo']; ?>"
                               class="btn btn-outline-danger"
                               data-confirm-delete="Êtes-vous sûr de vouloir supprimer cette disponibilité ?">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($commandes) && $_SESSION['user']['role'] === 'admin'): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-users"></i> Dernières commandes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($commandes, 0, 3) as $commande): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small><?php echo htmlspecialchars($commande['utilisateur_prenom'] . ' ' . $commande['utilisateur_nom']); ?></small>
                                        <span class="badge bg-primary"><?php echo $commande['quantite']; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($commandes) > 3): ?>
                            <div class="text-center mt-2">
                                <a href="/resto-campus/index.php?controller=commande&action=recap&id=<?php echo $disponibilite['idDispo']; ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    Voir toutes les commandes
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
