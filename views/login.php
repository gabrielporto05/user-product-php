<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <main class="w-full max-w-sm bg-white p-6 rounded-lg shadow-md">
        <header class="text-center mb-6">
            <h1 class="text-2xl font-bold text-purple-700">Login no Sistema</h1>
            <p class="text-sm text-gray-500">Acesse sua conta para continuar</p>
        </header>

        <form action="/controllers/UserController.php" method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" required
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" required
                    class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
            </div>

            <button type="submit" name="login"
                class="w-full py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                Entrar
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Ainda não tem uma conta?
                <a href="/views/register.php" class="text-purple-600 hover:underline font-medium">
                    Cadastre-se aqui
                </a>
            </p>
        </div>
    </main>
</body>

</html>