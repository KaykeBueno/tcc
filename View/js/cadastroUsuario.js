const formCadastroUsuario = {
    // Objeto que armazena os dados do formulário do usuário.
    // O petite-vue usa este objeto para ler e atualizar os valores dos campos do HTML (via v-model).
    formData: {
        nome: '',
        cpf: '',
        telefone: '',
        dataNascimento: '',
        email: '',
        senha: '',
        confirmarSenha: '',
        sexo: ''
    },

    // Função assíncrona para cadastrar o usuário, chamada quando o formulário é enviado.
    // O 'async' é necessário para poder usar 'await' na chamada fetch.
    async cadastrarUsuario() {
        // Validação inicial no lado do cliente: verifica se as senhas digitadas são iguais.
        if (this.formData.senha !== this.formData.confirmarSenha) {
            alert('A senha e a confirmação de senha não coincidem!');
            return; // Interrompe a execução da função se as senhas forem diferentes.
        }

        // O bloco try...catch é uma boa prática para tratar erros de comunicação com o servidor.
        try {
            // 'fetch' é a função do JavaScript que envia a requisição para o seu backend PHP.
            const response = await fetch("../Controller/UsuarioController.php?acao=inserir", {
                method: "POST", // Define o método como POST, o padrão para criar novos registros.
                headers: { "Content-Type": "application/json" }, // Informa ao PHP que o corpo da requisição está em formato JSON.
                body: JSON.stringify(this.formData) // Converte o objeto de dados em uma string JSON para ser enviada.
            });

            // Aguarda a resposta do servidor e a converte de volta para um objeto JavaScript.
            const resultado = await response.json(); 

            // Verifica se a resposta do servidor indica um erro (ex: status 400 para 'CPF já existe').
            if (!response.ok) {
                // Se for um erro, lança uma exceção com a mensagem de erro vinda do PHP,
                // que será capturada pelo bloco 'catch' abaixo.
                throw new Error(resultado.mensagem);
            }

            // Se a requisição foi bem-sucedida, exibe a mensagem de sucesso vinda do PHP.
            alert(resultado.mensagem);
            // Redireciona o usuário para a página de login após o cadastro.
            // O caminho foi corrigido para o endereço relativo correto.
            window.location.href = 'http://localhost/tcc/index.html';

        } catch (error) {
            // Este bloco é executado se o 'fetch' falhar (ex: sem internet) ou se um erro for lançado no 'try'.
            // Exibe um alerta para o usuário com a mensagem de erro.
            alert("Falha no cadastro: " + error.message);
        }
    }
};

// Garante que o script do petite-vue só será executado depois que toda a página HTML for carregada.
document.addEventListener("DOMContentLoaded", () => {
    // Inicializa o petite-vue, associando o objeto 'formCadastroUsuario' ao elemento HTML
    // que tem o atributo v-scope="formCadastroUsuario".
    PetiteVue.createApp({ formCadastroUsuario }).mount();
});