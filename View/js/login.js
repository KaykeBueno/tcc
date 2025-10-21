
const FormApp = {

    formData: {
        cpf: '',
        cnpj: '',
        senha: '',
        tipoUsuario: ''
    },

    async autenticar() {


        if (!this.formData.tipoUsuario) {
            alert("Por favor, selecione o tipo de usuário (CPF ou CNPJ).");
            return;
        }


        if (this.formData.tipoUsuario === 'cpf') {

            if (this.formData.cpf != '' && this.formData.senha != '') {

                const response = await fetch("Controller/UsuarioController.php?acao=autenticar", {
                    method: "POST",

                    body: JSON.stringify(this.formData),
                    headers: { "Content-Type": "application/json" }
                });

                if (response.ok) {
                    window.location.href = "View/perfilUsuario.php";
                }
                else {
                    const retorno = await response.json()
                    alert(retorno.mensagem)
                }
            }
        }
        else {
            if (this.formData.cnpj != '' && this.formData.senha != '') {
                const response = await fetch("Controller/EmpresaController.php?acao=autenticar", {
                    method: "POST",
                    body: JSON.stringify(this.formData),
                    headers: { "Content-Type": "application/json" }
                });

                if (response.ok) {
                    window.location.href = "View/perfilEmpresa.php";
                }

                else {
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