document.addEventListener("DOMContentLoaded", function () {
    let saques = [
        { data: "04/12/2024", nome: "Felippe Oliveira", banco: "mercadopago", agencia: "0001", cc: "846385-01", status: "pendente", valor: "R$ 1.000,00" },
        { data: "03/12/2024", nome: "Maria Souza", banco: "bradesco", agencia: "1234", cc: "567890-02", status: "aprovado", valor: "R$ 2.500,00" },
        { data: "02/12/2024", nome: "João Silva", banco: "itau", agencia: "4321", cc: "098765-03", status: "negado", valor: "R$ 800,00" }
    ];

    let tabelaSaques = document.getElementById("tabelaSaques");

    function carregarSaques(lista) {
        tabelaSaques.innerHTML = "";
        lista.forEach(saque => {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>${saque.data}</td>
                <td>${saque.nome}</td>
                <td><img src="bancos/${saque.banco}.png" alt="${saque.banco}"></td>
                <td>${saque.agencia}</td>
                <td>${saque.cc}</td>
                <td><span class="status-${saque.status}">${saque.status.charAt(0).toUpperCase() + saque.status.slice(1)}</span></td>
                <td>${saque.valor}</td>
            `;
            tabelaSaques.appendChild(row);
        });
    }

    carregarSaques(saques);

    window.filtrarSaques = function () {
        let filtroStatus = document.getElementById("filtroStatus").value;
        let filtroMes = document.getElementById("filtroMes").value;

        let resultado = saques.filter(saque => {
            return (filtroStatus === "" || saque.status === filtroStatus) &&
                   (filtroMes === "" || saque.data.split("/")[1] === filtroMes);
        });

        carregarSaques(resultado);
    };
});
