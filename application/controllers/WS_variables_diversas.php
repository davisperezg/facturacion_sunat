<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_variables_diversas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');
        $this->load->model('variables_diversas_model');
    }
    
    public function productos_automaticos(){
        $data = $this->variables_diversas_model->productos_automaticos;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function datos_accesorios(){
        $data = $this->variables_diversas_model->datos_accesorios;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function porcentaje_valor_igv(){
        $data = $this->variables_diversas_model->porcentaje_valor_igv;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function select_all(){        
        $data = $this->variables_diversas_model->select(2);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function tipo_de_operaciones(){        
        $data['tipo_de_operacion'] = $this->variables_diversas_model->tipo_de_operacion();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function datos_configuracion(){
        $data['datos_configuracion'] = $this->variables_diversas_model->datos_configuracion(base_url(), date('Y'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function tipo_operaciones(){
        $tipo_operacion_id = $this->uri->segment(3);
        $data = $this->variables_diversas_model->operaciones($tipo_operacion_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function tipo_operaciones_compras(){
        $tipo_operacion_id = $this->uri->segment(3);
        $data = $this->variables_diversas_model->operaciones_compras($tipo_operacion_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ruta_guias(){
        $data = $this->variables_diversas_model->path_ruta_file();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ultima_actualizacion_kardex(){
        $data = array(
            'ultima_actualizacion_kardex'  =>  date("Y-m-d H:i:s")
        );
        $data = $this->variables_diversas_model->modificar(1, $data);
        
        $jsondata = array(            
            'message'       =>  'Operación correcta',
            'fecha_hora'    =>  date("d-m-Y H:i:s")
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function meses(){
        $data = $this->variables_diversas_model->meses();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function update_value(){        
        $data = array(
            $this->uri->segment(3) => $this->uri->segment(4)
        );
        
        $this->variables_diversas_model->modificar(1, $data);
        $respuesta = $this->uri->segment(4);
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);        
    }
}