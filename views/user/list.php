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
                <i class="fas fa-users"></i> Gestion des Utilisateurs
            </h1>
            <p class="text-muted">Administrer les comptes utilisateurs du système</p>
        </div>
        <a href="/resto-campus/index.php?controller=user&action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un utilisateur
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($users)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier utilisateur.</p>
                    <a href="/resto-campus/index.php?controller=user&action=add" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un utilisateur
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Login</th>
                                <th><i class="fas fa-id-card"></i> Nom complet</th>
                                <th><i class="fas fa-user-tag"></i> Rôle</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['login']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'secondary'; ?>">
                                            <i class="fas fa-<?php echo $user['role'] === 'admin' ? 'crown' : 'user-graduate'; ?>"></i>
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/resto-campus/index.php?controller=user&action=edit&id=<?php echo $user['idUtilisateur']; ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($user['login'] !== $_SESSION['user']['login']): ?>
                                                <a href="/resto-campus/index.php?controller=user&action=delete&id=<?php echo $user['idUtilisateur']; ?>"
                                                   class="btn btn-sm btn-outline-danger"
                                                   title="Supprimer"
                                                   data-confirm-delete="Êtes-vous sûr de vouloir supprimer cet utilisateur ?">
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
                        Total: <?php echo count($users); ?> utilisateur(s)
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
