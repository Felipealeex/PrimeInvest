<?php
session_start();
include("../../conexao/conexao.php");
include("../../conexao/criptografia.php");

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
    <title>Termos e Condições | PrimeInvest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
        <button class="back-button" onclick="history.back()">Página Anterior</button>
        </nav>
    </header>
    
    <div class="container">
        <h1>TERMOS DE USO E POLÍTICA DE PRIVACIDADE</h1>
        <div class="content">
            <p><strong>CLUBE DE INVESTIMENTOS PRIME</strong><br>
            <span class="white-text">Última atualização: 10 de março de 2025</span></p>
            <p>Bem-vindo ao Clube de Investimentos Prime! Antes de utilizar nosso site (clubedeinvestimentosprime.com.br), leia atentamente estes Termos de Uso e nossa Política de Privacidade. O uso deste site implica na aceitação integral das condições aqui descritas.</p>
            <hr>
            <p><strong>1. OBJETIVO DO SITE</strong></p>
            <p>O Clube de Investimentos Prime é um site informativo sobre investimentos, não realizando operações financeiras, transações bancárias, nem exigindo inserção de cartões de crédito ou débito. As informações disponibilizadas têm caráter estritamente educativo e não constituem recomendações de investimentos ou assessoria financeira.</p>
            <hr>
            <p><strong>2. DIREITOS DO USUÁRIO</strong></p>
            <p>De acordo com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018), o usuário tem os seguintes direitos em relação às suas informações pessoais:</p>
            <ul>
                <li><span class="white-text">Acesso:</span> O usuário pode solicitar informações sobre quais dados seus são armazenados e como são utilizados.</li>
                <li><span class="white-text">Correção:</span> Caso os dados estejam incorretos ou desatualizados, o usuário pode solicitar a retificação.</li>
                <li><span class="white-text">Restrição de uso:</span> O usuário pode pedir a limitação do uso de seus dados em determinados casos.</li>
                <li><span class="white-text">Portabilidade:</span> O usuário pode solicitar a transferência de seus dados para outro serviço, quando aplicável.</li>
            </ul>
            <hr>
            <p><strong>3. ACEITAÇÃO DOS TERMOS</strong></p>
            <p>Ao se cadastrar no site, o usuário declara estar ciente e concordar integralmente com estes Termos de Uso e com a Política de Privacidade. O uso contínuo do site confirma essa aceitação.</p>
            <hr>
            <p><strong>4. PRIVACIDADE E SEGURANÇA DOS DADOS</strong></p>
            <p><strong>4.1. Coleta de Informações</strong></p>
            <p>Para utilizar nossos serviços, coletamos e armazenamos os seguintes dados dos usuários:</p>
            <ul>
                <li><span class="white-text">Dados Pessoais:</span> Data de nascimento, nacionalidade, gênero, CPF, RG, estado civil, CEP, país, endereço, número, bairro, complemento.</li>
                <li><span class="white-text">Dados Financeiros:</span> Banco, agência, dígito da conta, conta bancária, chave PIX, tipo do PIX.</li>
                <li><span class="white-text">Dados Empresariais (se aplicável):</span> CNPJ.</li>
                <li><span class="white-text">Dados de Contato:</span> E-mail, telefone.</li>
                <li><span class="white-text">Dados de Acesso:</span> Usuário e senha.</li>
                <li><span class="white-text">Dados de Habilitação:</span> Número da CNH, categoria da CNH.</li>
            </ul>
            <p><strong>4.2. Uso e Proteção dos Dados</strong></p>
            <p>Os dados informados são utilizados exclusivamente para fins de cadastro e administração do site. Apenas a equipe de desenvolvimento e a administração do site possuem acesso a essas informações.</p>
            <p><strong>4.3. Segurança da Conta</strong></p>
            <p>O usuário é responsável por manter suas credenciais de acesso (usuário e senha) seguras e não deve compartilhá-las com terceiros. O Clube de Investimentos Prime não se responsabiliza por acessos indevidos decorrentes de negligência na proteção dos dados de login.</p>
            <hr>
            <p><strong>5. RESPONSABILIDADES DO USUÁRIO</strong></p>
            <ul>
                <li>Fornecer informações verdadeiras e atualizadas no cadastro.</li>
                <li>Não compartilhar seu login e senha com terceiros.</li>
                <li>Utilizar o site apenas para fins informativos, sem prática de atividades ilegais.</li>
            </ul>
            <hr>
        </div>
    </div>
</body>
</html>
