<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_venta_detalles extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('venta_detalles_model');
        $this->load->model('variables_diversas_model');
        
        $this->load->helper('ayuda');
    }    
    
    public function ws_detalle(){
        $venta_id = $this->uri->segment(3);
        $data = $this->venta_detalles_model->query_detalle($venta_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_detalle_guia(){
        $serie = $this->uri->segment(3);
        $numero = $this->uri->segment(4);
        $data = $this->venta_detalles_model->query_detalle_guia($serie, $numero);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
}