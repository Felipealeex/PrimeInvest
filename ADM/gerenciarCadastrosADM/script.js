
        document.addEventListener("DOMContentLoaded", function () {
            let clientes = [
                { id: 1, nome: "Cliente Exemplo", cpf: "123.456.789-00", valorConta: 5000 },
                { id: 2, nome: "João da Silva", cpf: "987.654.321-00", valorConta: 12000 },
                { id: 3, nome: "Maria Souza", cpf: "456.123.789-00", valorConta: 7800 }
            ];

            let tabelaClientes = document.getElementById("tabelaClientes");

            clientes.forEach(cliente => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${cliente.nome}</td>
                    <td>
                        <button class="btn btn-warning" onclick="editarCliente(${cliente.id})">Ver/Editar</button>
                    </td>
                `;
                tabelaClientes.appendChild(row);
            });

            window.editarCliente = function (id) {
                let cliente = clientes.find(c => c.id == id);
                if (cliente) {
                    document.getElementById("nomeCliente").value = cliente.nome;
                    document.getElementById("cpfCliente").value = cliente.cpf;
                    document.getElementById("valorConta").value = cliente.valorConta;

                    document.getElementById("listaClientes").classList.add("d-none");
                    document.getElementById("formEdicao").classList.remove("d-none");
                }
            };

            window.voltarLista = function () {
                document.getElementById("listaClientes").classList.remove("d-none");
                document.getElementById("formEdicao").classList.add("d-none");
            };

            document.getElementById("formEditar").addEventListener("submit", function (event) {
                event.preventDefault();
                alert("Dados do cliente foram atualizados!");
                voltarLista();
            });
        });
