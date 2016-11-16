<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ===========================================================================
//            - Produto_model.php (1a versão)
//            - Guardar em models/
//            - Modelo de dados para o produto
// ===========================================================================

class Preferencia_model extends CI_Model {
	
	function get_PrefById($id)
	{
		//Voluntario
		$interrogacao_sql = "SELECT * FROM PREFERENCIA  WHERE id_seqP LIKE '%" . $id . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}
}