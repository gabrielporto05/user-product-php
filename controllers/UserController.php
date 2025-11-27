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

        $erro = $this->validateDados($name, $email, $password, $confirmPassword);
        if ($erro) {
            return $erro;
        }

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

    private function validateDados($name, $email, $password, $confirmPassword)
    {
        if (empty($name) || empty($email) || empty($password)) {
            return "Todos os campos são obrigatórios";
        }

        if (!$this->isValidEmail($email)) {
            return "Formato de e-mail inválido";
        }

        if ($this->isEmailExists($email)) {
            return "E-mail já cadastrado";
        }

        if ($password !== $confirmPassword) {
            return "As senhas não conferem";
        }

        if (strlen($password) < 6) {
            return "A senha deve ter no mínimo 6 caracteres";
        }

        return null;
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
