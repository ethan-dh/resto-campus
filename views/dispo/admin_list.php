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
            <p class="text-muted">Administrer les créneaux de distribution</p>
        </div>
        <a href="/resto-campus/index.php?controller=dispo&action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une disponibilité
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($disponibilites)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune disponibilité trouvée</h5>
                    <p class="text-muted">Commencez par créer votre premier créneau de distribution.</p>
                    <a href="/resto-campus/index.php?controller=dispo&action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une disponibilité
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
                                <th><i class="fas fa-chart-bar"></i> Statut</th>
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
                                        <?php echo date('d/m/Y', strtotime($dispo['dateHeureDebut'])); ?><br>
                                        <small class="text-muted">
                                            <?php echo date('H:i', strtotime($dispo['dateHeureDebut'])); ?> -
                                            <?php echo date('H:i', strtotime($dispo['dateHeureFin'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $dispo['quantite_commandee']; ?> / <?php echo $dispo['quantiteMax']; ?>
                                        </span>
                                        <?php if ($dispo['quantite_restante'] == 0): ?>
                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Épuisé</small>
                                        <?php elseif ($dispo['quantite_restante'] < $dispo['quantiteMax'] * 0.2): ?>
                                            <br><small class="text-warning"><i class="fas fa-exclamation-circle"></i> Presque épuisé</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $now = time();
                                        $debut = strtotime($dispo['dateHeureDebut']);
                                        $fin = strtotime($dispo['dateHeureFin']);

                                        if ($now < $debut) {
                                            echo '<span class="badge bg-secondary">À venir</span>';
                                        } elseif ($now >= $debut && $now <= $fin) {
                                            echo '<span class="badge bg-success">En cours</span>';
                                        } else {
                                            echo '<span class="badge bg-dark">Terminé</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=dispo&action=edit&id=<?php echo $dispo['idDispo']; ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/resto-campus/index.php?controller=dispo&action=view&id=<?php echo $dispo['idDispo']; ?>"
                                               class="btn btn-sm btn-outline-info"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
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
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
