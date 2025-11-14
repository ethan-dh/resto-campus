<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus"></i> Ajouter un Article
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

                    <form method="post" action="/resto-campus/index.php?controller=article&action=add" data-validate="true">
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="fas fa-utensils"></i> Nom de l'article *
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                   value="<?php echo $_POST['nom'] ?? ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $_POST['description'] ?? ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-list"></i> Ingrédients
                            </label>
                            <div id="ingredients-container">
                                <div class="ingredient-row mb-2">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="ingredients[0][nom]" placeholder="Nom de l'ingrédient">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="ingredients[0][description]" placeholder="Description (optionnel)">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-ingredient" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm" id="add-ingredient">
                                <i class="fas fa-plus"></i> Ajouter un ingrédient
                            </button>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/resto-campus/index.php?controller=article&action=list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Créer l'article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('add-ingredient').addEventListener('click', function() {
    const container = document.getElementById('ingredients-container');
    const rows = container.querySelectorAll('.ingredient-row');
    const index = rows.length;

    const newRow = document.createElement('div');
    newRow.className = 'ingredient-row mb-2';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" name="ingredients[${index}][nom]" placeholder="Nom de l'ingrédient">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ingredients[${index}][description]" placeholder="Description (optionnel)">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-ingredient">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(newRow);

    // Activer le bouton supprimer pour toutes les lignes
    document.querySelectorAll('.remove-ingredient').forEach(btn => {
        btn.disabled = false;
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-ingredient') || e.target.closest('.remove-ingredient')) {
        const row = e.target.closest('.ingredient-row');
        if (row) {
            row.remove();

            // Désactiver le bouton supprimer s'il ne reste qu'une ligne
            const rows = document.querySelectorAll('.ingredient-row');
            if (rows.length === 1) {
                document.querySelector('.remove-ingredient').disabled = true;
            }
        }
    }
});
</script>
