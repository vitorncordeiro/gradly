<?php

include_once("../config/conexao.php");

class Orientador {

    public $id;
    public $atuacao;
    public $titulacao;

    public function inserir() {

        try {

            $parametros = Array(
                ':id' => $this->id,
                ':atuacao' => $this->atuacao,
                ':titulacao' => $this->titulacao,
            );

            $query = "INSERT INTO orientador
                    (id, areaAtuacao, titulacao)
                    VALUES
                    (:id, :atuacao, :titulacao)";

            Conexao::executarComParametros($query, $parametros);

            return true;

        } catch (Exception $e) {
            throw new Exception("Erro ao inserir orientador: " . $e->getMessage());
        }
    }

    public static function contar() {
        $query = "SELECT COUNT(*) AS total FROM orientador";

        $stmt = Conexao::executar($query);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado['total'];
    }

    public static function buscarOrientador() {
        $query = "
            SELECT 
                orientador.id,
                user.nome
            FROM orientador
            INNER JOIN user 
                ON orientador.id = user.id
        ";

        $stmt = Conexao::executar($query);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }
}
?>