<?php
session_start();
include("../conexao/conexao.php");
include("../conexao/criptografia.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Dashboard-login/index.php');
    exit();
}

$usuario_logado = $_SESSION['usuario'];

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a';

$sql = "SELECT nome FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_logado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuario_nome = openssl_decrypt($row['nome'], 'aes-256-cbc', $chaveAES, 0, str_repeat('0', 16));
} else {
    echo "Erro ao recuperar os dados do usuário.";
    exit();
}

setlocale(LC_TIME, 'pt_BR.UTF-8', 'Portuguese_Brazil', 'ptb');
$mes_atual = strftime('%B'); 
$mes_atual = ucfirst($mes_atual); 

$sql = "SELECT saldo_disponivel, total_investido, saldo_projetado, rentabilidade_CDI, ultima_atualizacao 
        FROM relatorio_cliente_grafico 
        WHERE usuario_id = (SELECT id FROM usuarios WHERE usuario = ?) AND LOWER(mes) = LOWER(?) LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario_logado, $mes_atual);
$stmt->execute();
$result = $stmt->get_result();

$saldo_disponivel = 0;
$total_investido = 0;
$saldo_projetado = 0;
$rentabilidade_CDI = 0;
$ultima_atualizacao = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $saldo_disponivel = $row['saldo_disponivel'];
    $total_investido = $row['total_investido'];
    $saldo_projetado = $row['saldo_projetado'];
    $rentabilidade_CDI = $row['rentabilidade_CDI'];
    $ultima_atualizacao = $row['ultima_atualizacao'];
} else {
    echo "Nenhum dado encontrado para o mês atual: " . $mes_atual;
}

$ordem_meses = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
];

$sql = "SELECT mes, rentabilidade_CDI, total_investido, saldo_projetado 
        FROM relatorio_cliente_grafico 
        WHERE usuario_id = (SELECT id FROM usuarios WHERE usuario = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_logado);
$stmt->execute();
$result = $stmt->get_result();

$dados_meses = [];

while ($row = $result->fetch_assoc()) {
    $dados_meses[strtolower($row['mes'])] = [
        "rentabilidade_CDI" => (float) $row['rentabilidade_CDI'],
        "total_investido" => (float) $row['total_investido'],
        "saldo_projetado" => (float) $row['saldo_projetado']
    ];
}

$labels = [];
$rentabilidade_cdi_array = [];
$total_investido_array = [];
$saldo_projetado_array = [];

foreach ($ordem_meses as $mes) {
    if (isset($dados_meses[$mes])) {
        $labels[] = ucfirst($mes);
        $rentabilidade_cdi_array[] = $dados_meses[$mes]["rentabilidade_CDI"];
        $total_investido_array[] = $dados_meses[$mes]["total_investido"];
        $saldo_projetado_array[] = $dados_meses[$mes]["saldo_projetado"];
    } else {
        $labels[] = ucfirst($mes);
        $rentabilidade_cdi_array[] = 0;
        $total_investido_array[] = 0;
        $saldo_projetado_array[] = 0;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Relatorios | PrimeInvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



</head>
<body class="bg-white text-black">
    <header class="bg-black text-white py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold">PrimeInvest</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="../DashCliente/index.php" class="hover:text-yellow-500 transition">Area Cliente</a></li>
                    <li><a href="../solicitarSaque/index.php" class="hover:text-yellow-500 transition">Solicitar Saque</a></li>
                    <li><a href="../historicoSaque/index.php" class="hover:text-yellow-500 transition">Hitorico de Saques</a></li>
                </ul>
            </nav>
        </div>
    </header>
   <section class="bg-gray-900 text-white py-36" style="background: url('rendimentos.png') center/cover no-repeat;">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 class="text-4xl font-bold"></h2>
                <p class="mt-4 text-gray-400"></p>
            </div>
            <a href="../MeuPerfil/index.php" class="mt-6 md:mt-0 bg-yellow-500 text-black font-bold px-6 py-3 rounded hover:bg-yellow-400 transition self-end">
                Meus Dados
            </a>
        </div>
    </section>

    
    
    <section class="max-w-5xl mx-auto p-6">
        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
            <p class="font-bold text-black">Sua posição padrão está a mercado</p>
            <p class="text-gray-600 text-sm mt-1">
                Posição a mercado é uma estimativa de quanto os ativos valeriam caso fossem resgatados antes do vencimento. 
                Válido para Títulos Públicos, Debêntures, CRIs e CRAs. Os outros ativos são precificados na taxa de compra.
 </p>
        </div>
        <div class="flex items-center space-x-6 mt-4">
        
          
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 border-4 border-gray-300 rounded-full flex items-center justify-center shadow-md overflow-hidden">
                    <img src="./LOGO PRIME INVEST.png" alt="Logo PrimeInvest" class="w-full h-full object-contain">
                </div>
                <strong><p class="mt-2 text-gray-600 text-sm">Invista com Segurança</p></strong>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($usuario_nome);?> este é o seu patrimônio</p>
                <p class="text-2xl font-bold text-red-600"><?php echo number_format($saldo_disponivel, 2, ',', '.'); ?></p>
                <p class="text-xs text-gray-500 mt-1">Atualizado em  <?php echo date('d/m/Y H:i:s', strtotime($ultima_atualizacao)); ?></p>
                
                <div class="grid grid-cols-3 gap-6 mt-4 text-sm">
                    <div>
                        <p class="text-gray-500">Saldo disponível</p>
                        <p class="text-red-600 font-bold"><?php echo number_format($saldo_disponivel, 2, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total investido</p>
                        <p class="text-black font-bold">R$ <?php echo number_format($total_investido, 2, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Saldo projetado</p>
                        <p class="text-red-600 font-bold">R$ <?php echo number_format($saldo_projetado, 2, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Rentabilidade CDI</p>
                        <p class="text-red-600 font-bold">    <?php echo number_format($rentabilidade_CDI, 2, ',', '.'); ?>%</p>
                    </div>
                 
                </div>
        </div>
        

        <div class="mt-6 flex justify-end">
            <div class="carousel-container shadow-lg">
                <div id="carousel-track" class="carousel-track">
                    <img src="./CARROSEL 1.jpg" alt="Imagem 1">
                    <img src="./CARROSEL 2.jpg" alt="Imagem 2">
                    <img src="./CARROSEL 3.jpg" alt="Imagem 3">
                </div>
                
            </div>
        </div>
    </section>
    <br>
    <br>
    <section class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between text-center border-b pb-4">
            <div>
            </div>
            <div>
            </div>
            <div>
            </div>
        </div>
        <div class="flex justify-between items-center mt-6">
            <h2 class="text-xl font-semibold flex items-center">
                <span class="mr-2">📊</span> Seu patrimônio
            </h2>
            <div class="flex space-x-4">
            
            </div>
        </div>
        <div class="mt-4">
            <canvas id="graficoPatrimonio"></canvas>
        </div>
    </section> 
    <br>
    <br>
  
    <br>
    <br>
    <br>
    <br>
    <footer>
        <p>© 2025 PrimeInvest. Todos os direitos reservados.</p>
            </div>
    </footer>
    <style>
        #graficoPatrimonio {
            width: 100% !important;
            height: 300px !important; 
        }
    </style>
    
    <script>
    let index = 0;
    const totalImages = document.querySelectorAll("#carousel-track img").length;
    const track = document.getElementById("carousel-track");
    
    function changeImage() {
        index = (index + 1) % totalImages;
        track.style.transform = `translateX(-${index * 100}%)`;
    }
    
    setInterval(changeImage, 3000);
    
    const ctx = document.getElementById('graficoPatrimonio').getContext('2d');
    
    // ✅ Pegando os dados reais do PHP
    const labels = <?php echo json_encode($labels); ?>;
    const rentabilidadeCDI = <?php echo json_encode($rentabilidade_cdi_array); ?>;
    const totalInvestido = <?php echo json_encode($total_investido_array); ?>;
    const saldoProjetado = <?php echo json_encode($saldo_projetado_array); ?>;
    
    // ✅ Encontrar o maior e o menor valor para definir o eixo Y dinamicamente
    const allValues = [...rentabilidadeCDI, ...totalInvestido, ...saldoProjetado];
    const minValue = Math.min(...allValues);
    const maxValue = Math.max(...allValues);
    
    // ✅ Definir um limite mínimo de -100% e máximo de 100%, mas ajustável ao usuário
    const dynamicMin = minValue < -100 ? minValue * 1.2 : -100; // Se menor que -100, expande
    const dynamicMax = maxValue > 100 ? maxValue * 1.2 : 100; // Se maior que 100, expande
    
    const data = {
        labels: labels,
        datasets: [
            {
                label: "Rentabilidade CDI",
                data: rentabilidadeCDI,
                borderColor: "green",
                backgroundColor: "transparent",
                borderWidth: 2,
                pointRadius: 3,
            },
            {
                label: "Total Investido",
                data: totalInvestido,
                borderColor: "orange",
                backgroundColor: "transparent",
                borderWidth: 2,
                pointRadius: 3,
            },
            {
                label: "Saldo Projetado",
                data: saldoProjetado,
                borderColor: "blue",
                backgroundColor: "transparent",
                borderWidth: 2,
                pointRadius: 3,
            }
        ]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    font: { size: 14 }
                }
            }
        },
        scales: {
            x: { grid: { display: true } },
            y: {
                grid: { display: true },
                min: dynamicMin, // ✅ Agora o eixo Y é dinâmico conforme os valores do usuário
                max: dynamicMax,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString("pt-BR") + "%"; // ✅ Formata corretamente com "%"
                    }
                }
            }
        }
    };
    
    new Chart(ctx, {
        type: "line",
        data: data,
        options: options
    });
    </script>
</body>
</html>


