<?php
session_start();
include("../../conexao/conexao.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Dashboard-login/index.php');
    exit();
}

$usuario_logado = $_SESSION['usuario']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function getPostValue($key) {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    $pergunta1 = getPostValue('pergunta1');
    $pergunta2 = getPostValue('pergunta2');
    $pergunta3 = getPostValue('pergunta3');
    $pergunta4 = getPostValue('pergunta4');
    $pergunta5 = getPostValue('pergunta5');
    $pergunta6 = getPostValue('pergunta6');
    $pergunta7_fundos = getPostValue('pergunta7_fundos');
    $pergunta7_acoes = getPostValue('pergunta7_acoes');
    $pergunta7_titulos = getPostValue('pergunta7_titulos');
    $pergunta7_previdencia = getPostValue('pergunta7_previdencia');
    $pergunta7_derivativos = getPostValue('pergunta7_derivativos');
    $pergunta8 = getPostValue('pergunta8');
    $pergunta9 = getPostValue('pergunta9');
    $pergunta10 = getPostValue('pergunta10');
    $pergunta11 = getPostValue('pergunta11');
    $pergunta12 = getPostValue('pergunta12');
    $pergunta13 = getPostValue('pergunta13');
    $pergunta14 = getPostValue('pergunta14');
    $pergunta15 = getPostValue('pergunta15');

    $scoreConservador = 0;
    $scoreArrojado = 0;
    $scoreModerado = 0;

    $respostasConservadoras = ["Preservar meu capital", "Fico muito preocupado", "Baixo", "Menor risco"];
    $respostasArrojadas = ["Buscar alto retorno", "Estou disposto a correr grandes riscos para grandes retornos", "Alto", "Maior risco"];

    foreach ([$pergunta1, $pergunta2, $pergunta3, $pergunta4] as $resposta) {
        if (in_array($resposta, $respostasConservadoras)) {
            $scoreConservador += 3;
        } elseif (in_array($resposta, $respostasArrojadas)) {
            $scoreArrojado += 3;
        } else {
            $scoreModerado += 2;
        }
    }

    $investimentos = [
        'fundos' => $pergunta7_fundos,
        'acoes' => $pergunta7_acoes,
        'titulos' => $pergunta7_titulos,
        'previdencia' => $pergunta7_previdencia,
        'derivativos' => $pergunta7_derivativos
    ];

    foreach ($investimentos as $tipo => $valor) {
        if ($valor !== null) {
            if ($tipo === 'acoes' || $tipo === 'derivativos') {
                $scoreArrojado += ($valor >= 60) ? 3 : 2;
            } elseif ($tipo === 'fundos' || $tipo === 'titulos') {
                $scoreConservador += ($valor >= 60) ? 3 : 2;
            } elseif ($tipo === 'previdencia') {
                $scoreModerado += ($valor >= 60) ? 3 : 2;
            }
        }
    }

    if ($scoreArrojado > $scoreConservador && $scoreArrojado > $scoreModerado) {
        $perfil = "Arrojado";
    } elseif ($scoreConservador > $scoreArrojado && $scoreConservador > $scoreModerado) {
        $perfil = "Conservador";
    } else {
        $perfil = "Moderado";
    }

    $sql = "UPDATE usuarios SET 
                pergunta1 = ?, 
                pergunta2 = ?, 
                pergunta3 = ?, 
                pergunta4 = ?, 
                pergunta5 = ?, 
                pergunta6 = ?, 
                pergunta7_fundos = ?, 
                pergunta7_acoes = ?, 
                pergunta7_titulos = ?, 
                pergunta7_previdencia = ?, 
                pergunta7_derivativos = ?, 
                pergunta8 = ?, 
                pergunta9 = ?, 
                pergunta10 = ?, 
                pergunta11 = ?, 
                pergunta12 = ?, 
                pergunta13 = ?, 
                pergunta14 = ?, 
                pergunta15 = ?, 
                perfil = ? 
            WHERE usuario = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssss", 
        $pergunta1, 
        $pergunta2, 
        $pergunta3, 
        $pergunta4, 
        $pergunta5, 
        $pergunta6, 
        $pergunta7_fundos, 
        $pergunta7_acoes, 
        $pergunta7_titulos, 
        $pergunta7_previdencia, 
        $pergunta7_derivativos, 
        $pergunta8, 
        $pergunta9, 
        $pergunta10, 
        $pergunta11, 
        $pergunta12, 
        $pergunta13, 
        $pergunta14, 
        $pergunta15, 
        $perfil, 
        $usuario_logado
    );

    if ($stmt->execute()) {
        header('Location: ../index.php');
        exit();
    } else {
        echo "Erro ao salvar as respostas: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionário de Perfil - Prime Invest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #333;
            color: white;
        }
        header {
            background-color: #000;
            color: #FFD700; 
            padding: 10px 20px;
            text-align: center;
        }

        header .header-left a,
        header .header-right p {
            color: #FFF;
            text-decoration: none;
            padding: 5px;
        }

        header .header-right {
            position: relative;
        }

        form {
            width: 80%;
            margin: 20px auto;
            background-color: #444;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .pergunta {
            margin-bottom: 15px;
        }
        .pergunta p {
            font-size: 16px;
            margin-bottom: 5px;
            color: #FFD700;
        }
        .pergunta input {
            margin-right: 10px;
        }
        button {
            background-color: #FFD700;
            color: #333;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #cc9a00;
        }
    </style>
</head>
<body>


<header>
    <h1>Questionário de Perfil de Investidor</h1>
    <p>Olá, <?php echo htmlspecialchars($usuario_logado); ?>! Por favor, preencha o questionário abaixo.</p>
    <div class="header-left">
            <a href="../index.php">Voltar</a>
        </div>
</header>

<form action="index.php" method="POST">
    <div class="pergunta">
        <p><strong>1. Qual o seu objetivo com os investimentos?</strong></p>
        <input type="radio" name="pergunta1" value="Preservar meu capital" required> Preservar meu capital<br>
        <input type="radio" name="pergunta1" value="Buscar alto retorno"> Buscar alto retorno<br>
        <input type="radio" name="pergunta1" value="Equilibrar risco e retorno"> Equilibrar risco e retorno<br>
    </div>

    <div class="pergunta">
        <p><strong>2. Como você se sente diante de uma possível perda financeira?</strong></p>
        <input type="radio" name="pergunta2" value="Fico muito preocupado" required> Fico muito preocupado<br>
        <input type="radio" name="pergunta2" value="Não gosto de perdas, mas aceito riscos calculados"> Não gosto de perdas, mas aceito riscos calculados<br>
        <input type="radio" name="pergunta2" value="Estou disposto a correr grandes riscos para grandes retornos"> Estou disposto a correr grandes riscos para grandes retornos<br>
    </div>

    <div class="pergunta">
        <p><strong>3. Qual o seu nível de conhecimento sobre o mercado financeiro?</strong></p>
        <input type="radio" name="pergunta3" value="Baixo" required> Baixo<br>
        <input type="radio" name="pergunta3" value="Moderado"> Moderado<br>
        <input type="radio" name="pergunta3" value="Alto"> Alto<br>
    </div>

    <div class="pergunta">
        <p><strong>4. Você prefere investimentos com maior ou menor risco?</strong></p>
        <input type="radio" name="pergunta4" value="Menor risco" required> Menor risco<br>
        <input type="radio" name="pergunta4" value="Maior risco"> Maior risco<br>
        <input type="radio" name="pergunta4" value="Risco controlado"> Risco controlado<br>
    </div>

    <div class="pergunta">
        <p><strong>5. Você já investe em ações, fundos ou outros ativos de risco?</strong></p>
        <input type="radio" name="pergunta5" value="Sim" required> Sim<br>
        <input type="radio" name="pergunta5" value="Não"> Não<br>
    </div>

    <div class="pergunta">
        <p><strong>6. Você já teve alguma experiência negativa com investimentos?</strong></p>
        <input type="radio" name="pergunta6" value="Sim" required> Sim<br>
        <input type="radio" name="pergunta6" value="Não"> Não<br>
    </div>

    <div class="pergunta">
        <p><strong>7. Quanto você destina dos seus recursos para investimentos em fundos?</strong></p>
        <input type="number" name="pergunta7_fundos" required min="0" max="100" step="0.01"> % dos seus recursos<br>
    </div>

    <!-- Pergunta sobre ações -->
    <div class="pergunta">
        <p><strong>8. Quanto você destina dos seus recursos para investimentos em ações?</strong></p>
        <input type="number" name="pergunta7_acoes" required min="0" max="100" step="0.01"> % dos seus recursos<br>
    </div>

    <!-- Pergunta sobre títulos -->
    <div class="pergunta">
        <p><strong>9. Quanto você destina dos seus recursos para investimentos em títulos (ex: Tesouro Direto)?</strong></p>
        <input type="number" name="pergunta7_titulos" required min="0" max="100" step="0.01"> % dos seus recursos<br>
    </div>

    <!-- Pergunta sobre previdência -->
    <div class="pergunta">
        <p><strong>10. Quanto você destina dos seus recursos para investimentos em previdência?</strong></p>
        <input type="number" name="pergunta7_previdencia" required min="0" max="100" step="0.01"> % dos seus recursos<br>
    </div>

    <!-- Pergunta sobre derivativos -->
    <div class="pergunta">
        <p><strong>11. Quanto você destina dos seus recursos para investimentos em derivativos (ex: opções, futuros)?</strong></p>
        <input type="number" name="pergunta7_derivativos" required min="0" max="100" step="0.01"> % dos seus recursos<br>
    </div>

    <div class="pergunta">
        <p><strong>12. Você investiria em criptomoedas?</strong></p>
        <input type="radio" name="pergunta12" value="Sim" required> Sim<br>
        <input type="radio" name="pergunta12" value="Não"> Não<br>
    </div>

    <div class="pergunta">
        <p><strong>13. Qual é o seu nível de interesse por investimentos alternativos (ex: imóveis, arte)?</strong></p>
        <input type="radio" name="pergunta13" value="Baixo" required> Baixo<br>
        <input type="radio" name="pergunta13" value="Moderado"> Moderado<br>
        <input type="radio" name="pergunta13" value="Alto"> Alto<br>
    </div>

    <div class="pergunta">
        <p><strong>14. Quanto tempo você planeja deixar o seu dinheiro investido?</strong></p>
        <input type="radio" name="pergunta14" value="Curto prazo (menos de 1 ano)" required> Curto prazo (menos de 1 ano)<br>
        <input type="radio" name="pergunta14" value="Médio prazo (1 a 5 anos)"> Médio prazo (1 a 5 anos)<br>
        <input type="radio" name="pergunta14" value="Longo prazo (mais de 5 anos)"> Longo prazo (mais de 5 anos)<br>
    </div>

    <div class="pergunta">
        <p><strong>15. Você tem algum objetivo específico para seus investimentos (ex: aposentadoria, compra de imóvel)?</strong></p>
        <input type="radio" name="pergunta15" value="Sim" required> Sim<br>
        <input type="radio" name="pergunta15" value="Não"> Não<br>
    </div>

    <button type="submit">Enviar Respostas</button>
    
</form>

</body>
</html>
