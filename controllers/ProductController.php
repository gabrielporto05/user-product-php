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
            header("Location: ../views/error.php?titulo=Dados+incompletos&subtitulo=Todos+os+campos+são+obrigatórios");
            exit;
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

    public function delete($id)
    {
        if (!is_numeric($id)) {
            header("Location: ../views/error.php?titulo=Erro+na+Exclusão&subtitulo=ID+inválido");
            exit;
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

if (isset($_POST['delete_id'])) {
    $controller->delete($_POST['delete_id']);
}
