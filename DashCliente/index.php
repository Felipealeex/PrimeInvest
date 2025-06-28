<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id']) || empty($_SESSION['usuario']) || empty($_SESSION['id'])) {
    session_destroy();
    header("Location: ../Login/login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$id_usuario = $_SESSION['id'];

$sql = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome_usuario = descriptografarAES($row['nome'], $chaveAES);
    } else {
        $nome_usuario = "Usuário"; 
    }
    $stmt->close();
} else {
    die("Erro na consulta ao banco de dados: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente - Investimentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial"></script>

    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #121212;
    color: #ffffff;
    background-image: url(banner.png);
    background-size: cover;
    background-repeat: no-repeat; 
    background-attachment: fixed;
}
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top shadow">
        <div class="container">
            <a class="navbar-brand text-warning" href="#">PrimeInvest</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-warning" href="../inicioLogin/index.php">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-warning" href="#" id="investimentosDropdown" role="button" data-bs-toggle="dropdown">
                            Investimentos
                        </a>
                        <ul class="dropdown-menu bg-dark border-warning">
                            <li><a class="dropdown-item text-warning" href="../solicitarSaque/index.php">Solicitar Saque</a></li>
                            <li><a class="dropdown-item text-warning" href="../Relatorios/index.php">Meu Relatorio</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-warning" href="#" id="contaDropdown" role="button" data-bs-toggle="dropdown">
                            Minha Conta
                        </a>
                        <ul class="dropdown-menu bg-dark border-warning">
                            <li><a class="dropdown-item text-warning" href="../historicoSaque/index.php">Historico de saques</a></li>
                            <li><a class="dropdown-item text-warning" href="../PaginaCurso/CursoLogin/index.php">Cursos</a></li>
                            <li><a class="dropdown-item text-warning" href="../MeuPerfil/index.php">Meu Perfil</a></li>
                          <!-- <li><a class="dropdown-item text-warning" href="../Suporte/index.php">Suporte</a></li> 
                            <li><a class="dropdown-item text-warning" href="../feedback/feedback.php">Feedback</a></li> -->
                            <li><a class="dropdown-item text-warning" href="../logout/logout.php">Logout</a></li>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h1 class="text-center text-warning mt-5">Painel do Investidor</h1>
        <p class="text-center text-light">Acompanhe seus investimentos em tempo real.</p>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

</body>
</html>
