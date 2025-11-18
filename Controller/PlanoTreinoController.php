<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);

require_once("../DAOS/PlanoTreinoDAO.php");
require_once("../entities/PlanoTreino.php");
require_once("../DAOS/PortifolioDao.php");

class PlanoTreinoController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new PlanoTreinoDAO();
    }

    /**
     * Lista planos. Se ?meus=1 e usuário autenticado, lista apenas os planos do usuário.
     */
    public function listar()
    {
        header('Content-Type: application/json');
        session_start();

        $meus = isset($_GET['meus']) ? intval($_GET['meus']) : 0;
        $userId = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : null;

        if ($meus === 1) {
            if (!isset($_SESSION['id_usuario'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Usuário não autenticado']);
                return;
            }
            $id = $_SESSION['id_usuario'];
            $planos = $this->dao->selecionarPorUsuario($id);
            echo json_encode($planos, JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($userId) {
            $planos = $this->dao->selecionarPorUsuario($userId);
            echo json_encode($planos, JSON_UNESCAPED_UNICODE);
            return;
        }

        // por padrão retorna todos
        $planos = $this->dao->selecionarTodos();
        echo json_encode($planos, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Insere um plano. Espera JSON: { usuario_id_usuario: int, portifolio_idPortifolio: int }
     * Somente usuário autenticado (tipo 'usuario') pode criar seu plano.
     */
    public function inserir()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipoUsuario'] ?? '') !== 'usuario') {
            http_response_code(401);
            echo json_encode(['error' => 'Apenas usuários autenticados podem criar planos']);
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $usuarioId = $data['usuario_id_usuario'] ?? $_SESSION['id_usuario'];
        $portifolioId = $data['portifolio_idPortifolio'] ?? null;

        if (!$portifolioId) {
            http_response_code(400);
            echo json_encode(['error' => 'portifolio_idPortifolio obrigatório']);
            return;
        }

        // garantir que o usuário só crie para si
        if ($usuarioId != $_SESSION['id_usuario']) {
            http_response_code(403);
            echo json_encode(['error' => 'Não permitido criar plano para outro usuário']);
            return;
        }

        // valida existência do portifólio
        $portDao = new PortifolioDAO();
        $port = $portDao->selecionarPorId($portifolioId);
        if (!$port) {
            http_response_code(400);
            echo json_encode(['error' => 'Portifólio informado não existe']);
            return;
        }

        $plano = new PlanoTreino('', $usuarioId, $portifolioId);
        $insertedId = $this->dao->inserir($plano);
        echo json_encode(['mensagem' => 'Plano criado', 'idPlano' => $insertedId]);
    }

    /**
     * Exclui um plano por id (somente dono pode excluir).
     */
    public function excluir()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipoUsuario'] ?? '') !== 'usuario') {
            http_response_code(401);
            echo json_encode(['error' => 'Apenas usuário autenticado pode excluir']);
            return;
        }

        $idPlano = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$idPlano) {
            http_response_code(400);
            echo json_encode(['error' => 'id do plano necessário']);
            return;
        }

        $plano = $this->dao->selecionarPorId($idPlano);
        if (!$plano) {
            http_response_code(404);
            echo json_encode(['error' => 'Plano não encontrado']);
            return;
        }

        if ($plano->getUsuario_id_usuario() != $_SESSION['id_usuario']) {
            http_response_code(403);
            echo json_encode(['error' => 'Não autorizado']);
            return;
        }

        $this->dao->excluir($idPlano);
        echo json_encode(['mensagem' => 'Plano excluído']);
    }
}

$acao = $_GET['acao'] ?? '';
$controller = new PlanoTreinoController();
if ($acao === 'listar') {
    $controller->listar();
} elseif ($acao === 'inserir') {
    $controller->inserir();
} elseif ($acao === 'excluir') {
    $controller->excluir();
} else {
    // default -> listar
    $controller->listar();
}

?>
