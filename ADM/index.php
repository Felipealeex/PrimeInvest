<?php
session_start();
include("../conexao/conexao.php");

if (!isset($_SESSION['admin_email'])) {
    header('Location: ../areaADM/index.php');
    exit();
}

$admin_email = $_SESSION['admin_email'];

$sql = "SELECT id FROM register_adm WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../areaADM/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - PrimeInvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showForm() {
            var formSection = document.getElementById("form-section");
            formSection.classList.remove("hidden");
        }
    </script>
</head>
<body class="bg-gray-100 flex flex-col md:flex-row">

    <!-- Botão para abrir o menu em telas pequenas -->
    <button id="menu-toggle" class="md:hidden p-4 bg-black text-white absolute top-4 left-4 rounded">
        ☰ Menu
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-black text-white h-screen p-6 fixed inset-y-0 left-0 transform -translate-x-full transition-transform md:translate-x-0 md:relative">
        <h2 class="text-xl font-bold mb-6">PrimeInvest</h2>
        <nav>
            <ul>
                <li class="mb-4">
                    <a href="#" class="block p-2 rounded bg-gray-800">🏠 Dashboard</a>
                </li>
                <li class="mb-4">
                    <a href="./aprovarSaqueADM/index.php" class="block p-2 rounded hover:bg-gray-800">
                        💰 Aprovar Saque
                    </a>
                </li>
                <li class="mb-4">
                    <a href="./gerenciarCadastrosADM/index.php" class="block p-2 rounded hover:bg-gray-800">
                        📝 Gerenciar Cadastros
                    </a>
                </li>
                <li class="mb-4">
                    <a href="./GerenciarCDIADM/NovoCDI.php" class="block p-2 rounded hover:bg-gray-800">
                        📝 Gerenciar CDI
                    </a>
                </li>
               <!--   <li class="mb-4">
                    <a href="./GerenciarSuporte/admin_chat.php" class="block p-2 rounded hover:bg-gray-800">
                        📝 Gerenciar Suporte 
                    </a>
                </li
                 <li class="mb-4">
                    <a href="./FeedbackAdmin/admin_feedback.php" class="block p-2 rounded hover:bg-gray-800">
                        Feedbacks!
                    </a>
                </li> -->
                <li class="mb-4">
                    <a href="../logout/logout.php" class="block p-2 rounded hover:bg-gray-800">
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Conteúdo Principal -->
    <main class="flex-1 p-6 mt-12 md:mt-0"> <!-- Espaço extra para o botão do menu -->
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <center>
                <h2 class="text-2xl font-bold text-gray-900">Área ADMINISTRADOR</h2>
                <strong>
                    <p class="text-gray-600 text-sm mb-6">
                        O dinheiro trabalha para quem tem visão. Construa riqueza, conquiste seu futuro.
                    </p>
                </strong>
            </center>
        </div>
    </main>

    <script>
     const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
        function toggleSubmenu() {
            var submenu = document.getElementById("submenu");
            submenu.classList.toggle("hidden");
        }

        function showForm() {
            var formSection = document.getElementById("form-section");
            formSection.classList.remove("hidden");
        }
        
    </script>

</body>
</html>
