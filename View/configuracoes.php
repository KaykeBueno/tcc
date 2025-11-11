<?php
// Protege a página, exigindo login
require_once("validaLogin.php"); //
// O validaLogin.php já inicia a sessão
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - IGym</title>

    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="js/inicial.js" defer></script> <script src="js/configuracoes.js" defer></script>
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
            <?php else: ?>
                <div class="sidebar-login">
                    <a href="login.html" class="btn-login-sidebar">Fazer Login</a>
                </div>
            <?php endif; ?>

            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="inicial.php">
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
                    <a class="nav-link active" href="configuracoes.php">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <?php if (isset($_SESSION['id_usuario']) || isset($_SESSION['id_empresa'])): ?>
            <div class="sidebar-footer">
                <a href="sair.php" class="nav-link"> <i class="fas fa-sign-out-alt"></i> <span>Sair</span> </a>
            </div>
            <?php endif; ?>
        </nav>
        
        <main id="content">
            
            <header class="navbar">
                <button class="hamburger-btn-header"><i class="fas fa-bars"></i></button>
                <a href="#" class="logo-header-only">CONFIGURAÇÕES</a>
            </header>
            
            <div class="config-container" v-scope="ConfigApp">
                <h1 class="config-title">CONFIGURAÇÕES</h1>

                <section class="config-section">
                    <h2 class="config-subtitle"><i class="fas fa-user-circle"></i> DADOS PESSOAIS</h2>
                    <div class="config-card">
                        <div class="config-row">
                            <label>INFORMAÇÕES DE CONTATO</label>
                            <div class="info-box-group">
                                <span class="info-box"><?= isset($_SESSION["email"]) ? $_SESSION["email"] : "igym123@gmail.com" ?></span>
                                <span class="info-box"><?= isset($_SESSION["telefone"]) ? $_SESSION["telefone"] : "+5512345678910" ?></span>
                                <a href="#" @click.prevent="alterarContato" class="config-link-alterar">ALTERAR</a>
                            </div>
                        </div>
                        <div class="config-row">
                            <label>DATA DE NASCIMENTO</label>
                            <span class="info-text"><?= isset($_SESSION["dataNascimento"]) ? $_SESSION["dataNascimento"] : "01/01/2001" ?></span>
                        </div>
                    </div>
                </section>

                <section class="config-section">
                    <h2 class="config-subtitle"><i class="fas fa-lock"></i> SENHA E SEGURANÇA</h2>
                    <div class="config-card">
                        <h3>ALTERAR SENHA</h3>
                        <form @submit.prevent="alterarSenha">
                            <div class="form-grid-senha"> 
                                <input type="password" v-model="formData.senhaAtual" placeholder="Senha Atual" class="config-input">
                                <input type="password" v-model="formData.novaSenha" placeholder="Nova Senha" class="config-input">
                                <input type="password" v-model="formData.confirmarNovaSenha" placeholder="Redigite a nova senha" class="config-input">
                            </div>
                            <div class="form-footer">
                                <a href="#" class="config-link">esqueceu a senha?</a>
                                <button type="submit" class="config-btn-salvar">ALTERAR SENHA</button>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="config-section-final">
                    <a href="sair.php" class="config-link-sair">SAIR</a>
                    <a href="#" @click.prevent="excluirConta" class="config-link-excluir">EXCLUIR CONTA</a>
                </section>

            </div> 
        </main> 
    </div>
</body>
</html>