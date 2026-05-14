<?php

include_once("../config/conexao.php");

class Aluno {

    public $id;
    public $matricula;
    public $curso;

    public function inserir() {

        try {

            $parametros = Array(
                ':id' => $this->id,
                ':matricula' => $this->matricula,
                ':curso' => $this->curso,
            );

            $query = "INSERT INTO aluno
                    (id, matricula, curso)
                    VALUES
                    (:id, :matricula, :curso)";

            Conexao::executarComParametros($query, $parametros);

            return true;

        } catch (Exception $e) {
            throw new Exception("Erro ao inserir aluno: " . $e->getMessage());
        }
    }

    public static function contar() {
        $query = "SELECT COUNT(*) AS total FROM aluno";

        $stmt = Conexao::executar($query);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado['total'];
    }
    public static function buscarAlunosCoordenador() {
        $query = "
            SELECT 
                user.nome,
                user.email,
                projeto_tcc.titulo AS titulo_projeto
            FROM aluno
            JOIN user 
                ON user.id = aluno.id
            LEFT JOIN projeto_tcc 
                ON projeto_tcc.grupo_id = aluno.grupo_id
        ";

        $stmt = Conexao::executar($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>