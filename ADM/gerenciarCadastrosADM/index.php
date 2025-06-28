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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['valor_conta'])) {
    $id = $_POST['id'];
    $valor_conta = $_POST['valor_conta'];

    if (is_numeric($valor_conta) && $valor_conta >= 0) {
        $sql_update = "UPDATE usuarios SET valor_conta = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("di", $valor_conta, $id);
        $stmt_update->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT id, nome, cpf, valor_conta FROM usuarios";
$result = $conn->query($sql);

$clientes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nome_cliente = openssl_decrypt($row['nome'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
        $cpf_cliente = openssl_decrypt($row['cpf'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));

        $clientes[] = [
            'id' => $row['id'],
            'nome' => $nome_cliente,
            'cpf' => $cpf_cliente,
            'valor_conta' => $row['valor_conta']
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
    <title>Gerenciamento de Clientes - PrimeInvest</title>
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
                    <li class="nav-item"><a class="nav-link text-warning" href="../aprovarSaqueADM/index.php">Solicitações de Saque</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h1 class="text-warning text-center mt-5">Gerenciamento de Clientes</h1>
        <div id="listaClientes" class="card bg-dark text-light shadow p-4 mt-4">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaClientes">
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                            <td>
                            <button class="btn btn-warning btn-sm"
        onclick="window.location.href='../gerenciarClientesADM/index.php?id=<?php echo $cliente['id']; ?>&nome=<?php echo urlencode($cliente['nome']); ?>&cpf=<?php echo urlencode($cliente['cpf']); ?>&valor_conta=<?php echo $cliente['valor_conta']; ?>'">
    Editar
</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="formEdicao" class="card bg-dark text-light shadow p-4 mt-4 d-none">
            <h3 class="text-warning text-center">Detalhes do Cliente</h3>
            <form id="formEditar" method="POST">
                <input type="hidden" name="id" id="idCliente">
                <div class="mb-3">
                    <label class="form-label text-warning">Nome:</label>
                    <input type="text" class="form-control" id="nomeCliente" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label text-warning">CPF:</label>
                    <input type="text" class="form-control" id="cpfCliente" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label text-warning">Valor na Conta:</label>
                    <input type="number" class="form-control" id="valorConta" name="valor_conta" required min="0" step="0.01">
                </div>
                <button type="submit" class="btn btn-warning w-100">Salvar Alterações</button>
                <button type="button" class="btn btn-outline-warning w-100 mt-2" onclick="voltarLista()">Voltar</button>
            </form>
        </div>
    </div>

    <script>
        function editarCliente(id, nome, cpf, valorConta) {
            document.getElementById("listaClientes").classList.add("d-none");
            document.getElementById("formEdicao").classList.remove("d-none");

            document.getElementById("idCliente").value = id;
            document.getElementById("nomeCliente").value = nome;
            document.getElementById("cpfCliente").value = cpf;
            document.getElementById("valorConta").value = valorConta;
        }

        function voltarLista() {
            document.getElementById("listaClientes").classList.remove("d-none");
            document.getElementById("formEdicao").classList.add("d-none");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
