<?php
$title = 'Gestion des Utilisateurs - Admin';
$pageTitle = 'Gestion des Utilisateurs';
$breadcrumb = [
    ['label' => 'Administration', 'url' => '/admin/users'],
    ['label' => 'Utilisateurs']
];

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste des Utilisateurs</h2>
        <a href="/admin/users/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel Utilisateur
        </a>
    </div>
    <div class="card-body">
        <!-- 🔍 Your Search Input -->
        <div class="table-search" style="margin-bottom: 1rem; text-align: right;">
            <input 
                type="text" 
                id="search-users" 
                placeholder="Rechercher dans tous les champs..." 
                style="padding: 0.5rem; width: 300px; border: 1px solid #d1d5db; border-radius: 0.375rem;"
            >
        </div>

        <?php if (empty($users)): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>Aucun utilisateur</h3>
                <p>Commencez par créer votre premier utilisateur.</p>
                <a href="/admin/users/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un utilisateur
                </a>
            </div>
        <?php else: ?>
            <div class="table-container" style="position: relative;">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom Complet</th>
                            <th class="no-sort">Email</th>
                            <th>Rôle</th>
                            <th>Date de Création</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="status-badge role-<?= htmlspecialchars($user['role']) ?>">
                                        <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="/admin/users/edit/<?= (int)$user['id'] ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form method="POST" action="/admin/users/delete/<?= (int)$user['id'] ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ✅ Universal Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-users');
    const tableBody = document.getElementById('users-table-body');
    const rows = tableBody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function () {
        const term = this.value.toLowerCase().trim();

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        }
    });

    // ✅ Remove any duplicate search bar added by global JS
    setTimeout(() => {
        const allSearchBars = document.querySelectorAll('.table-search');
        allSearchBars.forEach((bar, index) => {
            // Keep only the one containing our input
            if (!bar.contains(searchInput)) {
                bar.remove();
            }
        });
    }, 100);
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/backoffice.php';
?>