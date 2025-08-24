<?php
// admin/view_idea.php
// Variable: $idea (array)
ob_start();
?>
<div class="idea-details" style="max-width:700px;margin:0 auto;background:#fff;padding:30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <h1 style="margin-bottom:24px;">Détail de l'idée</h1>
    <table class="table" style="width:100%;margin-bottom:24px;">
        <tr><th style="width:200px;" class="no-sort">Titre</th><td><?= htmlspecialchars($idea['titre']) ?></td></tr>
        <tr><th class="no-sort">Description</th><td><?= nl2br(htmlspecialchars($idea['description'])) ?></td></tr>
        <tr><th class="no-sort">Salarié</th><td><?= htmlspecialchars($idea['salarie_nom'] . ' ' . $idea['salarie_prenom']) ?></td></tr>
        <tr><th class="no-sort">Thématique</th><td><?= htmlspecialchars($idea['thematique_nom']) ?></td></tr>
        <tr><th class="no-sort">Date de soumission</th><td><?= htmlspecialchars($idea['date_soumission']) ?></td></tr>
        <tr><th class="no-sort">Moyenne des étoiles</th><td><?= (isset($idea['moyenne_note']) && $idea['moyenne_note'] !== null) ? round($idea['moyenne_note'], 2) : 'N/A' ?></td></tr>
        <tr>
            <th class="no-sort">Statut</th>
            <td>
                <div style="position: relative;">
                    <?php error_log('Current idea status: ' . $idea['statut']); ?>
                <form action="/admin/updateIdeaStatus/<?= htmlspecialchars($idea['id']) ?>" method="POST" class="status-form" style="display: flex; gap: 15px; align-items: center;">
                        <div class="custom-select" style="position: relative; min-width: 200px;">
                            <select name="statut" class="form-control" style="
                                width: 100%;
                                padding: 10px 15px;
                                border-radius: 8px;
                                border: 2px solid #e0e0e0;
                                background-color: white;
                                font-size: 14px;
                                appearance: none;
                                -webkit-appearance: none;
                                cursor: pointer;
                                transition: all 0.3s ease;
                            " onchange="this.style.backgroundColor = this.options[this.selectedIndex].getAttribute('data-color')">
                                <option value="en_evaluation" data-color="#ffd700" style="background-color: #ffd700;" 
                                    <?= $idea['statut'] === 'en_evaluation' ? 'selected' : '' ?>>En évaluation</option>
                                <option value="approuvee" data-color="#90EE90" style="background-color: #90EE90;" 
                                    <?= $idea['statut'] === 'approuvee' ? 'selected' : '' ?>>Approuvée</option>
                                <option value="rejetee" data-color="#FFB6B6" style="background-color: #FFB6B6;" 
                                    <?= $idea['statut'] === 'rejetee' ? 'selected' : '' ?>>Rejetée</option>
                            </select>
                            <div style="
                                position: absolute;
                                right: 10px;
                                top: 50%;
                                transform: translateY(-50%);
                                pointer-events: none;
                            ">
                                <i class="fas fa-chevron-down" style="color: #666;"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="
                            padding: 10px 20px;
                            border-radius: 8px;
                            background: linear-gradient(145deg, #007bff, #0056b3);
                            color: #fff;
                            border: none;
                            font-weight: 600;
                            text-transform: uppercase;
                            font-size: 13px;
                            letter-spacing: 0.5px;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        " onmouseover="this.style.transform='translateY(-2px)'" 
                          onmouseout="this.style.transform='translateY(0)'">
                            <i class="fas fa-check" style="margin-right: 8px;"></i>
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    </table>
    <h2 style="margin-top:32px;">Évaluations</h2>
    <?php if (!empty($idea['evaluations'])): ?>
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th class="no-sort">Évaluateur</th>
                    <th class="no-sort">Note</th>
                    <th class="no-sort">Commentaire</th>
                    <th class="no-sort">Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($idea['evaluations'] as $eval): ?>
                <tr>
                    <td><?= htmlspecialchars($eval['evaluateur_nom'] . ' ' . $eval['evaluateur_prenom']) ?></td>
                    <td><?= htmlspecialchars($eval['note']) ?></td>
                    <td><?= htmlspecialchars($eval['commentaire']) ?></td>
                    <td><?= htmlspecialchars($eval['date_evaluation']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune évaluation pour cette idée.</p>
    <?php endif; ?>
    <div style="margin-top:32px;text-align:right;">
        <a href="/admin/ideas" class="btn btn-secondary" style="padding:6px 14px;border-radius:4px;background:#6c757d;color:#fff;text-decoration:none;border:none;">Retour à la liste</a>
        <a href="/pdf/ideaPdf/<?= $idea['id'] ?>?lang=fr" class="btn btn-primary" style="padding:6px 14px;border-radius:4px;margin-left:10px;background:#007bff;color:#fff;text-decoration:none;border:none;">Extraire en PDF (FR)</a>
        <a href="/pdf/ideaPdf/<?= $idea['id'] ?>?lang=en" class="btn btn-success" style="padding:6px 14px;border-radius:4px;margin-left:10px;background:#10b981;color:#fff;text-decoration:none;border:none;">Extract as PDF (EN)</a>
    </div>
</div>
<script>
setTimeout(() => {
    const allSearchBars = document.querySelectorAll('.table-search');
    allSearchBars.forEach(bar => {
        bar.remove();
    });
}, 100);
</script>
<?php
$content = ob_get_clean();
$title = "Détail de l'idée";
require_once __DIR__ . '/../layouts/backoffice.php';