<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_empleados extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('empleados_model');     
    }
    
    //para la tabla
    public function ws_select(){
        $data['ws_select_empleados'] = $this->empleados_model->query_standar(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //para la tabla
    public function select(){        
        $campos = array();
        for($i = 0; $i < count($_GET['campos']); $i++){
            $campos = ($campos == array()) ? array($_GET['campos'][$i]) : array_merge($campos, array($_GET['campos'][$i]));
        }
        $data = $this->empleados_model->select(3, $campos, array('fecha_delete' => 'IS NULL'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //para el detalle y para el update......
    public function ws_select_item(){
        $empleado_id = $this->uri->segment(3);
        if(isset($empleado_id) && ($empleado_id != '')){
            $jsondata = $this->empleados_model->query_standar(2, '', array('emp.id' => $empleado_id));
            echo json_encode($jsondata);
        }
    }
    
    //para eliminar
    public function delete_item(){
        $empleado_id = $this->uri->segment(3);
        if(isset($empleado_id) && ($empleado_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s"),
                'empleado_delete'   =>  $this->session->userdata('empleado_id')
            );
            $this->empleados_model->modificar($empleado_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    } 
    
}