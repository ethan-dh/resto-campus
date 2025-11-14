<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Modifier un Article
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

                    <form method="post" action="/resto-campus/index.php?controller=article&action=edit&id=<?php echo $article['idArticle']; ?>" data-validate="true">
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="fas fa-utensils"></i> Nom de l'article *
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom"
                                   value="<?php echo htmlspecialchars($article['nom']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($article['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-list"></i> Ingrédients
                            </label>
                            <div id="ingredients-container">
                                <?php if (!empty($article['ingredients'])): ?>
                                    <?php foreach ($article['ingredients'] as $index => $ingredient): ?>
                                        <div class="ingredient-row mb-2">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="ingredients[<?php echo $index; ?>][nom]"
                                                           value="<?php echo htmlspecialchars($ingredient['nom']); ?>" placeholder="Nom de l'ingrédient">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="ingredients[<?php echo $index; ?>][description]"
                                                           value="<?php echo htmlspecialchars($ingredient['description'] ?? ''); ?>" placeholder="Description (optionnel)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-ingredient">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm" id="add-ingredient">
                                <i class="fas fa-plus"></i> Ajouter un ingrédient
                            </button>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/resto-campus/index.php?controller=article&action=list" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Modifier l'article
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
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-ingredient') || e.target.closest('.remove-ingredient')) {
        const row = e.target.closest('.ingredient-row');
        if (row) {
            row.remove();
        }
    }
});
</script>
