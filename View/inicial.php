<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IGYM - Seja Seu Melhor</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="js/inicial.js" defer></script>
    <script src="js/util.js" defer></script>
</head>

<body class="page-landing">
    <div class="wrapper">

        <nav id="sidebar">
            <div class="sidebar-header">
                <button type="button" class="menu-hamburguer-flutuante" aria-label="Abrir menu" onclick="exibeMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
                <div class="user-profile">
                    <img src="https://i.imgur.com/kDwRGt2.png" alt="Foto do Usuário">
                    <div class="user-info">
                        <h6><?= isset($_SESSION["id_usuario"]) ? $_SESSION["nome"] : $_SESSION["nomeFantasia"]?></h6>
                        <span><?= isset($_SESSION["id_usuario"]) ? "Aluno(a)" : "Profissional(a)" ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="sidebar-login">
                     <a href="login.html" class="btn-login-sidebar">Fazer Login</a>
                 </div>
            <?php endif; ?>

            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="inicial.php">
                        <i class="fas fa-home"></i>
                        <span>Inicial</span>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="perfil.php">
                        <i class="fas fa-user"></i>
                        <span>Meu Perfil</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="configuracoes.php">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

             <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
            <div class="sidebar-footer">
                <a href="sair.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </a>
            </div>
            <?php endif; ?>
        </nav>

        <main id="content">
            
            <header class="landing-header">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" class="landing-search-bar" placeholder="Busque por treinos, profissionais ou dicas...">
                </div>
            </header>
            <section class="hero">
                <div class="hero-content">
                    <h1>SEJA SEU MELHOR</h1>
                    <a href="#" class="btn-primary">COMECE AGORA</a>
                </div>
            </section>

             <section class="sobre">
                <div class="container">
                    <div class="container-grid">
                        <div class="sobre-texto">
                            <h2>SOBRE A NOSSA EMPRESA</h2>
                            <p>A IGym foi fundada em 2025 por Luma Pavan, Bruna Hazama, Derik Kauã, Kayke Bueno e Davi, Ramalho...
                                Acreditamos que a tecnologia pode melhorar a qualidade de vida por meio da prática de exercícios
                                físicos de alta performance.</p>
                            <a href="#" class="link-mais">leia mais</a>
                        </div>
                        <div class="sobre-imagens">
                            <img src="../imagens/homemTreinandoBoxe.png" alt="Homem treinando boxe">
                            <img src="../imagens/mulherTreinandoCordas.png" alt="Mulher treinando com cordas">
                        </div>
                    </div>
                </div>
            </section>

             <section class="oferta">
                <div class="container">
                    <h2>O QUE OFERECEMOS</h2>
                    <p class="subtitulo">Estamos empenhados em trazer a melhor experiência e mais.</p>
                    <div class="oferta-grid">
                        <div class="oferta-card" style="background-image: url('../imagens/equipamentosPonta.png');">
                            <h3>EQUIPAMENTOS DE PONTA</h3>
                        </div>
                        <div class="oferta-card" style="background-image: url('../imagens/aulasParticulares.png');">
                            <h3>AULAS PARTICULARES</h3>
                        </div>
                        <div class="oferta-card" style="background-image: url('../imagens/personais.png');">
                            <h3>PERSONAL TRAINER</h3>
                        </div>
                    </div>
                </div>
            </section>

            <section class="depoimentos">
                <div class="container">
                    <h2>ANTES E DEPOIS</h2>
                    <div class="depoimentos-grid">
                        <div class="depoimento-card">
                            <img src="../imagens/lauraBorges.png" alt="Foto de Laura Borges">
                            <div class="depoimento-texto">
                                <p>"A IGym transformou minha rotina! A estrutura é ótima e os instrutores super atenciosos."</p>
                                <span>Laura Borges, 31</span>
                            </div>
                        </div>
                        <div class="depoimento-card">
                            <img src="../imagens/luanaCruz.png" alt="Foto de Luana Cruz">
                            <div class="depoimento-texto">
                                <p>"Não troco por nada! Professores são muito explicativos. Eu era muito sedentária..."</p>
                                <span>Luana Cruz, 38</span>
                            </div>
                        </div>
                        <div class="depoimento-card">
                            <img src="../imagens/tomasCosta.png" alt="Foto de Tomas Costa">
                            <div class="depoimento-texto">
                                <p>"Consegui alcançar meus objetivos em 6 meses de plano. Alto nível de profissionalismo!"</p>
                                <span>Tomas Costa, 44</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="contato-cta">
                 <div class="container">
                    <h2>ENTRE EM CONTATO AINDA HOJE</h2>
                 </div>
            </section>

            <footer class="rodape">
                 <div class="container">
                    <div class="container-grid-footer">
                        <div class="rodape-info">
                            <strong>ENDEREÇO</strong>
                            <span>Rua Portugal, 123, Cidade - PR</span>
                        </div>
                        <div class="rodape-info">
                            <strong>E-MAIL</strong>
                            <span>igymcontato@gmail.com</span>
                        </div>
                        <div class="rodape-info">
                            <strong>TELEFONE</strong>
                            <span>(43) 3456-7890</span>
                        </div>
                    </div>
                 </div>
            </footer>
        </main> 
    </div> 
</body>
</html>