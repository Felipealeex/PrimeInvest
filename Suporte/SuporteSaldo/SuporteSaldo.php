<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php");

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id']) || empty($_SESSION['usuario']) || empty($_SESSION['id'])) {
    session_destroy();
    header("Location: ../../Login/login.php");
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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Suporte - Consulta de Saldo</title>
</head>
<body>
    <h1 style="color: red;">PÁGINA EM MANUTENÇÃO</h1>

    <header>
        <button class="back-button" onclick="history.back()">Página Anterior</button>
    </header>
    <h1>Suporte - Consulta de Saldo</h1>
    <div class="content">
        <p>Para consultar seu saldo, siga os passos abaixo:</p>
        <p>1. <strong>Após logar</strong>, acesse o painel principal.</p>
        <p>2. No canto <strong>superior esquerdo</strong>, clique em <strong>Área Cliente</strong>.</p>
        <p>3. No canto <strong>superior direito</strong>, selecione <strong>Investimentos</strong>.</p>
        <p>4. Em seguida, clique em <strong>Meu Relatório</strong>.</p>
        <p>5. O valor estará disponível na seção <strong>Saldo Disponível</strong>.</p>
        <p class="alert">⚠️ Caso o saldo apresente qualquer caractere que não seja número, entre em contato com o suporte imediatamente.</p>
        <a href="../ContatoSuporte/Suporte.php" class="support-button">Suporte</a>
    </div>
</body>
</html>
