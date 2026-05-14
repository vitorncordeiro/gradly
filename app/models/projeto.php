<?php

include_once("../config/conexao.php");

class Projeto {

    public $id;
    public $titulo;
    public $descricao;
    public $objetivo;
    public $temas;
    public $areas;
    public $orientador_id;
    public $grupo_id;
    public $orientador_nome;

    public function inserir() {

        try {

            $parametros = Array(
                ':titulo' => $this->titulo,
                ':descricao' => $this->descricao,
                ':objetivo' => $this->objetivo,
                ':temas' => $this->temas,
                ':areas' => $this->areas,
                ':orientador_id' => $this->orientador_id,
                ':grupo_id' => $this->grupo_id
            );

            $query = "INSERT INTO projeto_tcc
                    (titulo, descricao, objetivo, temas, areas, orientador_id, grupo_id)
                    VALUES
                    (:titulo, :descricao, :objetivo, :temas, :areas, :orientador_id, :grupo_id)";

            Conexao::executarComParametros($query, $parametros);

            return true;

        } catch (Exception $e) {
            throw new Exception("Erro ao inserir projeto: " . $e->getMessage());
        }
    }

    public function buscarPorGrupo() {

        try {

            $parametros = Array(
                ':grupo_id' => $this->grupo_id
            );

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

            $stmt = Conexao::executarComParametros($query, $parametros);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            throw new Exception("Erro ao buscar projeto: " . $e->getMessage());
        }
    }

    public static function contar() {
        $query = "SELECT COUNT(*) AS total FROM projeto_tcc";

        $stmt = Conexao::executar($query);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado['total'];
    }

    public static function buscarSemOrientador() {
        $query = "SELECT id, titulo FROM projeto_tcc WHERE orientador_id IS NULL";

        $stmt = Conexao::executar($query);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }
    
}
?>