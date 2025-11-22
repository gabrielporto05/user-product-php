<?php

session_start();
require_once __DIR__ . '/../db/config.php';

class UserController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function register($dados)
    {

        $name = trim($dados['name']);
        $user = trim($dados['user']);
        $email = trim($dados['email']);
        $password = $dados['password'];
        $confirmPassword = $dados['confirm_password'];

        if ($password !== $confirmPassword) {
            die("As senhas não conferem. <a href='../views/register.php'>Voltar</a>");
        }

        if (strlen($password) < 6) {
            die("A senha deve ter no mínimo 6 caracteres. <a href='../views/register.php'>Voltar</a>");
        }

        $hashedPassword = hash('sha512', $password);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (name, user, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $user, $email, $hashedPassword]);

            header("Location: ../views/login.php");
            exit;
        } catch (PDOException $e) {
            die("Erro ao cadastrar: " . $e->getMessage());
        }
    }

    public function login($dados)
    {
        $email = trim($dados['email']);
        $password = $dados['password'];

        $hashedPassword = hash('sha512', $password);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashedPassword]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user'] = $user['user'];
            $_SESSION['name'] = $user['name'];
            header("Location: ../views/home.php");
            exit;
        } else {
            die("Usuário ou senha inválidos. <a href='../views/login.php'>Tentar novamente</a>");
        }
    }

    private function isEmailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    private function isUserExists($user)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE user = ?");
        $stmt->execute([$user]);
        return $stmt->fetchColumn() > 0;
    }

    private function validateDados($dados) {}
}

$controller = new UserController($pdo);

if (isset($_POST['register'])) {
    $controller->register($_POST);
}

if (isset($_POST['login'])) {
    $controller->login($_POST);
}
