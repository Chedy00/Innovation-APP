<?php

require_once __DIR__ . "/../Models/IdeaModel.php";
require_once __DIR__ . "/../Models/ThematicModel.php";
require_once __DIR__ . "/../Models/EvaluationModel.php";
require_once __DIR__ . "/AuthController.php";

class SalarieController {
    private $ideaModel;
    private $thematicModel;
    private $evaluationModel;

    public function __construct() {
        AuthController::requireRole('salarie');
        $this->ideaModel = new IdeaModel();
        $this->thematicModel = new ThematicModel();
        $this->evaluationModel = new EvaluationModel();
    }

    public function myIdeas() {
    $salarieId = Session::get('user_id');
    $ideas = $this->ideaModel->findBySalarie($salarieId);
    
    $enhancedIdeas = [];
    foreach ($ideas as $idea) {
        $idea['evaluations'] = $this->evaluationModel->findByIdea($idea['id']);
        $idea['moyenne_note'] = $this->evaluationModel->getAverageNote($idea['id']);
        $idea['nb_evaluations'] = $this->evaluationModel->getEvaluationCount($idea['id']);
        $enhancedIdeas[] = $idea;
    }
    
    // Pass to view
    $ideas = $enhancedIdeas;
    require_once __DIR__ . "/../Views/salarie/my_ideas.php";
}

    public function submitIdea() {
        $thematiques = $this->thematicModel->findAll();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $id_thematique = $_POST['id_thematique'] ?? '';

            // Validation
            if (empty($titre) || empty($description) || empty($id_thematique)) {
                $error = 'Tous les champs sont requis.';
            } elseif (strlen($titre) > 255) {
                $error = 'Le titre ne peut pas dépasser 255 caractères.';
            } elseif (!$this->thematicModel->findById($id_thematique)) {
                $error = 'Thématique invalide.';
            } else {
                $data = [
                    'titre' => $titre,
                    'description' => $description,
                    'id_salarie' => Session::get('user_id'),
                    'id_thematique' => $id_thematique
                ];

                if ($this->ideaModel->create($data)) {
                    $success = 'Idée soumise avec succès.';
                    $titre = $description = $id_thematique = '';
                } else {
                    $error = 'Erreur lors de la soumission de l\'idée.';
                }
            }
        }

        require_once __DIR__ . "/../Views/salarie/submit_idea.php";
    }

    public function storeIdea() {
        $this->submitIdea();
    }

    public function viewIdea($id) {
        $idea = $this->ideaModel->getIdeaWithEvaluations($id);
        
        if (!$idea || $idea['id_salarie'] != Session::get('user_id')) {
            header('Location: /salarie/ideas');
            exit;
        }

        $idea['moyenne_note'] = $this->evaluationModel->getAverageNote($idea['id']);
        $idea['nb_evaluations'] = $this->evaluationModel->getEvaluationCount($idea['id']);

        require_once __DIR__ . "/../Views/salarie/view_idea.php";
    }

    // ✅ New: Edit Idea Form
    public function editIdea($id) {
    $idea = $this->ideaModel->findById($id);

    if (!$idea || $idea['id_salarie'] != Session::get('user_id')) {
        header('Location: /salarie/ideas');
        exit;
    }

    // ✅ Add nb_evaluations
    $idea['nb_evaluations'] = $this->evaluationModel->getEvaluationCount($id);

    // 🔥 Block if already evaluated
    if ($idea['nb_evaluations'] > 0) {
        $_SESSION['error'] = "Impossible de modifier une idée déjà évaluée.";
        header('Location: /salarie/ideas');
        exit;
    }

    $thematiques = $this->thematicModel->findAll();
    $error = '';

    require_once __DIR__ . "/../Views/salarie/edit_idea.php";
}
    // ✅ New: Update Idea
    public function updateIdea($id) {
        $idea = $this->ideaModel->findById($id);

        if (!$idea || $idea['id_salarie'] != Session::get('user_id')) {
            header('Location: /salarie/ideas');
            exit;
        }

        // 🔥 Block if already evaluated
        $evaluationCount = $this->evaluationModel->getEvaluationCount($id);
        if ($evaluationCount > 0) {
            $_SESSION['error'] = "Impossible de modifier une idée déjà évaluée.";
            header('Location: /salarie/ideas');
            exit;
        }

        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $id_thematique = $_POST['id_thematique'] ?? '';

        if (empty($titre) || empty($description) || empty($id_thematique)) {
            $error = 'Tous les champs sont requis.';
        } elseif (strlen($titre) > 255) {
            $error = 'Le titre ne peut pas dépasser 255 caractères.';
        } elseif (!$this->thematicModel->findById($id_thematique)) {
            $error = 'Thématique invalide.';
        } else {
            $data = [
                'titre' => $titre,
                'description' => $description,
                'id_thematique' => $id_thematique,
                'statut' => $idea['statut'] // Keep current status
            ];

            if ($this->ideaModel->update($id, $data)) {
                $_SESSION['success'] = "Idée mise à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
            }
        }

        header('Location: /salarie/ideas');
        exit;
    }

    // ✅ New: Delete Idea
    public function deleteIdea($id) {
        $idea = $this->ideaModel->findById($id);

        if (!$idea || $idea['id_salarie'] != Session::get('user_id')) {
            header('Location: /salarie/ideas');
            exit;
        }

        // 🔥 Block if already evaluated
        $evaluationCount = $this->evaluationModel->getEvaluationCount($id);
        if ($evaluationCount > 0) {
            $_SESSION['error'] = "Impossible de supprimer une idée déjà évaluée.";
        } else {
            if ($this->ideaModel->delete($id)) {
                $_SESSION['success'] = "Idée supprimée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression.";
            }
        }

        header('Location: /salarie/ideas');
        exit;
    }
}