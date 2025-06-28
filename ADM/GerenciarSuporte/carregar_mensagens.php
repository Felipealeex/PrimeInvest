<?php
session_start();
include("../../conexao/conexao.php");

$usuario_id = $_GET['usuario_id'] ?? 0;

if (!$usuario_id) {
    echo json_encode([]);
    exit();
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

    // Busca todas as mensagens da conversa e formata a data corretamente
    $sql = "SELECT usuario_id, admin_id, mensagem, DATE_FORMAT(enviado_em, '%H:%i') as horario 
            FROM chat_mensagens 
            WHERE conversa_id = ? 
            ORDER BY enviado_em ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $conversa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $mensagens = [];
    while ($row = $result->fetch_assoc()) {
        $mensagens[] = $row;
    }

    echo json_encode($mensagens);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>
