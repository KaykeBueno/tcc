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
        
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-cog"></i>
                <span>Configurações</span>
            </a>
        </li>
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