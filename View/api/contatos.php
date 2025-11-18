<?php
// API pública para fornecer contatos em JSON (substitui o Controller/ChatController)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

require_once(__DIR__ . '/../../DAOS/UsuarioDAO.php');
require_once(__DIR__ . '/../../DAOS/EmpresaDAO.php');
require_once(__DIR__ . '/../../DAOS/BaseDAO.php');

header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) session_start();

$usuarioDao = new UsuarioDAO();
$empresaDao = new EmpresaDAO();
$base = new BaseDAO();

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$empresaId = isset($_GET['empresa_id']) ? intval($_GET['empresa_id']) : null;
$treinoId = isset($_GET['treino_id']) ? intval($_GET['treino_id']) : null;

try {
    // contato específico por user_id
    if ($userId) {
        $sql = "SELECT id_usuario AS id, nome, email, telefone FROM usuario WHERE id_usuario = :id";
        $stmt = $base->executaComParametros($sql, [':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) $row['tipo'] = 'usuario';
        echo json_encode($row ?: [], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // contato específico por empresa_id
    if ($empresaId) {
        $sql = "SELECT id_empresa AS id, nomeFantasia AS nome, email, telefone FROM empresa WHERE id_empresa = :id";
        $stmt = $base->executaComParametros($sql, [':id' => $empresaId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) $row['tipo'] = 'empresa';
        echo json_encode($row ?: [], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // se sessão tem tipo empresa: listamos usuários vinculados
    if (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'empresa' && isset($_SESSION['id_empresa'])) {
        $empresaSess = $_SESSION['id_empresa'];
        if ($treinoId) {
            $sql = "SELECT DISTINCT u.id_usuario AS id, u.nome, u.email, u.telefone
                    FROM usuario u
                    INNER JOIN PlanoTreino pt ON pt.usuario_id_usuario = u.id_usuario
                    INNER JOIN portifolio p ON p.idPortifolio = pt.portifolio_idPortifolio
                    WHERE p.empresa_id_empresa = :empresaId AND p.treino_idTreino = :treinoId";
            $params = [':empresaId' => $empresaSess, ':treinoId' => $treinoId];
            $stmt = $base->executaComParametros($sql, $params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$r) $r['tipo'] = 'usuario';
            echo json_encode($rows, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $usuarios = $usuarioDao->selecionarPorEmpresa($empresaSess);
            $out = [];
            foreach ($usuarios as $u) {
                $out[] = [
                    'id' => $u->getId_usuario(),
                    'nome' => $u->getNome(),
                    'email' => $u->getEmail(),
                    'telefone' => $u->getTelefone(),
                    'tipo' => 'usuario'
                ];
            }
            echo json_encode($out, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // se sessão tem tipo usuario: listamos empresas vinculadas
    if (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'usuario' && isset($_SESSION['id_usuario'])) {
        $usuarioSess = $_SESSION['id_usuario'];
        if ($treinoId) {
            $sql = "SELECT DISTINCT e.id_empresa AS id, e.nomeFantasia AS nome, e.email, e.telefone
                    FROM empresa e
                    INNER JOIN portifolio p ON p.empresa_id_empresa = e.id_empresa
                    INNER JOIN PlanoTreino pt ON pt.portifolio_idPortifolio = p.idPortifolio
                    WHERE pt.usuario_id_usuario = :usuarioId AND p.treino_idTreino = :treinoId";
            $params = [':usuarioId' => $usuarioSess, ':treinoId' => $treinoId];
            $stmt = $base->executaComParametros($sql, $params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$r) $r['tipo'] = 'empresa';
            echo json_encode($rows, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $empresas = $empresaDao->selecionarPorUsuario($usuarioSess);
            $out = [];
            foreach ($empresas as $e) {
                $out[] = [
                    'id' => $e->getId_empresa(),
                    'nome' => $e->getNomeFantasia(),
                    'email' => $e->getEmail(),
                    'telefone' => $e->getTelefone(),
                    'tipo' => 'empresa'
                ];
            }
            echo json_encode($out, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // Se nenhum caso aplicou, retorna array vazio
    echo json_encode([], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao listar contatos', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

?>
