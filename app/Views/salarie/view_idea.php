<?php
$title = 'Détail de l\'Idée - Innovation Hub';

ob_start();
?>

<div class="idea-detail">
    <div class="idea-header-section">
        <div class="idea-main-info">
            <h1 class="idea-title"><?= htmlspecialchars($idea['titre']) ?></h1>
            <div class="idea-meta">
                <span class="thematique">
                    <i class="fas fa-tag"></i>
                    <?= htmlspecialchars($idea['thematique_nom']) ?>
                </span>
                <span class="date">
                    <i class="fas fa-calendar"></i>
                    Soumise le <?= date('d/m/Y à H:i', strtotime($idea['date_soumission'])) ?>
                </span>
                <span class="status-badge status-<?= $idea['statut'] ?>">
                    <?= ucfirst(str_replace('_', ' ', $idea['statut'])) ?>
                </span>
            </div>
        </div>
        
        <?php if ($idea['nb_evaluations'] > 0): ?>
            <div class="idea-rating">
                <div class="rating-display">
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($idea['moyenne_note']) ? '' : 'empty' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="rating-info">
                        <span class="rating-score"><?= number_format($idea['moyenne_note'], 1) ?>/5</span>
                        <span class="rating-count"><?= $idea['nb_evaluations'] ?> évaluation<?= $idea['nb_evaluations'] > 1 ? 's' : '' ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Description de l'Idée</h2>
        </div>
        <div class="card-body">
            <div class="idea-description">
                <?= nl2br(htmlspecialchars($idea['description'])) ?>
            </div>
        </div>
    </div>

    <?php if (!empty($idea['evaluations'])): ?>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Évaluations Reçues</h2>
            </div>
            <div class="card-body">
                <div class="evaluations-list">
                    <?php foreach ($idea['evaluations'] as $evaluation): ?>
                        <div class="evaluation-item">
                            <div class="evaluation-header">
                                <div class="evaluator-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="evaluator-name">
                                        <?= htmlspecialchars($evaluation['evaluateur_prenom'] . ' ' . $evaluation['evaluateur_nom']) ?>
                                    </span>
                                </div>
                                <div class="evaluation-meta">
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $evaluation['note'] ? '' : 'empty' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="evaluation-date">
                                        <?= date('d/m/Y à H:i', strtotime($evaluation['date_evaluation'])) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <?php if (!empty($evaluation['commentaire'])): ?>
                                <div class="evaluation-comment">
                                    <?= nl2br(htmlspecialchars($evaluation['commentaire'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <h3>En attente d'évaluation</h3>
                    <p>Votre idée n'a pas encore été évaluée. Les évaluateurs vont bientôt examiner votre proposition.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="actions-section">
        <a href="/salarie/ideas" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à Mes Idées
        </a>
    </div>
</div>

<style>
.idea-detail {
    max-width: 800px;
    margin: 0 auto;
}

.idea-header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 2rem;
}

.idea-main-info {
    flex: 1;
}

.idea-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 1rem;
    line-height: 1.3;
}

.idea-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.idea-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.idea-rating {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 0.75rem;
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
}

.rating-display .rating {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.rating-score {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.rating-count {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.idea-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: var(--gray-700);
}

.evaluations-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.evaluation-item {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.evaluation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.evaluator-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.evaluator-info i {
    font-size: 1.5rem;
    color: var(--gray-500);
}

.evaluator-name {
    font-weight: 600;
    color: var(--gray-800);
}

.evaluation-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.evaluation-date {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.evaluation-comment {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    padding: 1rem;
    line-height: 1.6;
    color: var(--gray-700);
}

.actions-section {
    margin-top: 2rem;
    text-align: center;
}

@media (max-width: 768px) {
    .idea-header-section {
        flex-direction: column;
    }
    
    .idea-title {
        font-size: 1.5rem;
    }
    
    .idea-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .evaluation-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .evaluation-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

