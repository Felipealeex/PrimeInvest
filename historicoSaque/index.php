<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../../Login/login.php');
    exit();
}

$usuario_logado = $_SESSION['usuario'];

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a';

$sql_usuario = "SELECT nome, banco, agencia, conta FROM usuarios WHERE usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $usuario_logado);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();

    $nome_criptografado = $row_usuario['nome'];
    $banco_criptografado = $row_usuario['banco'];
    $agencia_criptografada = $row_usuario['agencia'];
    $conta_criptografada = $row_usuario['conta'];

    $nome_usuario = openssl_decrypt($nome_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $banco_usuario = openssl_decrypt($banco_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $agencia_usuario = openssl_decrypt($agencia_criptografada, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $conta_usuario = openssl_decrypt($conta_criptografada, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
} else {
    echo "<script>alert('Erro ao recuperar os dados do usuário.'); window.location.href='../DashCliente/index.php';</script>";
    exit();
}

$sql_saques = "SELECT data_solicitacao, status, valor_solicitado FROM solicitacoes_saque WHERE usuario = ? ORDER BY data_solicitacao DESC";
$stmt_saques = $conn->prepare($sql_saques);
$stmt_saques->bind_param("s", $usuario_logado);
$stmt_saques->execute();
$result_saques = $stmt_saques->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Saques - PrimeInvest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top shadow">
    <div class="container">
        <a class="navbar-brand text-warning" href="#">PrimeInvest</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-warning" href="../DashCliente/index.php">Área Cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning active" href="../solicitarSaque/index.php">Solicitar Saque</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <h1 class="text-warning text-center mt-5">Suas Transferências</h1>
    <div class="card bg-dark text-light shadow p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Criado em</th>
                        <th>Favorecido</th>
                        <th>Banco</th>
                        <th>Agência</th>
                        <th>CC</th>
                        <th>Status</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_saques->num_rows > 0) {
                        while ($row_saque = $result_saques->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . date("d/m/Y H:i", strtotime($row_saque['data_solicitacao'])) . "</td>";
                            echo "<td>" . htmlspecialchars($nome_usuario) . "</td>";
                            echo "<td>" . htmlspecialchars($banco_usuario) . "</td>";
                            echo "<td>" . htmlspecialchars($agencia_usuario) . "</td>";
                            echo "<td>" . htmlspecialchars($conta_usuario) . "</td>";

                            $status = $row_saque['status'];
                            $status_badge = "";
                            if ($status == "Pendente") {
                                $status_badge = '<span class="badge bg-warning text-dark">Pendente</span>';
                            } elseif ($status == "Aprovado") {
                                $status_badge = '<span class="badge bg-success">Aprovado</span>';
                            } elseif ($status == "Rejeitado") {
                                $status_badge = '<span class="badge bg-danger">Negado</span>';
                            }
                            echo "<td>$status_badge</td>";

                            echo "<td>R$ " . number_format($row_saque['valor_solicitado'], 2, ',', '.') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-warning'>Nenhuma solicitação de saque encontrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>