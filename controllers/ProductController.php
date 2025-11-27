<?php

session_start();

require_once __DIR__ . '/../db/config.php';

class ProductController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($dados)
    {
        $nome = trim($dados['nome']);
        $preco = floatval($dados['preco']);
        $quantidade = intval($dados['quantidade']);
        $descricao = trim($dados['descricao']);
        $userId = $_SESSION['user_id'];

        if (empty($nome) || $preco <= 0 || $quantidade < 0) {
            return "Todos os campos são obrigatórios e devem ser válidos";
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO products (user_id, nome, preco, quantidade, descricao) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$userId, $nome, $preco, $quantidade, $descricao]);

            header("Location: ../views/home.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../views/error.php?titulo=Erro+ao+cadastrar+produto&subtitulo=Não+foi+possível+cadastrar+o+produto");
            exit;
        }
    }

    public function list()
    {
        $stmt = $this->pdo->query("SELECT * FROM products WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($dados)
    {
        $id = intval($dados['id']);
        $nome = trim($dados['nome']);
        $preco = floatval($dados['preco']);
        $quantidade = intval($dados['quantidade']);
        $descricao = trim($dados['descricao']);
        $userId = $_SESSION['user_id'];

        if (empty($nome) || $preco <= 0 || $quantidade < 0) {
            return "Todos os campos são obrigatórios e devem ser válidos";
        }

        try {
            $stmt = $this->pdo->prepare(
                "UPDATE products SET nome = ?, preco = ?, quantidade = ?, descricao = ? WHERE id = ? AND user_id = ?"
            );
            $stmt->execute([$nome, $preco, $quantidade, $descricao, $id, $userId]);

            header("Location: ../views/home.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../views/error.php?titulo=Erro+ao+editar+produto&subtitulo=Não+foi+possível+editar+o+produto");
            exit;
        }
    }


    public function delete($id)
    {
        if (!is_numeric($id)) {
            return "ID inválido";
        }

        $userId = $_SESSION['user_id'];

        try {
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);

            header("Location: ../views/home.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../views/error.php?titulo=Erro+ao+excluir+produto&subtitulo=Não+foi+possível+excluir+o+produto");
            exit;
        }
    }
}

$controller = new ProductController($pdo);

if (isset($_POST['create'])) {
    $controller->create($_POST);
}

if (isset($_POST['update'])) {
    $controller->update($_POST);
}

if (isset($_POST['delete'])) {
    $controller->delete($_POST['delete']);
}