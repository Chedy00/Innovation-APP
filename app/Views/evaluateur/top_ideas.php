<?php
$title = 'Top des Idées - Innovation Hub';

ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Top des Meilleures Idées</h2>
        <div class="header-actions">
            <a href="/evaluateur/ideas" class="btn btn-secondary">
                <i class="fas fa-clipboard-list"></i> Idées à Évaluer
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($ideas)): ?>
            <div class="empty-state">
                <i class="fas fa-trophy"></i>
                <h3>Aucune idée évaluée</h3>
                <p>Il n'y a pas encore d'idées évaluées. Commencez par évaluer quelques idées pour voir le classement.</p>
                <a href="/evaluateur/ideas" class="btn btn-primary">
                    <i class="fas fa-star"></i> Évaluer des Idées
                </a>
            </div>
        <?php else: ?>
            <div class="top-ideas-list">
                <?php foreach ($ideas as $index => $idea): ?>
                    <div class="top-idea-card rank-<?= $index + 1 ?>">
                        <div class="rank-badge">
                            <?php if ($index === 0): ?>
                                <i class="fas fa-crown"></i>
                            <?php elseif ($index === 1): ?>
                                <i class="fas fa-medal"></i>
                            <?php elseif ($index === 2): ?>
                                <i class="fas fa-award"></i>
                            <?php else: ?>
                                <span class="rank-number"><?= $index + 1 ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="idea-content">
                            <div class="idea-header">
                                <h3 class="idea-title"><?= htmlspecialchars($idea['titre']) ?></h3>
                                <div class="rating-display">
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= round($idea['moyenne_note']) ? '' : 'empty' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-score"><?= number_format($idea['moyenne_note'], 1) ?>/5</span>
                                </div>
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
                                <span class="evaluations">
                                    <i class="fas fa-users"></i>
                                    <?= $idea['nb_evaluations'] ?> évaluation<?= $idea['nb_evaluations'] > 1 ? 's' : '' ?>
                                </span>
                            </div>
                            
                            <div class="idea-description">
                                <?= htmlspecialchars(substr($idea['description'], 0, 150)) ?>
                                <?= strlen($idea['description']) > 150 ? '...' : '' ?>
                            </div>
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

.top-ideas-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.top-idea-card {
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: 1rem;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.top-idea-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gray-300);
}

.top-idea-card.rank-1 {
    border-color: #fbbf24;
    box-shadow: 0 8px 25px rgba(251, 191, 36, 0.2);
}

.top-idea-card.rank-1::before {
    background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
}

.top-idea-card.rank-2 {
    border-color: #9ca3af;
    box-shadow: 0 6px 20px rgba(156, 163, 175, 0.2);
}

.top-idea-card.rank-2::before {
    background: linear-gradient(90deg, #9ca3af 0%, #6b7280 100%);
}

.top-idea-card.rank-3 {
    border-color: #cd7c2f;
    box-shadow: 0 4px 15px rgba(205, 124, 47, 0.2);
}

.top-idea-card.rank-3::before {
    background: linear-gradient(90deg, #cd7c2f 0%, #92400e 100%);
}

.top-idea-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.rank-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    font-size: 1.5rem;
    font-weight: 700;
    flex-shrink: 0;
}

.rank-1 .rank-badge {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: var(--white);
}

.rank-2 .rank-badge {
    background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
    color: var(--white);
}

.rank-3 .rank-badge {
    background: linear-gradient(135deg, #cd7c2f 0%, #92400e 100%);
    color: var(--white);
}

.rank-badge .rank-number {
    background: var(--gray-200);
    color: var(--gray-700);
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.idea-content {
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

.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.rating-display .rating {
    font-size: 1.25rem;
}

.rating-score {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary-color);
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
}

/* Animations spéciales pour le podium */
.rank-1 .rank-badge {
    animation: goldGlow 2s ease-in-out infinite alternate;
}

.rank-2 .rank-badge {
    animation: silverGlow 2s ease-in-out infinite alternate;
}

.rank-3 .rank-badge {
    animation: bronzeGlow 2s ease-in-out infinite alternate;
}

@keyframes goldGlow {
    from { box-shadow: 0 0 10px rgba(251, 191, 36, 0.5); }
    to { box-shadow: 0 0 20px rgba(251, 191, 36, 0.8); }
}

@keyframes silverGlow {
    from { box-shadow: 0 0 10px rgba(156, 163, 175, 0.5); }
    to { box-shadow: 0 0 20px rgba(156, 163, 175, 0.8); }
}

@keyframes bronzeGlow {
    from { box-shadow: 0 0 10px rgba(205, 124, 47, 0.5); }
    to { box-shadow: 0 0 20px rgba(205, 124, 47, 0.8); }
}

@media (max-width: 768px) {
    .top-idea-card {
        flex-direction: column;
        text-align: center;
    }
    
    .rank-badge {
        align-self: center;
    }
    
    .idea-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .idea-meta {
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

