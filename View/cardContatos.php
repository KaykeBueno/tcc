<?php
require_once('validaLogin.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Contatos - IGym</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="page-dashboard">
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="inicial.php" class="sidebar-logo"><span>?</span> IGym</a>
                <button class="hamburger-btn">&#9776;</button>
            </div>
            <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
            <div class="user-profile">
                <img src="https://i.imgur.com/kDwRGt2.png" alt="Foto do Usuário">
                <div class="user-info">
                    <h6><?= isset($_SESSION["id_usuario"]) ? $_SESSION["nome"] : $_SESSION["nomeFantasia"]?></h6>
                    <span><?= isset($_SESSION["id_usuario"]) ? "Aluno(a)" : "Profissional(a)" ?></span>
                </div>
            </div>
            <?php endif; ?>
            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item"><a class="nav-link" href="inicial.php"><i class="fas fa-home"></i><span>Inicial</span></a></li>
                <li class="nav-item"><a class="nav-link" href="perfil.php"><i class="fas fa-user"></i><span>Meu Perfil</span></a></li>
                <li class="nav-item"><a class="nav-link" href="configuracoes.php"><i class="fas fa-cog"></i><span>Configurações</span></a></li>
            </ul>
        </nav>

        <main id="content">
            <header class="navbar">
                <a href="perfil.php">&larr; Voltar</a>
                <h2 style="display:inline-block;margin-left:20px;">Contatos</h2>
            </header>

            <div class="dashboard-grid" id="dashboard-grid">
                <!-- Cards serão carregados pelo JS (cardContatos.js) -->
            </div>
        </main>
    </div>

    <script src="js/inicial.js" defer></script>
    <script src="js/cardContatos.js" defer></script>
</body>
</html>
