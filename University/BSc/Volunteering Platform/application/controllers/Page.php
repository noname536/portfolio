<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:39
 *
 *
 * ----------> OBSOLETE <------------
 * So usado como exemplo!
 */
class Page extends CI_Controller
{


    /**
     * Loads a view
     * @param string $page
     */
    function view($page = "home")
    {

        // if the view doesn't exist, show 404 error
        if (!file_exists("application/views/pages/view_" . $page . ".php")) {
            show_404();
        }

        // parse data
        $data["title"] = ucfirst($page);
        $data["websiteTitle"] = "FC.UL@Volunteer";

        // load the views
        $this->load->view("templates/header", $data);
        $this->load->view("templates/navigator-default", $data);
        $this->load->view("pages/view_" . $page, $data);
        $this->load->view("templates/footer");

    }
}

