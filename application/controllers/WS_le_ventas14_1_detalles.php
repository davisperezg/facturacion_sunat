<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_le_ventas14_1_detalles extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('le_ventas14_1_detalles_model');
    }
    
    public function ws_select(){
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);
        
        $condicion = array(
            'MONTH(fecha_emision)' => "=".$mes, 
            'YEAR(fecha_emision)' => "=".$anio
        );        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_ventas14_1_detalles
        $data = $this->le_ventas14_1_detalles_model->select(3, '', array('periodo' => $anio.$mes."00", ' ORDER BY id DESC'));       
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select_item(){
        $data = $this->le_ventas14_1_detalles_model->select(2,'',array('id' => $this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}