<?php
include_once("../conexao/conexao.php");
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - XPrime Invest</title>
    <link rel="stylesheet" href="login.css">
    <link rel="shortcut icon" type="imagex/png" href="./NOVALOGO">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background: url(banner.png);
    background-size: cover; 
    background-position: center; 
    background-attachment: fixed;;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
    </style>
    <div class="login-container">
        <div class="login-box">
            <h2 class="title">Bem-vindo de volta!</h2>
            <p class="subtitle">Faça login na sua conta</p>

            <?php
            if (isset($_GET['erro'])) {
                echo "<p class='error-message'>Erro: ";
                switch ($_GET['erro']) {
                    case 'campos_vazios':
                        echo "Preencha todos os campos.";
                        break;
                    case 'email_invalido':
                        echo "Formato de e-mail inválido.";
                        break;
                    case 'credenciais_invalidas':
                        echo "E-mail ou senha incorretos.";
                        break;
                    case 'erro_query':
                        echo "Erro interno no sistema. Tente novamente.";
                        break;
                    default:
                        echo "Erro desconhecido.";
                        break;
                }
                echo "</p>";
            }
            ?>

            <form id="loginForm" method="post" action="processa_login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                </div>
                <div class="input-group">
                    <label for="senha_hash">Senha</label>
                    <input type="password" id="senha_hash" name="senha_hash" placeholder="Digite sua senha" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                <div class="forgot-password">
                    <a href="../registro/registro.php">Abra sua conta já!</a>
                </div>
                <div class="forgot-password">
                    <a href="../esqueceu-senha/esqueceu-senha.php">Esqueceu sua senha?</a>
                </div>
                <input class="btn-login" type="submit" value="Entrar">
            </form>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.querySelector("#togglePassword");
        const passwordInput = document.querySelector("#senha_hash"); // Ajustado para pegar o ID correto

        togglePassword.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.classList.remove("fa-eye");
                togglePassword.classList.add("fa-eye-slash"); // Ícone de olho fechado
            } else {
                passwordInput.type = "password";
                togglePassword.classList.remove("fa-eye-slash");
                togglePassword.classList.add("fa-eye"); // Ícone de olho aberto
            }
        });
    });
</script>


</body>
</html>
