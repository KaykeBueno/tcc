<?php
// Retorna lista de treinos (id e nome) em JSON
require_once(__DIR__ . '/../../DAOS/BaseDAO.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$dao = new BaseDAO();
try {
    // Se a tabela estiver vazia, popular com treinos padrão
    $countSql = "SELECT COUNT(*) as cnt FROM treino";
    $countStmt = $dao->executar($countSql);
    $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);
    $cnt = intval($countRow['cnt'] ?? 0);

    if ($cnt === 0) {
        $defaults = [
            'Bíceps', 'Tríceps', 'Peito', 'Costas', 'Pernas', 'Abdômen', 'Cardio', 'Full Body', 'HIIT', 'Alongamento'
        ];

        $insertSql = "INSERT INTO treino (nome) VALUES (:nome)";
        foreach ($defaults as $name) {
            $dao->executaComParametros($insertSql, [':nome' => $name]);
        }
    }

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
