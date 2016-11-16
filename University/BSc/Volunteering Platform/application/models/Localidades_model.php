<?php
class Localidades_model extends CI_Model
{

	function getAllDistritos() {
		$query = $this->db->get("DISTRITOS");
		return $query->result_array();
	}

	function getConcelhos($distrito) {
		$query = $this->db->get_where("CONCELHOS", array("ID_DISTRITO" => $distrito));
		return $query->result_array();
	}

	function getFreguesias($concelho) {
		$query = $this->db->get_where("FREGUESIAS", array("ID_CONCELHO" => $concelho));
		return $query->result_array();
	}

	function getDistritoById($distrito) {
		$query = $this->db->get_where("DISTRITOS", array("ID_DISTRITO" => $distrito));
		return $query->row_array();
	}

	function getConselhoById($concelho) {
		$query = $this->db->get_where("CONCELHOS", array("ID_CONCELHO" => $concelho));
		return $query->row_array();
	}	

	function getFreguesiaById($freguesia) {
		$query = $this->db->get_where("FREGUESIAS", array("ID_FREGUESIA" => $freguesia));
		return $query->row_array();
	}

}