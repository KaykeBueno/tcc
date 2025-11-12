<?php
// Endpoint simples que retorna todas as empresas em JSON.
// Nota: em produção seria melhor colocar esse código em um Controller
// e adicionar autenticação/validação e cache/paginação.

// Usa __DIR__ para construir caminho absoluto relativo ao arquivo atual.
require_once(__DIR__ . '/../../DAOS/EmpresaDAO.php');

// Inicia sessão para permitir checar o usuário logado (necessário para 'meus')
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o tipo de conteúdo para JSON UTF-8
header('Content-Type: application/json; charset=utf-8');

$dao = new EmpresaDAO();
try {
    // Se for pedido apenas 'meus' professores, retornamos empresas vinculadas
    // ao usuário logado via Treino_has_Usuario. Ex.: api/empresas.php?meus=1
    if (isset($_GET['meus']) && $_GET['meus'] == '1') {
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $idUsuario = $_SESSION['id_usuario'];
        $empresas = $dao->selecionarPorUsuario($idUsuario);
    } else {
        $empresas = $dao->selecionarTodos();
    }

    // Garantir que sempre retornamos um array (evita null no JSON)
    if (!$empresas) {
        $empresas = [];
    }

    echo json_encode($empresas, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    // Em caso de erro, retorne JSON com a mensagem para facilitar o debug
    http_response_code(500);
    $error = [
        'error' => 'Erro ao obter empresas',
        'message' => $e->getMessage()
    ];
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}
