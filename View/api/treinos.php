<?php
// Retorna lista de treinos (id e nome) em JSON
require_once(__DIR__ . '/../../DAOS/BaseDAO.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$dao = new BaseDAO();
try {
    $sql = "SELECT idTreino AS id, nome FROM treino";
    $stmt = $dao->executar($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) $rows = [];

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao listar treinos', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

?>
