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

$porPagina = 6;
$total = count($produtos);
$paginas = ceil($total / $porPagina);

$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($paginaAtual - 1) * $porPagina;

$produtosPagina = array_slice($produtos, $inicio, $porPagina);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-200 to-blue-300 min-h-screen flex flex-col items-center">

    <header class="w-full max-w-6xl flex justify-between items-center py-6 px-4">
        <h1 class="text-3xl font-extrabold text-purple-700">Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?></h1>
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
    </header>

    <main class="w-full max-w-6xl p-6">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">Produtos Cadastrados</h2>

        <?php if (count($produtosPagina) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($produtosPagina as $produto): ?>
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-purple-700 truncate"
                        title="<?= htmlspecialchars($produto['nome']) ?>">
                        <?= htmlspecialchars($produto['nome']) ?>
                    </h3>
                    <p class="text-gray-600 mt-2 text-sm truncate"
                        title="<?= htmlspecialchars($produto['descricao']) ?>">
                        <?= htmlspecialchars($produto['descricao']) ?>
                    </p>
                </div>

                <div class="mt-4">
                    <p class="text-gray-800 font-semibold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <p class="text-gray-500 text-sm">Quantidade: <?= $produto['quantidade'] ?></p>
                </div>

                <div class="flex w-full gap-3 mt-4">
                    <form action="../controllers/ProductController.php" method="post"
                        onsubmit="return confirm('Deseja excluir este produto?');" class="flex-1">
                        <input type="hidden" name="delete" value="<?= $produto['id'] ?>">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-600 transition">
                            Excluir
                        </button>
                    </form>

                    <form action="create-product.php" method="get" class="flex-1">
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-600 transition">
                            Editar
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center items-center gap-2 mt-8">
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>"
                class="px-4 py-2 rounded-lg text-sm font-semibold 
                    <?= $i == $paginaAtual ? 'bg-purple-600 text-white' : 'bg-white text-purple-600 border border-purple-300 hover:bg-purple-100' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>

        <?php else: ?>
        <div class="text-center py-10 bg-white rounded-xl shadow">
            <p class="text-gray-500">Nenhum produto cadastrado.</p>
        </div>
        <?php endif; ?>
    </main>
</body>

</html>