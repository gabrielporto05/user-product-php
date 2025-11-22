<?php

ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

require_once __DIR__ . '/../db/config.php';
require_once __DIR__ . '/../controllers/ProductController.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$controller = new ProductController($pdo);

$produtos = $controller->list();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <main class="w-full max-w-5xl bg-white p-10 rounded-xl shadow-lg">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-purple-700">Produtos Cadastrados</h1>
            <div class="flex gap-4">
                <a href="create-product.php"
                    class="px-6 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-purple-600 hover:to-purple-700 transition">
                    Cadastrar Produto
                </a>
                <a href="logout.php"
                    class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition">
                    Sair
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-purple-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Preço</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Quantidade</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Descrição</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-600"><?= $produto['id'] ?></td>

                        <td class="px-4 py-2 text-sm text-gray-600 max-w-[150px] truncate"
                            title="<?= htmlspecialchars($produto['nome']) ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </td>

                        <td class="px-4 py-2 text-sm text-gray-600 max-w-[100px] truncate"
                            title="R$ <?= number_format($produto['preco'], 2, ',', '.') ?>">
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </td>

                        <td class="px-4 py-2 text-sm text-gray-600"><?= $produto['quantidade'] ?></td>

                        <td class="px-4 py-2 text-sm text-gray-600 max-w-[200px] truncate"
                            title="<?= htmlspecialchars($produto['descricao']) ?>">
                            <?= htmlspecialchars($produto['descricao']) ?>
                        </td>

                        <td class="px-4 py-2 text-center">
                            <form action="../controllers/ProductController.php" method="post"
                                onsubmit="return confirm('Deseja excluir este produto?');">
                                <input type="hidden" name="delete_id" value="<?= $produto['id'] ?>">
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Nenhum produto cadastrado.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </main>
</body>

</html>