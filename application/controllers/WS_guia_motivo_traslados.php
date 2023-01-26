<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_guia_motivo_traslados extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('variables_diversas_model');
        $this->load->model('guia_motivo_traslados_model');
        
        $this->load->helper('ayuda');
    }
    
    public function ws_select(){      
        $data = $this->guia_motivo_traslados_model->select(3, '', '', ' ORDER BY id ASC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //return un dato especificado
    public function select_unDato(){
        $id = $this->uri->segment(3);
        $dato_select = $this->uri->segment(4);
        $jsondata = $this->guia_motivo_traslados_model->select(2, array($dato_select), array('id' => $id));
        echo json_encode($jsondata);        
    }
    
}