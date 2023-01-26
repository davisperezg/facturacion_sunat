<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cuenta_entidades extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('cuenta_entidades_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    

    public function save(){        
        $data = array(
            'entidad_id'            =>  $_GET['entidad_id'],
            'banco_id'              =>  $_GET['banco_id'],
            'tipo_cuenta_id'        =>  $_GET['tipo_cuenta_id'],
            'moneda_id'             =>  $_GET['moneda_id'],
            'numero_cuenta'         =>  $_GET['numero_cuenta'],
            'codigo_interbancario'  =>  $_GET['codigo_interbancario'],
            'titular'               =>  $_GET['titular'],
            'fecha_insert'          =>  date("Y-m-d H:i:s"),
            'empleado_insert'       =>  $this->session->userdata('empleado_id')
        );                       
        $this->cuenta_entidades_model->insertar($data);
        
        $max_cuenta_entidad_id = $this->cuenta_entidades_model->max_id();
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta',
            'cuenta_entidad_id'  => $max_cuenta_entidad_id
        );
        echo json_encode($jsondata);
    }
    

}