<?php
$title = 'Mes Idées - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Mes Idées Soumises</h2>
        <a href="/salarie/ideas/submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Idée
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($ideas)): ?>
            <div class="empty-state">
                <i class="fas fa-lightbulb"></i>
                <h3>Aucune idée soumise</h3>
                <p>Vous n'avez pas encore soumis d'idée. Commencez par partager votre première innovation !</p>
                <a href="/salarie/ideas/submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Soumettre une idée
                </a>
            </div>
        <?php else: ?>
            <div class="ideas-grid">
                <?php foreach ($ideas as $idea): ?>
                    <div class="idea-card">
                        <div class="idea-header">
                            <h3 class="idea-title"><?= htmlspecialchars($idea['titre']) ?></h3>
                            <span class="status-badge status-<?= $idea['statut'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $idea['statut'])) ?>
                            </span>
                        </div>
                        
                        <div class="idea-meta">
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
                            <?= htmlspecialchars(substr($idea['description'], 0, 150)) ?>
                            <?= strlen($idea['description']) > 150 ? '...' : '' ?>
                        </div>
                        
                        <div class="idea-stats">
                            <?php if ($idea['nb_evaluations'] > 0): ?>
                                <div class="rating-info">
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= round($idea['moyenne_note']) ? '' : 'empty' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-text">
                                        <?= number_format($idea['moyenne_note'], 1) ?>/5 
                                        (<?= $idea['nb_evaluations'] ?> évaluation<?= $idea['nb_evaluations'] > 1 ? 's' : '' ?>)
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="no-rating">
                                    <i class="fas fa-clock"></i>
                                    En attente d'évaluation
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="idea-actions">
                            <a href="/salarie/ideas/view/<?= $idea['id'] ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> Voir Détails
                            </a>

                            <?php if ($idea['statut'] !== 'evalue'): ?>
                                <a href="/salarie/ideas/edit/<?= $idea['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form method="POST" action="/salarie/ideas/delete/<?= $idea['id'] ?>" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette idée ?')">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="small-text" style="color: #64748b;">
                                    <i class="fas fa-lock"></i> Verrouillée (évaluée)
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.ideas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.idea-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 0.75rem;
    padding: 1.5rem;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.idea-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.idea-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.idea-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    line-height: 1.4;
}

.idea-meta {
    display: flex;
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

.idea-stats {
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-text {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.no-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-500);
    font-size: 0.875rem;
}

.idea-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-warning {
    background: #f59e0b;
    color: white;
    border: none;
}

.btn-warning:hover {
    background: #d97706;
}

.small-text {
    font-size: 0.875rem;
}
</style>

<?php
$content = ob_get_clean();
// ✅ Fixed path: from salarie/ → go up one → layouts/
require_once __DIR__ . '/../layouts/frontoffice.php';
?>