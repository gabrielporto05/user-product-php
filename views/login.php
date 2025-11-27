<?php
session_start();
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../db/config.php';

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $controller = new AuthController($pdo);
    $erro = $controller->login($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-300 to-blue-400 min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white shadow-xl rounded-xl overflow-hidden">

        <div class="hidden md:flex md:w-1/2 bg-blue-100 items-center justify-center">
            <img src="../assets/Online resume-bro.png" alt="Login ilustrativo" class="w-3/4 h-auto" />
        </div>

        <div class="flex-1 p-10 flex flex-col justify-center">
            <h1 class="text-4xl font-extrabold text-blue-700 mb-4 text-center">Bem-vindo de volta</h1>
            <p class="text-gray-500 text-center mb-8">Entre com suas credenciais para acessar sua conta</p>

            <?php if ($erro): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
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

                <button type="submit" name="login"
                    class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition">
                    Entrar
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Ainda não tem uma conta?
                <a href="/views/register.php" class="text-blue-600 hover:underline font-medium">Cadastre-se aqui</a>
            </p>
        </div>
    </div>
</body>

</html>