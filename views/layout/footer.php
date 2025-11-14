        </div>
    </main>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>RestoCampus</h5>
                    <p>Système de réservation de repas pour étudiants</p>
                </div>
                <div class="col-md-3">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="/resto-campus/">Accueil</a></li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                <li><a href="/resto-campus/index.php?controller=user&action=list">Administration</a></li>
                            <?php else: ?>
                                <li><a href="/resto-campus/index.php?controller=commande&action=myOrders">Mes Commandes</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> contact@restocampus.edu</li>
                        <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                        <li><i class="fas fa-map-marker-alt"></i> Campus Universitaire</li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <small>&copy; <?php echo date('Y'); ?> RestoCampus. Tous droits réservés.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personnalisé -->
    <script src="/resto-campus/assets/js/script.js"></script>
</body>
</html>
