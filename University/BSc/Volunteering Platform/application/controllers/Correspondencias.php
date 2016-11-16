<?php

class Correspondencias extends CI_Controller {

    //
    // Funcoes de enconding
    //
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
                $this->_oportunidades();
            } 
            else 
            {
                $this->_voluntarios();
            }
        }
        else
        {
            redirect('/index.php', 'refresh');
        }
    }
    
    //
    // Correspondencias entre 1 instituicao e varios voluntarios
    //
    private function _voluntarios() {
        
        $this->load->model('correspondencia_model');
        
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));
        
        // contagem dos valores        é & porque alteramos o data durante o loop
        foreach( $todos_voluntarios as &$data ) 
        {
            $count = 0;
            
            /*
            echo "<p>" . $voluntario . ": ";
            print_r($data);
            echo "</p>";
            */
            
            if ( $data["ID_DISTRITO"] )
                $count++;
            if ( $data["ID_CONCELHO"] )
                $count++;
            if ( $data["ID_FREGUESIA"] )
                $count++;
            if ( $data["ID_SEQD"] )
                $count++;
            if ( $data["ID_SEQP"] )
                $count++;
            if ( $data["ID_SEQHA"] )
                $count++;
            if ( $data["ID_SEQGA"] )
                $count++;
            
            $data["COUNT"] = ($count / 7) * 100;
            $data["url"] = $this->_base64url_encode($data["EMAIL"]);
        }
        
        // realizar o sort no array
        usort( $todos_voluntarios, function($a, $b) { return $a["COUNT"] < $b["COUNT"]; });
        
        // titulo
        $data["websiteTitle"]     = "Volunteer@FC.UL";
        $data["title"]            = $data["websiteTitle"] . " | Lista Voluntarios";
        $data["base_url"]         = base_url();
        $data["voluntarios"]      = $todos_voluntarios;
        $data["numCorrs"]         = count($todos_voluntarios);
        
        // do Controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_correspondencia_voluntarios", $data);
        $this->load->view("templates/footer", $data);
    }

    public function voluntario($id_cifrado)
    {
        if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        if ( empty($id_cifrado) )
            redirect('/index.php/perfil/voluntarios/', 'refresh');

        $email = $this->_base64url_decode($id_cifrado);

        $this->load->model("localidades_model");
        $this->load->model('Voluntario_model');
        $this->load->model('Interesse_ga_pref_model');
        $this->load->model('Preferencia_model');
        $this->load->model('Habilitacao_model');
        $this->load->model('GrupoAtuacao_model');
        $data = $this->Voluntario_model->get_voluntario_all($email);

        $distrito               = $data["distrito"];
        $concelho               = $data["concelho"];
        $freguesia              = $data["freguesia"];
        $preferencia            = $data["arrPref"];
        $grupo_atuacao          = $data["arrGrupo"];
        $habilitacao_academica  = $data["arrHab"];
        $disponibilidade        = $data["disponibilidade"];

        $data["distrito"] = $this->localidades_model->getDistritoById($distrito)["DISTRITO"];
        $data["concelho"] = $this->localidades_model->getConselhoById($concelho)["CONCELHO"];
        $data["freguesia"] = $this->localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];

        $all_Disponibilidades= $this->Interesse_ga_pref_model->getAllDisponibilidade();
        $data ["disponibilidade"] = $all_Disponibilidades[$disponibilidade-1]["PERIODICIDADE"];

        // preferencias
        $i=0;
        foreach($preferencia as $line){
            $data["arrPref"][$i] = $this->Preferencia_model->get_PrefById($line)["PREF"];
            $i++;
        }

        //habilitacoes academicas
        $i=0;
        foreach($habilitacao_academica as $line){
          $data["arrHab"][$i] = $this->Habilitacao_model->get_HabById($line)["GRAU"];
          $i++;
        }

        // grupos de atuacao
        $i=0;
        foreach($grupo_atuacao as $line){
          $data["arrGrupo"][$i] = $this->GrupoAtuacao_model->get_GrupoAtaucaoById($line)["GRUPO"];
          $i++;
        }
        
        // num inst
        $this->load->model('correspondencia_model');
        $todos_voluntarios = 
            $this->correspondencia_model->get_voluntarios_from_instituicao($this->session->userdata("emailv"));

        //Titulo
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Instituicao";
        $data["base_url"] = base_url();
        $data["numCorrs"] = count($todos_voluntarios);

        //Do Controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_vol_visitante", $data);
        $this->load->view("templates/footer", $data);
    }   

    private function _oportunidades() {
        
        // Oportunidades
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
    


        // Titulo
        $data["websiteTitle"]     = "Volunteer@FC.UL";
        $data["title"]            = $data["websiteTitle"] . " | Lista Oportunidades";
        $data["base_url"]         = base_url();
        $data["oportunities"]     = $oportunities;
        $data["numCorrs"]         = count($oportunities);
        
        // Controlador para View
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_correspondencia_oportunidades", $data);
        $this->load->view("templates/footer", $data);
    }

    public function oportunidade($encoded){

        if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        if ( empty($encoded) )
            redirect('/index.php/perfil/oportunidades/', 'refresh');

         // load models
        $this->load->model("localidades_model");
        $this->load->model("interesse_ga_pref_model");
        $this->load->model('Preferencia_model');
        $this->load->model('Habilitacao_model');
        $this->load->model('GrupoAtuacao_model');
        $this->load->model("oportunidades_model");

        $decoded = $this->_base64url_decode($encoded);



        $emailFuncao = explode (":",$decoded);
        $emailv = $emailFuncao[0];
        $encodeInst = $this->_base64url_encode($emailv);
        
        $funcao = $emailFuncao[1];
        $oportunidadeRaw = $this->oportunidades_model->get_oportunidade($emailv,$funcao);

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
        $data["disponibilidade"]       = $all_disponibilidades[$disponibilidade-1]["PERIODICIDADE"];
        $data["url"]                   = $encodeInst;


        // do controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_oportunidade_com_butao", $data);
        $this->load->view("templates/footer", $data);


    }

    public function instituicao ($inst_encode){

         // load models
        $this->load->model("Instituicao_model");
        $this->load->model("Localidades_model");

         if ( !$this->dx_auth->is_logged_in() )
            redirect('/index.php', 'refresh');

        $decode_inst = $this->_base64url_decode($inst_encode);
        $instituicaoRaw = $this->Instituicao_model->get_Instituicao($decode_inst);

        $distrito               = $instituicaoRaw["ID_DISTRITO"];
        $concelho               = $instituicaoRaw["ID_CONCELHO"];
        $freguesia              = $instituicaoRaw["ID_FREGUESIA"];
       

        //Titulo
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Instituicao";
        $data["base_url"] = base_url();


        $data["nome"]  = $instituicaoRaw["NOME"];
        $data["telefone"] = $instituicaoRaw["TELEFONE"];
        $data["distrito"] = $this->Localidades_model->getDistritoById($distrito)["DISTRITO"];
        $data["conselho"] = $this->Localidades_model->getConselhoById($concelho)["CONCELHO"];
        $data["freguesia"] = $this->Localidades_model->getFreguesiaById($freguesia)["FREGUESIA"];
        $data["nome_rep"] = $instituicaoRaw["NOME_REP"];
        $data["email_rep"] = $instituicaoRaw["EMAIL_REP"];
        $data["morada"] = $instituicaoRaw["MORADA"];
        $data["website"] = $instituicaoRaw["WEBSITE"];
        $data["descricao"] = $instituicaoRaw["DESCRICAO"];
        //Do Controlador para a view
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-perfil", $data);
        $this->load->view("pages/view_perfil_instituicao_correspondencia", $data);
        $this->load->view("templates/footer", $data);

    } 
}
?>
