<?php

session_start();
require_once __DIR__ . '/../db/config.php';
require_once __DIR__ . '/../controllers/ProductController.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$controller = new ProductController($pdo);

if (isset($_POST['create'])) {
    $controller->create($_POST);
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

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <main class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg">
        <header class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-purple-700">Cadastrar Produto</h1>
            <p class="text-sm text-gray-500">Preencha os dados abaixo para adicionar um novo produto</p>
        </header>

        <form action="" method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="nome" required
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="number" step="0.01" name="preco" required
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Quantidade</label>
                <input type="number" name="quantidade" required
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" rows="3"
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400"></textarea>
            </div>

            <button type="submit" name="create"
                class="w-full py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold rounded-md hover:from-purple-600 hover:to-purple-700 transition">
                Salvar Produto
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="home.php" class="text-purple-600 hover:underline font-medium">Voltar para Home</a>
        </div>
    </main>
</body>

</html>