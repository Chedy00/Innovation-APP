<?php
$title = 'Idées à Évaluer - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Idées à Évaluer</h2>
        <div class="header-actions">
            <a href="/evaluateur/ideas/top" class="btn btn-secondary">
                <i class="fas fa-trophy"></i> Top Idées
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($ideas)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <h3>Aucune idée à évaluer</h3>
                <p>Toutes les idées disponibles ont déjà été évaluées par vous. Consultez le classement des meilleures idées.</p>
                <a href="/evaluateur/ideas/top" class="btn btn-primary">
                    <i class="fas fa-trophy"></i> Voir le Top des Idées
                </a>
            </div>
        <?php else: ?>
            <div class="ideas-to-evaluate">
                <?php foreach ($ideas as $idea): ?>
                    <div class="evaluation-card">
                        <div class="idea-info">
                            <div class="idea-header">
                                <h3 class="idea-title"><?= htmlspecialchars($idea['titre']) ?></h3>
                                <span class="status-badge status-<?= $idea['statut'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $idea['statut'])) ?>
                                </span>
                            </div>
                            
                            <div class="idea-meta">
                                <span class="author">
                                    <i class="fas fa-user"></i>
                                    <?= htmlspecialchars($idea['salarie_prenom'] . ' ' . $idea['salarie_nom']) ?>
                                </span>
                                <span class="thematique">
                                    <i class="fas fa-tag"></i>
                                    <?= htmlspecialchars($idea['thematique_nom']) ?>
                                </span>
                                <span class="date">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($idea['date_soumission'])) ?>
                                </span>
                            </div>
                            
                            <div class="idea-description">
                                <?= htmlspecialchars(substr($idea['description'], 0, 200)) ?>
                                <?= strlen($idea['description']) > 200 ? '...' : '' ?>
                            </div>
                        </div>
                        
                        <div class="evaluation-actions">
                            <a href="/evaluateur/ideas/evaluate/<?= $idea['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-star"></i> Évaluer cette Idée
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.header-actions {
    display: flex;
    gap: 0.5rem;
}

.ideas-to-evaluate {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.evaluation-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 0.75rem;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.evaluation-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.idea-info {
    flex: 1;
}

.idea-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.idea-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    line-height: 1.4;
}

.idea-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.idea-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.idea-description {
    color: var(--gray-700);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.evaluation-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 200px;
}

@media (max-width: 768px) {
    .evaluation-card {
        flex-direction: column;
    }
    
    .evaluation-actions {
        min-width: auto;
        width: 100%;
    }
    
    .idea-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .idea-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

