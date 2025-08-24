<?php

require_once __DIR__ . "/../Core/Database.php";

class EvaluationModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT e.*, i.titre as idee_titre, u.nom as evaluateur_nom, u.prenom as evaluateur_prenom 
            FROM evaluations e 
            JOIN idees i ON e.id_idee = i.id 
            JOIN utilisateurs u ON e.id_evaluateur = u.id 
            ORDER BY e.date_evaluation DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, i.titre as idee_titre, u.nom as evaluateur_nom, u.prenom as evaluateur_prenom 
            FROM evaluations e 
            JOIN idees i ON e.id_idee = i.id 
            JOIN utilisateurs u ON e.id_evaluateur = u.id 
            WHERE e.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByIdea($ideaId) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, u.nom as evaluateur_nom, u.prenom as evaluateur_prenom 
            FROM evaluations e 
            JOIN utilisateurs u ON e.id_evaluateur = u.id 
            WHERE e.id_idee = ? 
            ORDER BY e.date_evaluation DESC
        ");
        $stmt->execute([$ideaId]);
        return $stmt->fetchAll();
    }

    public function findByEvaluateur($evaluateurId) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, i.titre as idee_titre 
            FROM evaluations e 
            JOIN idees i ON e.id_idee = i.id 
            WHERE e.id_evaluateur = ? 
            ORDER BY e.date_evaluation DESC
        ");
        $stmt->execute([$evaluateurId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO evaluations (note, commentaire, id_idee, id_evaluateur) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['note'],
            $data['commentaire'],
            $data['id_idee'],
            $data['id_evaluateur']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE evaluations SET note = ?, commentaire = ? WHERE id = ?");
        return $stmt->execute([
            $data['note'],
            $data['commentaire'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM evaluations WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function hasEvaluated($ideaId, $evaluateurId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM evaluations WHERE id_idee = ? AND id_evaluateur = ?");
        $stmt->execute([$ideaId, $evaluateurId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAverageNote($ideaId) {
        $stmt = $this->pdo->prepare("SELECT AVG(note) as moyenne FROM evaluations WHERE id_idee = ?");
        $stmt->execute([$ideaId]);
        $result = $stmt->fetch();
        return $result ? round($result['moyenne'], 2) : 0;
    }

    public function getEvaluationCount($ideaId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM evaluations WHERE id_idee = ?");
        $stmt->execute([$ideaId]);
        return $stmt->fetchColumn();
    }
}

?>

