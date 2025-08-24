<?php
$title = 'Évaluer une Idée - Innovation Hub';

ob_start();
?>

<div class="evaluation-page">
    <div class="idea-to-evaluate">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Idée à Évaluer</h2>
            </div>
            <div class="card-body">
                <div class="idea-header">
                    <h1 class="idea-title"><?= htmlspecialchars($idea['titre']) ?></h1>
                    <span class="status-badge status-<?= $idea['statut'] ?>">
                        <?= ucfirst(str_replace('_', ' ', $idea['statut'])) ?>
                    </span>
                </div>
                
                <div class="idea-meta">
                    <span class="author">
                        <i class="fas fa-user"></i>
                        Proposée par <?= htmlspecialchars($idea['salarie_prenom'] . ' ' . $idea['salarie_nom']) ?>
                    </span>
                    <span class="thematique">
                        <i class="fas fa-tag"></i>
                        <?= htmlspecialchars($idea['thematique_nom']) ?>
                    </span>
                    <span class="date">
                        <i class="fas fa-calendar"></i>
                        Soumise le <?= date('d/m/Y à H:i', strtotime($idea['date_soumission'])) ?>
                    </span>
                </div>
                
                <div class="idea-description">
                    <h3>Description de l'idée :</h3>
                    <div class="description-content">
                        <?= nl2br(htmlspecialchars($idea['description'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="evaluation-form-section">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Votre Évaluation</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/evaluateur/ideas/store_evaluation/<?= $idea['id'] ?>" id="evaluationForm">
                    <div class="form-group">
                        <label class="form-label">Note (sur 5) *</label>
                        <div class="rating-input">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" id="note-<?= $i ?>" name="note" value="<?= $i ?>" 
                                       <?= ($note ?? '') == $i ? 'checked' : '' ?> required>
                                <label for="note-<?= $i ?>" class="star-label">
                                    <i class="fas fa-star"></i>
                                    <span class="rating-text"><?= $i ?> - <?= 
                                        $i == 1 ? 'Très faible' : 
                                        ($i == 2 ? 'Faible' : 
                                        ($i == 3 ? 'Moyen' : 
                                        ($i == 4 ? 'Bon' : 'Excellent'))) 
                                    ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                        <span class="error-text" id="note-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                        <textarea id="commentaire" name="commentaire" class="form-textarea" 
                                  placeholder="Expliquez votre évaluation : points forts, points faibles, suggestions d'amélioration..."><?= htmlspecialchars($commentaire ?? '') ?></textarea>
                        <span class="error-text" id="commentaire-error"></span>
                    </div>

                    <div class="evaluation-guidelines">
                        <h4><i class="fas fa-info-circle"></i> Critères d'évaluation</h4>
                        <ul>
                            <li><strong>Innovation :</strong> L'idée apporte-t-elle quelque chose de nouveau ?</li>
                            <li><strong>Faisabilité :</strong> Peut-elle être mise en œuvre facilement ?</li>
                            <li><strong>Impact :</strong> Quel bénéfice pour l'entreprise ou les employés ?</li>
                            <li><strong>Clarté :</strong> L'idée est-elle bien expliquée et compréhensible ?</li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer l'Évaluation
                        </button>
                        <a href="/evaluateur/ideas" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la Liste
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.evaluation-page {
    max-width: 900px;
    margin: 0 auto;
    display: grid;
    gap: 2rem;
}

.idea-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.idea-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
    line-height: 1.3;
}

.idea-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 0.5rem;
}

.idea-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    font-weight: 500;
}

.idea-description h3 {
    color: var(--gray-800);
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.description-content {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    padding: 1.5rem;
    line-height: 1.7;
    color: var(--gray-700);
    font-size: 1.05rem;
}

.rating-input {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin: 1rem 0;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--white);
}

.star-label:hover {
    border-color: var(--primary-color);
    background: var(--gray-50);
}

.rating-input input[type="radio"]:checked + .star-label {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
    color: var(--primary-color);
}

.star-label i {
    font-size: 1.5rem;
    color: #fbbf24;
}

.rating-text {
    font-weight: 500;
}

.evaluation-guidelines {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1px solid #22c55e;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.evaluation-guidelines h4 {
    color: #15803d;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.evaluation-guidelines ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.evaluation-guidelines li {
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(34, 197, 94, 0.2);
    color: #15803d;
}

.evaluation-guidelines li:last-child {
    border-bottom: none;
}

.evaluation-guidelines strong {
    color: #166534;
}

@media (max-width: 768px) {
    .idea-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .idea-meta {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .rating-input {
        gap: 0.5rem;
    }
    
    .star-label {
        padding: 0.75rem;
    }
}
</style>

<script>
document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    let isValid = true;
    clearErrors();
    
    const note = document.querySelector('input[name="note"]:checked');
    
    if (!note) {
        showError('note-error', 'Veuillez attribuer une note.');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Animation des étoiles au survol
document.querySelectorAll('.star-label').forEach(label => {
    label.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.02)';
    });
    
    label.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});
</script>
<script>
// ✅ Force scroll to top only if there's an error
if (document.querySelector('.alert.alert-error')) {
    document.body.style.scrollBehavior = 'auto';
    window.scrollTo(0, 0);
    
    // Optional: re-enable smooth scrolling after
    setTimeout(() => {
        document.body.style.scrollBehavior = 'smooth';
    }, 50);
}
</script>


<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/frontoffice.php';
?>

