<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ===========================================================================
//            - Produto_model.php (1a versão)
//            - Guardar em models/
//            - Modelo de dados para o produto
// ===========================================================================

class GrupoAtuacao_model extends CI_Model {

	function get_GrupoAtaucaoById($id)
	{
		$interrogacao_sql = "SELECT * FROM Grupo_Atuacao WHERE id_seqGA LIKE '%" . $id . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}
}