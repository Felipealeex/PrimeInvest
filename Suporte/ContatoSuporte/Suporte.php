<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php");

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
    <title>Suporte - PrimeInvest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0D0D0D;
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
        }
        header {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .back-button {
            background-color: #FFD700;
            color: #0D0D0D;
            border: none;
            padding: 8px 16px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .back-button:hover {
            background-color: #B8860B;
        }
        h1 {
            color: #FFD700;
            font-size: 2em;
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
            display: flex;
            justify-content: space-between;
        }
        .user-message {
            background: #333;
            align-self: flex-end;
            text-align: right;
        }
        .admin-message {
            background: #FFD700;
            color: black;
            align-self: flex-start;
            text-align: left;
        }
        .message-time {
            font-size: 0.8em;
            color: #ccc;
            margin-right: 10px;
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
        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em;
            }
            .chat-container {
                width: 90%;
                padding: 15px;
            }
            .chat-input {
                padding: 8px;
            }
            .send-button {
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <button class="back-button" onclick="history.back()">Página Anterior</button>
    </header>
    <h1>Suporte - Chat com o Administrador</h1>
    <div class="chat-container">
        <div class="messages" id="chatMessages"></div>
        <div class="input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Digite sua mensagem...">
            <button class="send-button" onclick="sendMessage()">Enviar</button>
        </div>
    </div>
    <script>
        const usuarioId = <?php echo $_SESSION['id']; ?>;

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

                if (msg.admin_id !== null) {
                    newMessage.classList.add("message", "admin-message");
                } else {
                    newMessage.classList.add("message", "user-message");
                }

                newMessage.innerHTML = `[${horario}] ${msg.mensagem}`;
                messages.appendChild(newMessage);
            });

            messages.scrollTop = messages.scrollHeight;
        });
}


        setInterval(carregarMensagens, 3000);
    </script>
</body>
</html>