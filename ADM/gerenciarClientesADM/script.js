document.addEventListener("DOMContentLoaded", function () {
    let clientes = [
        { nome: "Cliente 1", inicial: 1000, atual: 1200, projecao: 1500 },
        { nome: "Cliente 2", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 3", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 4", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 5", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 6", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 7", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 8", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 9", inicial: 1500, atual: 1800, projecao: 2000 },
        { nome: "Cliente 10", inicial: 1500, atual: 1800, projecao: 2000 }
    ];

    let tabelaClientes = document.getElementById("tabelaClientes");

    clientes.forEach((cliente, index) => {
        let row = document.createElement("tr");

        row.innerHTML = `
            <td>${cliente.nome}</td>
            <td><input type="number" value="${cliente.inicial}" id="inicial-${index}"></td>
            <td><input type="number" value="${cliente.atual}" id="atual-${index}"></td>
            <td><input type="number" value="${cliente.projecao}" id="projecao-${index}"></td>
            <td><button class="btn btn-salvar" onclick="salvarCliente(${index})">Salvar</button></td>
        `;

        tabelaClientes.appendChild(row);
    });
});

function salvarCliente(index) {
    let inicial = document.getElementById(`inicial-${index}`).value;
    let atual = document.getElementById(`atual-${index}`).value;
    let projecao = document.getElementById(`projecao-${index}`).value;

    console.log(`Dados salvos para Cliente ${index + 1}: Inicial = ${inicial}, Atual = ${atual}, Projeção = ${projecao}`);
    alert(`Dados do Cliente ${index + 1} foram salvos com sucesso!`);
}
