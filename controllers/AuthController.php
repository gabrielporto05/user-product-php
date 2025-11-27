<?php

session_start();
require_once __DIR__ . '/../db/config.php';

class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($dados)
    {
        $email = trim($dados['email']);
        $password = $dados['password'];

        if (!$this->isValidEmail($email)) {
            return "Formato de e-mail inválido";
        }

        $hashedPassword = hash('sha512', $password);

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $stmt->execute([$email, $hashedPassword]);
            $user = $stmt->fetch();

            if (!$user) {
                return "Usuário ou senha incorretos";
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['name']    = $user['name'];

            header("Location: ../views/home.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../views/error.php?titulo=Erro+no+Login&subtitulo=Não+foi+possível+realizar+o+login");
            exit;
        }
    }

    public function logout()
    {

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: ../views/login.php");

        exit;
    }

    private function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

$controller = new AuthController($pdo);

if (isset($_POST['login'])) {
    $controller->login($_POST);
}

if (isset($_GET['logout'])) {
    $controller->logout();
}