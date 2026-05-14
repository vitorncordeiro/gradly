<?php
include_once("../models/user.php");
include_once("../models/coordenador.php");
include_once("../models/aluno.php");
include_once("../models/orientador.php");
include_once("../models/projeto.php");
header('Content-Type: application/json; charset=utf-8');

class CoordenadorControle {

    public function cadastrar() {
        $conn = Conexao::conectar();

        try {
            $conn->beginTransaction();

            $user = new User();
            $user->nome = $_POST['nome'];
            $user->email = $_POST['email'];
            $user->senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $user->dataCadastro = $_POST['data_cadastro'];

            $userId = $user->inserir();

            $coordenador = new Coordenador();
            $coordenador->id = $userId;
            $coordenador->departamento = $_POST['departamento'];
            $coordenador->instituicao = $_POST['instituicao'];

            $coordenador->inserir();

            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Coordenador cadastrado com sucesso',
            ]);

        } catch (Exception $e) {
            $conn->rollBack();
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao cadastrar usuário',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function preencher_cards(){
        $conn = Conexao::conectar();
        try {

            $alunos = Aluno::contar();
            $orientadores = Orientador::contar();
            $projetos = Projeto::contar();

            echo json_encode([
                'success' => true,
                'alunos' => $alunos,
                'orientadores' => $orientadores,
                'projetos' => $projetos
            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar dados dos cards',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function buscarSemOrientador(){
        $conn = Conexao::conectar();
        try {
            $projetos = Projeto::buscarSemOrientador();

            echo json_encode([
                'success' => true,
                'projetos' => $projetos
            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar dados dos cards',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function buscarOrientador(){
        $conn = Conexao::conectar();
        try {
            $orientadores = Orientador::buscarOrientador();

            echo json_encode([
                'success' => true,
                'orientadores' => $orientadores
            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar dados dos orientadores',
                'error' => $e->getMessage()
            ]);
        }
    }

        public function buscarAlunos(){
        $conn = Conexao::conectar();
        try {
            $alunos = Aluno::buscarAlunosCoordenador();

            echo json_encode([
                'success' => true,
                'alunos' => $alunos
            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => 'Erro ao buscar dados dos alunos',
                'error' => $e->getMessage()
            ]);
        }
    }
}

$controle = new CoordenadorControle();
$acao = $_POST["acao"];

if ($acao == "cadastrar") {
    $controle->cadastrar();
} else if ($acao == "preencher_cards"){
    $controle->preencher_cards();
} else if ($acao == "buscarSemOrientador"){
    $controle->buscarSemOrientador();
} else if ($acao == "buscarOrientador"){
    $controle->buscarOrientador();
} else if ($acao == "buscarAlunos"){
    $controle->buscarAlunos();
}
?>