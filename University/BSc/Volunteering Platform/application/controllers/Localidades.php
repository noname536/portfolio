<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 25/04/2016
 * Time: 18:30
 */
class Localidades extends CI_Controller
{
    public function parse() {
        // parse input
        $distrito  = $this->input->post("distrito");
        $concelho  = $this->input->post("concelho");

        $this->load->model("localidades_model");

        // envia o json
        if ( $distrito != false && $distrito != null ) {
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($this->localidades_model->getConcelhos($distrito)));
        }
        if ( $concelho != false && $concelho != null) {
            $this->output
                ->set_content_type("application/json")
                ->set_output(json_encode($this->localidades_model->getFreguesias($concelho)));
        }
    }
}