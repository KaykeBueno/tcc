<?php
// Endpoint que retorna usuários em JSON — comportamento similar a View/api/empresas.php

require_once(__DIR__ . '/../../DAOS/UsuarioDAO.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$dao = new UsuarioDAO();
try {
    // Se for pedido apenas 'meus' (usuários vinculados à empresa logada)
    // ex.: api/usuarios.php?meus=1
    if (isset($_GET['meus']) && $_GET['meus'] == '1') {
        // Apenas empresas (tipo 'empresa') podem pedir seus usuários vinculados
        if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'empresa' || !isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Empresa não autenticada ou tipo incorreto'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $idEmpresa = $_SESSION['id_empresa'];
        $usuarios = $dao->selecionarPorEmpresa($idEmpresa);
    } else {
        $usuarios = $dao->selecionarTodos();
    }

    if (!$usuarios) {
        $usuarios = [];
    }

    echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    $error = [
        'error' => 'Erro ao obter usuários',
        'message' => $e->getMessage()
    ];
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}
