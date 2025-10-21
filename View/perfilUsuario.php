<?php
require_once("validaLogin.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - IGym</title>

    <link rel="stylesheet" href="style-inicial.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-logo"><span>?</span> IGym</a>
                <button class="hamburger-btn">&#9776;</button>
            </div>

            <div class="user-profile">
                <img src="https://i.imgur.com/kDwRGt2.png" alt="Foto do Usuário">
                <div class="user-info">
                    <h6>Maria da Silva</h6>
                    <span>Aluno(a)</span>
                </div>
            </div>

            <ul class="nav flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-home"></i>
                        <span>Inicial</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i>
                        <span>Configurações</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="#" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </a>
            </div>
        </nav>

        <main id="content">
            <div class="dashboard-grid">
                <a href="#" class="dashboard-card">
                    <img src="../imagens/ProfessorIcone.png" alt="Ícone Professores">
                    <h5>Professores</h5>
                    <p>Visualize seus professores e acesse informações sobre seus responsáveis pelos treinos.</p>
                </a>

                <a href="#" class="dashboard-card">
                    <img src="../imagens/CalendarioTreinoIcone.png" alt="Ícone Treinos">
                    <h5>Treinos</h5>
                    <p>Acompanhe seus treinos e veja os exercícios recomendados para você.</p>
                </a>

                <a href="#" class="dashboard-card">
                    <img src="../imagens/quizIcone.png" alt="Ícone Quiz">
                    <h5>Quiz</h5>
                    <p>Responda perguntas e receba indicações de esportes ideais para o seu perfil e estilo de vida.</p>
                </a>

                <a href="#" class="dashboard-card">
                    <img src="../imagens/chatIcone.png" alt="Ícone Chat">
                    <h5>Chat</h5>
                    <p>Comunique-se com seus professores de forma rápida e prática.</p>
                </a>

                <a href="#" class="dashboard-card">
                    <img src="../imagens/calendarioFrequenciaIcone.png" alt="Ícone Frequência">
                    <h5>Frequência</h5>
                    <p>Acompanhe sua presença nas aulas e veja seu histórico de participação.</p>
                </a>

                <a href="#" class="dashboard-card">
                    <img src="../imagens/SacIcone.png" alt="Ícone SAC">
                    <h5>SAC</h5>
                    <p>Entre em contato com o suporte para tirar dúvidas ou relatar problemas.</p>
                </a>
            </div>
        </main>
    </div>

</body>

</html>