<?php
session_start();
if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['id_empresa'])) {
    header("Location: ../index.html");
    exit;
} 
?>