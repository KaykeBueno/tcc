<?php
require_once("validaLogin.php"); //

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - IGym</title>

    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> 
    <script src="https://unpkg.com/petite-vue" defer init></script> 
    <script src="js/util.js" defer></script> 
</head>
<body class="page-dashboard"> <div class="wrapper"> <nav id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-header">
            <button type="button" class="menu-hamburguer-flutuante" aria-label="Abrir menu" onclick="exibeMenu()">
            <span></span>
            <span></span>
            <span></span>
            </button>
</div>

            <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?> <div class="user-profile">
                    <img src="https://i.imgur.com/kDwRGt2.png" alt="Foto do Usuário"> <div class="user-info">
                        <h6><?= isset($_SESSION["id_usuario"]) ? $_SESSION["nome"] : $_SESSION["nomeFantasia"]?></h6> <span><?= isset($_SESSION["id_usuario"]) ? "Aluno(a)" : "Profissional(a)" ?></span> </div>
                </div>
            <?php endif; ?>

            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="inicial.php"> <i class="fas fa-home"></i>
                        <span>Inicial</span>
                    </a>
                </li>
                
                <li class="nav-item">
                     <a class="nav-link active" href="perfil.php">
                        <i class="fas fa-user"></i>
                        <span>Meu Perfil</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="configuracoes.php">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
            </ul>

            
            <button type="button" class="menu-hamburguer-flutuante" aria-label="Abrir menu" onclick="exibeMenu()">
            <span></span>
            <span></span>
            <span></span>
            </button>

            <div class="sidebar-footer">
                <a href="sair.php" class="nav-link"> <i class="fas fa-sign-out-alt"></i> <span>Sair</span> </a>
            </div>
        </nav>
        <header class="navbar"> 
                <section>
                    <!-- Botões para filtrar os cards; IDs usados pelo JS -->
                    <button id="btn-todos" type="button">Todos os Alunos</button>
                    <button id="btn-meus" type="button">Meus Alunos</button>
                </section>
            </header>
        <main id="content">
            <div class="dashboard-grid" id="dashboard-grid">
                <!--
                    Container vazio onde os cards serão inseridos dinamicamente pelo
                    script `js/cardsDados.js`. Mantivemos a renderização via JS para
                    UX dinâmica.
                -->
            </div>
    </div>
</body>
</html>
<script src="js/cardsDados.js" defer></script>