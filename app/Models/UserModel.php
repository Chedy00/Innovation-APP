<?php

require_once __DIR__ . "/../Core/Database.php";

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM utilisateurs WHERE actif = 1 ORDER BY nom, prenom");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = ? AND actif = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND actif = 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findByRole($role) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE role = ? AND actif = 1 ORDER BY nom, prenom");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            $data['role']
        ]);
    }

    public function update($id, $data) {
        if (isset($data['mot_de_passe']) && !empty($data['mot_de_passe'])) {
            $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, role = ? WHERE id = ?");
            return $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
                $data['role'],
                $id
            ]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, role = ? WHERE id = ?");
            return $stmt->execute([
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['role'],
                $id
            ]);
        }
    }

    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id != ? AND actif = 1");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND actif = 1");
            $stmt->execute([$email]);
        }
        return $stmt->fetchColumn() > 0;
    }
}

?>