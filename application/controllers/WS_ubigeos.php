<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_ubigeos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('ubigeos_model');
    }
    
    public function ws_departamentos(){               
        $data['departamentos'] = $this->ubigeos_model->select(3, '', '', ' ORDER BY departamento ASC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function cargaProvincias(){        
        $data['provincias'] = $this->ubigeos_model->select_pronvincias($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
    
    public function cargaDistritos(){        
        $data['distritos'] = $this->ubigeos_model->select_distritos($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
    
    public function datos_ubigeo(){
        $data['datos_ubigeo'] = $this->ubigeos_model->datos_ubigeo($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
    
}