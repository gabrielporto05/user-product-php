<?php

session_start();

$titulo = isset($_GET['titulo']) ? htmlspecialchars($_GET['titulo']) : "Erro inesperado";
$subtitulo = isset($_GET['subtitulo']) ? htmlspecialchars($_GET['subtitulo']) : "Algo deu errado. Tente novamente.";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Erro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="flex flex-col md:flex-row items-center gap-8">
        <div class="w-[300px] h-[300px] md:w-[400px] md:h-[400px]">
            <img src="../assets/500 Internal Server Error-amico.png" alt="Erro ilustrativo"
                class="w-full h-full object-contain" />
        </div>

        <main class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg text-center">
            <h1 class="text-3xl font-extrabold text-red-600 mb-4"><?= $titulo ?></h1>
            <p class="text-gray-600 mb-8"><?= $subtitulo ?></p>

            <button onclick="history.back()"
                class="px-6 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-400 transition">
                Voltar
            </button>

        </main>
    </div>
</body>

</html>