<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $rg_input = $_POST['rg'];

    $sql = "SELECT id, rg FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rg_criptografado = $row['rg'];
        $rg_descriptografado = openssl_decrypt($rg_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));

        if ($rg_input === $rg_descriptografado) {
            $_SESSION['reset_user_id'] = $row['id'];
            header("Location: redefinir-senha.php");
            exit();
        } else {
            $erro = "RG incorreto.";
        }
    } else {
        $erro = "E-mail não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
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
    <script>
        function senhaAlterada() {
            alert("Senha alterada com sucesso!");
            window.location.href = "../Login/login.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Recuperação de Senha</h2>
        (RG com pontos e traço.)
        <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Digite seu e-mail" required>
            <input type="text" name="rg" placeholder="Digite seu RG" required>
            <button type="submit">Verificar</button>
        </form>
    </div>
</body>
</html>
