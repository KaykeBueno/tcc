<?php
require_once("validaLogin.php"); 
// O validaLogin.php já tem o session_start()
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - IGym</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="js/inicial.js" defer></script> </head>

<body class="page-dashboard">
    <div class="wrapper">
        
        <?php require_once 'sidebar.php'; ?>

        <?php 
            if(isset($_SESSION['id_usuario']))
            {
                require_once 'perfilUsuario.php'; 
            }
            else {
                require_once 'perfilEmpresa.php'; 
            }
        ?>

    </div>
</body>
</html>