<?php

require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Core/Session.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger selon son rôle
        if (Session::has('user_id')) {
            $this->redirectByRole();
            return;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->userModel->authenticate($email, $password);
                if ($user) {
                    Session::set('user_id', $user['id']);
                    Session::set('user_nom', $user['nom']);
                    Session::set('user_prenom', $user['prenom']);
                    Session::set('user_email', $user['email']);
                    Session::set('user_role', $user['role']);
                    
                    $this->redirectByRole();
                    return;
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }

        require_once __DIR__ . "/../Views/auth/login.php";
    }

    public function authenticate() {
        // Cette méthode est appelée par POST, la logique est dans login()
        $this->login();
    }

    public function logout() {
        Session::destroy();
        header('Location: /login');
        exit;
    }

    private function redirectByRole() {
        $role = Session::get('user_role');
        switch ($role) {
            case 'admin':
                header('Location: /admin/users');
                break;
            case 'salarie':
                header('Location: /salarie/ideas');
                break;
            case 'evaluateur':
                header('Location: /evaluateur/ideas');
                break;
            default:
                header('Location: /login');
        }
        exit;
    }

    public static function requireAuth() {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole($requiredRole) {
        self::requireAuth();
        $userRole = Session::get('user_role');
        if ($userRole !== $requiredRole) {
            header('HTTP/1.0 403 Forbidden');
            echo "Accès interdit. Rôle requis : " . $requiredRole;
            exit;
        }
    }

    public static function requireRoles($requiredRoles) {
        self::requireAuth();
        $userRole = Session::get('user_role');
        if (!in_array($userRole, $requiredRoles)) {
            header('HTTP/1.0 403 Forbidden');
            echo "Accès interdit. Rôles autorisés : " . implode(', ', $requiredRoles);
            exit;
        }
    }
}

?>

