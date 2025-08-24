<?php
$title = 'Modifier un Utilisateur - Admin';
$pageTitle = 'Modifier l\'Utilisateur';
$breadcrumb = [
    ['label' => 'Administration', 'url' => '/admin/users'],
    ['label' => 'Utilisateurs', 'url' => '/admin/users'],
    ['label' => 'Modifier']
];

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Modifier l'Utilisateur : <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
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

        <form method="POST" action="/admin/users/update/<?= $user['id'] ?>" id="editUserForm">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" id="nom" name="nom" class="form-input" 
                           value="<?= htmlspecialchars($user['nom']) ?>" required>
                    <span class="error-text" id="nom-error"></span>
                </div>

                <div class="form-group">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" class="form-input" 
                           value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    <span class="error-text" id="prenom-error"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-input" 
                       value="<?= htmlspecialchars($user['email']) ?>" required>
                <span class="error-text" id="email-error"></span>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="mot_de_passe" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-input">
                    <small style="color: #64748b;">Laissez vide pour conserver le mot de passe actuel</small>
                    <span class="error-text" id="mot_de_passe-error"></span>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Rôle *</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                        <option value="salarie" <?= $user['role'] === 'salarie' ? 'selected' : '' ?>>Salarié</option>
                        <option value="evaluateur" <?= $user['role'] === 'evaluateur' ? 'selected' : '' ?>>Évaluateur</option>
                    </select>
                    <span class="error-text" id="role-error"></span>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Sauvegarder les Modifications
                </button>
                <a href="/admin/users" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    let isValid = true;
    clearErrors();
    
    // Validation des champs
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const email = document.getElementById('email').value.trim();
    const motDePasse = document.getElementById('mot_de_passe').value.trim();
    const role = document.getElementById('role').value;
    
    if (!nom) {
        showError('nom-error', 'Le nom est requis.');
        isValid = false;
    }
    
    if (!prenom) {
        showError('prenom-error', 'Le prénom est requis.');
        isValid = false;
    }
    
    if (!email) {
        showError('email-error', 'L\'email est requis.');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('email-error', 'Format d\'email invalide.');
        isValid = false;
    }
    
    if (motDePasse && motDePasse.length < 6) {
        showError('mot_de_passe-error', 'Le mot de passe doit contenir au moins 6 caractères.');
        isValid = false;
    }
    
    if (!role) {
        showError('role-error', 'Veuillez sélectionner un rôle.');
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

