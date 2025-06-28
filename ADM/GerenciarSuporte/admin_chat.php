<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php"); // Certifique-se de que este arquivo contém a função `descriptografarAES`

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../Login/login.php");
    exit();
}

// Busca todos os usuários que possuem conversas abertas
$sql = "SELECT DISTINCT usuarios.id, usuarios.nome 
        FROM chat_conversas 
        JOIN usuarios ON chat_conversas.usuario_id = usuarios.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Suporte - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0D0D0D;
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
        }
        h1 {
            color: #FFD700;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #222;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #B8860B;
        }
        .user-list {
            list-style: none;
            padding: 0;
        }
        .user-item {
            background: #333;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .chat-button {
            background-color: #FFD700;
            color: #0D0D0D;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .chat-button:hover {
            background-color: #B8860B;
        }
        .delete-button {
            background-color: #FF4500;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .delete-button:hover {
            background-color: #D32F2F;
        }
    </style>
</head>
<body>
     <header>
        <button class="back-button" onclick="history.back()">Página Anterior</button>
    </header>
    <h1>Painel de Suporte</h1>
    <div class="container">
        <h2>Usuários com Conversas Abertas</h2>
        <ul class="user-list">
            <?php while ($row = $result->fetch_assoc()) { 
                // Descriptografa o nome do usuário antes de exibir
                $nome_usuario = descriptografarAES($row['nome'], $chaveAES);
            ?>
                <li class="user-item">
                    <span><?php echo htmlspecialchars($nome_usuario); ?> (ID: <?php echo $row['id']; ?>)</span>
                    <div class="buttons">
                        <a href="admin_chat_view.php?usuario_id=<?php echo $row['id']; ?>" class="chat-button">Abrir Chat</a>
                        <button class="delete-button" onclick="excluirChat(<?php echo $row['id']; ?>)">Excluir</button>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>

    <script>
        function excluirChat(usuarioId) {
            if (confirm("Tem certeza que deseja excluir este chat? Essa ação não pode ser desfeita.")) {
                fetch("excluir_chat.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `usuario_id=${usuarioId}`
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
