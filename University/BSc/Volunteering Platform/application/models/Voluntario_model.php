<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ===========================================================================
//            - Produto_model.php (1a versão)
//            - Guardar em models/
//            - Modelo de dados para o produto
// ===========================================================================

class Voluntario_model extends CI_Model {

	//obtem os produtos com determinada designacao
	function get_voluntario($userID)
	{
		//Voluntario
		$interrogacao_sql = "SELECT * FROM Voluntario WHERE LOWER(emailV) LIKE '%" . strtolower($userID) . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}

	function get_grupoAtuaVol($userID)
	{
		//Grupo_Atuacao_Vol
		$interrogacao_sql = "SELECT * FROM Grupo_Atuacao_Vol WHERE LOWER(emailV) LIKE '%" . strtolower($userID) . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->result_array();
	}

	function get_prefVol($userID)
	{
		//Preferencia_Vol
		$interrogacao_sql = "SELECT * FROM Preferencia_Vol WHERE LOWER(emailV) LIKE '%" . strtolower($userID) . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->result_array();
	}

	function get_habAcaVol($userID)
	{
		//Habilitacao_Academica_Vol
		$interrogacao_sql = "SELECT * FROM Habilitacao_Academica_Vol WHERE LOWER(emailV) LIKE '%" . strtolower($userID) . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->result_array();
	}
	
	function getName ($emailV)
	{
		$this->db->select("NOME");
		$this->db->from("VOLUNTARIO");
		$this->db->where("EMAILV",$emailV);
		$query= $this->db->get();
		$p = $query->row_array();
		
		return $p['NOME']; 
	}

	function check_voluntario($userID)
	{
		//Voluntario
		$interrogacao_sql = "SELECT * FROM Voluntario WHERE LOWER(emailV) LIKE '%" . strtolower($userID) . "%'";
		$query = $this->db->query($interrogacao_sql);
		return $query->num_rows();
	}

	/*
	//pq ? Preciso do email para passar nos outros metodos do update, visto que no update nao se actualiza o mail
	function get_emailV ($nome)
	{
		//$interrogacao_sql = "SELECT emailV FROM Voluntario WHERE LOWER (nome) = " . strtolower ($nome);
		$query = $this->db->query("SELECT emailV FROM voluntario WHERE  nome = " .$nome);
		return $query->row_array();
	}*/

	function insertVoluntario($email, $password, $nome, $phone, $gender, $birth,
							  $distrito, $concelho, $freguesia, $disponibilidade, $foto) {
		$values = array(
			"EMAILV" => $email,
			"PASSWORD" => $password,
			"NOME" => $nome,
			"TELEFONE" => $phone,
			"GENERO" => $gender,
			"DATA_DE_NASCIMENTO" => $birth,
			"ID_DISTRITO" => $distrito,
			"ID_CONCELHO" => $concelho,
			"ID_FREGUESIA" => $freguesia,
			"ID_SEQD" => $disponibilidade,
			"FOTO" => $foto
		);
		$this->db->insert("VOLUNTARIO", $values);
	}

	function insertGrupoAtuacao($email, $id) {
		$values = array(
			"EMAILV" => $email,
			"ID_SEQGA" => $id
		);
		$this->db->insert("GRUPO_ATUACAO_VOL", $values);
	}

	function insertHabilitacaoAcademica($email, $id) {
		$values = array(
			"EMAILV" => $email,
			"ID_SEQHA" => $id
		);
		$this->db->insert("HABILITACAO_ACADEMICA_VOL", $values);
	}

	function insertInteresse($email, $id) {
		$values = array(
			"EMAILV" => $email,
			"ID_SEQP" => $id
		);
		$this->db->insert("PREFERENCIA_VOL", $values);
	}

	
	function insert_Vol ($nomeV,$emailV,$distrito_id,$id_concelho,$id_freguesia,$id_seqD,$telefone,$data_de_nascimento,$genero,$foto,$password)
	{
		$interrogacao_sql = "INSERT INTO Voluntario(nome,emailV,id_distrito,id_concelho,id_freguesia,id_seqD,telefone,data_de_nascimento,genero,foto,password) VALUES  (" .$nomeV.",".$emailV.",".$distrito_id.",".$id_concelho.",".$id_freguesia.",".$id_seqD.",".$telefone.",".$data_de_nascimento.",".$genero.",".$foto.",".$password.")";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}
	function insert_grupoA_Vol ($emailV,$id_seqGA)
	{
		$interrogacao_sql = "INSERT INTO Grupo_Atuacao_Vol(emailV,id_seqGA) VALUES (". $emailV . ", " . $id_seqGA . ")";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}

	function insert_HA_Vol ($emailV,$id_seqHA)
	{
		$interrogacao_sql = "INSERT INTO Habilitacao_Academica_Vol(emailV,id_seqHA) VALUES ( " .$emailV . "," .$id_seqHA. ")";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}

	function insert_pref_Vol ($emailV,$id_seqP)
	{
		$interrogacao_sql = "INSERT INTO Preferencia_Vol (emailV,id_seqP) VALUES(".$emailV.",". id_seqP .")";
		$query = $this->db->query($interrogacao_sql);
		return $query->row_array();
	}

	function update_vol($emailV,$nome,$id_distrito,$id_concelho,$id_freguesia,$id_seqD,$telefone,$data_de_nascimento,$genero,$foto)
	{
	
		$data = array(
			'DATA_DE_NASCIMENTO' => $data_de_nascimento,
			'NOME' => $nome,
			'TELEFONE' => $telefone,
			'GENERO' => $genero,
			'FOTO' => $foto,
			'ID_SEQD' => $id_seqD,
			'ID_DISTRITO' => $id_distrito,
			'ID_CONCELHO' => $id_concelho,
			'ID_FREGUESIA' => $id_freguesia
			);
			
		$this->db->where('EMAILV',$emailV);
		$this->db->update('VOLUNTARIO',$data);
	}

	function update_GA_vol ($emailV,$id_seqGA)
	{
		$query = "SELECT ID_SEQGA FROM GRUPO_ATUACAO_VOL WHERE EMAILV = ? ";
		$q = $this->db ->query($query,$emailV);
		$queryA = $q->result_array();
		foreach ($queryA as $k=>$v){
			$all[$k] = $v['ID_SEQGA'];
		}
		echo "<p>". print_r($all)."</p>";
		foreach ($id_seqGA as $idseq)
		{
			
			$check = in_array($idseq,$all);
			
			if($check){ //SE EXISTIR UPDATE
				echo $idseq;
				$data = array(
						'ID_SEQGA' => $idseq,
						'EMAILV' => $emailV
				);
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQGA', $idseq);
				$this->db->delete('GRUPO_ATUACAO_VOL');
				
				$this->db->insert('GRUPO_ATUACAO_VOL',$data);
				
				
			}else{ //senao INSERT
				echo "<p>$idseq</p>";
				$data = array(
					'ID_SEQGA' => $idseq,
					'EMAILV' => $emailV
					);
				$this->db->insert('GRUPO_ATUACAO_VOL',$data);
			}
		}


		foreach ($all as $id) //retira todos aqueles que nao forem nem update nem inseridos
		{
			$verifica = in_array($id,$id_seqGA);
			if(!$verifica){
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQGA', $id);
				$this->db->delete('GRUPO_ATUACAO_VOL');
				
			}
		}

	}

	function update_HA_vol ($emailV,$id_seqHA)
	{
		$query = "SELECT ID_SEQHA FROM HABILITACAO_ACADEMICA_VOL WHERE EMAILV = ? ";
		$q = $this->db ->query($query,$emailV);
		$queryA = $q->result_array();
		foreach ($queryA as $k=>$v){
			$all[$k] = $v['ID_SEQHA'];
		}
		echo "<p>". print_r($all)."</p>";
		foreach ($id_seqHA as $idseq)
		{
			
			$check = in_array($idseq,$all);
			
			if($check){ //SE EXISTIR UPDATE
				echo $idseq;
				$data = array(
						'ID_SEQHA' => $idseq,
						'EMAILV' => $emailV
				);
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQHA', $idseq);
				$this->db->delete('HABILITACAO_ACADEMICA_VOL');
				
				$this->db->insert('HABILITACAO_ACADEMICA_VOL',$data);
				
				
			}else{ //senao INSERT
				echo "<p>$idseq</p>";
				$data = array(
					'ID_SEQHA' => $idseq,
					'EMAILV' => $emailV
					);
				$this->db->insert('HABILITACAO_ACADEMICA_VOL',$data);
			}
		}


		foreach ($all as $id) //retira todos aqueles que nao forem nem update nem inseridos
		{
			$verifica = in_array($id,$id_seqHA);
			if(!$verifica){
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQHA', $id);
				$this->db->delete('HABILITACAO_ACADEMICA_VOL');
				
			}
		}

	}

	function update_pref_vol ($emailV,$id_seqP)
	{
		$query = "SELECT ID_SEQP FROM PREFERENCIA_VOL WHERE EMAILV = ? ";
		$q = $this->db ->query($query,$emailV);
		$queryA = $q->result_array();
		foreach ($queryA as $k=>$v){
			$all[$k] = $v['ID_SEQP'];
		}
		echo "<p>". print_r($all)."</p>";
		foreach ($id_seqGA as $idseq)
		{
			
			$check = in_array($idseq,$all);
			
			if($check){ //SE EXISTIR UPDATE
				echo $idseq;
				$data = array(
						'ID_SEQP' => $idseq,
						'EMAILV' => $emailV
				);
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQP', $idseq);
				$this->db->delete('PREFERENCIA_VOL');
				
				$this->db->insert('PREFERENCIA_VOL',$data);
				
				
			}else{ //senao INSERT
				echo "<p>$idseq</p>";
				$data = array(
					'ID_SEQP' => $idseq,
					'EMAILV' => $emailV
					);
				$this->db->insert('PREFERENCIA_VOL',$data);
			}
		}


		foreach ($all as $id) //retira todos aqueles que nao forem nem update nem inseridos
		{
			$verifica = in_array($id,$id_seqP);
			if(!$verifica){
				$this->db->where('EMAILV',$emailV);
				$this->db->where('ID_SEQP', $id);
				$this->db->delete('PREFERENCIA_VOL');
				
			}
		}
	
	}

	//função privada chamada quando é submetida uma pesquisa
    function get_voluntario_all($idVoluntario) {

      //faz o load do modelo de dados necessário
      $this->load->model('Preferencia_model');
      $this->load->model('Habilitacao_model');
      $this->load->model('GrupoAtuacao_model');
      $this->load->model('Localidades_model');

      $voluntario = $this->Voluntario_model->get_voluntario($idVoluntario);

      $data["email"] = $voluntario["EMAILV"];
      $data["nome"] = $voluntario["NOME"];
      $data["distrito"] = $voluntario["ID_DISTRITO"];
      $data["concelho"] = $voluntario["ID_CONCELHO"];
      $data["freguesia"] = $voluntario["ID_FREGUESIA"];
      $data["telefone"] = $voluntario["TELEFONE"];
      $data["nascimento"] = $voluntario["DATA_DE_NASCIMENTO"];
      $data["genero"] = $voluntario["GENERO"];
      $data["foto"] = $voluntario["FOTO"];
      $data["disponibilidade"] = $voluntario["ID_SEQD"];

      //Dados de Preferencias
      $arrIDs = $this->get_prefVol($idVoluntario);

      $i=0;
      foreach($arrIDs as $line){
        $data["arrPref"][$i] = $line["ID_SEQP"];
        $i++;
      }

      //Dados de Habilitações Académicas
      $arrIDs = $this->get_habAcaVol($idVoluntario);

      $data["arrHab"] = $arrIDs;

      $i=0;
      foreach($arrIDs as $line){
        $data["arrHab"][$i] = $line["ID_SEQHA"];
        $i++;
      }

      //Dados de Grupo de Atuação
      $arrIDs = $this->get_grupoAtuaVol($idVoluntario);

      $i=0;
      foreach($arrIDs as $line){
        $data["arrGrupo"][$i] = $line["ID_SEQGA"];
        $i++;
      }

      return $data;
    }

}