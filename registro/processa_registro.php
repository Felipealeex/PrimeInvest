<?PHP
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

$nome = $_POST['nome'];
$nascimento = $_POST['nascimento'];
$banco = $_POST['banco'];
$conta = $_POST['conta'];
$digito = $_POST['digito'];
$pix_chave = $_POST['pix_chave'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$nacionalidade = $_POST['nacionalidade'];
$genero = $_POST['genero'];
$estado_civil = $_POST['estado_civil'];
$cep = $_POST['cep'];
$cnh = $_POST['cnh'];
$categoria_cnh = $_POST['categoria_cnh'];
$pais = $_POST['pais'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$complemento = $_POST['complemento'];
$agencia = $_POST['agencia'];
$pix_tipo = $_POST['pix_tipo'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$total_investido = $_POST['total_investido'];

// Garantindo que o valor seja decimal correto (substitui ',' por '.' e converte para float)
$total_investido = str_replace(',', '.', $total_investido);
$total_investido = floatval($total_investido);

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a'; 

$nome_criptografado = openssl_encrypt($nome, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$nascimento_criptografado = openssl_encrypt($nascimento, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$banco_criptografado = openssl_encrypt($banco, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$conta_criptografada = openssl_encrypt($conta, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$digito_criptografado = openssl_encrypt($digito, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$pix_chave_criptografada = openssl_encrypt($pix_chave, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$cpf_criptografado = openssl_encrypt($cpf, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$rg_criptografado = openssl_encrypt($rg, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$nacionalidade_criptografada = openssl_encrypt($nacionalidade, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$genero_criptografado = openssl_encrypt($genero, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$estado_civil_criptografado = openssl_encrypt($estado_civil, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$cep_criptografado = openssl_encrypt($cep, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$cnh_criptografada = openssl_encrypt($cnh, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$categoria_cnh_criptografada = openssl_encrypt($categoria_cnh, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$pais_criptografado = openssl_encrypt($pais, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$endereco_criptografado = openssl_encrypt($endereco, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$numero_criptografado = openssl_encrypt($numero, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$bairro_criptografado = openssl_encrypt($bairro, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$complemento_criptografado = openssl_encrypt($complemento, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$agencia_criptografada = openssl_encrypt($agencia, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
$pix_tipo_criptografado = openssl_encrypt($pix_tipo, 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));

$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

$sql = "INSERT INTO usuarios (
    nome, nascimento, banco, conta, digito, pix_chave, cpf, rg, nacionalidade, genero, estado_civil, 
    cep, cnh, categoria_cnh, pais, endereco, numero, bairro, complemento, agencia, pix_tipo, email, telefone, 
    usuario, senha_hash, status, valor_conta, valor_inicial, valor_atual, projecao_valor_final
) VALUES (
    '$nome_criptografado', '$nascimento_criptografado', '$banco_criptografado', '$conta_criptografada', 
    '$digito_criptografado', '$pix_chave_criptografada', '$cpf_criptografado', '$rg_criptografado', 
    '$nacionalidade_criptografada', '$genero_criptografado', '$estado_civil_criptografado', 
    '$cep_criptografado', '$cnh_criptografada', '$categoria_cnh_criptografada', '$pais_criptografado', 
    '$endereco_criptografado', '$numero_criptografado', '$bairro_criptografado', '$complemento_criptografado', 
    '$agencia_criptografada', '$pix_tipo_criptografado', '$email', '$telefone', '$usuario', '$senha_hash', 
    'Ativa', 0.00, 0.00, 0.00, 0.00
)";

if ($conn->query($sql) === TRUE) {
    $usuario_id = $conn->insert_id;

    // Obtém o mês atual em português
    setlocale(LC_TIME, 'pt_BR.utf8'); 
    $mes_atual = strftime('%B');

    // Atualiza o total_investido na tabela relatorio_cliente_grafico
    $update_sql = "UPDATE relatorio_cliente_grafico 
                   SET total_investido = '$total_investido' 
                   WHERE usuario_id = '$usuario_id' AND mes = '$mes_atual'";

    if (!$conn->query($update_sql)) {
        die("Erro no UPDATE em relatorio_cliente_grafico: " . $conn->error);
    }

    // Redireciona após a atualização bem-sucedida
    header('Location: ../inicioLogin/index.php');
    exit();
} else {
    die("Erro no INSERT em usuarios: " . $conn->error);
}

$conn->close();
?>
