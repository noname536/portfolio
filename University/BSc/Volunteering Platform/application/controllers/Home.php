<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:39
 */
class Home extends CI_Controller
{

    function index()
    {

        if ( $this->dx_auth->is_logged_in() )
            redirect("index.php/perfil", "refresh");

        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Homepage";
        $data["base_url"] = base_url();

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_home", $data);
        $this->load->view("templates/footer", $data);

    }

    function about()
    {

        
    }

    function faculdade(){
        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Homepage";
        $data["base_url"] = base_url();

        if ( $this->dx_auth->is_logged_in() )
            $this->load->view("templates/navigator-perfil", $data);
        else
            $this->load->view("templates/navigator-default", $data);

        $this->load->view("templates/header", $data);
        $this->load->view("pages/view_faculdade", $data);
        $this->load->view("templates/footer", $data);
    }

    function cadeira(){
        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Homepage";
        $data["base_url"] = base_url();

        if ( $this->dx_auth->is_logged_in() )
            $this->load->view("templates/navigator-perfil", $data);
        else
            $this->load->view("templates/navigator-default", $data);

        $this->load->view("templates/header", $data);
        $this->load->view("pages/view_cadeira", $data);
        $this->load->view("templates/footer", $data);
    }

    function alunos(){
        // parse data
        $data["websiteTitle"] = "Volunteer@FC.UL";
        $data["title"] = $data["websiteTitle"] . " | Homepage";
        $data["base_url"] = base_url();

        if ( $this->dx_auth->is_logged_in() )
            $this->load->view("templates/navigator-perfil", $data);
        else
            $this->load->view("templates/navigator-default", $data);

        $this->load->view("templates/header", $data);
        $this->load->view("pages/view_alunos", $data);
        $this->load->view("templates/footer", $data);
    }
}
