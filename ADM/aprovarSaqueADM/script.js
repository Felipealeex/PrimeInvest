document.addEventListener("DOMContentLoaded", function () {
    let saquesPendentes = [
        { id: 1, data: "04/12/2024", nome: "Felippe Oliveira", banco: "mercadopago", agencia: "0001", cc: "846385-01", valor: "R$ 1.000,00" },
        { id: 2, data: "03/12/2024", nome: "Maria Souza", banco: "bradesco", agencia: "1234", cc: "567890-02", valor: "R$ 2.500,00" },
        { id: 3, data: "02/12/2024", nome: "João Silva", banco: "itau", agencia: "4321", cc: "098765-03", valor: "R$ 800,00" }
    ];

    let tabelaSaquesPendentes = document.getElementById("tabelaSaquesPendentes");

    function carregarSaquesPendentes() {
        tabelaSaquesPendentes.innerHTML = "";
        saquesPendentes.forEach(saque => {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>${saque.data}</td>
                <td>${saque.nome}</td>
                <td><img src="bancos/${saque.banco}.png" alt="${saque.banco}"></td>
                <td>${saque.agencia}</td>
                <td>${saque.cc}</td>
                <td>${saque.valor}</td>
                <td>
                    <button class="btn btn-aprovar" onclick="aprovarSaque(${saque.id})">Aprovar</button>
                    <button class="btn btn-negar" onclick="negarSaque(${saque.id})">Negar</button>
                </td>
            `;
            tabelaSaquesPendentes.appendChild(row);
        });
    }

    carregarSaquesPendentes();

    window.aprovarSaque = function (id) {
        saquesPendentes = saquesPendentes.filter(saque => saque.id !== id);
        carregarSaquesPendentes();
        alert("Saque aprovado com sucesso!");
    };

    window.negarSaque = function (id) {
        saquesPendentes = saquesPendentes.filter(saque => saque.id !== id);
        carregarSaquesPendentes();
        alert("Saque negado com sucesso!");
    };
});
