<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../db/config.php';

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $controller = new UserController($pdo);
    $erro = $controller->register($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-100 min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-xl rounded-xl overflow-hidden">

        <div class="flex-1 p-10 flex flex-col justify-center">
            <h1 class="text-4xl font-extrabold text-blue-700 mb-4 text-center">Crie sua conta</h1>
            <p class="text-gray-500 text-center mb-8">Preencha os dados abaixo para começar</p>

            <?php if ($erro): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome completo</label>
                    <input type="text" name="name" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" name="email" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" name="password" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
                    <input type="password" name="confirm_password" required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <button type="submit" name="register"
                    class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition">
                    Cadastrar
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Já possui uma conta?
                <a href="login.php" class="text-blue-600 hover:underline font-medium">Entrar</a>
            </p>
        </div>

        <div class="hidden md:flex md:w-1/2 bg-blue-100 items-center justify-center">
            <img src="../assets/Mobile UI-UX-bro.png" alt="Cadastro ilustrativo" class="w-3/4 h-auto" />
        </div>
    </div>
</body>

</html>