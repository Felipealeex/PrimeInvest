<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php"); 

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

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a';

$usuario_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql_cliente = "SELECT nome FROM usuarios WHERE id = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param("i", $usuario_id);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$cliente_nome = "Cliente Desconhecido";

if ($result_cliente->num_rows > 0) {
    $row = $result_cliente->fetch_assoc();
    $nome_criptografado = $row['nome'];

    $cliente_nome = openssl_decrypt($nome_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id']) && isset($_POST['mes'])) {
    $usuario_id = intval($_POST['usuario_id']);
    $mes = $_POST['mes'];
    $saldo_disponivel = floatval($_POST['saldo_disponivel']);
    $total_investido = floatval($_POST['total_investido']);
    $saldo_projetado = floatval($_POST['saldo_projetado']);

    $sql_update = "UPDATE relatorio_cliente_grafico 
                   SET saldo_disponivel = ?, total_investido = ?, saldo_projetado = ?
                   WHERE usuario_id = ? AND mes = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("dddis", $saldo_disponivel, $total_investido, $saldo_projetado, $usuario_id, $mes);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $usuario_id);
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar os dados!');</script>";
    }
}

$sql = "SELECT mes, saldo_disponivel, total_investido, saldo_projetado
        FROM relatorio_cliente_grafico
        WHERE usuario_id = ?
        ORDER BY FIELD(mes, 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$meses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meses[] = [
            'mes' => $row['mes'],
            'saldo_disponivel' => $row['saldo_disponivel'],
            'total_investido' => $row['total_investido'],
            'saldo_projetado' => $row['saldo_projetado']
        ];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento do Cliente <?php echo htmlspecialchars($cliente_nome); ?> - PrimeInvest</title>
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
                    <li class="nav-item"><a class="nav-link text-warning" href="../index.php">Área ADM</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h1 class="text-center text-warning mt-5">Gerenciamento do Cliente <?php echo htmlspecialchars($cliente_nome); ?></h1>
        <p class="text-center text-light">Ajuste os valores dos meses para o cliente.</p>

        <div class="card bg-dark text-light shadow p-4 mt-4">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Mês</th>
                        <th>Saldo Disponível</th>
                        <th>Total Investido</th>
                        <th>Saldo Projetado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meses as $mes_info): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mes_info['mes']); ?></td>
                        <form method="POST">
                            <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                            <input type="hidden" name="mes" value="<?php echo htmlspecialchars($mes_info['mes']); ?>">
                            <td><input type="number" class="form-control" name="saldo_disponivel" step="0.01" value="<?php echo $mes_info['saldo_disponivel']; ?>"></td>
                            <td><input type="number" class="form-control" name="total_investido" step="0.01" value="<?php echo $mes_info['total_investido']; ?>"></td>
                            <td><input type="number" class="form-control" name="saldo_projetado" step="0.01" value="<?php echo $mes_info['saldo_projetado']; ?>"></td>
                            <td><button type="submit" class="btn btn-warning">Salvar</button></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
