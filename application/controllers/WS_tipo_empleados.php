<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_empleados extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_empleados_model');
        $this->load->model('modulos_model');
    }
    
    //para la tabla
    public function ws_select(){
        $data['ws_tipo_empleados'] = $this->tipo_empleados_model->select(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_modulos_all(){
        $data= $this->modulos_model->modulos_all();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_modulos_usados(){
        $tipo_empleado_id = $this->uri->segment(3);
        $data= $this->modulos_model->modulos_usados($tipo_empleado_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }        
    
}