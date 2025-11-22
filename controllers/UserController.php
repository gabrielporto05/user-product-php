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
            header("Location: ../views/error.php?titulo=Erro+no+Cadastro&subtitulo=Não+foi+possível+realizar+o+cadastro");
            exit;
        }
    }

    public function login($dados)
    {
        $email = trim($dados['email']);
        $password = $dados['password'];

        if (!$this->isValidEmail($email)) {
            header("Location: ../views/error.php?titulo=Email+inválido&subtitulo=E-mail+no+formato+inválido");
        }

        $hashedPassword = hash('sha512', $password);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashedPassword]);
        $user = $stmt->fetch();

        if (!$user) {
            header("Location: ../views/error.php?titulo=Usuário+ou+senha+inválidos&subtitulo=Usuário+ou+senha+inválidos");
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        header("Location: ../views/home.php");

        exit;
    }

    private function validateDados($name, $email, $password, $confirmPassword)
    {
        if (empty($name) || empty($email) || empty($password)) {
            header("Location: ../views/error.php?titulo=Dados+incompletos&subtitulo=Todos+os+campos+são+obrigatórios");
            exit;
        }

        if (!$this->isValidEmail($email)) {
            header("Location: ../views/error.php?titulo=Email+inválido&subtitulo=E-mail+no+formato+inválido");
            exit;
        }

        if ($this->isEmailExists($email)) {
            header("Location: ../views/error.php?titulo=Cadastro+Inválido&subtitulo=E-mail+já+cadastrado");
            exit;
        }

        if ($password !== $confirmPassword) {
            header("Location: ../views/error.php?titulo=Senha+Inválida&subtitulo=As+senhas+não+conferem");
            exit;
        }

        if (strlen($password) < 6) {
            header("Location: ../views/error.php?titulo=Senha+Fraca&subtitulo=A+senha+deve+ter+no+mínimo+6+caracteres");
            exit;
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
