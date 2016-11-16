<?php

class Perfil extends CI_Controller {


    private function _base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function _base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    //função chamada por omissão
    public function index()
    {
        if ($this->dx_auth->is_logged_in())
        {
            if($this->session->userdata("voluntario"))
            {
                $email = $this->session->userdata("emailv");
                $this->_get_voluntario_sessions();
            } else {
                $email= $this->session->userdata("emailv");
                $this->_get_instituicao_sessions();
            }
        }
        else
        {
            redirect('/index.php', 'refresh');
        }
    }

    private function _get_voluntario_sessions()
    {

        //faz o load do modelo de dados necessário
        $this->load->model('Preferencia_model');
        $this->load->model('Habilitacao_model');
        $this->load->model('GrupoAtuacao_model');
        $this->load->model('Localidades_model');
        $this->load->model('Interesse_ga_pref_model');

        // load do conteudo de uma session
        $data["email"] = $this->session->userdata("emailv");
        $data["nome"]  = $this->session->userdata("nome");

        $distrito = $this->session->userdata("distrito");
        $concelho = $this->session->userdata("concelho");
        $freguesia = $this->session->userdata("freguesia");

        $data["distrito"] = $this->Localidades_model->getDistritoById($distrito)["DISTRITO"];
        $data["conselho"] = $this->Localidades_model->getConselhoById($concelho)["CONCELHO"];
        $data["freguesia"] = $this->Localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];

        $data["telefone"] = $this->session->userdata("phone");
        $data["nascimento"] = $this->session->userdata("birth");
        $data["genero"] = ( $this->session->userdata("gender") == "M" ? "Masculino" : "Feminino" );
        $data["foto"] = $this->session->userdata("foto");

        //Disponibilidade
        $all_Disponibilidades= $this->Interesse_ga_pref_model->getAllDisponibilidade();
        $id_Disponibilidade = $this->session->userdata("disponibilidade");
        $data ["disponibilidade"] = $all_Disponibilidades[$id_Disponibilidade-1]["PERIODICIDADE"];

        // preferencias
        $i=0;
        foreach($this->session->userdata("interesses") as $line){
            $data["arrPref"][$i] = $this->Preferencia_model->get_PrefById($line)["PREF"];
            $i++;
        }

        //habilitacoes academicas
        $i=0;
        foreach($this->session->userdata("habilitacoes_academicas") as $line){
          $data["arrHab"][$i] = $this->Habilitacao_model->get_HabById($line)["GRAU"];
          $i++;
        }

        // grupos de atuacao
        $i=0;
        foreach($this->session->userdata("grupos_atuacao") as $line){
          $data["arrGrupo"][$i] = $this->GrupoAtuacao_model->get_GrupoAtaucaoById($line)["GRUPO"];
          $i++;
        }
        
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
                    "COUNT"     => $op["COUNT"],
                    // a data e cifrada de modo a que fique mais obscuro no url
                    "url" => $this->_base64url_encode($op["EMAIL"]. ":". $op["OPORTUNIDADE"])
            );
            $oportunities[] = $array;
        }

        //Titulo
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Perfil";
        $data["base_url"] = base_url();
        $data["numCorrs"] = count($oportunities);

        //Do Controlador para a view

        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_voluntario", $data);
        $this->load->view("templates/footer", $data);
    }

    private function _get_instituicao_sessions()
    {

        //faz o load do modelo de dados necessário
        $this->load->model('Localidades_model');
        $this->load->model('correspondencia_model');

        //Titulo
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Instituicao";
        $data["base_url"] = base_url();

        $distrito = $this->session->userdata("distrito");
        $concelho = $this->session->userdata("concelho");
        $freguesia = $this->session->userdata("freguesia");
        
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));

        $data["nome"]  = $this->session->userdata("nome");
        $data["telefone"] = $this->session->userdata("phone");
        $data["distrito"] = $this->Localidades_model->getDistritoById($distrito)["DISTRITO"];
        $data["conselho"] = $this->Localidades_model->getConselhoById($concelho)["CONCELHO"];
        $data["freguesia"] = $this->Localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];
        $data["nome_rep"] = $this->session->userdata("nomerep");
        $data["email_rep"] = $this->session->userdata("emailrep");
        $data["morada"] = $this->session->userdata("morada");
        $data["website"] = $this->session->userdata("website");
        $data["descricao"] = $this->session->userdata("descricao");
        $data["numCorrs"]  = count($todos_voluntarios);
        
        //Do Controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_instituicao", $data);
        $this->load->view("templates/footer", $data);
    }

    public function logout() {
        if ( $this->dx_auth->is_logged_in())
        {
            $this->dx_auth->logout();

            redirect("index.php/home");
        }
    }

    public function oportunidades() {

        if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        // load models
        $this->load->model("oportunidades_model");
        $this->load->model("correspondencia_model");

        // titulo
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Oportunidades";
        $data["base_url"] = base_url();
        $data["nome"] = $this->session->userdata("nome");

        // get all oportunities
        $oportunitiesRaw = $this->oportunidades_model->get_all_oportunidades($this->session->userdata("emailv"));
        $oportunities = array();
        foreach( $oportunitiesRaw as $op ) {
            $array = array(
                    "nome" => $op["FUNCAO"],
                    // a data e cifrada de modo a que fique mais obscuro no url
                    "url" => $this->_base64url_encode($op["FUNCAO"])
            );
            $oportunities[] = $array;
        }

        $data["oportunities"] = $oportunities;
        
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));
        $data["numCorrs"] = count($todos_voluntarios);

        // do controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_lista_oportunidades", $data);
        $this->load->view("templates/footer", $data);

    }

    public function oportunidade($id_cifrado) {
        if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        if ( empty($id_cifrado) )
            redirect('/index.php/perfil/oportunidades/', 'refresh');

        // decifra
        $funcao = $this->_base64url_decode($id_cifrado);

        // load models
        $this->load->model("localidades_model");
        $this->load->model("interesse_ga_pref_model");
        $this->load->model('Preferencia_model');
        $this->load->model('Habilitacao_model');
        $this->load->model('GrupoAtuacao_model');
        $this->load->model("oportunidades_model");
        $this->load->model("correspondencia_model");


        // buscar valores da oportunidade
        $oportunidadeRaw = $this->oportunidades_model->get_oportunidade($this->session->userdata("emailv"), $funcao);
       
        $distrito               = $oportunidadeRaw["ID_DISTRITO"];
        $concelho               = $oportunidadeRaw["ID_CONCELHO"];
        $freguesia              = $oportunidadeRaw["ID_FREGUESIA"];
        $interesse              = $oportunidadeRaw["ID_SEQP"];
        $grupo_atuacao          = $oportunidadeRaw["ID_SEQGA"];
        $habilitacao_academica  = $oportunidadeRaw["ID_SEQHA"];
        $disponibilidade        = $oportunidadeRaw["ID_SEQD"];

        $all_disponibilidades = $this->interesse_ga_pref_model->getAllDisponibilidade();

        // titulo
        $data["websiteTitle"]          = "Volunteer@FC.UL";
        $data["title"]                 = $data["websiteTitle"] . " | Ver Oportunidade";
        $data["base_url"]              = base_url();
        $data["funcao"]                = $funcao;
        $data["distrito"]              = $this->localidades_model->getDistritoById($distrito)["DISTRITO"];
        $data["conselho"]              = $this->localidades_model->getConselhoById($concelho)["CONCELHO"];
        $data["freguesia"]             = $this->localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];
        $data["interesse"]             = $this->Preferencia_model->get_PrefById($interesse)["PREF"];
        $data["habilitacao_academica"] = $this->Habilitacao_model->get_HabById($habilitacao_academica)["GRAU"];
        $data["grupo_atuacao"]         = $this->GrupoAtuacao_model->get_GrupoAtaucaoById($grupo_atuacao)["GRUPO"];
        $data["disponibilidade"]       = $all_disponibilidades[$disponibilidade -1] ["PERIODICIDADE"];
        
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));
        $data["numCorrs"] = count($todos_voluntarios);


        // do controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_oportunidade", $data);
        $this->load->view("templates/footer", $data);

    }

    public function nova_oportunidade() {

        if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        // load models
        $this->load->model("localidades_model");
        $this->load->model("oportunidades_model");
        $this->load->model("interesse_ga_pref_model");
        
        // titulo
        $data["websiteTitle"]          = "Volunteer@FC.UL";
        $data["title"]                 = $data["websiteTitle"] . " | Criar Oportunidade";
        $data["base_url"]              = base_url();
        $data['distritos']             = $this->localidades_model->getAllDistritos();
        $data['disponibilidade']       = $this->interesse_ga_pref_model->getAllDisponibilidade();
        $data['grupo_atuacao']         = $this->interesse_ga_pref_model->getAllGrupo_Atuacao();
        $data['preferencias']          = $this->interesse_ga_pref_model->getAllPreferencia();
        $data['habilitacao_academica'] = $this->interesse_ga_pref_model->getAllHabilitacao_academica();

        // do controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_registo_oportunidade", $data);
        $this->load->view("templates/footer", $data);      
    }

    
    public function submeter_oportunidade() 
    {
        if ( !$this->dx_auth->is_logged_in())
            redirect('/index.php', 'refresh');

        // load models
        $this->load->model("oportunidades_model");

        // data from post request                
        $funcao          = $this->input->post("nome");
        $distrito        = $this->input->post("distrito");
        $concelho        = $this->input->post("concelho");
        $freguesia       = $this->input->post("freguesia");
        $interesse       = $this->input->post("interesse");
        $grupo_atuacao   = $this->input->post("grupos_atuacao");
        $habilitacoes    = $this->input->post("habilitacoes");
        $disponibilidade = $this->input->post("disponibilidade");
        $email = $this->session->userdata("emailv"); // institute email

        // insert into db                
        $this->oportunidades_model->insert_oportunidade($email,$funcao,$distrito,$concelho,$freguesia,
            $interesse,$grupo_atuacao,$habilitacoes,$disponibilidade);  

    }

    public function remove_oportunidade($encodeData) 
    {
        if (!$this->dx_auth->is_logged_in()){
            redirect('/index.php', 'refresh');
        }

        if (empty($encodeData)){
            redirect('/index.php', 'refresh');
        }

        // load models
        $this->load->model("oportunidades_model");

        // session
        $email = $this->session->userdata("emailv"); // institute email

        // decode
        $funcao = $this->_base64url_decode($encodeData);

        // execute
        $this->oportunidades_model->remove_oportunidade($email, $funcao);

        //redirect
        redirect('/index.php/perfil/oportunidades', 'refresh');
    }

    public function editar_oportunidade($encodeData) 
    {
        if (!$this->dx_auth->is_logged_in())
            redirect('/index.php', 'refresh');

        if (empty($encodeData)){
            redirect('/index.php', 'refresh');
        }

        // load models
        $this->load->model("localidades_model");
        $this->load->model("interesse_ga_pref_model");
        $this->load->model('Preferencia_model');
        $this->load->model('Habilitacao_model');
        $this->load->model('GrupoAtuacao_model');
        $this->load->model("oportunidades_model");
        $this->load->model("correspondencia_model");

        // session
        $email = $this->session->userdata("emailv"); // institute email

        // decode
        $funcao = $this->_base64url_decode($encodeData);

        // execute
        $query = $this->oportunidades_model->get_oportunidade($email, $funcao);

        $distrito               = $query["ID_DISTRITO"];
        $concelho               = $query["ID_CONCELHO"];
        $freguesia              = $query["ID_FREGUESIA"];

        // Actual 
        $data["preferencia"]           = $query["ID_SEQP"];
        $data["grupo_atuacao"]         = $query["ID_SEQGA"];
        $data["habilitacao_academica"] = $query["ID_SEQHA"];
        $data["disponibilidade"]       = $query["ID_SEQD"];

        $all_disponibilidades = $this->interesse_ga_pref_model->getAllDisponibilidade();

        // Titule
        $data["websiteTitle"]           = "Volunteer@FC.UL";
        $data["title"]                  = $data["websiteTitle"] . " | Ver Oportunidade";
        $data["base_url"]               = base_url();

        $data["funcao"]                 = $funcao;
        
        // Address
        $data['distritos']             = $this->localidades_model->getAllDistritos();
                    
        // All
        $data["preferencias"]            = $this->interesse_ga_pref_model->getAllPreferencia();
        $data["grupos_atuacoes"]         = $this->interesse_ga_pref_model->getAllGrupo_Atuacao();
        $data["habilitacoes_academicas"] = $this->interesse_ga_pref_model->getAllHabilitacao_academica();
        $data["disponibilidades"]        = $this->interesse_ga_pref_model->getAllDisponibilidade();
        
        // num vols
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));
        $data["numCorrs"] = count($todos_voluntarios);


        // do controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_editar_oportunidade", $data);
        $this->load->view("templates/footer", $data);

        // $data["distrito"]               = $this->localidades_model->getDistritoById($distrito)["DISTRITO"];
        // $data["conselho"]               = $this->localidades_model->getConselhoById($concelho)["CONCELHO"];
        // $data["freguesia"]              = $this->localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];
    }

    public function atualizar_oportunidade() 
    {
        if ( !$this->dx_auth->is_logged_in())
            redirect('/index.php', 'refresh');

        // load models
        $this->load->model("oportunidades_model");
        
        // institute email
        $email = $this->session->userdata("emailv"); 

        // data from post request                
        $funcao          = $this->input->post("nome");
        $distrito        = $this->input->post("distrito");
        $concelho        = $this->input->post("concelho");
        $freguesia       = $this->input->post("freguesia");
        $interesse       = $this->input->post("interesse");
        $grupo_atuacao   = $this->input->post("grupos_atuacao");
        $habilitacoes    = $this->input->post("habilitacoes");
        $disponibilidade = $this->input->post("disponibilidade");        

        // update into db                
        $this->oportunidades_model->update_oportunidade($email,$funcao,$distrito,$concelho,$freguesia,
            $interesse,$grupo_atuacao,$habilitacoes,$disponibilidade);
        
        // send json data to ajax
        $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode(array( "url" => $this->_base64url_encode($funcao) )));
        
        
    }


    public function exist_oportunidade(){
        
         // load models
        $this->load->model("oportunidades_model");

        // post request
        $nome = $this->input->post("nome");
        $email = $this->session->userdata("emailv"); 
        
        // Exist That Name Already
        $r = $this->oportunidades_model->exist_oportunidade($email,$nome);
        
        $this->output->set_output($r);        
    }    
}
?>
