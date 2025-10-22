const formCadastroEmpresa = {
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

  
    async cadastrarEmpresa() {

        if (this.formData.senha !== this.formData.confirmarSenha) {
            alert('A senha e a confirmação de senha não coincidem!');
            return;
        }


        try {

            const response = await fetch("../Controller/EmpresaController.php?acao=inserir", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(this.formData)
            });


            const resultado = await response.json();


            if (!response.ok) {
 
                throw new Error(resultado.mensagem);
            }

            alert(resultado.mensagem);
        
            window.location.href = 'http://localhost/tcc/index.html';

        } catch (error) {

            alert("Falha no cadastro: " + error.message);
        }
    }
};


document.addEventListener("DOMContentLoaded", () => {
    PetiteVue.createApp({ formCadastroEmpresa }).mount();
});