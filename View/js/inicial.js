// Arquivo mínimo para evitar 404 quando páginas esperam por ele.
// Pode conter inicializações comuns (menu, handlers)
console.log('inicial.js loaded');

// Hambúrguer simples para abrir/fechar sidebar
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.hamburger-btn, .hamburger-btn-header').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.getElementById('sidebar').classList.toggle('open');
        });
    });

    // Pesquisa dinâmica de portfólios
    const input = document.getElementById('landingSearchInput');
    const resultsBox = document.getElementById('searchResults');
    if (!input || !resultsBox) return;

    let lastQuery = '';
    let debounceTimer = null;

    function escapeHtml(s) {
        return s ? String(s).replace(/[&"'<>]/g, function (m) { return ({'&':'&amp;','"':'&quot;',"'":"&#39;",'<':'&lt;','>':'&gt;'})[m]; }) : '';
    }

    function renderResults(items, q) {
        resultsBox.innerHTML = '';
        if (!items || items.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'search-empty';
            empty.textContent = q ? 'Nenhum resultado encontrado.' : '';
            resultsBox.appendChild(empty);
            return;
        }

        items.slice(0, 8).forEach(function(it){
            const a = document.createElement('a');
            a.href = '#';
            a.className = 'search-result-item';
            // título: nome do treino (treinoNome or nome)
            const title = document.createElement('div');
            title.className = 'search-result-title';
            title.innerHTML = escapeHtml(it.treinoNome || it.nome || it.nomeTreino || 'Treino');

            const subtitle = document.createElement('div');
            subtitle.className = 'search-result-sub';
            subtitle.textContent = (it.nomeFantasia ? it.nomeFantasia + ' — ' : '') + (it.descricao ? it.descricao : '');

            const meta = document.createElement('div');
            meta.className = 'search-result-meta';
            const partes = [];
            if (it.metodologia) partes.push('Metodologia: ' + it.metodologia);
            if (it.dificuldade) partes.push('Dificuldade: ' + it.dificuldade);
            meta.textContent = partes.join(' • ');

            a.appendChild(title);
            a.appendChild(subtitle);
            if (partes.length) a.appendChild(meta);

            // comportamento: ao clicar preenche input e fecha lista
            a.addEventListener('click', function (e) {
                e.preventDefault();
                input.value = title.textContent;
                resultsBox.innerHTML = '';
            });

            resultsBox.appendChild(a);
        });
    }

    function doSearch(q) {
        const trimmed = q.trim();
        if (trimmed === '') {
            renderResults([], '');
            return;
        }
        // evita chamadas repetidas
        if (trimmed === lastQuery) return;
        lastQuery = trimmed;

        // consulta a API de portifólios com parâmetro q
        const url = 'api/portifolios.php?q=' + encodeURIComponent(trimmed);
        fetch(url, { credentials: 'same-origin' })
            .then(function (res) { if (!res.ok) throw new Error('network'); return res.json(); })
            .then(function (json) { renderResults(json, trimmed); })
            .catch(function (err) { console.error('Busca falhou', err); renderResults([], trimmed); });
    }

    input.addEventListener('input', function (ev) {
        const q = ev.target.value || '';
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () { doSearch(q); }, 300);
    });

    // fecha lista ao clicar fora
    document.addEventListener('click', function (ev) {
        if (!resultsBox.contains(ev.target) && ev.target !== input) {
            resultsBox.innerHTML = '';
        }
    });
});
