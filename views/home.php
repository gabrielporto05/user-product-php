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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center">
    <span class="text-gray-600 font-extrabold text-3xl mb-4">
        Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?>
    </span>
    <main class="w-full max-w-5xl bg-white p-10 rounded-xl shadow-lg">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-purple-700">
                Produtos Cadastrados
            </h1>
            <div class="flex items-center gap-6">
                <div class="flex gap-4">
                    <a href="create-product.php"
                        class="px-6 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-purple-600 hover:to-purple-700 transition">
                        Cadastrar Produto
                    </a>
                    <a href="../controllers/AuthController.php?logout=1"
                        class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition">
                        Sair
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="produtosTable"
                class="min-w-full border border-gray-200 rounded-lg shadow-sm divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nome</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Preço</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Quantidade</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Descrição</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                    <tr class="hover:bg-purple-50 transition">
                        <td class="px-4 py-2 text-sm text-gray-700"><?= $produto['id'] ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 max-w-[150px] truncate"
                            title="<?= htmlspecialchars($produto['nome']) ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">R$
                            <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700"><?= $produto['quantidade'] ?></td>
                        <td class="px-4 py-2 text-sm text-gray-700 max-w-[200px] truncate"
                            title="<?= htmlspecialchars($produto['descricao']) ?>">
                            <?= htmlspecialchars($produto['descricao']) ?>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <form action="../controllers/ProductController.php" method="post"
                                onsubmit="return confirm('Deseja excluir este produto?');">
                                <input type="hidden" name="delete" value="<?= $produto['id'] ?>">
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg shadow hover:bg-red-600 transition">
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

    <script>
    $(document).ready(function() {
        $('#produtosTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            dom: '<"flex justify-between items-center mb-4"lf>t<"flex justify-between items-center mt-4"ip>',
        });
    });
    </script>

</body>

</html>