<?php
// Endpoint para criar um novo portifólio vinculado à empresa logada
require_once(__DIR__ . '/../../DAOS/PortifolioDao.php');
require_once(__DIR__ . '/../../DAOS/BaseDAO.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

try {
    // Se for GET, retornamos a lista de portfólios (com JOIN para trazer dados da empresa e do treino)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $base = new BaseDAO();

        // filtro meus=1 -> portfólios da empresa logada
        if (isset($_GET['meus']) && $_GET['meus'] == '1') {
            if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'empresa' || !isset($_SESSION['id_empresa'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Empresa não autenticada ou tipo incorreto'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            // Retorna os treinos que estão no portifólio desta empresa
            $empresaId = $_SESSION['id_empresa'];
                $sql = "SELECT p.idPortifolio, t.idTreino AS id, t.nome, p.descricao AS descricao
                    FROM portifolio p
                    INNER JOIN treino t ON t.idTreino = p.treino_idTreino
                    WHERE p.empresa_id_empresa = :empresaId";
            $stmt = $base->executaComParametros($sql, [':empresaId' => $empresaId]);
        } else {
            // Lista geral: mantemos informação completa (empresa + treino)
                $sql = "SELECT p.idPortifolio, t.idTreino AS id, t.nome AS treinoNome, p.descricao AS descricao, e.nomeFantasia, e.atividadeEconomica, e.telefone, e.email
                    FROM portifolio p
                    INNER JOIN treino t ON t.idTreino = p.treino_idTreino
                    INNER JOIN empresa e ON e.id_empresa = p.empresa_id_empresa";
            $stmt = $base->executar($sql);
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) $rows = [];
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Caso contrário (POST), criamos um novo portifólio
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Empresa não autenticada'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['treino_idTreino'])) {
            http_response_code(400);
            echo json_encode(['error' => 'treino_idTreino é obrigatório'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $empresaId = $_SESSION['id_empresa'];
        $treinoId = $input['treino_idTreino'];
        $descricao = isset($input['descricao']) ? $input['descricao'] : '';

        $portifolio = new Portifolio(null, $empresaId, $treinoId, $descricao);
        $dao = new PortifolioDAO();
        $newId = $dao->inserir($portifolio);

        echo json_encode(['success' => true, 'id' => $newId], JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao criar portifólio', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

?>
