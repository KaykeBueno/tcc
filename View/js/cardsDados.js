// Quando o DOM estiver pronto, buscamos os dados e montamos os cards dinamicamente.
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('dashboard-grid');
    if (!grid) return; // nada a fazer se o container não existir

    /**
     * Cria o elemento DOM de um card a partir dos dados da empresa.
     * Espera um objeto com chaves: nomeFantasia, atividadeEconomica, telefone, email.
     */
    function criaCard(emp) {
        const card = document.createElement('div');
        card.className = 'card';

        const body = document.createElement('div');
        body.className = 'card-body';

        const titulo = document.createElement('h5');
        titulo.className = 'card-title';
        // Usa nomeFantasia se disponível, senão tenta 'nome'
        titulo.innerText = emp.nomeFantasia || emp.nome || '';

        body.appendChild(titulo);

        // Campos opcionais — adiciona apenas se existirem
        if (emp.atividadeEconomica) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerText = emp.atividadeEconomica;
            body.appendChild(p);
        }

        if (emp.telefone) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>Telefone:</strong> ' + emp.telefone;
            body.appendChild(p);
        }

        if (emp.email) {
            const p = document.createElement('p');
            p.className = 'card-text';
            p.innerHTML = '<strong>Email:</strong> ' + emp.email;
            body.appendChild(p);
        }

        card.appendChild(body);
        return card;
    }

    // Função que carrega empresas do backend. Se 'meus' for true, pede apenas
    // as empresas vinculadas ao usuário logado (api/empresas.php?meus=1).
    function loadEmpresas(meus = false) {
        const url = meus ? 'api/empresas.php?meus=1' : 'api/empresas.php';

        return fetch(url)
            .then(resp => {
                // Se o servidor respondeu com status de erro, ainda tentamos ler o corpo
                if (!resp.ok) {
                    return resp.text().then(text => {
                        throw new Error('Falha ao buscar empresas: ' + resp.status + '\n' + text);
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
                data.forEach(emp => {
                    const card = criaCard(emp);
                    grid.appendChild(card);
                });
            });
    }

    // Inicial: carrega todos os professores
    loadEmpresas(false).catch(err => {
        console.error('Erro ao carregar empresas:', err);
        grid.innerHTML = '<p>Erro ao carregar empresas. Veja console para detalhes.</p>';
    });

    // Liga os botões de filtro (IDs adicionados em cardEmpresa.php)
    const btnTodos = document.getElementById('btn-todos');
    const btnMeus = document.getElementById('btn-meus');

    if (btnTodos) {
        btnTodos.addEventListener('click', function () {
            loadEmpresas(false).catch(err => {
                console.error(err);
                grid.innerHTML = '<p>Erro ao carregar empresas. Veja console para detalhes.</p>';
            });
        });
    }

    if (btnMeus) {
        btnMeus.addEventListener('click', function () {
            loadEmpresas(true).catch(err => {
                console.error(err);
                grid.innerHTML = '<p>Erro ao carregar seus professores. Veja console para detalhes.</p>';
            });
        });
    }
});
