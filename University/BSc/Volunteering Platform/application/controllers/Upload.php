<?php

/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 25/04/2016
 * Time: 18:30
 */
class Upload extends CI_Controller
{


    public function parse() {

        // upload config
        $config['upload_path'] = './res/uploads/';
        $config['encrypt_name'] = TRUE;
        $config['allowed_types'] = 'jpg|png';
        $config['overwrite'] = TRUE;
        $config['max_width'] = 0;
        $config['max_height'] = 0;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $output_array = array( "response" => FALSE,
                                   "errors" => $error,
                                   "file_path" => "" );
        }
        else {
            $data = array('upload_data' => $this->upload->data());

            $output_array = array( "response" => TRUE,
                                   "errors" => "",
                                   "file_path" => base_url() . "res/uploads/" . $this->upload->data("file_name")  );
        }

        // send json data to ajax
        $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode($output_array));
    }
}