<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:39
 */
class Registo extends CI_Controller
{



    function index()
    {
        // esta logged?
        if ( $this->dx_auth->is_logged_in())
            redirect("index.php/perfil/", "refresh");

        // parse input
        $voluntario = $this->input->post("voluntario");
        $email      = $this->input->post("email");
        $emailCheck = $this->input->post("email_check");
        $password   = $this->input->post("password");

        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Registo";
        $data["base_url"] = base_url();

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_registo", $data);
        $this->load->view("templates/footer", $data);

        // verifica se um utilizador ja esta registado com esse email
        if ( !empty($emailCheck) ) {
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode(array( "available" => $this->dx_auth->is_email_available($emailCheck))));
        }

        // valida a password/email recebidos de um POST ajax
        if ( $email != false && $password != false ) {
            if ( $this->_isEmail($email) && $password != "" ) {
                $authData = array("email"    => $email,
                                  "password" => $password);


                // store in session
                $this->session->set_tempdata($authData, 300);

                // send json data to ajax
                $this->output
                    ->set_content_type("application/json")
                    ->set_output(json_encode(array( "response" => true )));
            }
            else {
                // send json data to ajax
                $this->output
                    ->set_content_type("application/json")
                    ->set_output(json_encode(array( "response" => false )));
            }
        }
    }

    /**
     *
     */
    function voluntario()
    {
        // esta logged?
        if ( $this->dx_auth->is_logged_in())
            redirect("index.php/perfil/", "refresh");

        // load models
        $this->load->model("localidades_model");
        $this->load->model("interesse_ga_pref_model");

        // parse session data
        // se nao existir nada, fazer redirect de volta ao inicio
        if ( $this->session->userdata("email") == NULL
            || $this->session->userdata("password") == NULL )
            redirect("index.php/registo/", "refresh");

        // parse data
        $data["websiteTitle"]          = "Volunteer@FC.UL";
        $data["title"]                 = $data["websiteTitle"] . " | Registo de Voluntarios";
        $data["base_url"]              = base_url();
        $data['distritos']             = $this->localidades_model->getAllDistritos();
        $data['disponibilidade']       = $this->interesse_ga_pref_model->getAllDisponibilidade();
        $data['grupo_atuacao']         = $this->interesse_ga_pref_model->getAllGrupo_Atuacao();
        $data['preferencias']          = $this->interesse_ga_pref_model->getAllPreferencia();
        $data['habilitacao_academica'] = $this->interesse_ga_pref_model->getAllHabilitacao_academica();

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_registo_voluntario", $data);
        $this->load->view("templates/footer", $data);

    }

    function confirmacao_voluntario() {
        // parse input
        $email      = $this->session->userdata("email");
        $password   = $this->session->userdata("password");
        $nome       = $this->input->post("nome");
        $phone      = $this->input->post("phone");
        $gender     = $this->input->post("gender");
        $birth      = $this->input->post("birth");
        $disponibilidade = intval($this->input->post("disponibilidade"));
        $distrito        = intval($this->input->post("distrito"));
        $concelho        = intval($this->input->post("concelho"));
        $freguesia       = intval($this->input->post("freguesia"));
        $interesses      = $this->input->post("interesses");
        $grupos_atuacao  = $this->input->post("grupos_atuacao");
        $habilitacoes_academicas = $this->input->post("habilitacoes_academicas");
        $foto            = $this->input->post("foto");

        $answer = FALSE;

        // validation
        if ( $email == NULL || $password == NULL ) {
            redirect("index.php/registo/", "refresh");
        }
        else if ( empty($email) || empty($phone) || empty($gender) || empty($birth)
            || empty($disponibilidade) || empty($distrito) || empty($concelho) || empty($freguesia)
            || empty($interesses) || empty($grupos_atuacao) || empty($habilitacoes_academicas) ) {
            redirect("index.php/registo/voluntario/", "refresh");
        }
        else {

            $this->load->model("voluntario_model");

            // registar o voluntario
            if ( !$this->dx_auth->is_logged_in() ) {
                $registo_ok = $this->dx_auth->register("voluntario", $password, $email);
                $this->voluntario_model->insertVoluntario($email, $password, $nome, $phone, $gender, $birth,
                    $distrito, $concelho, $freguesia, $disponibilidade, $foto);


                foreach( $interesses as $interesse ) {
                    $this->voluntario_model->insertInteresse($email, intval($interesse));
                }

                foreach( $grupos_atuacao as $grupo ) {
                    $this->voluntario_model->insertGrupoAtuacao($email, intval($grupo));
                }

                foreach( $habilitacoes_academicas as $habilitacao ) {
                    $this->voluntario_model->insertHabilitacaoAcademica($email, intval($habilitacao));
                }

                // criar variaveis de sessao
                if ( $registo_ok ) {
                    $log_keys = array(
                        "nome",        // STRING
                        "emailv",      // STRING
                        "gender",    // M ou F
                        "phone",      // STRING
                        "birth",      // STRING
                        "disponibilidade", // INT
                        "distrito",    // INT
                        "concelho",    // INT
                        "freguesia",  // INT
                        "interesses",          // ARRAY
                        "grupos_atuacao",  // ARRAY
                        "habilitacoes_academicas", // ARRAY
                        "foto",      // STRING
                        "voluntario" // BOOLEAN
                    );

                    $this->session->unset_userdata($log_keys);

                    $log_data = array(
                        "nome" => $nome,        // STRING
                        "emailv" => $email,      // STRING
                        "gender" => $gender,    // M ou F
                        "phone" => $phone,      // STRING
                        "birth" => $birth,      // STRING
                        "disponibilidade" => $disponibilidade, // INT
                        "distrito" => $distrito,    // INT
                        "concelho" => $concelho,    // INT
                        "freguesia" => $freguesia,  // INT
                        "interesses" => $interesses,          // ARRAY
                        "grupos_atuacao" => $grupos_atuacao,  // ARRAY
                        "habilitacoes_academicas" => $habilitacoes_academicas, // ARRAY
                        "foto" => $foto, // STRING
                        "voluntario" => TRUE
                    );

                    $this->session->set_userdata($log_data);

                    // autentica o utilizador
                    $this->dx_auth->login($email, $password);
                }

                $answer = $registo_ok;
            }
            else {
                redirect("index.php/perfil/", "refresh");
            }
        }

        $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode(array( "response" => $answer )));
    }

    function instituicao()
    {
        // esta logged?
        if ( $this->dx_auth->is_logged_in())
            redirect("index.php/perfil/", "refresh");

        // load models
        $this->load->model("localidades_model");

        // parse session data
        // se nao existir nada, fazer redirect de volta ao inicio
        if ( $this->session->userdata("email") == NULL
            || $this->session->userdata("password") == NULL )
            redirect("index.php/registo/", "refresh");

        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Registo de Instituicoes";
        $data["base_url"] = base_url();
        $data['distritos'] = $this->localidades_model->getAllDistritos();

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_registo_instituicao", $data);
        $this->load->view("templates/footer", $data);
    }

    function confirmacao_instituicao()
    {
        // parse input
        $email      = $this->session->userdata("email");
        $password   = $this->session->userdata("password");
        $nome       = $this->input->post("nome");
        $phone      = $this->input->post("phone");
        $website    = $this->input->post("website");
        $morada     = $this->input->post("morada");
        $descricao  = $this->input->post("descricao");
        $email_rep  = $this->input->post("email_rep");
        $nome_rep   = $this->input->post("nome_rep");
        $distrito        = intval($this->input->post("distrito"));
        $concelho        = intval($this->input->post("concelho"));
        $freguesia       = intval($this->input->post("freguesia"));

        $answer = FALSE;

        // validation
        if ( $email == NULL || $password == NULL ) {
            redirect("index.php/registo/", "refresh");
        }
        else if ( empty($email) || empty($phone) || empty($website) || empty($morada)
            || empty($descricao) || empty($distrito) || empty($concelho) || empty($freguesia)
            || empty($email_rep) || empty($nome_rep) ) {
            redirect("index.php/registo/instituicao/", "refresh");
        }
        else {

            $this->load->model("instituicao_model");

            // registar o instituicao
            if ( !$this->dx_auth->is_logged_in() ) {
                $registo_ok = $this->dx_auth->register("instituicao", $password, $email);
                $this->instituicao_model->insert_instituicao($email, $password, $nome, $distrito, $concelho, $freguesia,
                                                             $email_rep, $phone, $nome_rep, $morada, $website, $descricao);

                // criar variaveis de sessao
                if ( $registo_ok ) {
                    $log_keys = array(
                        "nome",        // STRING
                        "emailv",      // STRING
                        "phone",       // STRING
                        "morada",      // STRING
                        "nomerep",     // STRING
                        "emailrep",    // STRING
                        "descricao",   // STRING
                        "website",     // STRING
                        "distrito",    // INT
                        "concelho",    // INT
                        "freguesia",   // INT
                        "voluntario"   // BOOLEAN
                    );

                    $this->session->unset_userdata($log_keys);

                    $log_data = array(
                        "nome"      => $nome,        // STRING
                        "emailv"    => $email,      // STRING
                        "phone"     => $phone,       // STRING
                        "morada"    => $morada,      // STRING
                        "nomerep"   => $nome_rep,     // STRING
                        "emailrep"  => $email_rep,    // STRING
                        "descricao" => $descricao,   // STRING
                        "website"   => $website,     // STRING
                        "distrito"  => $distrito,    // INT
                        "concelho"  => $concelho,    // INT
                        "freguesia" => $freguesia,   // INT
                        "voluntario" => FALSE
                    );

                    $this->session->set_userdata($log_data);

                    // autentica o utilizador
                    $this->dx_auth->login($email, $password);
                }

                $answer = $registo_ok;
            }
            else {
                redirect("index.php/perfil/", "refresh");
            }
        }

        $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode(array( "response" => $answer )));
    }

    //
    //
    //
    private function _isEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
