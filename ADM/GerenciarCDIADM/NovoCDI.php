<?php 
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php"); 

if (!isset($_SESSION['admin_email'])) {
    header('Location: ../Login/login.php');
    exit();
}

$sql = "SELECT rentabilidade_CDI FROM relatorio_cliente_grafico LIMIT 1";
$result = $conn->query($sql);
$rentabilidade_CDI_anterior = 0.00;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rentabilidade_CDI_anterior = floatval($row['rentabilidade_CDI']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rentabilidade = floatval($_POST['rentabilidade']);
    $rentabilidade_CDI = floatval($_POST['rentabilidade_CDI']);

    $sql_update = "UPDATE relatorio_cliente_grafico 
                   SET rentabilidade = ?, rentabilidade_CDI = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("dd", $rentabilidade, $rentabilidade_CDI);
    $stmt->execute();

    $diferenca_CDI = $rentabilidade_CDI - $rentabilidade_CDI_anterior;

    if ($diferenca_CDI != 0) {
        $sql_apply_cdi = "UPDATE relatorio_cliente_grafico 
                          SET saldo_disponivel = saldo_disponivel + (saldo_disponivel * (? / 100))";
        $stmt_cdi = $conn->prepare($sql_apply_cdi);
        $stmt_cdi->bind_param("d", $diferenca_CDI);
        $stmt_cdi->execute();
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT rentabilidade, rentabilidade_CDI FROM relatorio_cliente_grafico LIMIT 1";
$result = $conn->query($sql);
$rentabilidade = $rentabilidade_CDI = 0.00;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rentabilidade = $row['rentabilidade'];
    $rentabilidade_CDI = $row['rentabilidade_CDI'];
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estatísticas - PrimeInvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    function showForm() {
        var formSection = document.getElementById("form-section");
        formSection.classList.remove("hidden");
    }
    </script>
</head>
<body class="bg-gray-100 flex">
     
    <aside class="w-64 bg-black text-white h-screen p-6 hidden md:block">
        <h2 class="text-xl font-bold mb-6">PrimeInvest</h2>
        <nav>
        <ul>
                <li class="mb-4">
                    <a href="../index.php" class="block p-2 rounded bg-gray-800">🏠 Dashboard</a>
                </li>
                <li class="mb-4">
                    <a href="../aprovarSaqueADM/index.php" class="block p-2 rounded hover:bg-gray-800">   💰 Aprovar Saque</a>
                
                </li>
                <li class="mb-4">
                    <a href="../gerenciarCadastrosADM/index.php" class="block p-2 rounded hover:bg-gray-800">
                        📝 Gerenciar Cadastros
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../gerenciarCDIADM/NovoCDI.php" class="block p-2 rounded hover:bg-gray-800">
                        📝 Gerenciar CDI
                    </a>
                </li>
                <li class="mb-4">
                    <a href="../../logout/logout.php" class="block p-2 rounded hover:bg-gray-800">
                        Logout
                    </a>
                </li>

                </ul>
        </nav>
    </aside>
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-900">Editar Estatísticas de Performance Historica</h2>
            <p class="text-gray-600 text-sm mb-6">Selecione um cliente para editar as estatísticas.</p>
            <form method="POST" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
		   <div>
                    <label class="text-gray-700 font-semibold block">Rentabilidade:</label>
                    <input type="number" class="w-full p-2 border rounded-lg" name="rentabilidade" step="0.01" value="<?php echo $rentabilidade; ?>" required>
                </div>
                <div>
                    <label class="form-label text-warning">Rentabilidade CDI:</label>
                    <input type="number" class="w-full p-2 border rounded-lg" name="rentabilidade_CDI" step="0.01" value="<?php echo $rentabilidade_CDI; ?>" required>
                </div>    
                <button type="submit" class="mt-6 w-full bg-yellow-500 text-black p-3 rounded-lg font-bold hover:bg-yellow-400 transition">
                        Salvar Alterações
                    </button>  
            </div>
</form>
            <div class="mt-4 text-center">
            <h3 class="text-warning">Valores Atuais</h3>
            <p class="text-light"><strong>Rentabilidade:</strong> <?php echo number_format($rentabilidade, 2, ',', '.') . '%'; ?></p>
            <p class="text-light"><strong>Rentabilidade CDI:</strong> <?php echo number_format($rentabilidade_CDI, 2, ',', '.') . '%'; ?></p>
           </div>
                   
    </div>
</div>
    </main>

</body>
</html>
