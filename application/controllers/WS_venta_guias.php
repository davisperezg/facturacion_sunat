<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_venta_guias extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('venta_guias_model');       
        $this->load->helper('ayuda');
    }    
    
    public function ws_select_ventas(){
        $guia_id = $this->uri->segment(3);
        $data = $this->venta_guias_model->select_ventas($guia_id);        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }    
    
}