<?php

/**
 * 
 * Criado por: noname536
 */
class Editar extends CI_Controller
{
	function index (){
		  // parse data
		  if ( !$this->dx_auth->is_logged_in())
            redirect("index.php/registo", "refresh");
			
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Edicao";
		

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_registo", $data);
        $this->load->view("templates/footer");

	}

	function voluntario (){

		if ( !$this->dx_auth->is_logged_in())
            redirect("index.php/registo", "refresh");
        
		 // load models
        $this->load->model("localidades_model");
		$this->load->model("interesse_ga_pref_model");
		$this->load->model("Voluntario_model");

		$data["websiteTitle"] = "FC.UL@Volunteer";
        $data["title"]        = $data["websiteTitle"] . " | Edicao de Voluntarios";
		$data["base_url"]     = base_url();
        $data['distritos']    = $this->localidades_model->getAllDistritos();
		$data['nome'] 			= $this->session->userdata("nome");
		$data['genero']         = $this->session->userdata("gender");
		$data['telefone']  		= $this->session->userdata("phone");
		$data['distritoV'] 		= $this->session->userdata("distrito");
		$data['concelhoV'] 		= $this->session->userdata("concelho");
		$data['freguesiaV'] 		= $this->session->userdata("freguesia");
		$data['interessesV']  = $this->session->userdata("interesses");
		$data['disponibilidadeV'] = $this->session->userdata("disponibilidade");
		$data['grupoAtuacaoV'] = $this->session->userdata("grupos_atuacao");
		$data['habilitacaoAcademicaV'] = $this->session->userdata("habilitacoes_academicas");
		$data['data_de_nascimento'] 		= $this->session->userdata("birth");
		$data['disponibilidade'] = $this->interesse_ga_pref_model->getAllDisponibilidade();
		$data['grupo_atuacao'] = $this->interesse_ga_pref_model->getAllGrupo_Atuacao();
		$data['preferencias'] = $this->interesse_ga_pref_model->getAllPreferencia();
		$data['habilitacao_academica'] = $this->interesse_ga_pref_model->getAllHabilitacao_Academica();
		$data['fotoV'] = $this->session->userdata("foto");
        
        //
        // numero de oportunidades
        $this->load->model('correspondencia_model');
        $oportunidades = $this->correspondencia_model->get_oportunidades();
        $oportunities = array ();

         foreach( $oportunidades as $op ) {
            $array = array(
                    "ID_DISTRITO" => $op["ID_DISTRITO"],
                    "OPORTUNIDADE" => $op["OPORTUNIDADE"],
                    "EMAIL" => $op["EMAIL"],
                    "ID_FREGUESIA" => $op["ID_FREGUESIA"],
                    "ID_CONCELHO" => $op["ID_CONCELHO"],
                    "ID_SEQGA"  => $op["ID_SEQGA"],
                    "ID_SEQHA"  => $op["ID_SEQHA"],
                    "ID_SEQP"   => $op["ID_SEQP"],
                    "ID_SEQD"   => $op["ID_SEQD"],
                    "COUNT"     => $op["COUNT"]
            );
            $oportunities[] = $array;
        }
        
        $data["numCorrs"] = count($oportunidades);


        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_editar_voluntario", $data);
        $this->load->view("templates/footer", $data);

	}

	function instituicao (){

		if ( !$this->dx_auth->is_logged_in())
            redirect("index.php/registo", "refresh");

        //faz o load do modelo de dados necessário
        $this->load->model('Localidades_model');
        /*
		 // load models
        $this->load->model("localidades_model");
		$this->load->model("interesse_ga_pref_model");
		$this->load->model("Voluntario_model");

		
		$data["websiteTitle"] = "FC.UL@Volunteer";
        $data["title"]        = $data["websiteTitle"] . " | Edicao de Voluntarios";
		$data["base_url"]     = base_url();
        $data['distritos']    = $this->localidades_model->getAllDistritos();
		$data['nome'] 			= $this->session->userdata("nome");
		$data['genero']         = $this->session->userdata("gender");
		$data['telefone']  		= $this->session->userdata("phone");
		$data['distritoV'] 		= $this->session->userdata("distrito");
		$data['concelhoV'] 		= $this->session->userdata("concelho");
		$data['freguesiaV'] 		= $this->session->userdata("freguesia");
		$data['interessesV']  = $this->session->userdata("interesses");
		$data['disponibilidadeV'] = $this->session->userdata("disponibilidade");
		$data['grupoAtuacaoV'] = $this->session->userdata("grupos_atuacao");
		$data['habilitacaoAcademicaV'] = $this->session->userdata("habilitacoes_academicas");
		$data['data_de_nascimento'] 		= $this->session->userdata("birth");
		$data['disponibilidade'] = $this->interesse_ga_pref_model->getAllDisponibilidade();
		$data['grupo_atuacao'] = $this->interesse_ga_pref_model->getAllGrupo_Atuacao();
		$data['preferencias'] = $this->interesse_ga_pref_model->getAllPreferencia();
		$data['habilitacao_academica'] = $this->interesse_ga_pref_model->getAllHabilitacao_Academica();
		$data['fotoV'] = $this->session->userdata("foto");*/


		$data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Instituicao";
        $data["base_url"] = base_url();


        $data["nome"]  = $this->session->userdata("nome");
        $data["telefone"] = $this->session->userdata("phone");
        $data["distrito"] = $this->session->userdata("distrito");
        $data["conselho"] = $this->session->userdata("conselho");
        $data["freguesia"] = $this->session->userdata("freguesia");
        $data["nomerep"] = $this->session->userdata("nomerep");
        $data["emailrep"] = $this->session->userdata("emailrep");
        $data["morada"] = $this->session->userdata("morada");
        $data["website"] = $this->session->userdata("website");
        $data["descricao"] = $this->session->userdata("descricao");
        $data['distritos']    = $this->Localidades_model->getAllDistritos();
        
        $this->load->model("correspondencia_model");
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));
        $data["numCorrs"] = count($todos_voluntarios);
		


        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_editar_instituicao", $data);
        $this->load->view("templates/footer", $data);

	}
	
	public function update (){
	
		//load models
		$this->load->model("Voluntario_model");
	
		
		$nome = $this->input->post('nome');
		$data_de_nascimento = $this->input->post('birth');
		$distrito = $this->input->post('distrito');
		$concelho = $this->input->post('concelho');
		$freguesia = $this->input->post('freguesia');
		$preferencias = $this->input->post('interesses');
		$habilitacao_academica = $this->input->post('habilitacoes_academicas');
		$genero = $this->input->post('gender');
		$disponibilidade = $this->input-> post('disponibilidade');
		$grupo_atuacao = $this->input->post('grupos_atuacao');
		$foto = $this->input->post('foto');
		$telefone = $this->input->post('phone');
		
		
		
		$emailV = $this->session->userdata("emailv");

		
		$this->Voluntario_model->update_vol($emailV,$nome,$distrito,$concelho,$freguesia, $disponibilidade,$telefone,$data_de_nascimento,$genero,$foto);
		$this->Voluntario_model->update_GA_vol($emailV,$grupo_atuacao);
		$this->Voluntario_model->update_HA_vol($emailV,$habilitacao_academica);
		$this->Voluntario_model->update_pref_vol($emailV,$preferencias);
		
		
			
		$this->session->unset_userdata(array("nome","emailv","gender","phone","birth","disponibilidade","distrito","concelho","freguesia","interesses","grupo_atuacao","habilitacoes_academicas"));
		$log_data = array(
                        "nome" => $nome,        // STRING
                        "emailv" => $emailV,      // STRING
                        "gender" => $genero,    // M ou F
                        "phone" => $telefone,      // STRING
                        "birth" => $data_de_nascimento,      // STRING
                        "disponibilidade" => $disponibilidade, // INT
                        "distrito" => $distrito,    // INT
                        "concelho" => $concelho,    // INT
                        "freguesia" => $freguesia,  // INT
                        "interesses" => $preferencias,          // ARRAY
                        "grupos_atuacao" => $grupo_atuacao,  // ARRAY
                        "habilitacoes_academicas" => $habilitacao_academica, // ARRAY
						"foto" => $foto
                    );
		
		
		$this->session-> set_userdata($log_data);
		
		
	
	}


	public function update_instituicao (){
	
		//load models
		$this->load->model("Instituicao_model");
	
		
		$nome = $this->input->post('nome');
		$distrito = $this->input->post('distrito');
		$concelho = $this->input->post('concelho');
		$freguesia = $this->input->post('freguesia');
		$telefone = $this->input->post('phone');
		$emailrep = $this->input->post('emailrep');
		$nomerep = $this->input->post('nomerep');
		$morada = $this->input->post('morada');
		$website = $this->input->post('website');
		$descricao = $this->input->post('descricao');
		
		
		
		$email = $this->session->userdata("emailv");

		
		$this->Instituicao_model->update_instituicao($email,$nome,$distrito,$concelho,$freguesia, $emailrep,$telefone,$nomerep,$morada,$website,$descricao);
		
		
			
		$this->session->unset_userdata(array("nome","emailv","emailrep","nomerep","phone","distrito","concelho","freguesia","descricao","morada","website"));
		$log_data = array(
                        "nome" => $nome,        // STRING
                        "emailv" => $email,      // STRING
                        "emailrep" => $emailrep,    // STRING
                        "phone" => $telefone,      // INT
                        "descricao" => $descricao,      // STRING
                        "website" => $website, // STRING
                        "distrito" => $distrito,    // INT
                        "concelho" => $concelho,    // INT
                        "freguesia" => $freguesia,  // INT
                        "morada" => $morada,          // STRING
                        "nomerep" => $nomerep  // STRING
                    );
		
		
		$this->session-> set_userdata($log_data);
		
		
	
	}
}

