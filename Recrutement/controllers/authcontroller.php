<?php
require_once 'models/User.php';

class AuthController {
    public function register() {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            $user = new User();

            if ($user->emailExists($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                if ($user->register($nom, $email, $password, $role)) {
                    // Auto-login après inscription
                    session_start();
                    $_SESSION['user_id'] = $user->pdo->lastInsertId(); // ou via SELECT juste après INSERT
                    $_SESSION['role'] = $role;
                    $_SESSION['nom'] = $nom;

                    // Redirection par rôle
                    switch ($role) {
                        case 'candidat':
                            header('Location: index.php?controller=candidat&action=dashboard');
                            break;
                        case 'recruteur':
                            header('Location: index.php?controller=recruteur&action=dashboard');
                            break;
                        case 'admin':
                            header('Location: index.php?controller=admin&action=dashboard');
                            break;
                        default:
                            header('Location: index.php');
                    }
                    exit;
                } else {
                    $error = "Erreur lors de l'enregistrement.";
                }
            }
        }

        require 'views/auth/register.php';
    }
}
