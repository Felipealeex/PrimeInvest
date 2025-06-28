<?php
session_start();
include("../conexao/conexao.php");

if (empty($_POST['email']) || empty($_POST['senha_hash'])) {
    echo "<script>alert('Preencha todos os campos corretamente.'); window.history.back();</script>";
    exit();
}

$email = trim($_POST['email']); 
$senha_digitada = $_POST['senha_hash']; 

$sql_adm = "SELECT id, email, senha_hash FROM register_adm WHERE email = ?";
$stmt_adm = $conn->prepare($sql_adm);
$stmt_adm->bind_param("s", $email);
$stmt_adm->execute();
$result_adm = $stmt_adm->get_result();

if ($result_adm->num_rows > 0) {
    $row_adm = $result_adm->fetch_assoc();

    if (password_verify($senha_digitada, $row_adm['senha_hash'])) {
        $_SESSION['admin_email'] = $row_adm['email'];
        $_SESSION['admin_id'] = $row_adm['id'];

        header('Location: ../ADM/index.php');
        exit();
    } else {
        echo "<script>alert('Senha incorreta para o administrador.'); window.history.back();</script>";
        exit();
    }
}

$sql_user = "SELECT id, usuario, senha_hash FROM usuarios WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();

    if (password_verify($senha_digitada, $row_user['senha_hash'])) {
        $_SESSION['usuario'] = $row_user['usuario'];
        $_SESSION['id'] = $row_user['id'];

        header('Location: ../inicioLogin/index.php');
        exit();
    } else {
        echo "<script>alert('Senha incorreta para o usuário.'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('E-mail não encontrado no sistema.'); window.history.back();</script>";
    exit();
}

$conn->close();
?>
