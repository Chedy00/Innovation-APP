<?php

require_once __DIR__ . "/../Models/IdeaModel.php";
require_once __DIR__ . "/../Models/EvaluationModel.php";
require_once __DIR__ . "/AuthController.php";

class EvaluateurController {
    private $ideaModel;
    private $evaluationModel;

    public function __construct() {
        AuthController::requireRole('evaluateur');
        $this->ideaModel = new IdeaModel();
        $this->evaluationModel = new EvaluationModel();
    }

    public function ideasToEvaluate() {
        $evaluateurId = Session::get('user_id');
        $ideas = $this->ideaModel->findIdeasToEvaluate($evaluateurId);
        
        require_once __DIR__ . "/../Views/evaluateur/ideas_to_evaluate.php";
    }

    public function evaluateIdea($id) {
        $evaluateurId = Session::get('user_id');
        $idea = $this->ideaModel->findById($id);
        
        if (!$idea) {
            header('Location: /evaluateur/ideas');
            exit;
        }

        
        if ($idea['statut'] === 'approuvee' || $idea['statut'] === 'rejetee') {
            header('Location: /evaluateur/ideas?error=Cette idée ne peut plus être évaluée car elle a été ' . ($idea['statut'] === 'approuvee' ? 'approuvée' : 'rejetée'));
            exit;
        }

        if ($this->evaluationModel->hasEvaluated($id, $evaluateurId)) {
            header('Location: /evaluateur/ideas?error=Vous avez déjà évalué cette idée');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = $_POST['note'] ?? '';
            $commentaire = trim($_POST['commentaire'] ?? '');

            // Validation
            if (empty($note)) {
                $error = 'La note est requise.';
            } elseif (!is_numeric($note) || $note < 1 || $note > 5) {
                $error = 'La note doit être comprise entre 1 et 5.';
            } else {
                $data = [
                    'note' => (int)$note,
                    'commentaire' => $commentaire,
                    'id_idee' => $id,
                    'id_evaluateur' => $evaluateurId
                ];

                if ($this->evaluationModel->create($data)) {
                    
                    $this->ideaModel->updateStatus($id, 'en_evaluation');
                    
                    header('Location: /evaluateur/ideas?success=Évaluation enregistrée avec succès');
                    exit;
                } else {
                    $error = 'Erreur lors de l\'enregistrement de l\'évaluation.';
                }
            }
        }

        require_once __DIR__ . "/../Views/evaluateur/evaluate_idea.php";
    }

    public function storeEvaluation($id) {
        $this->evaluateIdea($id);
    }

    public function topIdeas() {
        $ideas = $this->ideaModel->findTopIdeas(20);
        require_once __DIR__ . "/../Views/evaluateur/top_ideas.php";
    }

    public function myEvaluations() {
        $evaluateurId = Session::get('user_id');
        $evaluations = $this->evaluationModel->findByEvaluateur($evaluateurId);
        
        require_once __DIR__ . "/../Views/evaluateur/my_evaluations.php";
    }
}

?>

