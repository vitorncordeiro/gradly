<?php
include_once("../models/projeto.php");
include_once("../config/conexao.php");

session_start();

header('Content-Type: application/json; charset=utf-8');

class ProjetoControle {

    public function criar() {
        $conn = Conexao::conectar();

        try {
            $conn->beginTransaction();

            // Obter ID do aluno logado da session
            $aluno_id = $_SESSION['usuario_id'] ?? null;
            if (!$aluno_id) {
                throw new Exception("Aluno não autenticado");
            }

            // Buscar grupo_id do aluno
            $stmt = Conexao::executarComParametros(
                "SELECT grupo_id FROM aluno WHERE id = :id",
                [':id' => $aluno_id]
            );
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$aluno) {
                throw new Exception("Aluno não encontrado");
            }
            
            $grupo_id = $aluno['grupo_id'];
            if (!$grupo_id) {
                throw new Exception("Aluno não possui um grupo atribuído");
            }

            // Criar projeto com o grupo_id do aluno
            $projeto = new Projeto();
            $projeto->titulo = $_POST['titulo'];
            $projeto->descricao = $_POST['descricao'];
            $projeto->objetivo = $_POST['objetivo'];
            $projeto->temas = $_POST['temas'];
            $projeto->areas = $_POST['areas'];
            $projeto->orientador_id = $_POST['orientador_id'];
            $projeto->grupo_id = $grupo_id;

            // (Opcional, mas recomendado) validar se o ID existe
            if (empty($projeto->orientador_id)) {
                throw new Exception("ID do orientador não informado");
            }

            $projeto->inserir();

            $conn->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Projeto criado com sucesso'
            ]);

        } catch (Exception $e) {
            $conn->rollBack();
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao criar projeto',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function buscar() {
        try {
            $aluno_id = $_SESSION['usuario_id'] ?? null;
            if (!$aluno_id) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Aluno não autenticado'
                ]);
                return;
            }

            $stmt = Conexao::executarComParametros(
                "SELECT grupo_id FROM aluno WHERE id = :id",
                [':id' => $aluno_id]
            );
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$aluno || !$aluno['grupo_id']) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Aluno sem grupo associado',
                    'data' => null
                ]);
                return;
            }

            $query = "SELECT p.titulo,
                             p.descricao,
                             p.objetivo,
                             p.temas,
                             p.areas,
                             p.estado,
                             u.nome AS orientador_nome
                      FROM projeto_tcc p
                      LEFT JOIN orientador o ON o.id = p.orientador_id
                      LEFT JOIN user u ON u.id = o.id
                      WHERE p.grupo_id = :grupo_id
                      LIMIT 1";

            $projeto = Conexao::executarComParametros(
                $query,
                [':grupo_id' => $aluno['grupo_id']]
            )->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $projeto ?: null
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar projeto',
                'error' => $e->getMessage()
            ]);
        }
    }
}

$controle = new ProjetoControle();

$acao = $_POST["acao"] ?? $_GET["acao"] ?? null;

if ($acao == "criar") {
    $controle->criar();
} else if ($acao == "buscar") {
    $controle->buscar();
}
?>