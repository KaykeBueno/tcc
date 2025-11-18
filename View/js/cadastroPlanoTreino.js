// Script para a página cadastroPlanoTreino.html
// Carrega portifólios disponíveis (treino + empresa) e envia requisição para criar PlanoTreino

window.formCadastroPlanoTreino = {
    formData: {
        portifolio_idPortifolio: '',
        descricao: ''
    },
    opcoes: [],
    mensagens: '',

    async mount() {
        try {
            console.log('carregando portifolios: GET /tcc/View/api/portifolios.php');
            const resp = await fetch('/tcc/View/api/portifolios.php');
            console.log('status fetch portifolios:', resp.status);
            if (!resp.ok) {
                const txt = await resp.text().catch(() => '');
                throw new Error('Falha ao carregar portifólios: ' + resp.status + ' ' + txt);
            }
            const data = await resp.json().catch(() => null);
            console.log('portifolios raw data:', data);

            // Mapear opções: valor = idPortifolio, label = nome do treino + ' — ' + empresa
            this.opcoes = (Array.isArray(data) ? data : []).map(item => {
                const portId = item.idPortifolio || item.id_portifolio || item.id || null;
                const nomeTreino = item.treinoNome || item.nome || item.nomeTreino || '';
                const empresa = item.nomeFantasia || item.nomeEmpresa || item.empresa || '';
                const label = nomeTreino ? (empresa ? `${nomeTreino} — ${empresa}` : nomeTreino) : (empresa || 'Portifólio');
                return { value: portId, label, raw: item };
            }).filter(o => o.value);

            // Preencher select no DOM (compatibilidade com não-Petite-Vue quando necessário)
            const select = document.getElementById('treinoSelect');
            if (select) {
                select.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.innerText = this.opcoes.length ? 'Selecione o treino/portifólio' : 'Nenhum portifólio disponível';
                select.appendChild(placeholder);
                this.opcoes.forEach(o => {
                    const opt = document.createElement('option');
                    opt.value = o.value;
                    opt.innerText = o.label;
                    select.appendChild(opt);
                });
            } else {
                console.warn('select #treinoSelect não encontrado no DOM');
            }

            if (!this.opcoes.length) {
                this.mensagens = 'Nenhum portifólio encontrado.';
            }
        } catch (err) {
            console.error('erro no mount cadastroPlanoTreino:', err);
            this.mensagens = 'Erro ao carregar portifólios.';
            // Exibe mensagem na tela se possível
            const select = document.getElementById('treinoSelect');
            if (select) {
                select.innerHTML = '';
                const opt = document.createElement('option');
                opt.value = '';
                opt.innerText = 'Erro ao carregar treinos';
                select.appendChild(opt);
            }
        }
    },

    async cadastrarPlanoTreino(e) {
        e && e.preventDefault && e.preventDefault();
        if (!this.formData.portifolio_idPortifolio) {
            alert('Selecione um portifólio/treino antes de cadastrar.');
            return;
        }

        try {
            const payload = {
                portifolio_idPortifolio: this.formData.portifolio_idPortifolio,
                descricao: this.formData.descricao || ''
            };

            const resp = await fetch('/tcc/Controller/PlanoTreinoController.php?acao=inserir', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await resp.json().catch(() => ({}));
            if (!resp.ok) {
                const msg = data && (data.error || data.mensagem) ? (data.error || data.mensagem) : 'Erro ao criar plano';
                alert(msg);
                return;
            }

            if (data && data.idPlano) {
                alert('Plano criado com sucesso (id ' + data.idPlano + ')');
                // redireciona para a página de planos
                window.location.href = '/tcc/View/cardPlanoTreino.php';
                return;
            }

            if (data && data.mensagem) {
                alert(data.mensagem);
                window.location.href = '/tcc/View/cardPlanoTreino.php';
                return;
            }

            alert('Plano criado.');
            window.location.href = '/tcc/View/cardPlanoTreino.php';
        } catch (err) {
            console.error(err);
            alert('Erro ao criar plano. Veja console para detalhes.');
        }
    }
};

// Inicializa quando DOM pronto
document.addEventListener('DOMContentLoaded', function () {
    if (window.formCadastroPlanoTreino && window.formCadastroPlanoTreino.mount) {
        window.formCadastroPlanoTreino.mount();
    }
});
