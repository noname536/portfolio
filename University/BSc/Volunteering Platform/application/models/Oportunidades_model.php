<?php
class Oportunidades_model extends CI_Model
{

	////////////////////////////////////////////////////////////////////////////
	//
	// Cria uma nova oportunidade na base de dados
	//
	public function insert_oportunidade($email, $funcao, $distrito, $concelho,
										$freguesia, $interesse, $grupo_atuacao,
										$habilitacao_academica, $disponibilidade)
	{
		$values = array(
			"EMAILI" => $email,
			"FUNCAO" => $funcao,
			"ID_DISTRITO" => $distrito,
			"ID_CONCELHO" => $concelho,
			"ID_FREGUESIA" => $freguesia,
			"ID_SEQD" => $disponibilidade,
			"ID_SEQP" => $interesse,
			"ID_SEQGA" => $grupo_atuacao,
			"ID_SEQHA" => $habilitacao_academica
		);
		$this->db->insert("OPORTUNIDADE", $values);
	}

	public function exist_oportunidade($email, $funcao)
	{
		//exists
		if($this->get_oportunidade($email,$funcao))
		{
			return "TRUE";
		}
		else
		{
			return "FALSE";
		}
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Remove uma oportunidade da base de dados
	//
	public function remove_oportunidade($email, $funcao) {
		$this->db->where("EMAILI", $email);
		$this->db->where("FUNCAO", $funcao);
		$this->db->delete("OPORTUNIDADE");
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Retorna toda a informacao de uma dada oportunidade
	//
	public function get_oportunidade($email, $funcao) {
		$this->db->select("*");
		$this->db->from("OPORTUNIDADE");
		$this->db->like("EMAILI", strtolower($email));
		$this->db->like("FUNCAO", $funcao);
		$query= $this->db->get();
		return $query->row_array();
	}

	////////////////////////////////////////////////////////////////////////////
	//	
	// Retorna todas as funcoes das oportunidades de uma dada instituicao
	//		-> isto vai ser usado para a geracao da lista de oportunidades
	//
	public function get_all_oportunidades($email) {
		$this->db->select("FUNCAO");
		$this->db->from("OPORTUNIDADE");
		$this->db->like("EMAILI", strtolower($email));
		$query = $this->db->get();
		return $query->result_array();
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Atualiza a informacao de uma oportunidade
	//
	public function update_oportunidade($email, $funcao, $distrito, $concelho,
										$freguesia, $interesse, $grupo_atuacao,
										$habilitacao_academica, $disponibilidade)
	{
		//print_r($email, $funcao, $distrito, $concelho,
				//						$freguesia, $interesse, $grupo_atuacao,
				//						$habilitacao_academica, $disponibilidade);
		$values = array(
			"ID_DISTRITO" => $distrito,
			"ID_CONCELHO" => $concelho,
			"ID_FREGUESIA" => $freguesia,
			"ID_SEQD" => $disponibilidade,
			"ID_SEQP" => $interesse,
			"ID_SEQGA" => $grupo_atuacao,
			"ID_SEQHA" => $habilitacao_academica
		);

		$this->db->like("EMAILI", strtolower($email));
		$this->db->like("FUNCAO", $funcao);
		$this->db->update("OPORTUNIDADE", $values);
	}

}
