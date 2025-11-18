// Quando o DOM estiver pronto, buscamos os dados e montamos os cards dinamicamente.
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('dashboard-grid');
    if (!grid) return; // nada a fazer se o container não existir

    /**
     * Cria o elemento DOM de um card a partir dos dados retornados pela API.
     * Suporta tanto objetos Empresa (nomeFantasia, atividadeEconomica...) quanto
     * Usuario (nome, cpf, sexo, telefone, email...). A função escolhe campos
     * relevantes dinamicamente.
     */
    function criaCard(item) {
        const card = document.createElement('div');
        card.className = 'card';

        const body = document.createElement('div');
        body.className = 'card-body';

        const titulo = document.createElement('h5');
        titulo.className = 'card-title';
        // Se for um card de treino (portifólio) usamos item.nome (treino)
        if (item.nome && !item.nomeFantasia) {
            titulo.innerText = item.nome;
        } else {
            // Prioriza nomeFantasia (empresa), senão nome (usuario)
            titulo.innerText = item.nomeFantasia || item.nome || '';
        }
        body.appendChild(titulo);

        // Campos adicionais dependem do tipo de item
        // Empresa: atividadeEconomica
        if (item.atividadeEconomica) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerText = item.atividadeEconomica;
            body.appendChild(p);
        }

        // Se o item tiver descrição (treino), exibe
        if (item.descricao) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerText = item.descricao;
            body.appendChild(p);
        }

        // Telefone
        if (item.telefone) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>Telefone:</strong> ' + item.telefone;
            body.appendChild(p);
        }

        // Email
        if (item.email) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>Email:</strong> ' + item.email;
            body.appendChild(p);
        }

        // Campos específicos de usuário: sexo e cpf
        if (item.sexo) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>Sexo:</strong> ' + item.sexo;
            body.appendChild(p);
        }

        if (item.cpf) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>CPF:</strong> ' + item.cpf;
            body.appendChild(p);
        }

        card.appendChild(body);

        // Rodapé do card com ações (ex.: Achar, Chat)
        const footer = document.createElement('div');
        footer.className = 'card-footer';

        // Botão Achar: quando o item representa um usuário ou empresa, direciona para a página com o contato específico
        if (item.id_usuario) {
            const btnAchar = document.createElement('a');
            btnAchar.className = 'card-btn';
            btnAchar.href = '/tcc/View/contatos.php?user_id=' + encodeURIComponent(item.id_usuario);
            btnAchar.innerText = 'Achar';
            footer.appendChild(btnAchar);
        } else if (item.id_empresa) {
            const btnAchar = document.createElement('a');
            btnAchar.className = 'card-btn';
            btnAchar.href = '/tcc/View/contatos.php?empresa_id=' + encodeURIComponent(item.id_empresa);
            btnAchar.innerText = 'Achar';
            footer.appendChild(btnAchar);
        }

        // Se for card de portifólio, adiciona botão para usuário adicionar ao seu plano
        const pagePath = location.pathname.toLowerCase();
        const pageIsPortifolio = pagePath.includes('cardportifolio.php');
        // API de portifólio retorna `idPortifolio` para cada item
        if (pageIsPortifolio && item.idPortifolio) {
            const btnAdd = document.createElement('button');
            btnAdd.className = 'card-btn';
            btnAdd.type = 'button';
            btnAdd.innerText = 'Adicionar ao Plano';
            btnAdd.addEventListener('click', function () {
                // Redireciona para a tela de cadastro de plano, passando portifolio e treino
                const portifolioId = item.idPortifolio || item.id_portifolio || item.idPortfólio || null;
                const treinoId = item.id || item.idTreino || item.treino_idTreino || null;
                const params = new URLSearchParams();
                if (portifolioId) params.set('portifolio_id', portifolioId);
                if (treinoId) params.set('treino_id', treinoId);
                // caminho absoluto para evitar problemas de rota
                window.location.href = '/tcc/View/cadastroPlanoTreino.html?' + params.toString();
            });
            footer.appendChild(btnAdd);
        }

        

        card.appendChild(footer);
        return card;
    }

    // Função que carrega itens do backend (empresas ou usuários). Detecta a
    // página atual e chama a API adequada. Se 'meus' for true, adiciona ?meus=1.
    function loadItems(meus = false) {
        // Detecta página atual: usuários, portifólio ou empresas
        const path = location.pathname.toLowerCase();
        const isUsuarioPage = path.includes('cardusuario.php');
        const isPortifolioPage = path.includes('cardportifolio.php');
        const isPlanoPage = path.includes('cardplanotreino.php') || path.includes('cardplanotreino.php'.toLowerCase());
        const apiBase = isUsuarioPage ? 'api/usuarios.php' : (isPortifolioPage ? 'api/portifolios.php' : (isPlanoPage ? 'api/planos.php' : 'api/empresas.php'));
        const url = meus ? apiBase + '?meus=1' : apiBase;

        return fetch(url)
            .then(resp => {
                // Se o servidor respondeu com status de erro, ainda tentamos ler o corpo
                if (!resp.ok) {
                    return resp.text().then(text => {
                        // Mensagem mais genérica, inclui corpo do erro
                        throw new Error('Falha ao buscar dados: ' + resp.status + '\n' + text);
                    });
                }

                // Confere content-type antes de tentar parsear como JSON
                const contentType = resp.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    return resp.text().then(text => {
                        throw new Error('Resposta não é JSON: ' + text);
                    });
                }

                return resp.json();
            })
            .then(data => {
                grid.innerHTML = ''; // limpa conteúdo enquanto monta os cards
                if (!Array.isArray(data) || data.length === 0) {
                    grid.innerHTML = '<p>Nenhum cadastro encontrado.</p>';
                    return;
                }

                        // Monta um card para cada item do array retornado
                        data.forEach(item => {
                            const card = criaCard(item);
                            grid.appendChild(card);
                        });
            });
    }

    // Inicial: se estivermos na página de portifólio, carregamos apenas 'meus', senão carregamos todos
    const initialPath = location.pathname.toLowerCase();
    const initialIsPortifolio = initialPath.includes('cardportifolio.php');
    const initialIsPlano = initialPath.includes('cardplanotreino.php');
    // para página de planos queremos carregar apenas 'meus' por padrão
    loadItems(initialIsPortifolio || initialIsPlano).catch(err => {
        console.error('Erro ao carregar dados:', err);
        grid.innerHTML = '<p>Erro ao carregar os dados. Veja console para detalhes.</p>';
    });

    // Liga os botões de filtro (IDs adicionados em cardEmpresa.php)
    const btnTodos = document.getElementById('btn-todos');
    const btnMeus = document.getElementById('btn-meus');

    if (btnTodos) {
        btnTodos.addEventListener('click', function () {
            loadItems(false).catch(err => {
                console.error(err);
                grid.innerHTML = '<p>Erro ao carregar os dados. Veja console para detalhes.</p>';
            });
        });
    }

    if (btnMeus) {
        btnMeus.addEventListener('click', function () {
            loadItems(true).catch(err => {
                console.error(err);
                grid.innerHTML = '<p>Erro ao carregar os dados. Veja console para detalhes.</p>';
            });
        });
    }

    // Botão 'Adicionar' específico para telas que possuem cadastro (ex: cardPlanoTreino)
    const btnAdicionar = document.getElementById('btn-adicionar');
    if (btnAdicionar) {
        btnAdicionar.addEventListener('click', function () {
            // redireciona para o formulário de cadastro de plano
            window.location.href = '/tcc/View/cadastroPlanoTreino.html';
        });
    }
});
