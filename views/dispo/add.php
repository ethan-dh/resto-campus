<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus"></i> Ajouter une Disponibilité
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

                    <form method="post" action="/resto-campus/index.php?controller=dispo&action=add" data-validate="true">
                        <div class="mb-3">
                            <label for="idArticle" class="form-label">
                                <i class="fas fa-utensils"></i> Article *
                            </label>
                            <select class="form-select" id="idArticle" name="idArticle" required>
                                <option value="">Choisir un article</option>
                                <?php foreach ($articles as $article): ?>
                                    <option value="<?php echo $article['idArticle']; ?>"
                                            <?php echo ($_POST['idArticle'] ?? '') == $article['idArticle'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($article['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dateHeureDebut" class="form-label">
                                    <i class="fas fa-calendar"></i> Date et heure de début *
                                </label>
                                <input type="datetime-local" class="form-control" id="dateHeureDebut" name="dateHeureDebut"
                                       value="<?php echo $_POST['dateHeureDebut'] ?? ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dateHeureFin" class="form-label">
                                    <i class="fas fa-calendar"></i> Date et heure de fin *
                                </label>
                                <input type="datetime-local" class="form-control" id="dateHeureFin" name="dateHeureFin"
                                       value="<?php echo $_POST['dateHeureFin'] ?? ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quantiteMax" class="form-label">
                                <i class="fas fa-hashtag"></i> Quantité maximale *
                            </label>
                            <input type="number" class="form-control" id="quantiteMax" name="quantiteMax"
                                   value="<?php echo $_POST['quantiteMax'] ?? '1'; ?>" min="1" required>
                            <div class="form-text">Nombre maximum de portions disponibles</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/resto-campus/index.php?controller=dispo&action=list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer la disponibilité
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
