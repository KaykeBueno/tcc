// Script para a página cadastroPlanoTreino.html
// Carrega portifólios disponíveis (treino + empresa) e envia requisição para criar PlanoTreino

window.formCadastroPlanoTreino = {
	formData: {
		portifolio_idPortifolio: '',
		descricao: ''
	},
	opcoes: [],
	mensagens: '',
	successMessage: '',
	loading: false,

	async mount() {
		try {
			this.loading = true;
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
		} finally {
			this.loading = false;
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

			// se a página foi aberta com ?debug=1, repassa o parâmetro ao controller para obter resposta de debug
			const urlParams = new URLSearchParams(window.location.search);
			const debugParam = urlParams.get('debug') === '1' ? '&debug=1' : '';

			console.log('cadastrarPlanoTreino -> payload:', payload, 'debugParam=', debugParam);
			this.loading = true;
			this.mensagens = '';
			this.successMessage = '';

			const resp = await fetch('/tcc/Controller/PlanoTreinoController.php?acao=inserir' + debugParam, {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(payload)
			});

			// tenta ler JSON; se não for JSON, pega o texto cru para melhor depuração
			let respText = '';
			let data = {};
			try {
				data = await resp.json();
			} catch (e) {
				respText = await resp.text().catch(() => '');
			}

			if (!resp.ok) {
				const msg = (data && (data.error || data.mensagem)) ? (data.error || data.mensagem) : (respText || ('Erro ao criar plano, status ' + resp.status));
				this.mensagens = 'Erro ao criar plano: ' + msg;
				console.error('Resposta do servidor:', resp.status, respText || data);
				return;
			}

			console.log('Resposta do servidor (parsed):', data, 'rawText:', respText);
			// sucesso
			if (data && (data.idPlano || data.idPlano === '0')) {
				this.successMessage = 'Plano criado com sucesso (id ' + data.idPlano + ')';
				// pequena pausa para o usuário ver a mensagem, então redireciona
				setTimeout(() => { window.location.href = '/tcc/View/cardPlanoTreino.php'; }, 800);
				return;
			}

			if (data && data.mensagem) {
				this.successMessage = data.mensagem;
				setTimeout(() => { window.location.href = '/tcc/View/cardPlanoTreino.php'; }, 800);
				return;
			}

			this.successMessage = 'Plano criado.';
			setTimeout(() => { window.location.href = '/tcc/View/cardPlanoTreino.php'; }, 800);
		} catch (err) {
			console.error(err);
			this.mensagens = 'Erro ao criar plano. Veja console para detalhes.';
		} finally {
			this.loading = false;
		}
	}
};

// Monta o app do Petite-Vue para que v-scope/v-model funcionem
if (typeof PetiteVue !== 'undefined' && PetiteVue && typeof PetiteVue.createApp === 'function') {
	PetiteVue.createApp({ formCadastroPlanoTreino }).mount();
	// chama mount manual para carregar dados iniciais
	if (window.formCadastroPlanoTreino && typeof window.formCadastroPlanoTreino.mount === 'function') {
		window.formCadastroPlanoTreino.mount();
	}
} else {
	// fallback: chama mount quando o DOM estiver pronto
	document.addEventListener('DOMContentLoaded', function () {
		if (window.formCadastroPlanoTreino && window.formCadastroPlanoTreino.mount) {
			window.formCadastroPlanoTreino.mount();
		}
	});
}
