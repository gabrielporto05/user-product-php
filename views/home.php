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

if (isset($_GET['busca']) && $_GET['busca'] !== '') {
    $busca = strtolower(trim($_GET['busca']));
    $produtos = array_filter(
        $produtos,
        fn($p) =>
        str_contains(strtolower($p['nome']), $busca) ||
            str_contains(strtolower($p['descricao']), $busca)
    );
}

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'estoque') {
        $produtos = array_filter($produtos, fn($p) => $p['quantidade'] > 0);
    } elseif ($_GET['status'] === 'esgotado') {
        $produtos = array_filter($produtos, fn($p) => $p['quantidade'] == 0);
    }
}

if (isset($_GET['preco_min']) && is_numeric($_GET['preco_min'])) {
    $produtos = array_filter($produtos, fn($p) => $p['preco'] >= $_GET['preco_min']);
}

if (isset($_GET['preco_max']) && is_numeric($_GET['preco_max'])) {
    $produtos = array_filter($produtos, fn($p) => $p['preco'] <= $_GET['preco_max']);
}

$totalProdutos = count($produtos);
$emEstoque = array_sum(array_map(fn($p) => $p['quantidade'] > 0 ? 1 : 0, $produtos));
$esgotados = $totalProdutos - $emEstoque;
$valorTotal = array_sum(array_map(fn($p) => $p['preco'] * $p['quantidade'], $produtos));

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

<body class="bg-gradient-to-r from-blue-300 to-blue-400 min-h-screen flex flex-col items-center">

    <header class="w-full max-w-6xl flex justify-between items-center py-6 px-4">
        <h1 class="text-3xl font-extrabold text-blue-700">Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?></h1>
        <div class="flex gap-4">
            <a href="create-product.php"
                class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-2xl font-bold text-blue-600"><?= $totalProdutos ?></span>
                <span class="text-gray-600 text-sm">Total de Produtos</span>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-2xl font-bold text-green-600"><?= $emEstoque ?></span>
                <span class="text-gray-600 text-sm">Em Estoque</span>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-2xl font-bold text-red-600"><?= $esgotados ?></span>
                <span class="text-gray-600 text-sm">Esgotados</span>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <span class="text-2xl font-bold text-purple-600">R$
                    <?= number_format($valorTotal, 2, ',', '.') ?></span>
                <span class="text-gray-600 text-sm">Valor em Estoque</span>
            </div>
        </div>

        <form method="get" class="bg-white rounded-xl shadow p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center">
            <input type="text" name="busca" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>"
                placeholder="Buscar produto..."
                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

            <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="estoque" <?= (($_GET['status'] ?? '') === 'estoque') ? 'selected' : '' ?>>Em estoque
                </option>
                <option value="esgotado" <?= (($_GET['status'] ?? '') === 'esgotado') ? 'selected' : '' ?>>Esgotados
                </option>
            </select>

            <input type="number" step="0.01" name="preco_min" value="<?= htmlspecialchars($_GET['preco_min'] ?? '') ?>"
                placeholder="Preço mínimo" class="w-32 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

            <input type="number" step="0.01" name="preco_max" value="<?= htmlspecialchars($_GET['preco_max'] ?? '') ?>"
                placeholder="Preço máximo" class="w-32 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition">
                Filtrar
            </button>
        </form>


        <?php if (count($produtosPagina) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($produtosPagina as $produto): ?>
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-blue-700 truncate"><?= htmlspecialchars($produto['nome']) ?></h3>
                    <span class="px-2 py-1 w-[90px] text-xs rounded-full 
                 <?= $produto['quantidade'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                        <?= $produto['quantidade'] > 0 ? 'Em estoque' : 'Esgotado' ?>
                    </span>
                </div>
                <p class="text-gray-600 mt-2 text-sm"><?= htmlspecialchars($produto['descricao']) ?></p>
                <p class="mt-4 text-gray-800 font-semibold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                <div class="flex gap-2 mt-4">
                    <a href="create-product.php?id=<?= $produto['id'] ?>"
                        class="flex-1 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition flex items-center justify-center gap-2">
                        Editar
                    </a>
                    <form action="../controllers/ProductController.php" method="post" class="flex-1"
                        onsubmit="return confirm('Deseja excluir este produto?');">
                        <input type="hidden" name="delete" value="<?= $produto['id'] ?>">
                        <button type="submit"
                            class="w-full py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 transition flex items-center justify-center gap-2">
                            Excluir
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
                    <?= $i == $paginaAtual ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border border-blue-300 hover:bg-blue-100' ?>">
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