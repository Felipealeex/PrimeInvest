<?php
session_start();
include("../../conexao/conexao.php");

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['id'])) {
    exit("Usuário não autenticado.");
}

$mensagem = trim($_POST['mensagem']);
$usuario_id = $_POST['usuario_id'] ?? null;
$admin_id = $_POST['admin_id'] ?? null;

if (empty($mensagem)) {
    exit("Mensagem vazia.");
}

$sql_conversa = "SELECT id FROM chat_conversas WHERE usuario_id = ?";
$stmt_conversa = $conn->prepare($sql_conversa);
$stmt_conversa->bind_param("i", $usuario_id);
$stmt_conversa->execute();
$result_conversa = $stmt_conversa->get_result();

if ($result_conversa->num_rows > 0) {
    $row = $result_conversa->fetch_assoc();
    $conversa_id = $row['id'];
} else {
    $sql_nova_conversa = "INSERT INTO chat_conversas (usuario_id) VALUES (?)";
    $stmt_nova_conversa = $conn->prepare($sql_nova_conversa);
    $stmt_nova_conversa->bind_param("i", $usuario_id);
    $stmt_nova_conversa->execute();
    $conversa_id = $stmt_nova_conversa->insert_id;
}

$sql = "INSERT INTO chat_mensagens (conversa_id, usuario_id, admin_id, mensagem) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $conversa_id, $usuario_id, $admin_id, $mensagem);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Mensagem enviada.";
} else {
    echo "Erro ao enviar mensagem.";
}

$stmt->close();
$conn->close();
?>
