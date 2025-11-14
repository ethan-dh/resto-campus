<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> Commander - <?php echo htmlspecialchars($disponibilite['article_nom']); ?>
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

                    <div class="row">
                        <div class="col-md-8">
                            <h6><?php echo htmlspecialchars($disponibilite['article_nom']); ?></h6>
                            <?php if (!empty($disponibilite['article_description'])): ?>
                                <p class="text-muted"><?php echo htmlspecialchars($disponibilite['article_description']); ?></p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <strong>Créneau:</strong>
                                <?php echo date('d/m/Y H:i', strtotime($disponibilite['dateHeureDebut'])); ?> -
                                <?php echo date('H:i', strtotime($disponibilite['dateHeureFin'])); ?>
                            </div>

                            <div class="mb-3">
                                <strong>Quantité disponible:</strong>
                                <span class="badge badge-disponible"><?php echo $disponibilite['quantiteMax']; ?> portions</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <form method="post" action="/resto-campus/index.php?controller=article&action=order&id=<?php echo $disponibilite['idDispo']; ?>" data-validate="true">
                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite"
                                           value="1" min="1" max="<?php echo $disponibilite['quantiteMax']; ?>" required>
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Confirmer la commande
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="/resto-campus/index.php?controller=article&action=listDispo" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour aux articles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
