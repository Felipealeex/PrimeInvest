<?php
session_start();
include("../conexao/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - XPrime Invest</title>
    <link rel="stylesheet" href="registro.css">
    <link rel="shortcut icon" type="image/png" href="../Dashboard-login/logo xprime PNG.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1>Cadastro de Cliente</h1>
            </div>
            <form id="cadastroForm" method="post" action="processa_registro.php" enctype="multipart/form-data">
            <div class="section">
        <h2>Dados pessoais</h2>
        <div class="input-group">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
        </div>
        <div class="input-group">
    <label for="nascimento">Data de nascimento</label>
    <input type="text" id="nascimento" name="nascimento" placeholder="dd/mm/aaaa" required maxlength="10" oninput="formatarNascimento(this)" onblur="verificarIdade(this)">
</div>
<p id="erroIdade" style="color: red; display: none;">Você deve ser maior de 18 anos para se cadastrar.</p>

<script>
    function formatarNascimento(input) {
        let value = input.value.replace(/\D/g, ''); 
        if (value.length <= 2) {
            value = value.replace(/(\d{2})/, '$1');
        } else if (value.length <= 4) {
            value = value.replace(/(\d{2})(\d{2})/, '$1/$2');
        } else {
            value = value.replace(/(\d{2})(\d{2})(\d{4})/, '$1/$2/$3');
        }
        input.value = value;
    }

    function verificarIdade(input) {
        const erroIdade = document.getElementById('erroIdade');
        const dataNascimento = input.value.replace(/\D/g, ''); 
        
        if (dataNascimento.length === 8) {
            const diaNascimento = parseInt(dataNascimento.substring(0, 2));
            const mesNascimento = parseInt(dataNascimento.substring(2, 4)) - 1; 
            const anoNascimento = parseInt(dataNascimento.substring(4, 8));

            const hoje = new Date();
            const dataNascimentoObj = new Date(anoNascimento, mesNascimento, diaNascimento);
            const idade = hoje.getFullYear() - dataNascimentoObj.getFullYear();
            const mesAtual = hoje.getMonth();
            const diaAtual = hoje.getDate();

            if (idade < 18 || (idade === 18 && (mesAtual < mesNascimento || (mesAtual === mesNascimento && diaAtual < diaNascimento)))) {
                erroIdade.style.display = 'inline'; 
            } else {
                erroIdade.style.display = 'none'; 
            }
        }
    }
</script>

        <div class="input-group">
            <label for="nacionalidade">Nacionalidade</label>
            <select id="nacionalidade" name="nacionalidade" required>
                <option value="">Selecione sua nacionalidade</option>
                <option value="Brasil">Brasil</option>
                <option value="Argentina">Argentina</option>
                <option value="Chile">Chile</option>
                <option value="Colômbia">Colômbia</option>
                <option value="México">México</option>
                <option value="Peru">Peru</option>
                <option value="Uruguai">Uruguai</option>
                <option value="Paraguai">Paraguai</option>
                <option value="Bolívia">Bolívia</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Estados Unidos">Estados Unidos</option>
                <option value="Canadá">Canadá</option>
                <option value="Reino Unido">Reino Unido</option>
                <option value="França">França</option>
                <option value="Alemanha">Alemanha</option>
                <option value="Itália">Itália</option>
                <option value="Espanha">Espanha</option>
                <option value="Portugal">Portugal</option>
                <option value="Austrália">Austrália</option>
                <option value="Nova Zelândia">Nova Zelândia</option>
                <option value="Japão">Japão</option>
                <option value="Coreia do Sul">Coreia do Sul</option>
                <option value="Suécia">Suécia</option>
                <option value="Noruega">Noruega</option>
                <option value="Finlândia">Finlândia</option>
                <option value="Dinamarca">Dinamarca</option>
                <option value="Suíça">Suíça</option>
                <option value="Holanda">Holanda</option>
                <option value="Bélgica">Bélgica</option>
            </select>
        </div>
        <div class="input-group">
            <label for="genero">Gênero</label>
            <select id="genero" name="genero" required>
                <option value="">Selecione seu gênero</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
            </select>
        </div>

        <div class="input-group">
    <label for="cpf">CPF</label>
    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required maxlength="14" oninput="formatCPF(this); validateCPF(this)">
    <small id="cpfWarning" style="color: red; display: none;">CPF inválido!</small>
</div>

<script>
    function formatCPF(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length <= 3) {
            value = value.replace(/(\d{3})/, '$1');
        } else if (value.length <= 6) {
            value = value.replace(/(\d{3})(\d{3})/, '$1.$2');
        } else if (value.length <= 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
        } else {
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1})/, '$1.$2.$3-$4');
        }
        input.value = value;
    }

    function validateCPF(input) {
        const cpf = input.value.replace(/\D/g, ''); 
        const cpfWarning = document.getElementById('cpfWarning');

        if (/^(\d)\1{10}$/.test(cpf)) {
            cpfWarning.style.display = 'inline';
            return false;
        }

        if (cpf.length !== 11) {
            cpfWarning.style.display = 'none';
            return false;
        }

        let sum = 0;
        let remainder;

        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
        remainder = sum % 11;
        if (remainder < 2) {
            remainder = 0;
        } else {
            remainder = 11 - remainder;
        }
        if (remainder !== parseInt(cpf.charAt(9))) {
            cpfWarning.style.display = 'inline';
            return false;
        }

        sum = 0;

        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
        remainder = sum % 11;
        if (remainder < 2) {
            remainder = 0;
        } else {
            remainder = 11 - remainder;
        }
        if (remainder !== parseInt(cpf.charAt(10))) {
            cpfWarning.style.display = 'inline';
            return false;
        }

        cpfWarning.style.display = 'none';
        return true;
    }
</script>


<div class="input-group">
    <label for="rg">RG</label>
    <input type="text" id="rg" name="rg" placeholder="00.000.000-0" required maxlength="12" oninput="formatRG(this); validateRG(this)">
    <small id="rgWarning" style="color: red; display: none;">RG inválido!</small>
</div>

<script>
    function formatRG(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length <= 2) {
            value = value.replace(/(\d{2})/, '$1');
        } else if (value.length <= 5) {
            value = value.replace(/(\d{2})(\d{3})/, '$1.$2');
        } else if (value.length <= 8) {
            value = value.replace(/(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
        } else {
            value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{1})/, '$1.$2.$3-$4');
        }
        input.value = value;
    }

    function validateRG(input) {
        const rg = input.value.replace(/\D/g, ''); 
        const rgWarning = document.getElementById('rgWarning');

        if (rg.length !== 9) {
            rgWarning.style.display = 'none';
            return false;
        }

        const rgRegex = /^[0-9]{2}\.[0-9]{3}\.[0-9]{3}-[0-9]{1}$/;

        if (rgRegex.test(input.value)) {
            rgWarning.style.display = 'none'; 
            return true;
        } else {
            rgWarning.style.display = 'inline';
            return false;
        }
    }
</script>

        <div class="input-group">
            <label for="estado-civil">Estado civil</label>
            <select id="estado-civil" name="estado_civil" required>
                <option value="">Selecione seu estado civil</option>
                <option value="Solteiro">Solteiro</option>
                <option value="Casado">Casado</option>
                <option value="Separado">Separado</option>
                <option value="Divorciado">Divorciado</option>
                <option value="Viúvo">Viúvo(a)</option>
            </select>
        </div>
    </div>

    <div class="section">
        <h2>Endereço</h2>
        <div class="input-group">
    <label for="cep">CEP</label>
    <input type="text" id="cep" name="cep" placeholder="XXXXX-XXX" required maxlength="10" oninput="formatCEP(this)" onblur="fetchAddress(this)">
</div>
<div class="input-group">
    <label for="pais">País</label>
    <input type="text" id="pais" name="pais" placeholder="Digite seu país" required>
</div>
<div class="input-group">
    <label for="endereco">Endereço</label>
    <input type="text" id="endereco" name="endereco" placeholder="Av. Nome da Rua" required>
</div>
<div class="input-group">
    <label for="numero">Número</label>
    <input type="text" id="numero" name="numero" placeholder="Número" required>
</div>
<div class="input-group">
    <label for="bairro">Bairro</label>
    <input type="text" id="bairro" name="bairro" placeholder="Digite seu bairro" required>
</div>

<script>
    function formatCEP(input) {
        let value = input.value.replace(/\D/g, ''); 
        if (value.length <= 5) {
            value = value.replace(/(\d{5})/, '$1');
        } else {
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
        }
        input.value = value;
    }

    function fetchAddress(input) {
        const cep = input.value.replace(/\D/g, ''); 
        if (cep.length === 8) { 
            const url = `https://viacep.com.br/ws/${cep}/json/`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        alert("CEP não encontrado!");
                    } else {
                        document.getElementById('pais').value = "Brasil"; 
                        document.getElementById('endereco').value = data.logradouro || "";
                        document.getElementById('bairro').value = data.bairro || "";
                        document.getElementById('numero').focus(); 
                    }
                })
                .catch(() => {
                    alert("Erro ao buscar o CEP!");
                });
        }
    }
</script>

        <div class="input-group">
            <label for="complemento">Complemento</label>
            <input type="text" id="complemento" name="complemento" placeholder="Opcional">
        </div>
    </div>

    <div class="section">
                <h2>Dados bancários</h2>
                <div class="input-group">
            <label for="banco">Banco</label>
            <select id="banco" name="banco" required>
                <option value="">Selecione um banco</option>
                <option value="Nubank">Nubank</option>
                <option value="Itau">Itaú</option>
                <option value="Banco Inter">Banco Inter</option>
                <option value="MercadoPago">Mercado pago</option>
                <option value="Banco Original">Banco Original</option>
                <option value="C6 Bank">C6 Bank</option>
                <option value="Banco Pan">Banco Pan</option>
                <option value="Santander">Santander</option>
                <option value="Bradesco">Bradesco</option>
                <option value="Banco do Brasil">Banco do Brasil</option>
            </select>
        </div>

        <div class="input-group">
            <label for="agencia">Agência</label>
            <input type="text" id="agencia" name="agencia" placeholder="0000" required>
        </div>
        <div class="input-group">
            <label for="conta">Conta (sem dígito)</label>
            <input type="text" id="conta" name="conta" placeholder="000000" required>
        </div>
        <div class="input-group">
            <label for="digito">Dígito</label>
            <input type="text" id="digito" name="digito" placeholder="00" required>
        </div>
        <div class="input-group">
            <label for="digito">Número CNH</label>
            <input type="text" id="cnh" name="cnh" maxlength="11" placeholder="00000000000" required>
        </div>
        <div class="input-group">
            <label for="digito">Categoria da habilitação</label>
            <input type="text" id="categoria_cnh" name="categoria_cnh" placeholder="A,B,C,D,E" required>
        </div>
         <div class="input-group">
            <label for="digito">Total Investido (Nesse mês)</label>
            <input type="number" id="total_investido" name="total_investido" step="0.01" lang="en" placeholder="Digite o valor" required>
        </div>
       
    </div>

    <div class="section">
        <h2>Pix</h2>
        <div class="input-group">
            <label for="pixTipo">Tipo de chave Pix</label>
            <select id="pixTipo" name="pix_tipo" required>
                <option value="">Selecione o tipo de chave</option>
                <option value="cpf">CPF</option>
                <option value="cnpj">CNPJ</option>
                <option value="email">E-MAIL</option>
                <option value="telefone">Número de telefone</option>
            </select>
        </div>
        <div class="input-group">
            <label for="pixChave">Chave Pix</label>
            <input type="text" id="pixChave" name="pix_chave" placeholder="Digite sua chave" required>
        </div>
    </div>

    <div class="section">
        <h2>Contato</h2>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="exemplo@dominio.com" required>
        </div>
        <div class="input-group">
    <label for="telefone">Telefone</label>
    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" required maxlength="15" oninput="formatTelefone(this)">
</div>

<script>
    function formatTelefone(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length <= 2) {
            value = value.replace(/(\d{2})/, '($1');
        } else if (value.length <= 6) {
            value = value.replace(/(\d{2})(\d{5})/, '($1) $2');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        input.value = value;
    }
</script>

    </div>

    <div class="section">
        <h2>Dados para login</h2>
        <div class="input-group">
            <label for="usuario">Usuário</label>
            <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
        </div>
        <div class="input-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
        </div>
    </div>
    <p style="color: white; font-weight: bold; text-align: center;">
    Ao criar sua conta você leu e concorda com os 
    <a href="../termos/index.php" style="color: #d4af37; text-decoration: none;"><Strong>termos de uso.</Strong></a>
</p>


    <input class="btn-cadastrar" type="submit" value="Registrar">

</form>

</body>
</html>
