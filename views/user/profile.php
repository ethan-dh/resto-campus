<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Mon Profil
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/resto-campus/index.php?controller=user&action=profile" data-validate="true">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="login" class="form-label">
                                    <i class="fas fa-user"></i> Login *
                                </label>
                                <input type="text" class="form-control" id="login" name="login"
                                       value="<?php echo htmlspecialchars($user['login']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-tag"></i> Rôle
                                </label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($user['role']); ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-signature"></i> Nom *
                                </label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">
                                    <i class="fas fa-signature"></i> Prénom *
                                </label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                       value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motDePasse" class="form-label">
                                <i class="fas fa-lock"></i> Nouveau mot de passe (optionnel)
                            </label>
                            <input type="password" class="form-control" id="motDePasse" name="motDePasse">
                            <div class="form-text">Laissez vide pour conserver le mot de passe actuel</div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save"></i> Mettre à jour mon profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
