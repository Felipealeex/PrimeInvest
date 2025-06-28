<?php
session_start();
include("../conexao/conexao.php"); 
include("../conexao/criptografia.php"); 

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id']) || empty($_SESSION['usuario']) || empty($_SESSION['id'])) {
    session_destroy();
    header("Location: ../Login/login.php");
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
    <title>Feedback - PrimeInvest</title>
  <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #E0E0E0;
            text-align: center;
            padding: 20px;
        }
        .feedback-container {
            max-width: 700px;
            margin: auto;
            background: linear-gradient(135deg, #1E1E1E, #292929);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 8px 8px 20px rgba(0, 0, 0, 0.4);
        }
        .feedback-input {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1em;
            resize: none;
            outline: none;
        }
        .send-button {
            background-color: #FFD700;
            color: #0D0D0D;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .send-button:hover {
            background-color: #B8860B;
        }
        .back-button {
            background-color: transparent;
            color: #FFD700;
            border: 2px solid #FFD700;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 15px;
        }
        .back-button:hover {
            background-color: #FFD700;
            color: #121212;
        }
        @media (max-width: 768px) {
            .feedback-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <button class="back-button" onclick="history.back()">Página Anterior</button>
    </header>
    <h1>Envie seu Feedback</h1>
    <div class="feedback-container">
        <textarea id="feedbackInput" class="feedback-input" rows="5" placeholder="Digite seu feedback..."></textarea>
        <button class="send-button" onclick="sendFeedback()">Enviar</button>
    </div>

    <script>
        function sendFeedback() {
            var mensagem = document.getElementById("feedbackInput").value.trim();
            if (mensagem !== "") {
                fetch("salvar_feedback.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `mensagem=${encodeURIComponent(mensagem)}`
                }).then(response => response.text()).then(result => {
                    alert(result);
                    document.getElementById("feedbackInput").value = "";
                });
            }
        }
    </script>
</body>
</html>
