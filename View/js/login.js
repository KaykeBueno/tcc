// js/login.js

const FormApp = {
    // Os dados do formulário permanecem os mesmos.
    formData: {
        cpf: '',
        cnpj: '',
        senha: '',
        tipoUsuario: ''
    },

    async autenticar() {

        // Valida se um tipo de usuário foi selecionado.
        if (!this.formData.tipoUsuario) {
            alert("Por favor, selecione o tipo de usuário (CPF ou CNPJ).");
            return;
        }

        // Prepara a URL e os dados com base na escolha do usuário.
        if (this.formData.tipoUsuario === 'cpf') {
            // Verifica se os campos de CPF e senha não estão vazios.
            if (this.formData.cpf != '' && this.formData.senha != '') {
                // Faz uma requisição POST ao backend (controller)
                const response = await fetch("Controller/UsuarioController.php?acao=autenticar", {
                    method: "POST",
                    // Envia o objeto formData completo, incluindo o CNPJ vazio, o que é ineficiente.
                    body: JSON.stringify(this.formData),
                    headers: { "Content-Type": "application/json" }
                });

                // Se a resposta for OK (HTTP 200), redireciona.
                if (response.ok) {
                    window.location.href = "View/listagemExercicios.html";
                }
                else {
                    // Tratamento de erro caso o login falhe (ex: senha errada).
                    const retorno = await response.json()
                    alert(retorno.mensagem)
                }
            }
        }
        else { // Se não for 'cpf', será 'cnpj'.
            if (this.formData.cnpj != '' && this.formData.senha != '') {
                // A lógica de fetch é repetida aqui.
                const response = await fetch("Controller/EmpresaController.php?acao=autenticar", {
                    method: "POST",
                    body: JSON.stringify(this.formData),
                    headers: { "Content-Type": "application/json" }
                });

                if (response.ok) {
                    window.location.href = "View/listagemExerciciosEmpresa.html";
                }

                else {
                    // Tratamento de erro caso o login falhe (ex: senha errada).
                    const retorno = await response.json()
                    alert(retorno.mensagem)
                }
            }
        }
    }
};

document.addEventListener("DOMContentLoaded", () => {
    PetiteVue.createApp({ FormApp }).mount();
});