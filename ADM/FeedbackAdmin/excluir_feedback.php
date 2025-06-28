<?php
session_start();
include("../../conexao/conexao.php");

if (!isset($_SESSION['admin_email'])) {
    exit("Acesso negado.");
}

$feedback_id = $_POST['feedback_id'] ?? null;

if (!$feedback_id) {
    exit("Erro: ID do feedback não fornecido.");
}

$sql = "DELETE FROM feedbacks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $feedback_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Feedback excluído com sucesso.";
} else {
    echo "Erro ao excluir feedback.";
}

$stmt->close();
$conn->close();
?>