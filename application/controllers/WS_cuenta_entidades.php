<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_cuenta_entidades extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('cuenta_entidades_model');
    }
    
    public function ws_select(){
        $entidad_id = $this->uri->segment(3);
       
        $data['ws_cuentas'] = $this->cuenta_entidades_model->ws_select($entidad_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function delete_item(){
        $cuenta_entidad_id = $this->uri->segment(3);
        if(isset($cuenta_entidad_id) && ($cuenta_entidad_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s"),
                'empleado_delete' => $this->session->userdata('empleado_id')
            );
            $this->cuenta_entidades_model->modificar($cuenta_entidad_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    }
    
}