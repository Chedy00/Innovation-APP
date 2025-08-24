<?php

require_once __DIR__ . "/../Core/Database.php";

class IdeaModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

   public function findAll() {
    $stmt = $this->pdo->query("
        SELECT i.id, i.titre, i.description, i.date_soumission, i.statut, i.id_salarie, i.id_thematique,
               u.nom as salarie_nom, u.prenom as salarie_prenom, 
               t.nom as thematique_nom,
               AVG(e.note) as moyenne_note, 
               COUNT(e.id) as nb_evaluations
        FROM idees i
        JOIN utilisateurs u ON i.id_salarie = u.id
        JOIN thematiques t ON i.id_thematique = t.id
        LEFT JOIN evaluations e ON i.id = e.id_idee
        GROUP BY i.id
        ORDER BY i.date_soumission DESC
    ");
    return $stmt->fetchAll();
}

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, u.nom as salarie_nom, u.prenom as salarie_prenom, t.nom as thematique_nom 
            FROM idees i 
            JOIN utilisateurs u ON i.id_salarie = u.id 
            JOIN thematiques t ON i.id_thematique = t.id 
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySalarie($salarieId) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, t.nom as thematique_nom 
            FROM idees i 
            JOIN thematiques t ON i.id_thematique = t.id 
            WHERE i.id_salarie = ? 
            ORDER BY i.date_soumission DESC
        ");
        $stmt->execute([$salarieId]);
        return $stmt->fetchAll();
    }

    public function findByEvaluateur($evaluateurId) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, t.nom as thematique_nom 
            FROM idees i 
            JOIN thematiques t ON i.id_thematique = t.id 
            JOIN evaluations e ON i.id = e.id_idee 
            WHERE e.id_evaluateur = ? 
            ORDER BY i.date_soumission DESC
        ");
        $stmt->execute([$evaluateurId]);
        return $stmt->fetchAll();
    }

    public function findIdeasToEvaluate($evaluateurId) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, u.nom as salarie_nom, u.prenom as salarie_prenom, t.nom as thematique_nom 
            FROM idees i 
            JOIN utilisateurs u ON i.id_salarie = u.id 
            JOIN thematiques t ON i.id_thematique = t.id 
            LEFT JOIN evaluations e ON i.id = e.id_idee AND e.id_evaluateur = ?
            WHERE e.id IS NULL AND i.statut IN ('soumise', 'en_evaluation')
            ORDER BY i.date_soumission ASC
        ");
        $stmt->execute([$evaluateurId]);
        return $stmt->fetchAll();
    }

    public function findTopIdeas($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, u.nom as salarie_nom, u.prenom as salarie_prenom, t.nom as thematique_nom, 
                   AVG(e.note) as moyenne_note, COUNT(e.id) as nb_evaluations
            FROM idees i 
            JOIN utilisateurs u ON i.id_salarie = u.id 
            JOIN thematiques t ON i.id_thematique = t.id 
            JOIN evaluations e ON i.id = e.id_idee
            GROUP BY i.id 
            HAVING nb_evaluations > 0
            ORDER BY moyenne_note DESC, nb_evaluations DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO idees (titre, description, id_salarie, id_thematique) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['id_salarie'],
            $data['id_thematique']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE idees SET titre = ?, description = ?, statut = ?, id_thematique = ? WHERE id = ?");
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['statut'],
            $data['id_thematique'],
            $id
        ]);
    }

    public function updateStatus($id, $statut) {
        $stmt = $this->pdo->prepare("UPDATE idees SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM idees WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getIdeaWithEvaluations($id) {
        $idea = $this->findById($id);
        if ($idea) {
            $stmt = $this->pdo->prepare("
                SELECT e.*, u.nom as evaluateur_nom, u.prenom as evaluateur_prenom 
                FROM evaluations e 
                JOIN utilisateurs u ON e.id_evaluateur = u.id 
                WHERE e.id_idee = ? 
                ORDER BY e.date_evaluation DESC
            ");
            $stmt->execute([$id]);
            $idea['evaluations'] = $stmt->fetchAll();
        }
        return $idea;
    }
}

?>

