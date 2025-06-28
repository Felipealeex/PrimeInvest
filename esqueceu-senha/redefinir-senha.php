<?php
session_start();
include("../conexao/conexao.php");

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: esqueceu-senha.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $user_id = $_SESSION['reset_user_id'];

        $sql = "UPDATE usuarios SET senha_hash = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $senha_hash, $user_id);
        
        if ($stmt->execute()) {
            unset($_SESSION['reset_user_id']);
            echo "<script>alert('Senha alterada com sucesso!'); window.location.href = '../Login/login.php';</script>";
            exit();
        } else {
            $erro = "Erro ao atualizar a senha. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #FFD700;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: #222;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0px 0px 10px rgba(255, 215, 0, 0.5);
        }
        input, button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 80%;
            background: #333;
            border: 1px solid #FFD700;
            color: #FFD700;
            border-radius: 5px;
        }
        button {
            background-color: #FFD700;
            color: #121212;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #ccac00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Redefinir Senha</h2>
        <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
        <form method="POST">
            <input type="password" name="nova_senha" placeholder="Nova senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
            <button type="submit">Alterar Senha</button>
        </form>
    </div>
</body>
</html>