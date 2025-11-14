<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-utensils fa-3x text-success mb-3"></i>
                        <h3 class="card-title">Connexion</h3>
                        <p class="text-muted">Accédez à votre compte RestoCampus</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/resto-campus/index.php?controller=user&action=login" data-validate="true">
                        <div class="mb-3">
                            <label for="login" class="form-label">
                                <i class="fas fa-user"></i> Login
                            </label>
                            <input type="text" class="form-control" id="login" name="login"
                                   placeholder="Votre login" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Mot de passe
                            </label>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Votre mot de passe" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Première visite ?</p>
                        <p class="mb-0">
                            Contactez l'administration pour obtenir vos identifiants d'accès au système de réservation.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="/resto-campus/" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
