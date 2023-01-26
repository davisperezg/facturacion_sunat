<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_entidades extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_entidades_model');
    }
    
    public function select(){       
        $data['tipo_entidades'] = $this->tipo_entidades_model->ws_select();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}