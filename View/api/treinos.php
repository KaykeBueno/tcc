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
            ['nome' => 'Bíceps', 'descricao' => 'Treinos focados em desenvolvimento do bíceps e antebraço.'],
            ['nome' => 'Tríceps', 'descricao' => 'Exercícios para fortalecer tríceps e estabilidade do braço.'],
            ['nome' => 'Peito', 'descricao' => 'Séries para hipertrofia e força do peitoral.'],
            ['nome' => 'Costas', 'descricao' => 'Treinos para latíssimos, trapézio e lombar.'],
            ['nome' => 'Pernas', 'descricao' => 'Agachamentos, leg press e exercícios para quadríceps e posteriores.'],
            ['nome' => 'Abdômen', 'descricao' => 'Exercícios localizados para core e estabilidade.'],
            ['nome' => 'Cardio', 'descricao' => 'Sessões aeróbicas para condicionamento e queima de gordura.'],
            ['nome' => 'Full Body', 'descricao' => 'Treinos compostos que trabalham todo o corpo.'],
            ['nome' => 'HIIT', 'descricao' => 'Treinos intervalados de alta intensidade.'],
            ['nome' => 'Alongamento', 'descricao' => 'Rotinas para flexibilidade e recuperação.']
        ];

        $insertSql = "INSERT INTO treino (nome, descricao) VALUES (:nome, :descricao)";
        foreach ($defaults as $d) {
            $dao->executaComParametros($insertSql, [':nome' => $d['nome'], ':descricao' => $d['descricao']]);
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
