<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>PrimeInvest | Investimentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
</head>
<body>
    <style>
    header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: black;
        }

        .right-section button {
            margin: 5px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }
            .right-section {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .whatsapp-float {
                width: 50px;
                height: 50px;
            }
        }
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        .whatsapp-float img {
            width: 35px;
            height: 35px;
        }
    </style>
    <header>
        <div class="left-section">
            <a href="./quemSomos/index.php">Quem Somos nós</a>
        </div>
        <div class="right-section">
            <a href="./registro/registro.php"><button class="normal">Abra Sua Conta</button></a>
          <a href="./Login/login.php">  <button class="transparent">Acesse Sua Conta</button></a>
        </div>
    </header>
    <section class="relative w-full h-[500px] md:h-[600px] flex items-center">
        <div class="absolute inset-0">
            <img src="./FUNDO PAGINA INICIA.png" alt="Vem Ser XP" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black opacity-30"></div>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-center w-full px-6 md:px-20 justify-between">
            <div class="max-w-lg text-left">
                <h2 class="text-4xl md:text-5xl font-light italic text-yellow-400">PrimeInvest</h2>
                <p class="mt-4 text-lg md:text-xl text-white">
                    Mude de vida  com uma das <span class="font-bold"> melhores assessoria de investimentos</span>.
                </p>
                <a href="./registro/registro.php"><button class="mt-6 bg-yellow-500 text-black font-bold py-3 px-6 rounded hover:bg-yellow-400 transition">
                    Abra sua conta
                </button></a>
                <p class="text-xs text-gray-300 mt-2"></p>
            </div>
            <div class="hidden md:flex flex-col space-y-4">
                <div class="bg-gray-800 text-white p-4 rounded-lg w-80 shadow-lg">
                    <p class="text-sm font-bold">SIMULE GRÁTIS quanto seu dinheiro pode render na PrimeInvest</p>
                    <a href="/calculadora/calculoRenda/calcularrentabilidade.php" class="text-yellow-400 text-sm mt-2 inline-block">Simule agora →</a>
                </div>
                <div class="bg-gray-800 text-white p-4 rounded-lg w-80 shadow-lg">
                    <p class="text-sm font-bold">Hora de mudar de vida com a PrimeInvest. Não perca essa oportunidade acesse já nosso curso !!!</p>
                    <a href="/PaginaCurso/CursoNoLogin/index.php" class="text-yellow-400 text-sm mt-2 inline-block">Acesse já →</a>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="text-center py-12 fade-in-element">
        <h2 class="text-4xl text-white font-bold">Produtos e serviços financeiros</h2>
        <p class="text-lg text-gray-400 mt-2">Aprenda tudo sobre investimentos e comece a investir hoje</p>
    </section>

    <div class="flex justify-center px-4 fade-in-element">
    <div class="w-full max-w-6xl bg-gray-900 rounded-lg overflow-hidden flex flex-col md:flex-row h-auto md:h-[270px]">
        <div class="p-8 flex flex-col justify-center w-full md:w-1/2 text-center">
            <h3 class="text-xl text-white font-semibold">Onde investir em 2025</h3>
            <p class="text-gray-400 mt-2">
                Confira as projeções e recomendações de investimento dos nossos especialistas para o próximo ano.
            </p>
            <button class="mt-4 bg-yellow-500 text-black font-semibold px-4 py-2 rounded hover:bg-yellow-400 transition">
                Baixar relatório
            </button>
        </div>
        <div class="w-full md:w-1/2 h-[250px] md:h-[300px]">
            <img src="./TECNICAS.png" alt="Onde investir em 2025" class="w-full h-full object-cover">
        </div>
    </div>
</div>
    </div>
    <section class="flex justify-center py-12 fade-in-element px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-5xl">
        <div class="bg-gray-900 rounded-lg overflow-hidden w-full">
            <div class="relative h-[150px]">
                <img src="https://noticiasconcursos.com.br/wp-content/uploads/2025/02/Moeda-1-real-1.png" 
                     alt="Investimentos" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-gray-800 opacity-50"></div>
            </div>
            <div class="p-4">
                <h3 class="text-md font-bold text-white">Investimentos</h3>
                <p class="text-gray-400 mt-2 text-sm">
                    Uma prateleira completa de produtos que atendem aos mais diversos perfis e objetivos.
                </p>
                <a href="#investimentos" class="text-yellow-400 font-semibold mt-3 inline-block text-sm">
                    Conheça nossos investimentos →
                </a>
            </div>
        </div>

        <div class="bg-gray-900 rounded-lg overflow-hidden w-full">
            <div class="relative h-[150px]">
                <img src="https://blog.daycoval.com.br/wp-content/uploads/2023/10/1D.png" 
                     alt="Investimentos" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-gray-800 opacity-50"></div>
            </div>
            <div class="p-4 fade-in-element">
                <a href="./registro/registro.php">
                    <h3 class="text-md font-bold text-white">Abra sua conta já</h3>
                </a>
                <p class="text-gray-400 mt-2 text-sm">
                    Está esperando o que? Abra sua conta e comece a investir agora mesmo.
                </p>
                <a href="./registro/registro.php" class="text-yellow-400 font-semibold mt-3 inline-block text-sm">
                    Abra sua Conta →
                </a>
            </div>
        </div>
    </div>
</section>

    
    <section class="text-center py-16 px-4 fade-in-element">
        <h2 class="text-3xl md:text-4xl font-bold text-white max-w-3xl mx-auto fade-in-element">
            Há mais de 5 anos transformando a vida das pessoas
        </h2>
        <p class="text-gray-600 text-lg text-white mt-4 max-w-2xl mx-auto fade-in-element">
            Tornando-se um destaque pela diversidade e exclusividade de produtos, além de prezar pela imparcialidade, transparência e forte conexão com os clientes.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-12 max-w-5xl mx-auto fade-in-element">
            <div class="flex flex-col items-center text-center">
                <div class="bg-yellow-400 p-3 rounded-full">
                    <i class="ph ph-handshake text-5xl text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mt-4">Uma das melhores em assessoria</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Com a Assessoria PrimeInvest, ajudamos as pessoas nas decisões relacionadas aos seus investimentos, sempre de acordo com seus objetivos e perfil.
                </p>
            </div>
            <div class="flex flex-col items-center text-center fade-in-element">
                <div class="bg-yellow-400 p-3 rounded-full">
                    <i class="ph ph-device-mobile text-5xl text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mt-4 fade-in-element">O novo Comum</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Nosso time de tecnologia antecipa tendências do mercado, para tornar a experiência de investir mais simples e acessível para todas as pessoas.
                </p>
            </div>
            <div class="flex flex-col items-center text-center fade-in-element">
                <div class="bg-yellow-400 p-3 rounded-full">
                    <i class="ph ph-chart-line text-5xl text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mt-4">Simples e Objetivo</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Serviços financeiros a favor dos seus investimentos. Tudo em um só lugar, para quem investe não depender mais dos bancos convencionais.
                </p>
            </div>
        </div>
    </section>
    <section id="investimentos" class="promo-section px-4 py-8">
    <strong><h2 class="text-center text-2xl md:text-3xl text-white">Opções de Investimentos</h2></strong>
    <br>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6 max-w-7xl mx-auto">
        <div class="promo-box fade-in-element bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <h3 class="text-xl font-bold text-white">Investimento Diamante</h3>
            <ul class="mt-4 text-sm space-y-2">
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
            <button onclick="window.location.href='https://w.app/primeinvestimentos?text=Olá%20quero%20saber%20sobre%20o%20Investimento%20Diamante%20de%20vocês'" 
                    class="mt-4 w-full bg-yellow-500 text-black font-semibold py-2 rounded hover:bg-yellow-400 transition">
                Saiba Mais
            </button>
        </div>

        <div class="promo-box fade-in-element bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <h3 class="text-xl font-bold text-white">Investimento Ouro</h3>
            <ul class="mt-4 text-sm space-y-2">
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
            <button onclick="window.location.href='https://w.app/primeinvestimentos?text=Olá%20quero%20saber%20sobre%20o%20Investimento%20Ouro%20de%20vocês'" 
                    class="mt-4 w-full bg-yellow-500 text-black font-semibold py-2 rounded hover:bg-yellow-400 transition">
                Saiba Mais
            </button>
        </div>

        <div class="promo-box fade-in-element bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <h3 class="text-xl font-bold text-white">Investimento Prata</h3>
            <ul class="mt-4 text-sm space-y-2">
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
            <button onclick="window.location.href='https://w.app/primeinvestimentos?text=Olá%20quero%20saber%20sobre%20o%20Investimento%20Prata%20de%20vocês'" 
                    class="mt-4 w-full bg-yellow-500 text-black font-semibold py-2 rounded hover:bg-yellow-400 transition">
                Saiba Mais
            </button>
        </div>

        <div class="promo-box fade-in-element bg-gray-900 p-6 rounded-lg shadow-lg text-white">
            <h3 class="text-xl font-bold text-white">Investimento Start</h3>
            <ul class="mt-4 text-sm space-y-2">
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
            <button onclick="window.location.href='https://w.app/primeinvestimentos?text=Olá%20quero%20saber%20sobre%20o%20Investimento%20Start%20de%20vocês'" 
                    class="mt-4 w-full bg-yellow-500 text-black font-semibold py-2 rounded hover:bg-yellow-400 transition">
                Saiba Mais
            </button>
        </div>
    </div>
</section>

    <section class="faq-section fade-in-element">
        <h2>Perguntas Frequentes</h2>
        <br>
        <div class="faq-container fade-in-element">
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">1. Quais são os passos para começar a investir na PrimeInvest?</button>
                <p class="faq-answer fade-in-element">Para investir, basta criar sua conta na PrimeInvest, realizar um depósito inicial e escolher o plano de investimento que melhor se adequa às suas necessidades financeiras.</p>
            </div>
    
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">2. Qual é o valor mínimo exigido para realizar o primeiro investimento?</button>
                <p class="faq-answer fade-in-element">O valor mínimo para começar a investir é de R$100,00. No entanto, valores mais elevados podem ser necessários para acessar planos e promoções específicas.</p>
            </div>
    
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">3. Quais opções de resgate estão disponíveis para os investidores?</button>
                <p class="faq-answer fade-in-element">Oferecemos resgates via PIX, TED e transferência bancária, todas processadas de forma rápida e segura.</p>
            </div>
    
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">4. Qual o prazo para o processamento dos resgates realizados?</button>
                <p class="faq-answer fade-in-element">Os resgates são processados dentro de até 48 horas úteis após a solicitação, dependendo do método escolhido.</p>
            </div>
    
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">5. A PrimeInvest segue os padrões de segurança financeira?</button>
                <p class="faq-answer fade-in-element">Sim! A PrimeInvest adota protocolos de segurança de nível bancário e segue as regulamentações mais rígidas do setor financeiro.</p>
            </div>
    
            <div class="faq-item fade-in-element">
                <button class="faq-question fade-in-element">6. Como posso entrar em contato com o suporte ao cliente?</button>
                <p class="faq-answer fade-in-element">Nosso suporte está disponível via e-mail, WhatsApp e chat ao vivo diretamente na plataforma. Estamos sempre prontos para ajudar!</p>
            </div>
        </div>
    </section>
    <a href="https://w.app/primeinvestimentos" target="_blank" class="whatsapp-float">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    </a>

    
    <footer>
        <p>Todos os direitos reservados para PrimeInvest © 2025</p>
    </footer>
    
    
    <script src="script.js"></script>
</body>
</html>
