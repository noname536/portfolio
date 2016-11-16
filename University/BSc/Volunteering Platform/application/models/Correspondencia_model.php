<?php
class Correspondencia_model extends CI_Model
{
	
    //
    //
    // PARTE DO LARANJO <3
    //
    //
    private function _get_all_oportunidades() {
        $this->db->select("*");
        $this->db->from("OPORTUNIDADE");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_oportunidades() {
        
        // Resultado        
        $oportunidadesMatched = array();        
        
        // Todas as Oportunidades
        $oportunidades = $this->_get_all_oportunidades();

        // Cada Oportunidade
        for ($i=0;$i<sizeof($oportunidades);$i++) {
           
            //Vale a pena ?
            if ($oportunidades[$i]["ID_DISTRITO"] == $this->session->userdata("distrito")) {
                $count = 1;
                 
                //Init
                $oportunidadesMatched[$i]["OPORTUNIDADE"] = $oportunidades[$i]["FUNCAO"]; //Nome
                $oportunidadesMatched[$i]["EMAIL"] = $oportunidades[$i]["EMAILI"]; //Email
                $oportunidadesMatched[$i]["ID_DISTRITO"] = true; //Distrito
                $oportunidadesMatched[$i]["ID_FREGUESIA"] = false; //Freguesia
                $oportunidadesMatched[$i]["ID_CONCELHO"] = false; //Conselho
                $oportunidadesMatched[$i]["ID_SEQGA"] = false; //Grupo Atuação
                $oportunidadesMatched[$i]["ID_SEQHA"] = false; //Habilitações
                $oportunidadesMatched[$i]["ID_SEQD"] = false; //Disponibilidade
                $oportunidadesMatched[$i]["ID_SEQP"] = false; //Preferencia

                //Conselho
                if ($oportunidades[$i]["ID_CONCELHO"] == $this->session->userdata("concelho")) {
                    $oportunidadesMatched[$i]["ID_CONCELHO"] = true;
                    $count++;
                }

                //Freguesia
                if ($oportunidades[$i]["ID_FREGUESIA"] == $this->session->userdata("freguesia")) {
                    $oportunidadesMatched[$i]["ID_FREGUESIA"] = true;
                    $count++;
                }
               
                //Disponibilidade
                if ($oportunidades[$i]["ID_SEQD"] == $this->session->userdata("disponibilidade")) {
                    $oportunidadesMatched[$i]["ID_SEQD"] = true;
                    $count++;
                }

                //Habilitações
                foreach ($this->session->userdata("habilitacoes_academicas") as $grau) {
                    if ($oportunidades[$i]["ID_SEQHA"] == $grau && !$oportunidadesMatched[$i]["ID_SEQHA"]) {
                        $oportunidadesMatched[$i]["ID_SEQHA"] = true;
                        $count++;
                    }
                }

                //Grupo_Atuação
                foreach ($this->session->userdata("grupos_atuacao") as $grupo) {
                    if ($oportunidades[$i]["ID_SEQGA"] == $grupo && !$oportunidadesMatched[$i]["ID_SEQGA"])  {
                        $oportunidadesMatched[$i]["ID_SEQGA"] = true;
                        $count++;
                    }
                }

                //Preferencia
                foreach ($this->session->userdata("interesses") as $pref) {
                    if ($oportunidades[$i]["ID_SEQP"] == $pref && !$oportunidadesMatched[$i]["ID_SEQP"]) {
                        $oportunidadesMatched[$i]["ID_SEQP"] = true;
                        $count++;
                    }
                }

                $oportunidadesMatched[$i]["COUNT"] = ($count / 7) * 100;
            }     
        }

        // Sort
        
        usort( $oportunidadesMatched, 
            function($a, $b) { 
                return $a["COUNT"] < $b["COUNT"]; 
            }
        );

        
        return $oportunidadesMatched;
    }
    
    //
    //
    // PARTE DO CHE <3
    //
    //
    public function get_voluntarios_from_distrito($distrito) 
    {
        $this->db->select("EMAILV, NOME");
        $this->db->from("VOLUNTARIO");
        $this->db->where("ID_DISTRITO", $distrito);
        
        return $this->db->get()->result_array();
    }
    
    public function voluntario_matches_concelho($voluntario, $concelho)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("VOLUNTARIO");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_CONCELHO", $concelho);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    public function voluntario_matches_freguesia($voluntario, $freguesia)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("VOLUNTARIO");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_FREGUESIA", $freguesia);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    public function voluntario_matches_disponibilidade($voluntario, $disponibilidade)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("VOLUNTARIO");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_SEQD", $disponibilidade);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    public function voluntario_matches_grupo_atuacao($voluntario, $grupo_atuacao)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("GRUPO_ATUACAO_VOL");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_SEQGA", $grupo_atuacao);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    public function voluntario_matches_habilitacao_academica($voluntario, $habilitacao_academica)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("HABILITACAO_ACADEMICA_VOL");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_SEQHA", $habilitacao_academica);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    public function voluntario_matches_interesse($voluntario, $interesse)
    {
        $this->db->select("COUNT(*)");
        $this->db->from("PREFERENCIA_VOL");
        $this->db->like("EMAILV", $voluntario);
        $this->db->where("ID_SEQP", $interesse);
        
        $query = $this->db->get()->result_array();
        
        return $query[0]["COUNT(*)"] != 0;
    }
    
    
    public function get_voluntarios_from_instituicao($instituicao) 
    {
        
        // obter todas as oportunidades
        $this->db->select("FUNCAO, ID_DISTRITO, ID_CONCELHO, ID_FREGUESIA, ID_SEQD, ID_SEQHA, ID_SEQP, ID_SEQGA");
        $this->db->from("OPORTUNIDADE");
        $this->db->like("EMAILI", $instituicao);
        
        $all_oport = $this->db->get()->result_array();
        
        // inicializar estrutura com os voluntarios
        $all_voluntarios = array();
        
        // counter
        $count = 0;
        
        // para todas as oportunidades
        foreach( $all_oport as $oport )
        {
            $distrito = $oport["ID_DISTRITO"];
            
            // para todos os voluntarios que tem pelo menos o distrito igual
            $voluntarios = $this->get_voluntarios_from_distrito($distrito);
            foreach( $voluntarios as $vol )
            {
                $matches_concelho = FALSE;
                $matches_freguesia = FALSE;
                $matches_disponibilidade = FALSE;
                $matches_interesse = FALSE;
                $matches_grupo_atuacao = FALSE;
                $matches_habilitacao_academica = FALSE;
                
                // verificar concelho
                $matches_concelho = $this->voluntario_matches_concelho($vol["EMAILV"], $oport["ID_CONCELHO"]);
                
                // verifica freguesia
                $matches_freguesia = $this->voluntario_matches_freguesia($vol["EMAILV"], $oport["ID_FREGUESIA"]);
                
                // verifica disponibilidade
                $matches_disponibilidade = $this->voluntario_matches_disponibilidade($vol["EMAILV"], $oport["ID_SEQD"]);
                
                // verifica interesse
                $matches_interesse = $this->voluntario_matches_interesse($vol["EMAILV"], $oport["ID_SEQP"]);
                
                // verifica grupo atuacao
                $matches_grupo_atuacao = $this->voluntario_matches_grupo_atuacao($vol["EMAILV"], $oport["ID_SEQGA"]);
                
                // verifica habilitacao academica
                $matches_habilitacao_academica = $this->voluntario_matches_habilitacao_academica($vol["EMAILV"], $oport["ID_SEQHA"]);
                
                //
                // adiciona a estrutura
                $all_voluntarios[$count] = array("ID_DISTRITO" => TRUE,
                                                         "ID_CONCELHO" => $matches_concelho,
                                                         "ID_FREGUESIA" => $matches_freguesia,
                                                         "ID_SEQD" => $matches_disponibilidade,
                                                         "ID_SEQP" => $matches_interesse,
                                                         "ID_SEQGA" => $matches_grupo_atuacao,
                                                         "ID_SEQHA" => $matches_habilitacao_academica,
                                                         "OPORTUNIDADE" => $oport["FUNCAO"],
                                                         "NOME" => $vol["NOME"],
                                                         "EMAIL" => $vol["EMAILV"] );
                $count++;
            }
            
        }
        
        return $all_voluntarios;
    }
    
}
?>