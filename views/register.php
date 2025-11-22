<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex">
    <div class="hidden md:flex flex-1">
        <img src="../assets/Mobile UI-UX-bro.png" alt="Imagem ilustrativa"
            class="w-full h-full p-20 object-cover rounded-r-xl">
    </div>

    <div class="flex-1 flex items-center justify-center p-10">
        <main class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
            <header class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-purple-700">Cadastro de Usuário</h1>
                <p class="text-sm text-gray-500">Preencha os dados para criar sua conta</p>
            </header>

            <form action="/controllers/UserController.php" method="post" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome completo</label>
                    <input type="text" name="name" required
                        class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Usuário</label>
                    <input type="text" name="user" required
                        class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
                </div>

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

                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
                    <input type="password" name="confirm_password" required
                        class="mt-1 w-full px-3 py-2 border border-purple-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400" />
                </div>

                <button type="submit" name="register"
                    class="w-full py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-md hover:from-purple-600 hover:to-purple-700 transition">
                    Cadastrar
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Já possui uma conta?
                    <a href="login.php" class="text-purple-600 hover:underline font-medium">
                        Entrar
                    </a>
                </p>
            </div>
        </main>
    </div>
</body>

</html>