<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_operaciones extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('variables_diversas_model');
    }
    
    public function tipo_de_operaciones(){        
        $data['tipo_de_operacion'] = $this->variables_diversas_model->tipo_de_operacion();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}