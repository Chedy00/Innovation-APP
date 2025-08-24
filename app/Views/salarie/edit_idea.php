<?php
$title = 'Modifier une Idée - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Modifier l'Idée</h2>
        <a href="/salarie/ideas" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
    <div class="card-body">
        <!-- ✅ Show error if idea is evaluated -->
        <?php if ($idea['nb_evaluations'] > 0): ?>
            <div class="alert alert-error">
                <i class="fas fa-ban"></i>
                Impossible de modifier une idée déjà évaluée.
            </div>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/salarie/ideas/update/<?= $idea['id'] ?>" id="ideaForm">
                <div class="form-group">
                    <label for="titre">Titre de l'idée *</label>
                    <input 
                        type="text" 
                        id="titre" 
                        name="titre" 
                        value="<?= htmlspecialchars($idea['titre']) ?>" 
                        maxlength="255" 
                        required
                        class="form-input"
                    >
                    <span class="error-text" id="titre-error"></span>
                </div>

                <div class="form-group">
                    <label for="id_thematique">Thématique *</label>
                    <select id="id_thematique" name="id_thematique" required class="form-select">
                        <option value="">Sélectionner une thématique</option>
                        <?php foreach ($thematiques as $thematique): ?>
                            <option value="<?= $thematique['id'] ?>" <?= $thematique['id'] == $idea['id_thematique'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($thematique['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error-text" id="id_thematique-error"></span>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="6" 
                        required
                        class="form-textarea"
                        placeholder="Décrivez votre idée en détail..."><?= htmlspecialchars($idea['description']) ?></textarea>
                    <small>Minimum 50 caractères</small>
                    <span class="error-text" id="description-error"></span>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour l'idée
                </button>
            </form>

            <script>
                // Reuse the same validation as submit_idea
                document.getElementById('ideaForm').addEventListener('submit', function(e) {
                    let isValid = true;
                    clearErrors();

                    const titre = document.getElementById('titre').value.trim();
                    const id_thematique = document.getElementById('id_thematique').value;
                    const description = document.getElementById('description').value.trim();

                    if (!titre) {
                        showError('titre-error', 'Le titre est requis.');
                        isValid = false;
                    } else if (titre.length > 255) {
                        showError('titre-error', 'Le titre ne peut pas dépasser 255 caractères.');
                        isValid = false;
                    }

                    if (!id_thematique) {
                        showError('id_thematique-error', 'Veuillez sélectionner une thématique.');
                        isValid = false;
                    }

                    if (!description) {
                        showError('description-error', 'La description est requise.');
                        isValid = false;
                    } else if (description.length < 50) {
                        showError('description-error', 'La description doit contenir au moins 50 caractères.');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });

                function showError(id, message) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = message;
                }

                function clearErrors() {
                    const errors = document.querySelectorAll('.error-text');
                    errors.forEach(el => el.textContent = '');
                }
            </script>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>