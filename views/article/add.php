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
                            <div class="form-text">Nom du plat (ex: "Sandwich Thon", "Salade César")</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $_POST['description'] ?? ''; ?></textarea>
                            <div class="form-text">Description optionnelle du plat</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-list"></i> Ingrédients
                            </label>
                            <div id="ingredients-container">
                                <?php
                                $ingredients = $_POST['ingredients'] ?? [['nom' => '', 'description' => '']];
                                if (!is_array($ingredients)) {
                                    $ingredients = [['nom' => '', 'description' => '']];
                                }
                                foreach ($ingredients as $index => $ingredient):
                                ?>
                                    <div class="ingredient-row mb-2 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Nom de l'ingrédient *</label>
                                                <input type="text" class="form-control"
                                                       name="ingredients[<?php echo $index; ?>][nom]"
                                                       value="<?php echo htmlspecialchars($ingredient['nom'] ?? ''); ?>"
                                                       placeholder="ex: Thon, Tomate, Salade">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control"
                                                       name="ingredients[<?php echo $index; ?>][description]"
                                                       value="<?php echo htmlspecialchars($ingredient['description'] ?? ''); ?>"
                                                       placeholder="ex: Thon naturel, Tomates fraîches">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger remove-ingredient"
                                                        <?php echo count($ingredients) <= 1 ? 'disabled' : ''; ?>>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm" id="add-ingredient">
                                <i class="fas fa-plus"></i> Ajouter un ingrédient
                            </button>
                            <div class="form-text">Au moins un ingrédient doit être défini</div>
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
// Gestion dynamique des ingrédients
document.addEventListener('DOMContentLoaded', function() {
    let ingredientIndex = <?php echo count($ingredients); ?>;

    // Ajouter un ingrédient
    document.getElementById('add-ingredient').addEventListener('click', function() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row mb-2 p-3 border rounded';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">Nom de l'ingrédient *</label>
                    <input type="text" class="form-control"
                           name="ingredients[${ingredientIndex}][nom]"
                           placeholder="ex: Thon, Tomate, Salade">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control"
                           name="ingredients[${ingredientIndex}][description]"
                           placeholder="ex: Thon naturel, Tomates fraîches">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-ingredient">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        ingredientIndex++;
        updateRemoveButtons();
    });

    // Supprimer un ingrédient
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ingredient') || e.target.closest('.remove-ingredient')) {
            const row = e.target.closest('.ingredient-row');
            if (row && document.querySelectorAll('.ingredient-row').length > 1) {
                row.remove();
                updateRemoveButtons();
            }
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.ingredient-row');
        const removeButtons = document.querySelectorAll('.remove-ingredient');

        if (rows.length <= 1) {
            removeButtons.forEach(btn => btn.disabled = true);
        } else {
            removeButtons.forEach(btn => btn.disabled = false);
        }
    }
});
</script>
