<?php
$title = 'Mes Évaluations - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Mes Évaluations</h2>
        <div class="header-actions">
            <a href="/evaluateur/ideas" class="btn btn-primary">
                <i class="fas fa-plus"></i> Évaluer d'Autres Idées
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($evaluations)): ?>
            <div class="empty-state">
                <i class="fas fa-star"></i>
                <h3>Aucune évaluation</h3>
                <p>Vous n'avez pas encore évalué d'idées. Commencez par examiner les propositions soumises.</p>
                <a href="/evaluateur/ideas" class="btn btn-primary">
                    <i class="fas fa-clipboard-list"></i> Voir les Idées à Évaluer
                </a>
            </div>
        <?php else: ?>
            <div class="evaluations-list">
                <?php foreach ($evaluations as $evaluation): ?>
                    <div class="evaluation-item">
                        <div class="evaluation-header">
                            <div class="idea-info">
                                <h3 class="idea-title"><?= htmlspecialchars($evaluation['idee_titre']) ?></h3>
                                <div class="evaluation-meta">
                                    <span class="evaluation-date">
                                        <i class="fas fa-calendar"></i>
                                        Évaluée le <?= date('d/m/Y à H:i', strtotime($evaluation['date_evaluation'])) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="rating-display">
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $evaluation['note'] ? '' : 'empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-score"><?= $evaluation['note'] ?>/5</span>
                            </div>
                        </div>
                        
                        <?php if (!empty($evaluation['commentaire'])): ?>
                            <div class="evaluation-comment">
                                <h4>Mon commentaire :</h4>
                                <div class="comment-content">
                                    <?= nl2br(htmlspecialchars($evaluation['commentaire'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="evaluation-stats">
                <div class="stats-card">
                    <div class="stat-item">
                        <div class="stat-value"><?= count($evaluations) ?></div>
                        <div class="stat-label">Idées Évaluées</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            <?= number_format(array_sum(array_column($evaluations, 'note')) / count($evaluations), 1) ?>
                        </div>
                        <div class="stat-label">Note Moyenne Attribuée</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            <?= count(array_filter($evaluations, function($e) { return !empty($e['commentaire']); })) ?>
                        </div>
                        <div class="stat-label">Avec Commentaire</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.header-actions {
    display: flex;
    gap: 0.5rem;
}

.evaluations-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.evaluation-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s ease;
}

.evaluation-item:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.evaluation-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.idea-info {
    flex: 1;
}

.idea-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.evaluation-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.evaluation-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.rating-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    flex-shrink: 0;
}

.rating-display .rating {
    font-size: 1.25rem;
}

.rating-score {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
}

.evaluation-comment {
    border-top: 1px solid var(--gray-200);
    padding-top: 1rem;
}

.evaluation-comment h4 {
    color: var(--gray-700);
    margin-bottom: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
}

.comment-content {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    padding: 1rem;
    line-height: 1.6;
    color: var(--gray-700);
}

.evaluation-stats {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--gray-200);
}

.stats-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 1rem;
    padding: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 2rem;
    color: var(--white);
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (max-width: 768px) {
    .evaluation-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .rating-display {
        flex-direction: row;
        align-items: center;
    }
    
    .stats-card {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

