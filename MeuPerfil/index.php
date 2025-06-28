<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Login/login.php');
    exit();
}

$usuario_logado = $_SESSION['usuario']; 
$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a';

$sql = "SELECT nome, nascimento, banco, conta, digito, pix_chave, perfil 
        FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_logado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $usuario_nome = openssl_decrypt($row['nome'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_nascimento = openssl_decrypt($row['nascimento'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_banco = openssl_decrypt($row['banco'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_conta = openssl_decrypt($row['conta'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_digito = openssl_decrypt($row['digito'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_pix = openssl_decrypt($row['pix_chave'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
    $usuario_perfil = $row['perfil'];
} else {
    echo "Erro ao recuperar os dados do usuário.";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Perfil - Prime Invest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color:rgb(0, 0, 0); 
    color: black; 
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #222;
}

.header-left a {
    color: white;
    text-decoration: none;
    margin-right: 15px;
}

.header-left a:hover {
    color: #ffbc00;
}

.header-title {
    font-weight: bold;
    color: white;
}

.header-right p {
    margin: 0;
    color: white;
}

@media (max-width: 768px) {
    header {
        flex-direction: column;
        text-align: center;
        padding: 10px;
    }

    .header-left {
        margin-bottom: 10px;
    }

    .header-left a {
        display: block;
        margin: 5px 0;
    }
}

.photo-section {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 60vh;
}

.video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    object-fit: cover;
}

.banner {
    width: 100%;
    min-height: 290px;
    background-color: #ffbc00;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
}

.banner-content h1 {
    color: black;
    font-size: 2rem;
    font-weight: bold;
}

@media (max-width: 768px) {
    .photo-section {
        height: 40vh;
    }

    .banner {
        padding: 15px;
        min-height: 200px;
    }

    .banner-content h1 {
        font-size: 1.5rem;
    }
}

.profile-action {
    text-align: center;
    margin-top: 30px;
}


.profile-button {
    background-color: #ffbc00;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease-in-out;
}

.profile-button:hover {
    background-color: #ff9900;
}

@media (max-width: 768px) {
    .profile-action {
        margin-top: 20px;
    }

    .profile-button {
        width: 100%; 
        padding: 12px;
    }
}

.container {
    color: white;
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: rgb(7, 7, 7);
    border-radius: 8px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
        width: 95%;
    }
}

.client-info {
    color: white;
    margin-top: 40px;
    text-align: center;
}

.client-info h2 {
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.info-box-container {
    background-color: rgb(12, 12, 12);
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: nowrap;
    padding: 20px;
}

.info-box {
    background-color: rgb(12, 12, 12);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 48%;
    text-align: left;
}

.info-box h2 {
    color: white;
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.info-box p {
    color: white;
    margin: 10px 0;
    font-size: 1rem;
}

.info-box strong {
    color: white;
}

.info-box ul {
    list-style-type: disc;
    padding-left: 20px;
}

.info-box ul li {
    margin: 5px 0;
}

@media (max-width: 768px) {
    .info-box-container {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }

    .info-box {
        width: 100%;
        text-align: center;
        padding: 15px;
    }

    .info-box h2 {
        font-size: 1.2rem;
    }

    .info-box p {
        font-size: 0.95rem;
    }

    .info-box ul {
        padding-left: 15px;
        text-align: left;
    }
}

.promo-section {
    padding: 40px;
    background-color: #070707;
    text-align: center;
}

.promo-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    justify-content: center;
}

.promo-box {
    padding: 20px;
    background-color: rgb(90, 90, 90);
    text-align: left;
    border-radius: 5px;
}

.promo-box h3 {
    color: rgb(0, 0, 0);
    text-align: center;
}

.promo-box ul {
    list-style: none;
    padding: 0;
}

.promo-box li {
    font-size: 14px;
    margin: 5px 0;
}

.promo-box button {
    display: block;
    width: 100%;
    background-color: #ffbc00;
    color: white;
    border: none;
    padding: 10px;
    font-size: 14px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
    transition: background-color 0.3s ease-in-out;
}

.promo-box button:hover {
    background-color: #ff9900;
}

@media (max-width: 1024px) {
    .promo-container {
        grid-template-columns: repeat(2, 1fr); 
    }
}

@media (max-width: 768px) {
    .promo-container {
        grid-template-columns: repeat(1, 1fr);
    }

    .promo-box {
        text-align: center;
        padding: 15px;
    }

    .promo-box h3 {
        font-size: 1.2rem;
    }

    .promo-box li {
        font-size: 0.95rem;
    }

    .promo-box button {
        padding: 12px;
        font-size: 1rem;
    }
}

footer {
    width: 100%;
    background-color: #040404;
    text-align: center;
    padding: 15px;
    color: white;
    margin-top: auto;
    font-size: 1rem;
}

@media (max-width: 768px) {
    footer {
        padding: 12px;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    footer {
        padding: 10px;
        font-size: 0.85rem;
    }
}

</style>
<header>
    <div class="header-left">
        <a href="../DashCliente/index.php">Area Cliente</a>
        <a href="../inicioLogin/index.php">Inicio</a>
    </div>
    <div class="header-title"></div>
    <div class="header-right">
        <p><?php echo htmlspecialchars($usuario_nome); ?></p>
    </div>
</header>

<section class="photo-section">
    <div class="image-background">
        <img src="./O SUCESSO DIANTE.png" alt="Banner" />
    </div>
</section>


<div class="container">
    <section class="profile-action">
      <a href="./questionario/index.php"><button class="profile-button">Questionário sobre o seu perfil de investidor</button></a>  
        <a href="../Relatorios/index.php"><button class="profile-button">Ir para Relatorios</button></a>
    </section>

    <section class="client-info">
        <h2>Informações do Cliente</h2>
        <div class="info-box-container">
            <div class="info-box">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario_nome); ?></p>
                <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($usuario_nascimento); ?></p>
                <p><strong>Banco:</strong> <?php echo htmlspecialchars($usuario_banco); ?></p>
                <p><strong>Conta:</strong> <?php echo htmlspecialchars($usuario_conta); ?></p>
                <p><strong>Dígito:</strong> <?php echo htmlspecialchars($usuario_digito); ?></p>
                <p><strong>PIX:</strong> <?php echo htmlspecialchars($usuario_pix); ?></p>
            </div>

            <div class="info-box">
    <h2>Perfil:  
        <?php 
        echo htmlspecialchars($usuario_perfil ?: 'Faça seu teste'); 
        ?>
    </h2>
    <p><strong>O que significa:</strong></p>

    <?php
    if ($usuario_perfil == 'Conservador') {
        echo '<ul>
                <li>Prefere a preservação do capital</li>
                <li>Tem baixa tolerância ao risco</li>
                <li>Busca estabilidade dos retornos</li>
                <li>Aceita rendimentos mais baixos em troca de maior segurança</li>
                <li>Evita ativos voláteis, como ações mais arriscadas</li>
                <li>Prioriza investimentos com notas de rating mais altas</li>
              </ul>';
    } elseif ($usuario_perfil == 'Arrojado') {
        echo '<ul>
                <li>Busca altos retornos: Está disposto a correr riscos para obter grandes lucros.</li>
                <li>Alta tolerância ao risco: Aceita flutuações no mercado e possíveis perdas.</li>
                <li>Investimentos em ativos voláteis: Prefere ativos com grande potencial de valorização, como ações e criptomoedas.</li>
                <li>Diversificação agressiva: Investe em setores de alto risco para maximizar o retorno.</li>
                <li>Prioriza o crescimento do patrimônio: Foca em aumentar seu capital, mesmo com riscos elevados.</li>
                <li>Aceita a possibilidade de perdas: Entende que perdas temporárias fazem parte da estratégia de alto risco.</li>
              </ul>';
    } elseif ($usuario_perfil == 'Moderado') {
        echo '<ul>
                <li>Busca equilíbrio entre risco e retorno: Quer um bom retorno, mas sem correr riscos extremos.</li>
                <li>Tolerância ao risco moderada: Aceita alguma volatilidade, mas evita grandes perdas.</li>
                <li>Investimentos diversificados: Combina ativos de risco mais baixo com opções de maior retorno.</li>
                <li>Prefere estabilidade com potencial de crescimento: Busca crescimento consistente sem expor-se a riscos elevados.</li>
                <li>Foca em segurança e rentabilidade: Quer investimentos seguros, mas com bom rendimento.</li>
                <li>Aceita perdas controladas: Entende que o risco é necessário, mas prefere perdas pequenas e controladas.</li>
              </ul>';
    } else {
        echo '<p>Faça seu teste para descobrir seu perfil de investidor.</p>';
    }
    ?>
</div>

    </section>

    <section class="content">
        <section class="promo-section fade-in-element">
            <h2>Promoções de Investimentos</h2>
            <div class="promo-container">
                <div class="promo-box fade-in-element">
                    <h3>Promoção Diamante</h3>
                    <ul>
                        <li>✅ Taxas reduzidas</li>
                        <li>✅ Cashback em investimentos</li>
                        <li>✅ Suporte VIP</li>
                        <li>✅ Acesso a fundos exclusivos</li>
                        <li>✅ Rentabilidade acima do mercado</li>
                        <li>✅ Consultoria personalizada</li>
                        <li>✅ Aplicação mínima reduzida</li>
                        <li>✅ Isenção de tarifas</li>
                        <li>✅ Resgate rápido</li>
                        <li>✅ Bônus em novos aportes</li>
                    </ul>
                    <button>Saiba Mais</button>
                </div>
                <div class="promo-box fade-in-element">
                    <h3>Promoção Ouro</h3>
                    <ul>
                        <li>✅ Taxas reduzidas</li>
                        <li>✅ Cashback em investimentos</li>
                        <li>✅ Suporte VIP</li>
                        <li>✅ Acesso a fundos exclusivos</li>
                        <li>✅ Rentabilidade acima do mercado</li>
                        <li>✅ Consultoria personalizada</li>
                        <li>❌ Aplicação mínima reduzida</li>
                        <li>❌ Isenção de tarifas</li>
                        <li>✅ Resgate rápido</li>
                        <li>✅ Bônus em novos aportes</li>
                    </ul>
                    <button>Saiba Mais</button>
                </div>
                <div class="promo-box fade-in-element">
                    <h3>Promoção Prata</h3>
                    <ul>
                        <li>✅ Taxas reduzidas</li>
                        <li>✅ Cashback em investimentos</li>
                        <li>✅ Suporte VIP</li>
                        <li>✅ Acesso a fundos exclusivos</li>
                        <li>✅ Rentabilidade acima do mercado</li>
                        <li>❌ Consultoria personalizada</li>
                        <li>❌ Aplicação mínima reduzida</li>
                        <li>✅ Isenção de tarifas</li>
                        <li>✅ Resgate rápido</li>
                        <li>✅ Bônus em novos aportes</li>
                    </ul>
                    <button>Saiba Mais</button>
                </div>
                <div class="promo-box fade-in-element">
                    <h3>Promoção Start</h3>
                    <ul>
                        <li>✅ Taxas reduzidas</li>
                        <li>✅ Cashback em investimentos</li>
                        <li>❌ Suporte VIP</li>
                        <li>❌ Acesso a fundos exclusivos</li>
                        <li>✅ Rentabilidade acima do mercado</li>
                        <li>❌ Consultoria personalizada</li>
                        <li>❌ Aplicação mínima reduzida</li>
                        <li>❌ Isenção de tarifas</li>
                        <li>✅ Resgate rápido</li>
                        <li>✅ Bônus em novos aportes</li>
                    </ul>
                    <button>Saiba Mais</button>
                </div>
            </div>
        </section>
    </section>
</div>
<br>
<br>
<br>
<br>
<br>
<footer>
    <p>&copy; 2025 Prime Invest. Todos os direitos reservados.</p>
</footer>

</body>
</html>
