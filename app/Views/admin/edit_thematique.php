<?php
$title = 'Modifier une Thématique - Admin';
$pageTitle = 'Modifier la Thématique';
$breadcrumb = [
    ['label' => 'Administration', 'url' => '/admin/users'],
    ['label' => 'Thématiques', 'url' => '/admin/thematiques'],
    ['label' => 'Modifier']
];

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Modifier la Thématique : <?= htmlspecialchars($thematique['nom']) ?></h2>
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

        <form method="POST" action="/admin/thematiques/update/<?= $thematique['id'] ?>" id="editThematiqueForm">
            <div class="form-group">
                <label for="nom" class="form-label">Nom de la thématique *</label>
                <input type="text" id="nom" name="nom" class="form-input" 
                       value="<?= htmlspecialchars($thematique['nom']) ?>" required
                       placeholder="Ex: Innovation Technologique, Environnement...">
                <span class="error-text" id="nom-error"></span>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-textarea" 
                          placeholder="Décrivez cette thématique et les types d'idées qu'elle peut contenir..."><?= htmlspecialchars($thematique['description']) ?></textarea>
                <span class="error-text" id="description-error"></span>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Sauvegarder les Modifications
                </button>
                <a href="/admin/thematiques" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editThematiqueForm').addEventListener('submit', function(e) {
    let isValid = true;
    clearErrors();
    
    const nom = document.getElementById('nom').value.trim();
    
    if (!nom) {
        showError('nom-error', 'Le nom de la thématique est requis.');
        isValid = false;
    } else if (nom.length > 255) {
        showError('nom-error', 'Le nom ne peut pas dépasser 255 caractères.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/backoffice.php';
?>

