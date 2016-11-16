<?php

class Login extends CI_Controller
{


	function index (){

        if ($this->dx_auth->is_logged_in())
        {
            redirect('/index.php/perfil', 'refresh');
        }
        //Load The Views
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Registo";
        $data["base_url"] = base_url();
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_login", $data);
        $this->load->view("templates/footer",$data);
    }

    function email(){
        //Data POST
        $email = $this->input->post("email");
        if(!$this->dx_auth->is_email_available($email))
        {
            $result = "TRUE";
        }
        else
        {
            $result = "FALSE";
        }

        $this->output->set_output($result);
    }

    function authenticate(){
        //Data POST
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $this->load->model('Voluntario_model');

        $vol = $this->Voluntario_model->check_voluntario($email);

       if($vol > 0){

        //Persist Session
        $manter_sessao = true;

        //Login
        $result = $this->dx_auth->login($email, $password, $manter_sessao);

        $data = $this->Voluntario_model->get_voluntario_all($email);

        $log_data["nome"] = $data["nome"];
        $log_data["emailv"] = $data["email"];
        $log_data["gender"] = $data["genero"];
        $log_data["phone"] = $data["telefone"];
        $log_data["birth"] = $data["nascimento"];
        $log_data["disponibilidade"] = $data["disponibilidade"];
        $log_data["distrito"] = $data["distrito"];
        $log_data["concelho"] = $data["concelho"];
        $log_data["freguesia"] = $data["freguesia"];
        $log_data["interesses"] = $data["arrPref"];
        $log_data["grupos_atuacao"] = $data["arrGrupo"];
        $log_data["habilitacoes_academicas"] = $data["arrHab"];
        $log_data["foto"] = $data["foto"];
        $log_data["voluntario"] = TRUE;

        $this->session->set_userdata($log_data);
        $this->output->set_output($result);
        }
        else{

         //Load model
         $this->load->model('Instituicao_model');

        //Persist Session
        $manter_sessao = true;

        //Login
        $result = $this->dx_auth->login($email, $password, $manter_sessao);

        $data = $this->Instituicao_model->get_instituicao_all($email);

        $log_data["nome"] = $data["nome"];
        $log_data["emailv"] = $data["email"];
        $log_data["phone"] = $data["telefone"];
        $log_data["morada"] = $data["morada"];
        $log_data["nomerep"] = $data["nomerep"];
        $log_data["emailrep"] = $data["emailrep"];
        $log_data["descricao"] = $data["descricao"];
        $log_data["website"] = $data["website"];
        $log_data["distrito"] = $data["distrito"];
        $log_data["concelho"] = $data["concelho"];
        $log_data["freguesia"] = $data["freguesia"];
        $log_data["voluntario"] = FALSE;

        $this->session->set_userdata($log_data);
        $this->output->set_output($result);

        }
    }
}
?>
