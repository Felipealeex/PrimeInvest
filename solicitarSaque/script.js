$(document).ready(function () {
    let formSaque = $("#formSaque");
    let mensagemSucesso = $("#mensagemSucesso");
    let inputDocumento = $("#documento");

    // Aplica máscara automática ao CPF/CNPJ enquanto digita
    inputDocumento.on("input", function () {
        let valor = inputDocumento.val().replace(/\D/g, ""); // Remove caracteres não numéricos

        if (valor.length <= 11) {
            inputDocumento.mask("000.000.000-00");
        } else {
            inputDocumento.mask("00.000.000/0000-00");
        }
    });

    formSaque.submit(function (event) {
        event.preventDefault();

        let documento = inputDocumento.val().trim();
        let valor = $("#valorSaque").val();
        let metodo = $("#metodoPagamento").val();

        if (documento.length < 14) {
            alert("Por favor, insira um CPF ou CNPJ válido.");
            return;
        }

        if (valor <= 0 || metodo === "") {
            alert("Por favor, insira um valor válido e selecione um método de pagamento.");
            return;
        }

        console.log("Saque solicitado:", { documento, valor, metodo });

        mensagemSucesso.removeClass("d-none");

        setTimeout(() => {
            mensagemSucesso.addClass("d-none");
            formSaque.trigger("reset");
        }, 2000);
    });
});
