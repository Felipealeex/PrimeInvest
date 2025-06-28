<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

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
    <link rel="stylesheet" href="style.css">
    <title>Suporte - PrimeInvest</title>
 
</head>
<body>
    <h1 style="color: red;">PÁGINA EM MANUTENÇÃO</h1>

    <header>
        <button class="back-button" onclick="history.back()">Página Anterior</button>
    </header>

    <h1>Página de Suporte!</h1>
    <h2>Escolha o suporte que você deseja.</h2>
    <h3>Deseja modificar algum dado da sua conta? Contate o suporte!</h3>
    <ul>
        <li><a href="./ContatoSuporte/Suporte.php">📞 Contato com o Suporte</a></li>
        <li><a href="./SuporteSaldo/SuporteSaldo.php">🔍 Problemas ao Consultar Saldo</a></li>
        <li><a href="../termos/termosLogin/index.php">📝 Termos de Uso</a></li>
    </ul>
</body>
</html>