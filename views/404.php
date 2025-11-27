<?php

session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 - Página não encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="flex flex-col md:flex-row items-center gap-8">
        <div class="w-[300px] h-[300px] md:w-[400px] md:h-[400px]">
            <img src="../assets/Oops! 404 Error with a broken robot-amico.png" alt="Página não encontrada"
                class="w-full h-full object-contain" />
        </div>

        <main class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg text-center">
            <h1 class="text-3xl font-extrabold text-blue-600 mb-4">Página não encontrada</h1>
            <p class="text-gray-600 mb-8">
                Ops! O endereço que você tentou acessar não existe ou foi removido.
            </p>

            <button onclick="history.back()"
                class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition">
                Voltar
            </button>
        </main>
    </div>
</body>

</html>