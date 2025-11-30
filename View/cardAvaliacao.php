<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Avaliação</title>
    <link href='https://cdn.boxicons.com/3.0.4/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <style>
        .card-avaliacao {
            position: relative;
            width: 260px;
            height: 140px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            font-family: Arial, Helvetica, sans-serif;
        }

        .ribbon {
            position: absolute;
            top: 12px;
            right: -36px;
            transform: rotate(45deg);
            background: #ff9800;
            color: #fff;
            padding: 6px 80px;
            font-weight: bold;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            cursor: pointer;
            user-select: none;
        }

        .ribbon:active { transform: rotate(45deg) scale(0.98); }

        .card-content { text-align: center; }

        /* Modal simples */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 320px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        .modal-buttons { margin-top: 14px; display:flex; gap:10px; justify-content:center; }
        .btn { padding:8px 14px; border-radius:6px; border: none; cursor: pointer; }
        .btn-cancel { background:#f0f0f0; }
        .btn-confirm { background:#1976d2; color:#fff; }
    </style>
</head>
<body>

    <div class="card-avaliacao" id="cardAvaliacao">
        <div class="card-content">
            <i class="bxr bx-star" style="font-size:36px;color:#ffb400"></i>
            <div style="margin-top:8px;font-weight:bold">Avaliações</div>
        </div>

        <div class="ribbon" id="ribbonAvaliacao">EM DESENVOLVIMENTO</div>
    </div>

    <!-- Modal de confirmação -->
    <div class="modal-backdrop" id="modalBackdrop" role="dialog" aria-hidden="true">
        <div class="modal" role="document" aria-labelledby="modalTitle">
            <div id="modalTitle" style="font-weight:700; margin-bottom:8px">Tem certeza?</div>
            <div>Essa funcionalidade ainda está em desenvolvimento. Deseja continuar?</div>
            <div class="modal-buttons">
                <button class="btn btn-cancel" id="modalNo">Não</button>
                <button class="btn btn-confirm" id="modalYes">Sim</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const ribbon = document.getElementById('ribbonAvaliacao');
            const modal = document.getElementById('modalBackdrop');
            const btnYes = document.getElementById('modalYes');
            const btnNo = document.getElementById('modalNo');
            const targetUrl = 'https://www.youtube.com/watch?v=Sagg08DrO5U&list=RDSagg08DrO5U&start_radio=1';

            function openModal() {
                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }

            ribbon.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
            });

            // also allow clicking the whole card to open modal (optional)
            const card = document.getElementById('cardAvaliacao');
            card.addEventListener('click', function (e) {
                // if user clicked on the ribbon itself, already handled
                if (e.target === ribbon) return;
                openModal();
            });

            btnNo.addEventListener('click', function () { closeModal(); });

            btnYes.addEventListener('click', function () {
                // redireciona para o link indicado
                window.location.href = targetUrl;
            });

            // fechar modal ao pressionar Esc
            document.addEventListener('keydown', function (ev) {
                if (ev.key === 'Escape') closeModal();
            });
        })();
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://cdn.boxicons.com/3.0.4/fonts/basic/boxicons.min.css' rel='stylesheet'>
<style>
.active{
    background-color: blue !important;
}

</style>
</head>
<body>


    <i class= "bxr bx-star estrela1"></i> 
</body>
</html>