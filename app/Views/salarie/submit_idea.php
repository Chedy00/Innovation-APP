<?php
$title = 'Soumettre une Idée - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Soumettre une Nouvelle Idée</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/salarie/ideas/store" id="submitIdeaForm">
            <div class="form-group">
                <label for="titre" class="form-label">Titre de l'idée *</label>
                <input type="text" id="titre" name="titre" class="form-input" 
                       value="<?= htmlspecialchars($titre ?? '') ?>" required
                       placeholder="Donnez un titre accrocheur à votre idée...">
                <span class="error-text" id="titre-error"></span>
            </div>

            <div class="form-group">
                <label for="id_thematique" class="form-label">Thématique *</label>
                <select id="id_thematique" name="id_thematique" class="form-select" required>
                    <option value="">Sélectionner une thématique</option>
                    <?php foreach ($thematiques as $thematique): ?>
                        <option value="<?= $thematique['id'] ?>" 
                                <?= ($id_thematique ?? '') == $thematique['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($thematique['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-text" id="id_thematique-error"></span>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description détaillée *</label>
                <textarea id="description" name="description" class="form-textarea" required
                          placeholder="Décrivez votre idée en détail : le problème qu'elle résout, comment elle fonctionne, ses avantages..."><?= htmlspecialchars($description ?? '') ?></textarea>
                <span class="error-text" id="description-error"></span>
            </div>

            <div class="idea-tips">
                <h4><i class="fas fa-lightbulb"></i> Conseils pour une bonne idée</h4>
                <ul>
                    <li><strong>Soyez précis :</strong> Expliquez clairement le problème et votre solution</li>
                    <li><strong>Pensez impact :</strong> Quel bénéfice apportera votre idée à l'entreprise ?</li>
                    <li><strong>Soyez réaliste :</strong> Proposez quelque chose de faisable</li>
                    <li><strong>Donnez des exemples :</strong> Illustrez votre propos avec des cas concrets</li>
                </ul>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Soumettre l'Idée
                </button>
                <a href="/salarie/ideas" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à Mes Idées
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.idea-tips {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #0ea5e9;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.idea-tips h4 {
    color: #0369a1;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.idea-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.idea-tips li {
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(14, 165, 233, 0.2);
    color: #0369a1;
}

.idea-tips li:last-child {
    border-bottom: none;
}

.idea-tips strong {
    color: #1e40af;
}
</style>

<script>
document.getElementById('submitIdeaForm').addEventListener('submit', function(e) {
    let isValid = true;
    clearErrors();
    
    const titre = document.getElementById('titre').value.trim();
    const description = document.getElementById('description').value.trim();
    const thematique = document.getElementById('id_thematique').value;
    
    if (!titre) {
        showError('titre-error', 'Le titre est requis.');
        isValid = false;
    } else if (titre.length > 255) {
        showError('titre-error', 'Le titre ne peut pas dépasser 255 caractères.');
        isValid = false;
    }
    
    if (!description) {
        showError('description-error', 'La description est requise.');
        isValid = false;
    } else if (description.length < 50) {
        showError('description-error', 'La description doit contenir au moins 50 caractères.');
        isValid = false;
    }
    
    if (!thematique) {
        showError('id_thematique-error', 'Veuillez sélectionner une thématique.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Compteur de caractères pour la description
document.getElementById('description').addEventListener('input', function() {
    const length = this.value.length;
    const minLength = 50;
    
    if (length < minLength) {
        this.style.borderColor = '#f59e0b';
    } else {
        this.style.borderColor = '#10b981';
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

