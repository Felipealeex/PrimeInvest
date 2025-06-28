<?php
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a'; 

$sql = "SELECT nome FROM usuarios WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nome_criptografado = $row['nome'];

    $nome_descriptografado = openssl_decrypt($nome_criptografado, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));

    echo "Nome descriptografado: " . $nome_descriptografado;
} else {
    echo "Usuário não encontrado.";
}

$conn->close();
?>
