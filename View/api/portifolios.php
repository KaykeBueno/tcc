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

        // filtro meus=1 -> portifólios da empresa logada
        if (isset($_GET['meus']) && $_GET['meus'] == '1') {
            if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'empresa' || !isset($_SESSION['id_empresa'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Empresa não autenticada ou tipo incorreto'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            // Retorna os treinos que estão no portifólio desta empresa
            $empresaId = $_SESSION['id_empresa'];
                $sql = "SELECT p.idPortifolio, t.idTreino AS id, t.nome, p.descricao AS descricao, p.metodologia AS metodologia, p.dificuldade AS dificuldade
                    FROM portifolio p
                    INNER JOIN treino t ON t.idTreino = p.treino_idTreino
                    WHERE p.empresa_id_empresa = :empresaId";
            $stmt = $base->executaComParametros($sql, [':empresaId' => $empresaId]);
        } else {
            // Suporta busca por q=texto — procura em treino, descricao, metodologia, dificuldade e nomeFantasia
            if (isset($_GET['q']) && trim($_GET['q']) !== '') {
                $q = '%' . trim($_GET['q']) . '%';
                $sql = "SELECT p.idPortifolio, t.idTreino AS id, t.nome AS treinoNome, p.descricao AS descricao, p.metodologia AS metodologia, p.dificuldade AS dificuldade, e.nomeFantasia, e.atividadeEconomica, e.telefone, e.email
                    FROM portifolio p
                    INNER JOIN treino t ON t.idTreino = p.treino_idTreino
                    INNER JOIN empresa e ON e.id_empresa = p.empresa_id_empresa
                    WHERE t.nome LIKE :q OR p.descricao LIKE :q OR p.metodologia LIKE :q OR p.dificuldade LIKE :q OR e.nomeFantasia LIKE :q OR e.atividadeEconomica LIKE :q";
                $stmt = $base->executaComParametros($sql, [':q' => $q]);
            } else {
                // Lista geral: mantemos informação completa (empresa + treino)
                $sql = "SELECT p.idPortifolio, t.idTreino AS id, t.nome AS treinoNome, p.descricao AS descricao, p.metodologia AS metodologia, p.dificuldade AS dificuldade, e.nomeFantasia, e.atividadeEconomica, e.telefone, e.email
                    FROM portifolio p
                    INNER JOIN treino t ON t.idTreino = p.treino_idTreino
                    INNER JOIN empresa e ON e.id_empresa = p.empresa_id_empresa";
                $stmt = $base->executar($sql);
            }
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) $rows = [];
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Caso contrário (POST), criamos um novo portifólio
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificação básica de sessão
        if (!isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            $resp = ['error' => 'Empresa não autenticada', 'session' => $_SESSION];
            // grava log temporário para diagnóstico
            @file_put_contents(__DIR__ . '/../../debug_portifolio.log', date('c') . " - 401 no POST - " . json_encode($resp, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            echo json_encode($resp, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $inputRaw = file_get_contents('php://input');
        $input = json_decode($inputRaw, true);

        if (!$input || !isset($input['treino_idTreino'])) {
            http_response_code(400);
            $resp = ['error' => 'treino_idTreino é obrigatório', 'payload_raw' => $inputRaw, 'payload' => $input, 'session' => $_SESSION];
            @file_put_contents(__DIR__ . '/../../debug_portifolio.log', date('c') . " - 400 payload inválido - " . json_encode($resp, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            echo json_encode($resp, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $empresaId = $_SESSION['id_empresa'];
        $treinoId = $input['treino_idTreino'];
        $descricao = isset($input['descricao']) ? $input['descricao'] : '';
        $metodologia = isset($input['metodologia']) ? $input['metodologia'] : '';
        $dificuldade = isset($input['dificuldade']) ? $input['dificuldade'] : '';

        try {
            $portifolio = new Portifolio(null, $empresaId, $treinoId, $descricao, $metodologia, $dificuldade);
            $dao = new PortifolioDAO();
            $newId = $dao->inserir($portifolio);

            $resp = ['success' => true, 'id' => $newId, 'session' => $_SESSION, 'payload' => $input];
            @file_put_contents(__DIR__ . '/../../debug_portifolio.log', date('c') . " - INSERT OK - " . json_encode($resp, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            echo json_encode($resp, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            http_response_code(500);
            $resp = ['error' => 'Erro ao inserir portifolio', 'message' => $e->getMessage(), 'session' => $_SESSION, 'payload' => $input];
            @file_put_contents(__DIR__ . '/../../debug_portifolio.log', date('c') . " - INSERT ERROR - " . json_encode($resp, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            echo json_encode($resp, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao criar portifólio', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

?>
