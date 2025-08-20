<?php
$title = 'Gestion des Thématiques - Admin';
$pageTitle = 'Gestion des Thématiques';
$breadcrumb = [
    ['label' => 'Administration', 'url' => '/admin/users'],
    ['label' => 'Thématiques']
];

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste des Thématiques</h2>
        <a href="/admin/thematiques/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Thématique
        </a>
    </div>
    <div class="card-body">
        <!-- 🔍 Your Search Input -->
        <div class="table-search" style="margin-bottom: 1rem; text-align: right;">
            <input 
                type="text" 
                id="search-thematiques" 
                placeholder="Rechercher dans toutes les thématiques..." 
                style="padding: 0.5rem; width: 300px; border: 1px solid #d1d5db; border-radius: 0.375rem;"
            >
        </div>

        <?php if (empty($thematiques)): ?>
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h3>Aucune thématique</h3>
                <p>Commencez par créer votre première thématique.</p>
                <a href="/admin/thematiques/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une thématique
                </a>
            </div>
        <?php else: ?>
            <div class="table-container" style="position: relative;">
                <table class="table" id="thematiques-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th class="no-sort">Description</th>
                            <th>Date de Création</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="thematiques-table-body">
                        <?php foreach ($thematiques as $thematique): ?>
                            <tr>
                                <td><?= htmlspecialchars($thematique['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($thematique['nom']) ?></strong>
                                </td>
                                <td>
                                    <?php if (!empty($thematique['description'])): ?>
                                        <?= htmlspecialchars(substr($thematique['description'], 0, 100)) ?>
                                        <?= strlen($thematique['description']) > 100 ? '...' : '' ?>
                                    <?php else: ?>
                                        <em style="color: #64748b;">Aucune description</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($thematique['date_creation'])) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="/admin/thematiques/edit/<?= (int)$thematique['id'] ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form method="POST" action="/admin/thematiques/delete/<?= (int)$thematique['id'] ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette thématique ?')">
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

<!-- ✅ Universal Search Script - Works Across All Columns -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-thematiques');
    const tableBody = document.getElementById('thematiques-table-body');
    if (!tableBody) return;

    const rows = tableBody.getElementsByTagName('tr');

    // 🔍 Real-time search
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

    console.log("✅ Search script loaded for thématiques");
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/backoffice.php';
?>