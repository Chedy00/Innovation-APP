<?php

require_once __DIR__ . "/../Core/Database.php";

class ThematicModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM thematiques WHERE actif = 1 ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM thematiques WHERE id = ? AND actif = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByNom($nom) {
        $stmt = $this->pdo->prepare("SELECT * FROM thematiques WHERE nom = ? AND actif = 1");
        $stmt->execute([$nom]);
        return $stmt->fetch();
    }

    public function create($data) {
        // ✅ Check if thematic with this name already exists (active OR inactive)
        $stmt = $this->pdo->prepare("SELECT id FROM thematiques WHERE nom = ?");
        $stmt->execute([$data['nom']]);
        if ($stmt->fetch()) {
            return false; // Name exists → block insert
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO thematiques (nom, description) VALUES (?, ?)");
            return $stmt->execute([
                $data['nom'],
                $data['description']
            ]);
        } catch (\PDOException $e) {
            // Log error (optional)
            error_log("Thematic creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        // ✅ Check if another thematic has this name
        $stmt = $this->pdo->prepare("SELECT id FROM thematiques WHERE nom = ? AND id != ?");
        $stmt->execute([$data['nom'], $id]);
        if ($stmt->fetch()) {
            return false; // Name taken
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE thematiques SET nom = ?, description = ? WHERE id = ?");
            return $stmt->execute([
                $data['nom'],
                $data['description'],
                $id
            ]);
        } catch (\PDOException $e) {
            error_log("Thematic update failed: " . $e->getMessage());
            return false;
        }
    }

    // ✅ HARD DELETE — remove from database permanently
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM thematiques WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("Thematic delete failed: " . $e->getMessage());
            return false;
        }
    }

    // ✅ Optional: soft delete check (not needed now, but kept for reference)
    public function nameExists($nom, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM thematiques WHERE nom = ? AND id != ? AND actif = 1");
            $stmt->execute([$nom, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM thematiques WHERE nom = ? AND actif = 1");
            $stmt->execute([$nom]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function getIdeasCount($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM idees WHERE id_thematique = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}

?>