<?php

require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/ThematicModel.php";
require_once __DIR__ . "/../Models/IdeaModel.php";
require_once __DIR__ . "/AuthController.php";

class AdminController {
    private $userModel;
    private $thematicModel;
    private $ideaModel;

    public function __construct() {
        AuthController::requireRole('admin');
        $this->userModel = new UserModel();
        $this->thematicModel = new ThematicModel();
        $this->ideaModel = new IdeaModel();
    }

    // ======================
    // DUPLICATE DETECTION
    // ======================

    /**
     * Find potentially duplicate ideas
     * @param string $newTitle
     * @param string $newDescription
     * @param int|null $excludeId (optional, to ignore self)
     * @return array List of similar ideas
     */
    private function findDuplicateIdeas($newTitle, $newDescription, $excludeId = null) {
        $threshold = 50; // % similarity threshold
        $allIdeas = $this->ideaModel->findAll();
        $duplicates = [];

        $newText = strtolower(strip_tags($newTitle . ' ' . $newDescription));

        foreach ($allIdeas as $idea) {
            if ($idea['id'] == $excludeId) continue;

            $existingText = strtolower(strip_tags($idea['titre'] . ' ' . $idea['description']));
            similar_text($newText, $existingText, $percent);

            if ($percent >= $threshold) {
                $duplicates[] = [
                    'idea' => $idea,
                    'similarity' => round($percent, 1)
                ];
            }
        }

        
        usort($duplicates, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return $duplicates;
    }

  

    public function deleteIdea($id) {
        if ($this->ideaModel->delete($id)) {
            header('Location: /admin/ideas?success=Idée supprimée avec succès');
        } else {
            header('Location: /admin/ideas?error=Erreur lors de la suppression');
        }
        exit;
    }

    public function updateIdeaStatus($id) {
        error_log('Received POST data: ' . print_r($_POST, true));
        error_log('Received ID: ' . $id);

        if (!isset($_POST['statut'])) {
            error_log('Status not set in POST data');
            header('Location: /admin/viewIdea/' . $id . '?error=Statut manquant');
            exit;
        }

        $allowedStatuses = ['en_evaluation', 'approuvee', 'rejetee'];
        if (!in_array($_POST['statut'], $allowedStatuses)) {
            error_log('Invalid status: ' . $_POST['statut']);
            header('Location: /admin/viewIdea/' . $id . '?error=Statut invalide');
            exit;
        }

        $result = $this->ideaModel->updateStatus($id, $_POST['statut']);
        error_log('Update result: ' . ($result ? 'success' : 'failure'));

        if ($result) {
            header('Location: /admin/viewIdea/' . $id . '?success=Statut mis à jour avec succès');
        } else {
            header('Location: /admin/viewIdea/' . $id . '?error=Erreur lors de la mise à jour du statut');
        }
        exit;
    }

    public function viewIdea($id) {
        $ideas = $this->ideaModel->findAll();
        $idea = null;
        foreach ($ideas as $i) {
            if ($i['id'] == $id) {
                $idea = $i;
                break;
            }
        }

        if (!$idea) {
            header('Location: /admin/ideas?error=Idée introuvable');
            exit;
        }

        // Get full idea data with evaluations
        $fullIdea = $this->ideaModel->getIdeaWithEvaluations($id);
        if ($fullIdea) {
            $idea = array_merge($idea, $fullIdea);
        }

        
        $duplicates = $this->findDuplicateIdeas($idea['titre'], $idea['description'], $id);

        require_once __DIR__ . '/../Views/admin/view_idea.php';
    }

    public function manageIdeas() {
        $sort = isset($_GET['sort']) && $_GET['sort'] === 'stars' ? 'stars' : 'date';
        if ($sort === 'stars') {
            $ideas = $this->ideaModel->findTopIdeas(100);
        } else {
            $ideas = $this->ideaModel->findAll();
        }
        require_once __DIR__ . '/../Views/admin/ideas.php';
    }


    public function manageUsers() {
        $users = $this->userModel->findAll();
        require_once __DIR__ . "/../Views/admin/users.php";
    }

    public function createUser() {
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
            $role = $_POST['role'] ?? '';

            if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($role)) {
                $error = 'Tous les champs sont requis.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format d\'email invalide.';
            } elseif ($this->userModel->emailExists($email)) {
                $error = 'Cet email est déjà utilisé.';
            } elseif (!in_array($role, ['admin', 'salarie', 'evaluateur'])) {
                $error = 'Rôle invalide.';
            } else {
                $data = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'mot_de_passe' => $mot_de_passe,
                    'role' => $role
                ];
                if ($this->userModel->create($data)) {
                    $success = 'Utilisateur créé avec succès.';
                    $nom = $prenom = $email = $mot_de_passe = $role = '';
                } else {
                    $error = 'Erreur lors de la création de l\'utilisateur.';
                }
            }
        }
        require_once __DIR__ . "/../Views/admin/create_user.php";
    }

    public function storeUser() {
        $this->createUser();
    }

    public function editUser($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
            $role = $_POST['role'] ?? '';

            if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
                $error = 'Tous les champs sont requis (sauf le mot de passe).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format d\'email invalide.';
            } elseif ($this->userModel->emailExists($email, $id)) {
                $error = 'Cet email est déjà utilisé par un autre utilisateur.';
            } elseif (!in_array($role, ['admin', 'salarie', 'evaluateur'])) {
                $error = 'Rôle invalide.';
            } else {
                $data = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'role' => $role
                ];

                if (!empty($mot_de_passe)) {
                    $data['mot_de_passe'] = $mot_de_passe;
                }

                if ($this->userModel->update($id, $data)) {
                    $success = 'Utilisateur modifié avec succès.';
                    $user = $this->userModel->findById($id);
                } else {
                    $error = 'Erreur lors de la modification de l\'utilisateur.';
                }
            }
        }

        require_once __DIR__ . "/../Views/admin/edit_user.php";
    }

    public function updateUser($id) {
        $this->editUser($id);
    }

    public function deleteUser($id) {
        if ($this->userModel->delete($id)) {
            header('Location: /admin/users?success=Utilisateur supprimé avec succès');
        } else {
            header('Location: /admin/users?error=Erreur lors de la suppression');
        }
        exit;
    }

   

    public function manageThematiques() {
        $thematiques = $this->thematicModel->findAll();
        require_once __DIR__ . "/../Views/admin/thematiques.php";
    }

    public function createThematique() {
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($nom)) {
                $error = 'Le nom de la thématique est requis.';
            } elseif ($this->thematicModel->nameExists($nom)) {
                $error = 'Cette thématique existe déjà.';
            } else {
                $data = [
                    'nom' => $nom,
                    'description' => $description
                ];
                if ($this->thematicModel->create($data)) {
                    $success = 'Thématique créée avec succès.';
                    $nom = $description = '';
                } else {
                    $error = 'Erreur lors de la création de la thématique.';
                }
            }
        }
        require_once __DIR__ . "/../Views/admin/create_thematique.php";
    }

    public function storeThematique() {
        $this->createThematique();
    }

    public function editThematique($id) {
        $thematique = $this->thematicModel->findById($id);
        if (!$thematique) {
            header('Location: /admin/thematiques');
            exit;
        }

        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($nom)) {
                $error = 'Le nom de la thématique est requis.';
            } elseif ($this->thematicModel->nameExists($nom, $id)) {
                $error = 'Cette thématique existe déjà.';
            } else {
                $data = [
                    'nom' => $nom,
                    'description' => $description
                ];
                if ($this->thematicModel->update($id, $data)) {
                    $success = 'Thématique modifiée avec succès.';
                    $thematique = $this->thematicModel->findById($id);
                } else {
                    $error = 'Erreur lors de la modification de la thématique.';
                }
            }
        }
        require_once __DIR__ . "/../Views/admin/edit_thematique.php";
    }

    public function updateThematique($id) {
        $this->editThematique($id);
    }

    public function deleteThematique($id) {
        $ideasCount = $this->thematicModel->getIdeasCount($id);
        if ($ideasCount > 0) {
            header('Location: /admin/thematiques?error=Impossible de supprimer une thématique qui contient des idées');
        } else {
            if ($this->thematicModel->delete($id)) {
                header('Location: /admin/thematiques?success=Thématique supprimée avec succès');
            } else {
                header('Location: /admin/thematiques?error=Erreur lors de la suppression');
            }
        }
        exit;
    }
}