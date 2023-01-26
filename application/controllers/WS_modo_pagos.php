<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_modo_pagos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('modo_pagos_model');
    }
    
    public function select_all(){       
        $data = $this->modo_pagos_model->select(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}