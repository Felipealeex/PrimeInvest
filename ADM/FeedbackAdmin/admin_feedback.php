<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php");

if (!isset($_SESSION['admin_email'])) {
    header('Location: ../Login/login.php');
    exit();
}

$admin_email = $_SESSION['admin_email'];

$sql = "SELECT id FROM register_adm WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ../Login/login.php');
    exit();
}

$sql = "SELECT feedbacks.id, usuarios.nome, feedbacks.mensagem, DATE_FORMAT(feedbacks.enviado_em, '%d/%m/%Y %H:%i') as enviado_em 
        FROM feedbacks 
        JOIN usuarios ON feedbacks.usuario_id = usuarios.id 
        ORDER BY feedbacks.enviado_em DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks - Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #E0E0E0;
            text-align: center;
            padding: 20px;
        }
        .feedback-container {
            max-width: 900px;
            margin: auto;
            background: linear-gradient(135deg, #1E1E1E, #292929);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 8px 8px 20px rgba(0, 0, 0, 0.4);
        }
        .feedback-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            margin: 15px 0;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease-in-out, background 0.3s;
        }
        .feedback-item:hover {
            transform: scale(1.03);
            background: rgba(255, 255, 255, 0.1);
        }
        .feedback-item strong {
            font-size: 1.3em;
            color: #FFD700;
        }
        .feedback-item p {
            font-size: 1.1em;
            color: #DDDDDD;
            margin: 10px 0;
        }
        .feedback-item small {
            color: #AAAAAA;
            font-size: 0.9em;
        }
        .delete-button {
            background-color: #FF4500;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
            font-weight: bold;
        }
        .delete-button:hover {
            background-color: #D32F2F;
        }
        @media (max-width: 768px) {
            .feedback-container {
                width: 95%;
            }
            .feedback-item {
                padding: 15px;
            }
        }
        @media (min-width: 1920px) {
            .feedback-container {
                max-width: 1100px;
            }
        }
    </style>
</head>
<body>
    <h1>Feedbacks dos Usuários</h1>
    <div class="feedback-container">
        <?php while ($row = $result->fetch_assoc()) { 
            $nome_usuario = descriptografarAES($row['nome'], $chaveAES); ?>
            <div class="feedback-item">
                <div>
                    <strong><?php echo htmlspecialchars($nome_usuario); ?>:</strong>
                    <p><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></p>
                    <small>Enviado em: <?php echo $row['enviado_em']; ?></small>
                </div>
                <button class="delete-button" onclick="excluirFeedback(<?php echo $row['id']; ?>)">Excluir</button>
            </div>
        <?php } ?>
    </div>

    <script>
        function excluirFeedback(feedbackId) {
            if (confirm("Tem certeza que deseja excluir este feedback?")) {
                fetch("excluir_feedback.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `feedback_id=${feedbackId}`
                }).then(response => response.text()).then(result => {
                    alert(result);
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>