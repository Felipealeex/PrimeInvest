<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Renda Passiva - XPrimeInvest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="nav-left">
                <span class="logo-header">
                    <span class="logo-xprime">Prime</span><span class="logo-invest">Invest</span>
                </span>
                <a href="../../index.php" class="nav-link">Página Inicial</a>
                <a href="../../quemSomos/index.php" class="nav-link">Quem somos nós?</a>
            </div>
            <div class="nav-right">
                <button class="btn-yellow"><a href="../registro/registro.php">Abra sua conta</a></button>
                <button class="btn-transparent"><a href="../Login/login.php">Acesse sua conta</a></button>
            </div>
        </nav>
    </header>
    <section class="banner-section">
        <div class="banner-content">
            <img src="./CALCULE SEU PATRIMONIO.png" alt="Banner">
        </div>
    </section>
    
    <div class="logo-section">
        <strong><span class="company-text">   | Um investimento com segurança e rentabilidade</span></strong>
    </div>
    <main class="main-container">
        <div class="container">
            <h2>Calculadora de Renda Passiva</h2>
            <p>Preencha os campos abaixo para realizar o cálculo e gerar seu resultado personalizado:</p>
            <form class="form" id="calculator-form" method="POST">
                <div class="form-group">
                    <label>Eu gostaria de receber</label>
                    <input type="number" name="renda_desejada" class="input" placeholder="R$0" step="0.01" required>
                    <span>de renda passiva por mês.</span>
                </div>
                <div class="form-group">
                    <label>Hoje eu possuo</label>
                    <input type="number" name="investimento_atual" class="input" placeholder="R$0" step="0.01" required>
                    <span>investidos e pretendo investir</span>
                    <input type="number" name="aporte_mensal" class="input" placeholder="R$0" step="0.01" required>
                    <span>por mês.</span>
                </div>
                <div class="form-group">
                    <label>Ao longo dos próximos</label>
                    <input type="number" name="anos" class="input" placeholder="10" required>
                    <span>anos.</span>
                </div>
                <button type="submit" class="btn" id="calculate-button">Calcular</button>
            </form>
        </div>
    </main>
    <div id="progress-container" class="progress-container" style="display: none;">
        <div id="progress-bar" class="progress-bar"></div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $renda_desejada = floatval($_POST['renda_desejada']);
        $investimento_atual = floatval($_POST['investimento_atual']);
        $aporte_mensal = floatval($_POST['aporte_mensal']);
        $anos = intval($_POST['anos']);

        $taxa_juros = 0.0065; 
        $meses = $anos * 12;

        $patrimonio_necessario = $renda_desejada / $taxa_juros;

        $patrimonio_projetado = $investimento_atual * pow(1 + $taxa_juros, $meses);
        for ($i = 0; $i < $meses; $i++) {
            $patrimonio_projetado += $aporte_mensal * pow(1 + $taxa_juros, $meses - $i);
        }

        $renda_atingida = $patrimonio_projetado * $taxa_juros;

        $atinge_renda_desejada = $patrimonio_projetado >= $patrimonio_necessario;
    ?>
        <section class="result-section" id="result-section">
            <h2>Detalhes do cálculo</h2>
            <div class="result-container">
                <div class="left-section">
                    <h3>Seus dados</h3>
                    <p><strong>Patrimônio investido hoje:</strong> R$ <?= number_format($investimento_atual, 2, ',', '.') ?></p>
                    <p><strong>Aportes mensais:</strong> R$ <?= number_format($aporte_mensal, 2, ',', '.') ?></p>
                    <p><strong>Período de tempo:</strong> <?= $anos ?> anos</p>
                    <p><strong>Renda passiva desejada:</strong> R$ <?= number_format($renda_desejada, 2, ',', '.') ?></p>
                </div>
                <div class="right-section">
                    <h3>Seu resultado</h3>
                    <p>Nos próximos <strong><?= $anos ?> anos</strong>, você terá um patrimônio projetado de <strong>R$ <?= number_format($patrimonio_projetado, 2, ',', '.') ?></strong> capaz de gerar uma renda passiva mensal de <strong>R$ <?= number_format($renda_atingida, 2, ',', '.') ?></strong>.</p>
                    <div class="patrimonio-box">
                        <p>
                            <?php if ($atinge_renda_desejada): ?>
                                <strong class="success">Com seu patrimônio atual, você já é capaz de atingir sua renda passiva desejada.</strong>
                            <?php else: ?>
                                <strong class="error">Você precisará aumentar seus aportes ou o prazo para atingir sua meta.</strong>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="cta-section">
                <h3 id="foott">Abra sua conta na PrimeInvest e invista com confiança</h3>
                <p id="foott">Investidores com mais de R$ 100 mil na PrimeInvest têm acesso a um assessor financeiro dedicado. Tudo de forma gratuita.</p>
                <button class="cta-button"><a href="../registro/registro.php">Abrir sua conta agora</a></button>
            </div>
        </section>
    <?php } ?>

    <footer class="footer">
        <p id="foott">© 2025 PrimeInvest. Todos os direitos reservados.</p>
    </footer>
    <script>
        document.getElementById("calculate-button").addEventListener("click", function () {
    const progressBar = document.getElementById("progress-bar");
    const progressContainer = document.getElementById("progress-container");
    const resultSection = document.getElementById("result-section");

    progressBar.style.width = "0%";
    progressContainer.style.display = "block";

    let progress = 0;
    const interval = setInterval(() => {
        progress += 5;
        progressBar.style.width = progress + "%";

        if (progress >= 100) {
            clearInterval(interval);
            progressContainer.style.display = "none";
            resultSection.style.display = "block";
        }
    }, 100);
});

    </script>
</body>
</html>
