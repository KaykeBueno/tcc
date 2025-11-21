console.log('cadastroPortifolio.js carregado');

document.addEventListener('DOMContentLoaded', function () {
    // Expor o escopo esperado pelo petite-vue imediatamente.
    // Isso evita ReferenceError quando o petite-vue fizer mount automaticamente.
    window.formCadastroPortifolio = {
        formData: {
            treino_idTreino: '',
            descricao: ''
        },
        treinos: [],

        async carregarTreinos() {
            try {
                const res = await fetch('/tcc/View/api/treinos.php');
                if (!res.ok) throw new Error('Erro ao carregar treinos');
                const data = await res.json();
                this.treinos = data;

                const select = document.getElementById('treinoSelect');
                if (!select) return;
                // Limpa options
                select.innerHTML = '';
                this.treinos.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.nome;
                    select.appendChild(opt);
                });

                // adicionar opção vazia no topo
                const empty = document.createElement('option');
                empty.value = '';
                empty.textContent = '-- selecione um treino --';
                select.insertBefore(empty, select.firstChild);
            } catch (err) {
                console.error(err);
                const select = document.getElementById('treinoSelect');
                if (select) select.innerHTML = '<option value="">Erro ao carregar treinos</option>';
            }
        },

        async cadastrarPortifolio() {
            try {
                if (!this.formData.treino_idTreino) {
                    alert('Selecione um treino antes de cadastrar.');
                    return;
                }

                const payload = {
                    treino_idTreino: this.formData.treino_idTreino,
                    descricao: this.formData.descricao
                };

                const res = await fetch('/tcc/View/api/portifolios.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                console.log('Resposta do servidor (status ' + res.status + '):', json);
                if (!res.ok) {
                    // servidor pode enviar { error: 'texto' } ou { message: 'texto' }
                    const msg = (json && (json.message || json.error)) ? (json.message || json.error) : 'Erro ao cadastrar portifólio';
                    alert(msg);
                    return;
                }

                // sucesso
                alert('Portfólio cadastrado com sucesso!');
                // redireciona para perfil da empresa
                window.location.href = 'cardPortifolio.php';
            } catch (err) {
                console.error(err);
                alert('Erro ao cadastrar portifólio');
            }
        }
    };

    // Inicializar carregamento automático de treinos (pequeno timeout para garantir que o select exista)
    setTimeout(() => {
        if (window.formCadastroPortifolio && typeof window.formCadastroPortifolio.carregarTreinos === 'function') {
            window.formCadastroPortifolio.carregarTreinos();
        }
    }, 50);

    // Fallback: intercepta submit caso petite-vue não esteja ativo
    setTimeout(() => {
        const form = document.querySelector('form[v-scope="formCadastroPortifolio"], form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                console.log('Interceptado submit do form - chamando cadastrarPortifolio()');
                if (window.formCadastroPortifolio && typeof window.formCadastroPortifolio.cadastrarPortifolio === 'function') {
                    // sincroniza o valor do select e do campo descricao com o objeto formData
                    // caso o v-model não esteja ativo (petite-vue não inicializado)
                    const sel = document.getElementById('treinoSelect');
                    if (sel) window.formCadastroPortifolio.formData.treino_idTreino = sel.value;
                    const desc = document.getElementById('descricao');
                    if (desc) window.formCadastroPortifolio.formData.descricao = desc.value;
                    window.formCadastroPortifolio.cadastrarPortifolio();
                } else {
                    console.warn('Função cadastrarPortifolio não disponível');
                }
            });
        }
    }, 300);
});
