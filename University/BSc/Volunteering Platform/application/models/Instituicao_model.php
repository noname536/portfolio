<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ===========================================================================
//            - Produto_model.php (1a versão)
//            - Guardar em models/
//            - Modelo de dados para o produto
// ===========================================================================

class Instituicao_model extends CI_Model {

    function get_instituicao($idInstituicao)
    {
        //Instituicao
        $interrogacao_sql = "SELECT * FROM Instituicao WHERE LOWER(emailI) LIKE '%" . strtolower($idInstituicao) . "%'";
        $query = $this->db->query($interrogacao_sql);
        return $query->row_array();
    }

    function update_instituicao($email,$nome,$id_distrito,$id_concelho,$id_freguesia,$emailRep,$telefone,$nomeRep,$morada,$website,$descricao)
    {

        $data = array(
            'NOME' => $nome,
            'TELEFONE' => $telefone,
            'NOME_REP' => $nomeRep,
            'EMAIL_REP' => $emailRep,
            'MORADA' => $morada,
            'WEBSITE' => $website,
            'DESCRICAO' => $descricao,
            'ID_DISTRITO' => $id_distrito,
            'ID_CONCELHO' => $id_concelho,
            'ID_FREGUESIA' => $id_freguesia
            );

        $this->db->where('EMAILI',$email);
        $this->db->update('INSTITUICAO',$data);
    }

    //função privada chamada quando é submetida uma pesquisa
    function get_instituicao_all($idInstituicao) {

        $instituicao = $this->Instituicao_model->get_instituicao($idInstituicao);

        $data["nome"] = $instituicao["NOME"];
        $data["email"] = $instituicao["EMAILI"];
        $data["telefone"] = $instituicao["TELEFONE"];
        $data["nomerep"] = $instituicao["NOME_REP"];
        $data["morada"] = $instituicao["MORADA"];
        $data["website"] = $instituicao["WEBSITE"];
        $data["descricao"] = $instituicao["DESCRICAO"];
        $data["distrito"] = $instituicao["ID_DISTRITO"];
        $data["concelho"] = $instituicao["ID_CONCELHO"];
        $data["freguesia"] = $instituicao["ID_FREGUESIA"];
        $data["emailrep"] = $instituicao["EMAIL_REP"];

        return $data;
    }
    
    function insert_instituicao($email, $password, $nome, $distrito, $concelho, $freguesia, $emailRep, 
                              $phone, $nomeRep, $morada, $website, $descricao) {
		$values = array(
			"EMAILI" => $email,
			"PASSWORD" => $password,
			"NOME" => $nome,
			"TELEFONE" => $phone,
			"MORADA" => $morada,
			"WEBSITE" => $website,
            "DESCRICAO" => $descricao,
			"ID_DISTRITO" => $distrito,
			"ID_CONCELHO" => $concelho,
			"ID_FREGUESIA" => $freguesia,
			"NOME_REP" => $nomeRep,
            "EMAIL_REP" => $emailRep
		);
		$this->db->insert("INSTITUICAO", $values);
	}
}