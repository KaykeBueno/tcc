

        <main id="content">
            <div class="dashboard-grid">
                <a href="cardUsuario.php" class="dashboard-card">
                    <img src="../imagens/alunosIcone.png" alt="Ícone Alunos">
                    <h5>Alunos</h5>
                    <p>Gerencie seus alunos e acompanhe o desempenho de cada um com facilidade.</p>
                </a>

                <a href="cardPortifolio.php" class="dashboard-card">
                    <img src="../imagens/portifolioIcone.png" alt="Ícone Portfólio">
                    <h5>Portfólio</h5>
                    <p>Organize seus treinos, certificados e materiais em um só lugar.</p>
                </a>

                <div class="dashboard-card" id="cardAvaliacao" role="button" tabindex="0" aria-pressed="false" style="position:relative;">
                    <img src="../imagens/avaliacoesIcone.png" alt="Ícone Avaliações">
                    <h5>Avaliações</h5>
                    <p>Registre avaliações e acompanhe a dos seus alunos.</p>

                    <div class="ribbon" id="ribbonAvaliacao" style="position:absolute;top:12px;right:-36px;transform:rotate(45deg);background:var(--cor-destaque);color:var(--cor-principal);padding:6px 70px;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,0.12);user-select:none;cursor:pointer;">EM DESENVOLVIMENTO</div>
                </div>

                <a href="cardContatos.php" class="dashboard-card">
                    <img src="../imagens/chatIcone.png" alt="Ícone Chat">
                    <h5>Chat</h5>
                    <p>Comunique-se com seus professores de forma rápida e prática.</p>
                </a>

                <a href="https://wa.me/5543988223904" class="dashboard-card">
                    <img src="../imagens/SacIcone.png" alt="Ícone SAC">
                    <h5>SAC</h5>
                    <p>Entre em contato com o suporte para tirar dúvidas ou relatar problemas.</p>
                </a>
            </div>
        </main>
        <!-- Modal de confirmação para Avaliações (em desenvolvimento) -->
        <div class="modal-backdrop" id="modalAvaliacaoBackdrop" role="dialog" aria-hidden="true" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;z-index:9999;">
            <div class="modal" role="document" aria-labelledby="modalAvaliacaoTitle" style="background:#fff;padding:20px;border-radius:8px;width:320px;box-shadow:0 6px 20px rgba(0,0,0,0.2);text-align:center;color:#000;">
                <div id="modalAvaliacaoTitle" style="font-weight:700;margin-bottom:8px">Tem certeza?</div>
                <div>Essa funcionalidade ainda está em desenvolvimento. Deseja continuar?</div>
                <div class="modal-buttons" style="margin-top:14px;display:flex;gap:10px;justify-content:center;">
                    <button class="btn btn-cancel" id="modalAvaliacaoNo" style="padding:8px 14px;border-radius:6px;border:1px solid #ccc;background:#f0f0f0;cursor:pointer;">Não</button>
                    <button class="btn btn-confirm" id="modalAvaliacaoYes" style="padding:8px 14px;border-radius:6px;border:none;background:#1976d2;color:#fff;cursor:pointer;">Sim</button>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const ribbon = document.getElementById('ribbonAvaliacao');
                const card = document.getElementById('cardAvaliacao');
                const modal = document.getElementById('modalAvaliacaoBackdrop');
                const btnYes = document.getElementById('modalAvaliacaoYes');
                const btnNo = document.getElementById('modalAvaliacaoNo');
                // link fornecido pelo usuário
                const targetUrl = 'https://www.youtube.com/watch?v=Sagg08DrO5U&list=RDSagg08DrO5U&start_radio=1';

                function openModal() {
                    modal.style.display = 'flex';
                    modal.setAttribute('aria-hidden', 'false');
                }
                function closeModal() {
                    modal.style.display = 'none';
                    modal.setAttribute('aria-hidden', 'true');
                }

                if (ribbon) ribbon.addEventListener('click', function (e) { e.preventDefault(); openModal(); });
                if (card) {
                    card.addEventListener('click', function (e) {
                        // ev   ita duplo disparo quando clica na tarja
                        if (e.target && e.target.id === 'ribbonAvaliacao') return;
                        openModal();
                    });
                    // suporte teclado (Enter / Space)
                    card.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(); }
                    });
                }

                if (btnNo) btnNo.addEventListener('click', function () { closeModal(); });
                if (btnYes) btnYes.addEventListener('click', function () { window.location.href = targetUrl; });

                // fechar com ESC
                document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape') closeModal(); });
                // fechar clicando fora do modal (no backdrop)
                if (modal) modal.addEventListener('click', function (ev) { if (ev.target === modal) closeModal(); });
            })();
        </script>