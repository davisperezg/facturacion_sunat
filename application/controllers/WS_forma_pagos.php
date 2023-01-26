<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_forma_pagos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('forma_pagos_model');
    }
    
    public function select_all(){       
        $data = $this->forma_pagos_model->select(3, '', '', ' ORDER BY id ASC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}