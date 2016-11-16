<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ===========================================================================
//            - Produto_model.php (1a versão)
//            - Guardar em models/
//            - Modelo de dados para o produto
// ===========================================================================

class Habilitacao_model extends CI_Model {

	function get_HabById($id)
	{
		$interrogacao_sql = "SELECT * FROM Habilitacao_Academica WHERE id_seqHA LIKE '%" . $id . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}
}