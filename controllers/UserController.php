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
        $email = trim($dados['email']);
        $password = $dados['password'];
        $confirmPassword = $dados['confirm_password'];

        $this->validateDados($name, $email, $password, $confirmPassword);

        $hashedPassword = hash('sha512', $password);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);

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

        if (!$this->isValidEmail($email)) {
            die("E-mail inválido. <a href='../views/login.php'>Voltar</a>");
        }

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

    private function validateDados($name, $email, $password, $confirmPassword)
    {
        if (empty($name) || empty($email) || empty($password)) {
            die("Todos os campos são obrigatórios. <a href='../views/register.php'>Voltar</a>");
        }

        if (!$this->isValidEmail($email)) {
            die("Formato de e-mail inválido. <a href='../views/register.php'>Voltar</a>");
        }

        if ($this->isEmailExists($email)) {
            die("E-mail já cadastrado. <a href='../views/register.php'>Voltar</a>");
        }

        if ($password !== $confirmPassword) {
            die("As senhas não conferem. <a href='../views/register.php'>Voltar</a>");
        }

        if (strlen($password) < 6) {
            die("A senha deve ter no mínimo 6 caracteres. <a href='../views/register.php'>Voltar</a>");
        }
    }

    private function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isEmailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetchColumn() > 0;
    }
}

$controller = new UserController($pdo);

if (isset($_POST['register'])) {
    $controller->register($_POST);
}

if (isset($_POST['login'])) {
    $controller->login($_POST);
}
