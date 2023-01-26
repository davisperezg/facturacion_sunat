<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_kardex_promedio extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('kardex_promedio_model');
        
        $this->load->helper('ayuda');
    }
    
    public function select(){
        $data = $this->kardex_promedio_model->select(3, '', array('producto_id' => '='.$this->uri->segment(3), 'fecha' => 'BETWEEN '."'".$this->uri->segment(4)."' AND "."'".$this->uri->segment(5)."'"), 'ORDER BY fecha, id');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function mas_vendidos_cantidad(){
        $fecha_emision_inicio   = "'" . format_fecha_0000_00_00($this->uri->segment(3)) . "'";
        $fecha_emision_final    = "'" . format_fecha_0000_00_00($this->uri->segment(4)) . "'";                
        
        $data = $this->kardex_promedio_model->mas_vendidos_cantidad($fecha_emision_inicio, $fecha_emision_final);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function actualizar_datos(){
        $data = $this->kardex_promedio_model->actualizar_datos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function reporte_mensual(){
        $data = $this->kardex_promedio_model->reporte_mensual($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
}