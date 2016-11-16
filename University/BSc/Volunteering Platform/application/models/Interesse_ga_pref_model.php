<?php
class Interesse_ga_pref_model extends CI_Model
{
	
		function __construct() {
			parent::__construct();
		}

		function getAllDisponibilidade() {
		$query = "SELECT id_seqD, periodicidade FROM Disponibilidade";
		$q = $this->db->query($query);
		return $q->result_array();
		}
		
		function getAllGrupo_Atuacao() {
		$query = "SELECT id_seqGA, grupo FROM Grupo_Atuacao";
		$q = $this->db->query($query);
		return $q->result_array();
		}
		
		function getAllPreferencia() {
		$query = "SELECT id_seqP, pref FROM Preferencia";
		$q = $this->db->query($query);
		return $q->result_array();
		}
		
		function getAllHabilitacao_Academica() {
		$query = "SELECT id_seqHA, grau FROM Habilitacao_Academica";
		$q = $this->db->query($query);
		return $q->result_array();
		}
}