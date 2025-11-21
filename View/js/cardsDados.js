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

        const pagePath = location.pathname.toLowerCase();
        const pageIsPlano = pagePath.includes('cardplanotreino.php');

        const titulo = document.createElement('h5');
        titulo.className = 'card-title';
        // Se estivermos na página de planos, o título deve ser o nome do treino
        if (pageIsPlano) {
            titulo.innerText = item.nome || '';
        } else {
            // Se for um card de treino (portifólio) usamos item.nome (treino)
            if (item.nome && !item.nomeFantasia) {
                titulo.innerText = item.nome;
            } else {
                // Prioriza nomeFantasia (empresa), senão nome (usuario)
                titulo.innerText = item.nomeFantasia || item.nome || '';
            }
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

        // Se estivermos na página de planos, mostre quem oferece (professor/empresa) e a descrição
        if (pageIsPlano) {
            if (item.nomeFantasia) {
                const pProf = document.createElement('p');
                pProf.className = 'card-text';
                pProf.innerHTML = '<strong>Professor:</strong> ' + item.nomeFantasia;
                body.appendChild(pProf);
            }
            if (item.descricao) {
                const p = document.createElement('p');
                p.className = 'card-text';
                p.innerText = item.descricao;
                body.appendChild(p);
            }
        } else {
            // Se o item tiver descrição (treino/empresa), exibe
            if (item.descricao) {
                const p = document.createElement('p');
                p.className = 'card-text';
                p.innerText = item.descricao;
                body.appendChild(p);
            }
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
