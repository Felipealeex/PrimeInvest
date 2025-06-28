<?php
session_start();
include("../../conexao/conexao.php");

if (!isset($_SESSION['admin_id'])) {
    exit("Acesso negado.");
}

$usuario_id = $_POST['usuario_id'] ?? null;

if (!$usuario_id) {
    exit("Erro: ID do usuário não fornecido.");
}

// Obtém a conversa associada ao usuário
$sql_conversa = "SELECT id FROM chat_conversas WHERE usuario_id = ?";
$stmt_conversa = $conn->prepare($sql_conversa);
$stmt_conversa->bind_param("i", $usuario_id);
$stmt_conversa->execute();
$result_conversa = $stmt_conversa->get_result();

if ($result_conversa->num_rows > 0) {
    $row = $result_conversa->fetch_assoc();
    $conversa_id = $row['id'];

    // Exclui todas as mensagens associadas à conversa
    $sql_delete_mensagens = "DELETE FROM chat_mensagens WHERE conversa_id = ?";
    $stmt_delete_mensagens = $conn->prepare($sql_delete_mensagens);
    $stmt_delete_mensagens->bind_param("i", $conversa_id);
    $stmt_delete_mensagens->execute();

    // Exclui a própria conversa
    $sql_delete_conversa = "DELETE FROM chat_conversas WHERE id = ?";
    $stmt_delete_conversa = $conn->prepare($sql_delete_conversa);
    $stmt_delete_conversa->bind_param("i", $conversa_id);
    $stmt_delete_conversa->execute();

    echo "Chat excluído com sucesso.";
} else {
    echo "Nenhuma conversa encontrada para este usuário.";
}

$stmt_conversa->close();
$conn->close();
?>
