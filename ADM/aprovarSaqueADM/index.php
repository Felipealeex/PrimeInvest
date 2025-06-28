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

function buscarSaques($conn, $status, $chaveAES) {
    $sql = "SELECT s.id, s.valor_solicitado, s.data_solicitacao, s.status, s.metodopagamento,
                   u.nome, u.agencia, u.conta
            FROM solicitacoes_saque s
            INNER JOIN usuarios u ON s.usuario = u.usuario
            WHERE s.status = ?
            ORDER BY s.data_solicitacao DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();

    $saques = [];
    while ($row = $result->fetch_assoc()) {
        $nome_cliente = openssl_decrypt($row['nome'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
        $agencia_cliente = openssl_decrypt($row['agencia'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
        $conta_cliente = openssl_decrypt($row['conta'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));

        $saques[] = [
            'id' => $row['id'],
            'data_solicitacao' => date("d/m/Y", strtotime($row['data_solicitacao'])),
            'nome' => $nome_cliente,
            'agencia' => $agencia_cliente,
            'conta' => $conta_cliente,
            'metodopagamento' => ucfirst($row['metodopagamento']),
            'valor_solicitado' => "R$ " . number_format($row['valor_solicitado'], 2, ',', '.'),
        ];
    }

    return $saques;
}

$saques_pendentes = buscarSaques($conn, "Pendente", $chaveAES);
$saques_aprovados = buscarSaques($conn, "Aprovado", $chaveAES);
$saques_rejeitados = buscarSaques($conn, "Rejeitado", $chaveAES);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    $id_saque = intval($_POST['id_saque']);
    
    if ($_POST['acao'] === 'excluir') {

        $sql_delete = "DELETE FROM solicitacoes_saque WHERE id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("i", $id_saque);
        $stmt->execute();
    } else {
        $novo_status = $_POST['acao'] === 'aprovar' ? 'Aprovado' : 'Rejeitado';
        $sql_update = "UPDATE solicitacoes_saque SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("si", $novo_status, $id_saque);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Saques - PrimeInvest</title>
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
        <h1 class="text-warning text-center mt-5">Aprovação de Saques Pendentes</h1>

        <div class="container mt-5 pt-5">
            <h1 class="text-warning text-center mt-5">Aprovação de Saques</h1>
            <div class="card bg-dark text-light shadow p-4 mt-4">
                <h3 class="text-center text-warning">Saques Pendentes</h3>
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Criado em</th>
                                <th>Cliente</th>
                                <th>Agência</th>
                                <th>Conta</th>
                                <th>Valor</th>
                                <th>Método de Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($saques_pendentes as $saque): ?>
                                <tr>
                                    <td><?php echo $saque['data_solicitacao']; ?></td>
                                    <td><?php echo htmlspecialchars($saque['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($saque['agencia']); ?></td>
                                    <td><?php echo htmlspecialchars($saque['conta']); ?></td>
                                    <td><?php echo $saque['valor_solicitado']; ?></td>
                                    <td><?php echo htmlspecialchars($saque['metodopagamento']); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_saque" value="<?php echo $saque['id']; ?>">
                                            <button type="submit" name="acao" value="aprovar" class="btn btn-success btn-sm">Aprovar</button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_saque" value="<?php echo $saque['id']; ?>">
                                            <button type="submit" name="acao" value="negar" class="btn btn-danger btn-sm">Negar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card bg-dark text-light shadow p-4 mt-4">
                <h3 class="text-center text-success">Saques Aprovados</h3>
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Criado em</th>
                                <th>Cliente</th>
                                <th>Agência</th>
                                <th>Conta</th>
                                <th>Valor</th>
                                <th>Método de Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($saques_aprovados as $saque): ?>
                                <tr>
                                    <td><?php echo $saque['data_solicitacao']; ?></td>
                                    <td><?php echo htmlspecialchars($saque['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($saque['agencia']); ?></td>
                                    <td><?php echo htmlspecialchars($saque['conta']); ?></td>
                                    <td><?php echo $saque['valor_solicitado']; ?></td>
                                    <td><?php echo htmlspecialchars($saque['metodopagamento']); ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="id_saque" value="<?php echo $saque['id']; ?>">
                                            <button type="submit" name="acao" value="excluir" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

    </body>
    </html>
    
