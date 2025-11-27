<?php

session_start();

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../db/config.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$controller = new ProductController($pdo);

$erro = null;
$produto = null;

if (isset($_GET['id'])) {
    $produto = $controller->findById($_GET['id']);
    if (!$produto) {
        $erro = "Produto não encontrado";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $erro = $controller->create($_POST);
    }
    if (isset($_POST['update'])) {
        $erro = $controller->update($_POST);
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-100 min-h-screen flex items-center justify-center p-6">
    <main class="w-full max-w-2xl bg-white p-10 rounded-2xl shadow-2xl">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-700">
                <?= $produto ? "Editar Produto" : "Novo Produto" ?>
            </h1>
            <p class="text-gray-500 mt-2">
                <?= $produto ? "Atualize os dados do produto" : "Preencha os campos abaixo para adicionar ao catálogo" ?>
            </p>
        </header>

        <?php if ($erro): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="space-y-6">
            <?php if ($produto): ?>
                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nome</label>
                <input type="text" name="nome" required
                    value="<?= $produto ? htmlspecialchars($produto['nome']) : '' ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                    placeholder="Ex: Notebook Dell Inspiron" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Preço</label>
                <input type="number" step="0.01" name="preco" required
                    value="<?= $produto ? htmlspecialchars($produto['preco']) : '' ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                    placeholder="Ex: 2999.90" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Quantidade</label>
                <input type="number" name="quantidade" required
                    value="<?= $produto ? htmlspecialchars($produto['quantidade']) : '' ?>"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                    placeholder="Ex: 10" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                    placeholder="Ex: Notebook com processador Intel i7, 16GB RAM, SSD 512GB..."><?= $produto ? htmlspecialchars($produto['descricao']) : '' ?></textarea>
            </div>

            <button type="submit" name="<?= $produto ? 'update' : 'create' ?>"
                class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition">
                <?= $produto ? "Atualizar Produto" : "Salvar Produto" ?>
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="home.php" class="inline-block px-6 py-2 text-blue-600 font-medium hover:text-blue-800 transition">
                Voltar para Home
            </a>
        </div>
    </main>

</body>

</html>