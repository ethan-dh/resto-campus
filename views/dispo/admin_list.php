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
                <i class="fas fa-calendar"></i> Gestion des Disponibilités
            </h1>
            <p class="text-muted">Administrer les créneaux de disponibilité des articles</p>
        </div>
        <a href="/resto-campus/index.php?controller=dispo&action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvelle disponibilité
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($disponibilites)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune disponibilité trouvée</h5>
                    <p class="text-muted">Commencez par créer votre première disponibilité.</p>
                    <a href="/resto-campus/index.php?controller=dispo&action=add" class="btn btn-success">
                        <i class="fas fa-plus"></i> Créer une disponibilité
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-utensils"></i> Article</th>
                                <th><i class="fas fa-calendar"></i> Date début</th>
                                <th><i class="fas fa-clock"></i> Créneau</th>
                                <th><i class="fas fa-boxes"></i> Stock</th>
                                <th><i class="fas fa-shopping-cart"></i> Commandé</th>
                                <th><i class="fas fa-check-circle"></i> Restant</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($disponibilites as $dispo): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($dispo['article_nom']); ?></strong>
                                        <?php if (!empty($dispo['article_description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($dispo['article_description']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($dispo['dateHeureDebut'])); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo date('H:i', strtotime($dispo['dateHeureDebut'])); ?> -
                                            <?php echo date('H:i', strtotime($dispo['dateHeureFin'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $dispo['quantiteMax']; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning"><?php echo $dispo['quantite_commandee']; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $restant = $dispo['quantite_restante'];
                                        $badgeClass = $restant > 0 ? 'bg-success' : 'bg-danger';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $restant; ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=dispo&action=view&id=<?php echo $dispo['idDispo']; ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/resto-campus/index.php?controller=dispo&action=edit&id=<?php echo $dispo['idDispo']; ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($dispo['quantite_commandee'] == 0): ?>
                                                <a href="/resto-campus/index.php?controller=dispo&action=delete&id=<?php echo $dispo['idDispo']; ?>"
                                                   class="btn btn-sm btn-outline-danger"
                                                   title="Supprimer"
                                                   data-confirm-delete="Êtes-vous sûr de vouloir supprimer cette disponibilité ?">
                                                    <i class="fas fa-trash"></i>
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
                        Total: <?php echo count($disponibilites); ?> disponibilité(s)
                        <?php
                        $totalCommande = array_sum(array_column($disponibilites, 'quantite_commandee'));
                        $totalStock = array_sum(array_column($disponibilites, 'quantiteMax'));
                        echo " | Total commandé: {$totalCommande} | Stock total: {$totalStock}";
                        ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
