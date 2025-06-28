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

$sql = "SELECT cpf FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_logado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cpf_criptografado = $row['cpf'];
    $cpf_usuario = openssl_decrypt($cpf_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
} else {
    $cpf_usuario = "CPF não encontrado";
}

$sql_verifica = "SELECT id FROM solicitacoes_saque WHERE usuario = ? AND status = 'Pendente'";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bind_param("s", $usuario_logado);
$stmt_verifica->execute();
$result_verifica = $stmt_verifica->get_result();

if ($result_verifica->num_rows > 0) {
    echo "<script>alert('Você já possui uma solicitação de saque pendente. Aguarde a aprovação antes de solicitar um novo saque.'); window.location.href='../DashCliente/index.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor_saque = $_POST['valor_saque'];
    $metodo_pagamento = $_POST['metodo_pagamento'];

    if (empty($valor_saque) || empty($metodo_pagamento)) {
        echo "<script>alert('Preencha todos os campos corretamente.'); window.history.back();</script>";
        exit();
    }

    $sql_insert = "INSERT INTO solicitacoes_saque (cpf, usuario, valor_solicitado, metodopagamento, status) 
                   VALUES (?, ?, ?, ?, 'Pendente')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssds", $cpf_usuario, $usuario_logado, $valor_saque, $metodo_pagamento);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Saque solicitado com sucesso!'); window.location.href='../historicoSaque/index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao solicitar saque. Tente novamente.'); window.history.back();</script>";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Saque - PrimeInvest</title>
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
                    <li class="nav-item"><a class="nav-link text-warning" href="../DashCliente/index.php">Área Cliente</a></li>
                    <li class="nav-item"><a class="nav-link text-warning" href="../historicoSaque/index.php">Histórico de Saques</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h1 class="text-center text-warning mt-5">Solicitar Saque</h1>
        <p class="text-center text-light">Preencha as informações abaixo para solicitar um saque.</p>

        <div class="card bg-dark text-light shadow p-4 mt-4">
            <h3 class="text-warning text-center">Detalhes do Saque</h3>
            <form id="formSaque" method="POST">
            
                <div class="mb-3">
                    <label for="cpf" class="form-label text-warning">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf_usuario); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="valorSaque" class="form-label text-warning">Valor do Saque (R$)</label>
                    <input type="number" class="form-control" id="valorSaque" name="valor_saque" placeholder="Digite o valor" required min="1" step="0.01">
                </div>
                
                <div class="mb-3">
                    <label for="metodoPagamento" class="form-label text-warning">Método de Pagamento</label>
                    <select class="form-control" id="metodoPagamento" name="metodo_pagamento" required>
                        <option value="">Selecione um método</option>
                        <option value="pix">PIX</option>
                        <option value="transferencia">Transferência Bancária</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning w-100">Solicitar Saque</button>
                <p id="mensagemSucesso" class="text-success text-center mt-3 d-none">Saque solicitado com sucesso!</p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mask-plugin/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#cpf').mask('000.000.000-00');
            $('#valorSaque').mask('000000000,00', {reverse: true});
        });
    </script>

</body>
</html>
