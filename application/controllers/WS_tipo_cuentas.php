<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_cuentas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_cuentas_model');
    }
    
    public function select_js(){       
        $data['tipo_cuentas'] = $this->tipo_cuentas_model->ws_select();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}