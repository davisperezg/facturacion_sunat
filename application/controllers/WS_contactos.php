<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_contactos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('contactos_model');
    }
    
    public function ws_select(){
        $entidad_id = $this->uri->segment(3);
       
        $data['ws_contactos'] = $this->contactos_model->ws_select($entidad_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function delete_item(){
        $contacto_id = $this->uri->segment(3);
        if(isset($contacto_id) && ($contacto_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s"),
                'empleado_delete' => $this->session->userdata('empleado_id')
            );
            $this->contactos_model->modificar($contacto_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    }
    
}