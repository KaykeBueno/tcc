const formCadastroEmpresa = {
    // Objeto que armazena os dados do formulário da empresa.
    // O petite-vue usa este objeto para ler e atualizar os valores dos campos do HTML (via v-model).
    formData: {
        nomeFantasia: '',
        cnpj: '',
        atividadeEconomica: '',
        telefone: '',
        porteEmpresarial: '',
        email: '',
        senha: '',
        confirmarSenha: ''
    },

    // Função assíncrona para cadastrar a empresa, chamada quando o formulário é enviado.
    async cadastrarEmpresa() {
        // Validação inicial: verifica se as senhas digitadas são iguais.
        if (this.formData.senha !== this.formData.confirmarSenha) {
            alert('A senha e a confirmação de senha não coincidem!');
            return; // Interrompe a execução se as senhas não baterem.
        }

        // O bloco try...catch é usado para tratar possíveis erros durante a comunicação com o servidor.
        try {
            // Envia os dados para o EmpresaController.php usando a função fetch.
            const response = await fetch("../Controller/EmpresaController.php?acao=inserir", {
                method: "POST", // Define o método como POST, que é o correto para enviar dados para criação.
                headers: { "Content-Type": "application/json" }, // Informa ao backend que estamos enviando dados em formato JSON.
                body: JSON.stringify(this.formData) // Converte o objeto de dados em uma string JSON para envio.
            });

            // Aguarda a resposta do servidor e a converte de volta para um objeto JavaScript.
            const resultado = await response.json();

            // Verifica se a resposta do servidor indica um erro (ex: status 400 ou 500).
            if (!response.ok) {
                // Se houver um erro, lança uma exceção com a mensagem enviada pelo PHP.
                throw new Error(resultado.mensagem);
            }

            // Se a resposta for de sucesso, exibe a mensagem de sucesso vinda do PHP.
            alert(resultado.mensagem);
            // Redireciona o usuário para a página de login após o cadastro bem-sucedido.
            // O caminho foi corrigido para o endereço completo.
            window.location.href = 'http://localhost/tcc/index.html';

        } catch (error) {
            // Se qualquer parte do bloco 'try' falhar (problema de rede ou erro do servidor),
            // este bloco 'catch' é executado e exibe um alerta com a mensagem de erro.
            alert("Falha no cadastro: " + error.message);
        }
    }
};

// Garante que o código JavaScript só será executado depois que todo o HTML da página for carregado.
document.addEventListener("DOMContentLoaded", () => {
    // Inicializa o petite-vue, associando o objeto 'formCadastroEmpresa' ao elemento HTML
    // que tem o atributo v-scope="formCadastroEmpresa".
    PetiteVue.createApp({ formCadastroEmpresa }).mount();
});