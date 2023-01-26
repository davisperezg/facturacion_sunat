<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_unidades extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('unidades_model');
    }
    
    public function select(){               
        $data = $this->unidades_model->select(3,'','',' ORDER BY unidad');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select_activos(){
        $data = $this->unidades_model->select(3,'',array('activo' => 1),' ORDER BY unidad');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){               
        $data['unidades'] = $this->unidades_model->ws_select_activo();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function activar(){
        $activo = ($this->uri->segment(4) == 1) ? 0 : 1;
        $data = $this->unidades_model->modificar($this->uri->segment(3), array('activo' => $activo));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
}