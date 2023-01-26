<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_empresas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('empresas_model');     
    }    
    
    //para el detalle y para el update......
    public function ws_select_item(){
        $jsondata = $this->empresas_model->select2(2);
        echo json_encode($jsondata);
    }
    
    //para el local store
    public function ws_select(){
        $jsondata = $this->empresas_model->select(2, array('id', 'empresa', 'nombre_comercial', 'ruc', 'domicilio_fiscal', 'telefono_fijo', 'telefono_fijo2', 'telefono_movil', 'telefono_movil2', 'foto', 'correo', 'ubigeo', 'urbanizacion', 'usu_secundario_prueba_user', 'usu_secundario_prueba_passoword', 'usu_secundario_produccion_user', 'certi_prueba_nombre', 'certi_prueba_password', 'certi_produccion_nombre', 'modo'));
        echo json_encode($jsondata);
    }    
}