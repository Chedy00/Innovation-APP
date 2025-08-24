<script>
setTimeout(() => {
    const allSearchBars = document.querySelectorAll('.table-search');
    allSearchBars.forEach((bar, index) => {
        if (index > 0 || bar !== document.getElementById('search-ideas')?.parentNode) {
            bar.remove();
        }
    });
}, 100);
</script>

<?php ob_start(); ?>

<h1>Idées soumises par les salariés</h1>

<div class="table-search" style="margin-bottom: 1rem; text-align: right;">
    <input 
        type="text" 
        id="search-ideas" 
        placeholder="Rechercher dans tous les champs..." 
        style="padding: 0.5rem; width: 300px; border: 1px solid #d1d5db; border-radius: 0.375rem;"
    >
</div>

<div class="table-container" style="position: relative;">
    <table class="table" id="ideas-table" style="width:100%; border-collapse:collapse; background:#fff;">
        <thead>
            <tr>
                <th class="no-sort">Titre</th>
                <th>Salarié</th>
                <th>Thématique</th>
                <th>Date de soumission</th>
                <th>Moyenne des étoiles</th>
                <th class="no-sort">Actions</th>
            </tr>
        </thead>
        <tbody id="ideas-table-body">
        <?php if (isset($ideas) && !empty($ideas)): 
            // Pre-load full text for duplicate detection
            $ideaTexts = [];
            foreach ($ideas as $i) {
                $text = strtolower(strip_tags(($i['titre'] ?? '') . ' ' . ($i['description'] ?? '')));
                $ideaTexts[$i['id']] = $text;
            }

            foreach ($ideas as $idea): 
                // Detect duplicates (50% similarity)
                $duplicates = [];
                $current = $ideaTexts[$idea['id']];
                foreach ($ideas as $other) {
                    if ($other['id'] == $idea['id']) continue;
                    $otherText = $ideaTexts[$other['id']];
                    similar_text($current, $otherText, $percent);
                    if ($percent >= 50) {
                        $duplicates[] = ['id' => $other['id'], 'similarity' => $percent];
                    }
                }

                // Set row background color based on status
                $rowStyle = '';
                if ($idea['statut'] === 'approuvee') {
                    $rowStyle = 'background-color: #f0f9f0; border-left: 3px solid #22c55e;';
                } elseif ($idea['statut'] === 'rejetee') {
                    $rowStyle = 'background-color: #fef2f2; border-left: 3px solid #ef4444;';
                }
                if (!empty($duplicates)) {
                    $rowStyle = str_replace('border-left:', 'border-left: 6px solid #f59e0b; ', $rowStyle);
                }
        ?>
            <tr style="<?= $rowStyle ?>"
                title="<?= !empty($duplicates) ? '⚠️ Idée similaire détectée à ' . round($duplicates[0]['similarity']) . '%' : '' ?>">
                <td>
                    <?= htmlspecialchars($idea['titre']) ?>
                    <?php if (!empty($duplicates)): ?>
                        <span style="color: #f59e0b; margin-left: 6px; font-size: 1.2em;">⚠️</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($idea['salarie_nom'] . ' ' . $idea['salarie_prenom']) ?></td>
                <td><?= htmlspecialchars($idea['thematique_nom']) ?></td>
                <td><?= htmlspecialchars($idea['date_soumission']) ?></td>
                <td>
                    <?php 
                    echo isset($idea['moyenne_note']) && $idea['moyenne_note'] !== null 
                        ? round($idea['moyenne_note'], 2) 
                        : 'N/A'; 
                    ?>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="/admin/viewIdea/<?= $idea['id'] ?>" class="btn btn-primary btn-sm" style="margin-right:6px;">
                            Voir
                        </a>
                        <form method="post" action="/admin/deleteIdea/<?= $idea['id'] ?>" onsubmit="return confirm('Supprimer cette idée ?');" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6">Aucune idée trouvée.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Live search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-ideas');
        const tableBody = document.getElementById('ideas-table-body');
        
        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            Array.from(tableBody.rows).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
$title = 'Gestion des idées';
require_once __DIR__ . '/../layouts/backoffice.php';
?>