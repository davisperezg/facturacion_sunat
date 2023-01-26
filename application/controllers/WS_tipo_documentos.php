<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_documentos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_documentos_model');     
    }
    
    public function tipo_documentos(){        
        $data['tipo_documentos'] = $this->tipo_documentos_model->select_formato_json();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function tipo_documentos_all(){        
        $data['tipo_documentos'] = $this->tipo_documentos_model->select2(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function documentos_menos_guia(){        
        $data['tipo_documentos'] = $this->tipo_documentos_model->documentos_menos_guia();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    

  
}