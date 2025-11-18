// Busca contatos via Controller/ChatController.php?acao=listar e monta cards
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('dashboard-grid');
    if (!grid) return;

    // Obtém query param treino_id se presente para filtrar
    const params = new URLSearchParams(location.search);
    const treinoId = params.get('treino_id');
    let url = '/tcc/View/api/contatos.php?acao=listar';
    if (treinoId) url += '&treino_id=' + encodeURIComponent(treinoId);

    fetch(url)
        .then(resp => resp.json())
        .then(data => {
            grid.innerHTML = '';
            if (!Array.isArray(data) || data.length === 0) {
                grid.innerHTML = '<p>Nenhum contato encontrado.</p>';
                return;
            }

            data.forEach(item => {
                const card = document.createElement('div');
                card.className = 'card';

                const body = document.createElement('div');
                body.className = 'card-body';

                const titulo = document.createElement('h5');
                titulo.className = 'card-title';
                titulo.innerText = item.nome || '';
                body.appendChild(titulo);

                const pEmail = document.createElement('p');
                pEmail.className = 'card-text';
                pEmail.innerHTML = '<strong>Email:</strong> ' + (item.email || '');
                body.appendChild(pEmail);

                const pTel = document.createElement('p');
                pTel.className = 'card-text';
                pTel.innerHTML = '<strong>Telefone:</strong> ' + (item.telefone || '');
                body.appendChild(pTel);

                card.appendChild(body);

                const footer = document.createElement('div');
                footer.className = 'card-footer';
                // botão WhatsApp
                if (item.telefone) {
                    const digits = (item.telefone || '').replace(/\D+/g, '');
                    let wa = digits;
                    if (wa && !wa.startsWith('55')) wa = '55' + wa.replace(/^0+/, '');
                    const a = document.createElement('a');
                    a.className = 'card-btn';
                    a.href = 'https://wa.me/' + wa;
                    a.target = '_blank';
                    a.rel = 'noopener';
                    a.innerText = 'WhatsApp';
                    footer.appendChild(a);
                }

                card.appendChild(footer);
                grid.appendChild(card);
            });
        })
        .catch(err => {
            console.error(err);
            grid.innerHTML = '<p>Erro ao carregar contatos.</p>';
        });
});
