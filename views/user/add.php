<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus"></i> Ajouter un Utilisateur
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

                    <form method="post" action="/resto-campus/index.php?controller=user&action=add" data-validate="true">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="login" class="form-label">
                                    <i class="fas fa-user"></i> Login *
                                </label>
                                <input type="text" class="form-control" id="login" name="login"
                                       value="<?php echo $_POST['login'] ?? ''; ?>" required>
                                <div class="form-text">Identifiant de connexion unique</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag"></i> Rôle *
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="etudiant" <?php echo ($_POST['role'] ?? '') === 'etudiant' ? 'selected' : ''; ?>>Étudiant</option>
                                    <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-signature"></i> Nom *
                                </label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="<?php echo $_POST['nom'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">
                                    <i class="fas fa-signature"></i> Prénom *
                                </label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                       value="<?php echo $_POST['prenom'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motDePasse" class="form-label">
                                <i class="fas fa-lock"></i> Mot de passe *
                            </label>
                            <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
                            <div class="form-text">Minimum 6 caractères</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/resto-campus/index.php?controller=user&action=list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
