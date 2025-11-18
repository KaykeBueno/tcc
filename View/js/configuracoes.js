// Frontend handler for the Configurações page.
// Expects `window.APP_SESSION` to be present with { tipoUsuario, idUsuario, idEmpresa }.
// Provides methods: alterarContato, alterarSenha, excluirConta.

console.log('configuracoes.js loaded');

window.ConfigApp = function () {
    return {
        // Dados do formulário de senha
        formData: {
            senhaAtual: '',
            novaSenha: '',
            confirmarNovaSenha: ''
        },

        // Dados de contato (se quiser preencher campos editáveis no futuro)
        contatoData: {
            email: '',
            telefone: ''
        },

        // Helper que retorna qual controller chamar (usuario/empresa)
        _controllerBase() {
            const tipo = window.APP_SESSION && window.APP_SESSION.tipoUsuario ? window.APP_SESSION.tipoUsuario : null;
            if (tipo === 'usuario') return '/tcc/Controller/UsuarioController.php';
            if (tipo === 'empresa') return '/tcc/Controller/EmpresaController.php';
            return null;
        },

        // Alterar contato: envia email e telefone para o controller
        async alterarContato() {
            const controller = this._controllerBase();
            if (!controller) {
                alert('Usuário não autenticado');
                return;
            }

            // Exemplo: perguntar via prompt (pode substituir por modal/form)
            const novoEmail = prompt('Novo email:', this.contatoData.email || '');
            if (novoEmail === null) return; // cancelado
            const novoTelefone = prompt('Novo telefone:', this.contatoData.telefone || '');
            if (novoTelefone === null) return;

            try {
                const payload = { email: novoEmail, telefone: novoTelefone };
                const res = await fetch(controller + '?acao=alterarContato', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (!res.ok) throw new Error(json && (json.error || json.mensagem) ? (json.error || json.mensagem) : 'Erro');
                alert(json.mensagem || 'Contato atualizado com sucesso');
                // atualiza dados locais
                this.contatoData.email = novoEmail;
                this.contatoData.telefone = novoTelefone;
                // opcional: recarregar página para refletir sessão
                window.location.reload();
            } catch (err) {
                console.error(err);
                alert('Falha ao atualizar contato: ' + err.message);
            }
        },

        // Alterar senha: requer senha atual e confirmação
        async alterarSenha() {
            const { senhaAtual, novaSenha, confirmarNovaSenha } = this.formData;
            if (!senhaAtual || !novaSenha) {
                alert('Preencha a senha atual e a nova senha');
                return;
            }
            if (novaSenha !== confirmarNovaSenha) {
                alert('Confirmação de senha não confere');
                return;
            }

            const controller = this._controllerBase();
            if (!controller) {
                alert('Usuário não autenticado');
                return;
            }

            try {
                const payload = { senhaAtual, novaSenha };
                const res = await fetch(controller + '?acao=alterarSenha', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (!res.ok) throw new Error(json && (json.error || json.mensagem) ? (json.error || json.mensagem) : 'Erro');
                alert(json.mensagem || 'Senha alterada com sucesso');
                // limpa campos
                this.formData.senhaAtual = '';
                this.formData.novaSenha = '';
                this.formData.confirmarNovaSenha = '';
            } catch (err) {
                console.error(err);
                alert('Falha ao alterar senha: ' + err.message);
            }
        },

        // Excluir conta: pede confirmação e envia para controller
        async excluirConta() {
            if (!confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.')) return;
            const controller = this._controllerBase();
            if (!controller) {
                alert('Usuário não autenticado');
                return;
            }

            // Para segurança, pedir a senha antes de excluir
            const senha = prompt('Para confirmar, digite sua senha atual:');
            if (senha === null) return;

            try {
                const payload = { senha }; // controller verificará
                const res = await fetch(controller + '?acao=excluir', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (!res.ok) throw new Error(json && (json.error || json.mensagem) ? (json.error || json.mensagem) : 'Erro');
                alert(json.mensagem || 'Conta excluída com sucesso');
                // redireciona para login
                window.location.href = '/tcc/View/login.html';
            } catch (err) {
                console.error(err);
                alert('Falha ao excluir conta: ' + err.message);
            }
        }
    };
}();
