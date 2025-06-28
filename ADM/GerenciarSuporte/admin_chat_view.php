<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php"); // Certifique-se de que este arquivo contém a função `descriptografarAES`

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../Login/login.php");
    exit();
}

$usuario_id = $_GET['usuario_id'] ?? 0;

// Verifica se o usuário existe
$sql_user = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_user = $stmt->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $nome_usuario = descriptografarAES($row_user['nome'], $chaveAES);
} else {
    die("Usuário não encontrado.");
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat com <?php echo htmlspecialchars($nome_usuario); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0D0D0D;
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
        }
        .chat-container {
            max-width: 600px;
            margin: auto;
            background: #222;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #B8860B;
            text-align: left;
        }
        .messages {
            height: 300px;
            overflow-y: auto;
            background: #111;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }
        .message {
            max-width: 70%;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            color: white;
        }
        .user-message {
            background: #333;
            align-self: flex-start;
        }
        .admin-message {
            background: #FFD700;
            color: black;
            align-self: flex-end;
        }
        .input-container {
            display: flex;
        }
        .chat-input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .send-button {
            background-color: #FFD700;
            color: #0D0D0D;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .send-button:hover {
            background-color: #B8860B;
        }
    </style>
</head>
<body>
    <h1>Chat com <?php echo htmlspecialchars($nome_usuario); ?></h1>

    <div class="chat-container">
        <div class="messages" id="chatMessages"></div>
        <div class="input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Digite sua mensagem...">
            <button class="send-button" onclick="sendMessage()">Enviar</button>
        </div>
    </div>

<script>
    const usuarioId = <?php echo $_GET['usuario_id']; ?>;

    function sendMessage() {
        var input = document.getElementById("chatInput");
        var mensagem = input.value.trim();

        if (mensagem !== "") {
            fetch("salvar_mensagem.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `mensagem=${encodeURIComponent(mensagem)}&usuario_id=${usuarioId}`
            }).then(response => response.text()).then(() => carregarMensagens());

            input.value = ""; 
        }
    }

function carregarMensagens() {
    fetch(`carregar_mensagens.php?usuario_id=${usuarioId}`)
        .then(response => response.json())
        .then(mensagens => {
            var messages = document.getElementById("chatMessages");
            messages.innerHTML = "";

            mensagens.forEach(msg => {
                var newMessage = document.createElement("p");

                var horario = msg.horario ? msg.horario : "Erro na hora";

                newMessage.innerHTML = `<span style="font-size: 0.8em; color: #ccc;">[${horario}]</span> ${msg.mensagem}`;
                newMessage.classList.add("message");

                if (msg.admin_id) {
                    newMessage.classList.add("admin-message"); 
                } else {
                    newMessage.classList.add("user-message"); 
                }

                messages.appendChild(newMessage);
            });

            messages.scrollTop = messages.scrollHeight;
        });
}



    setInterval(carregarMensagens, 3000); 
</script>


</body>
</html>
