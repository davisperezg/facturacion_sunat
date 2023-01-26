<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_pedido_almacen_detalles extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('pedido_almacen_detalles_model');
        $this->load->model('variables_diversas_model');        
        $this->load->helper('ayuda');
    }    
    
    public function ws_detalle(){
        $pedido_almacen_id = $this->uri->segment(3);
        $data = $this->pedido_almacen_detalles_model->query_detalle(3, array('pde.producto producto', 'pde.cantidad cantidad', 'pde.producto_id producto_id', 'und.unidad unidad'), array('pedido_almacen_id' => $pedido_almacen_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}