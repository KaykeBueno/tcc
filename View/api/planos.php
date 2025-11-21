<?php
// API para listar planos (com dados do treino e da empresa)
require_once(__DIR__ . '/../../DAOS/BaseDAO.php');

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');

$base = new BaseDAO();

try {
    if (isset($_GET['meus']) && $_GET['meus'] == '1') {
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuário não autenticado'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $usuarioId = $_SESSION['id_usuario'];
        $sql = "SELECT pt.idPlano, p.idPortifolio, t.idTreino AS idTreino, t.nome AS nome, p.descricao AS descricao, e.id_empresa, e.nomeFantasia, e.telefone, e.email
                FROM PlanoTreino pt
                INNER JOIN portifolio p ON pt.portifolio_idPortifolio = p.idPortifolio
                INNER JOIN treino t ON p.treino_idTreino = t.idTreino
                INNER JOIN empresa e ON p.empresa_id_empresa = e.id_empresa
                WHERE pt.usuario_id_usuario = :usuarioId";
        $stmt = $base->executaComParametros($sql, [':usuarioId' => $usuarioId]);
    } elseif (isset($_GET['usuario_id'])) {
        $usuarioId = intval($_GET['usuario_id']);
        $sql = "SELECT pt.idPlano, p.idPortifolio, t.idTreino AS idTreino, t.nome AS nome, p.descricao AS descricao, e.id_empresa, e.nomeFantasia, e.telefone, e.email
                FROM PlanoTreino pt
                INNER JOIN portifolio p ON pt.portifolio_idPortifolio = p.idPortifolio
                INNER JOIN treino t ON p.treino_idTreino = t.idTreino
                INNER JOIN empresa e ON p.empresa_id_empresa = e.id_empresa
                WHERE pt.usuario_id_usuario = :usuarioId";
        $stmt = $base->executaComParametros($sql, [':usuarioId' => $usuarioId]);
    } else {
        $sql = "SELECT pt.idPlano, p.idPortifolio, t.idTreino AS idTreino, t.nome AS nome, p.descricao AS descricao, e.id_empresa, e.nomeFantasia, e.telefone, e.email
                FROM PlanoTreino pt
                INNER JOIN portifolio p ON pt.portifolio_idPortifolio = p.idPortifolio
                INNER JOIN treino t ON p.treino_idTreino = t.idTreino
                INNER JOIN empresa e ON p.empresa_id_empresa = e.id_empresa";
        $stmt = $base->executar($sql);
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) $rows = [];
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao listar planos', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

?>
