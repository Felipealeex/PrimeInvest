<?php
session_start();
include("../conexao/conexao.php"); 

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id']) || empty($_SESSION['usuario']) || empty($_SESSION['id'])) {
    exit("Usuário não autenticado.");
}

$usuario_id = $_SESSION['id'];
$mensagem = trim($_POST['mensagem'] ?? '');

if (empty($mensagem)) {
    exit("Mensagem de feedback vazia.");
}

$sql = "INSERT INTO feedbacks (usuario_id, mensagem) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $usuario_id, $mensagem);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Feedback enviado com sucesso!";
} else {
    echo "Erro ao enviar feedback.";
}

$stmt->close();
$conn->close();
?>
